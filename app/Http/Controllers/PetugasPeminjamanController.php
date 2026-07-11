<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApprovePeminjamanRequest;
use App\Models\Anggota;
use App\Models\EksemplarBuku;
use App\Models\JadwalPengambilan;
use App\Models\Notifikasi;
use App\Models\Peminjaman;
use App\Models\SanksiAnggota;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PetugasPeminjamanController extends Controller
{
    private const INDEX_STATUS_FILTERS = [
        'menunggu' => 'diajukan',
        'disetujui' => 'siap_diambil',
        'ditolak' => 'ditolak',
    ];

    /**
     * Display a listing of loan applications.
     */
    public function index(Request $request): View
    {
        $statusFilter = strtolower($request->query('status', 'semua'));
        $search = $request->query('search');
        $idAnggota = $request->filled('id_anggota') ? $request->integer('id_anggota') : null;
        $anggotaFilter = $idAnggota ? Anggota::find($idAnggota) : null;

        $query = $this->loanApplicationsQuery($search, $idAnggota);

        if (array_key_exists($statusFilter, self::INDEX_STATUS_FILTERS)) {
            $query->where('status_peminjaman', self::INDEX_STATUS_FILTERS[$statusFilter]);
        }

        $peminjamans = $query->latest('id_peminjaman')->paginate(10)->withQueryString();
        $stats = $this->loanApplicationStats();

        return view('petugas.peminjaman.index', compact('peminjamans', 'stats', 'statusFilter', 'search', 'anggotaFilter'));
    }

    /**
     * Display the specified loan application details.
     */
    public function show(Peminjaman $peminjaman): View
    {
        $peminjaman->load(['anggota', 'detailPeminjaman.buku.eksemplar', 'aturanPeminjaman']);
        $anggota = $peminjaman->anggota;

        // Calculate active loan count for this member
        $bukuDipinjamCount = Peminjaman::where('id_anggota', $anggota->id_anggota)
            ->whereIn('status_peminjaman', ['aktif', 'terlambat'])
            ->count();

        // Check active sanctions
        $sanksiAktif = SanksiAnggota::where('id_anggota', $anggota->id_anggota)
            ->where('status_sanksi', 'aktif')
            ->where(function ($query) {
                $query->whereNull('tanggal_selesai')
                    ->orWhere('tanggal_selesai', '>=', today()->toDateString());
            })
            ->first();

        return view('petugas.peminjaman.show', compact('peminjaman', 'anggota', 'bukuDipinjamCount', 'sanksiAktif'));
    }

    /**
     * Reject the specified loan application.
     */
    public function reject(\Illuminate\Http\Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $petugas = $user->petugas;
        
        $request->validate([
            'catatan_admin' => 'nullable|string|max:500'
        ]);
        
        $catatan = $request->input('catatan_admin');

        DB::transaction(function () use ($peminjaman, $petugas, $catatan) {
            $peminjaman->update([
                'status_peminjaman' => 'ditolak',
                'id_petugas' => $petugas?->id_petugas,
                'catatan_admin' => $catatan
            ]);

            foreach ($peminjaman->detailPeminjaman as $detail) {
                $detail->update([
                    'status_detail' => 'ditolak',
                ]);
            }

            // Create notification for member
            if ($peminjaman->anggota?->user) {
                $isiNotif = 'Pengajuan peminjaman Anda dengan kode '.$peminjaman->kode_peminjaman.' telah ditolak oleh petugas.';
                if (!empty($catatan)) {
                    $isiNotif .= "\n\nAlasan: " . $catatan;
                }
                
                Notifikasi::create([
                    'id_user' => $peminjaman->anggota->user->id_user,
                    'id_peminjaman' => $peminjaman->id_peminjaman,
                    'judul' => 'Peminjaman Ditolak',
                    'isi' => $isiNotif,
                    'jenis_notifikasi' => 'peminjaman_ditolak',
                    'status_notifikasi' => 'terkirim',
                    'status_baca' => 'belum_dibaca',
                    'dikirim_pada' => now(),
                ]);
            }
        });

        return redirect()
            ->route('petugas.peminjaman.index')
            ->with('success', 'Pengajuan peminjaman #'.$peminjaman->kode_peminjaman.' berhasil ditolak.');
    }

    /**
     * Show the approval / pickup scheduling form.
     */
    public function approveForm(Peminjaman $peminjaman): View
    {
        $peminjaman->load(['anggota', 'detailPeminjaman.buku', 'jadwalPengambilan']);

        return view('petugas.peminjaman.approve', compact('peminjaman'));
    }

    /**
     * Approve and schedule pickup for the loan application.
     */
    public function approve(ApprovePeminjamanRequest $request, Peminjaman $peminjaman): RedirectResponse
    {
        $validated = $request->validated();

        /** @var User $user */
        $user = Auth::user();
        $petugas = $user->petugas;

        try {
            DB::transaction(function () use ($validated, $peminjaman, $petugas) {
                $detail = $peminjaman->detailPeminjaman->first();
                if (! $detail) {
                    throw new \Exception('Detail peminjaman tidak ditemukan.');
                }

                // Choose available exemplar using lockForUpdate()
                $eksemplar = EksemplarBuku::where('id_buku', $detail->id_buku)
                    ->whereIn('status_eksemplar', EksemplarBuku::AVAILABLE_COPY_STATUSES)
                    ->lockForUpdate()
                    ->first();

                if (! $eksemplar) {
                    throw new \Exception('Maaf, tidak ada eksemplar buku yang tersedia saat ini.');
                }

                // Update exemplar status to 'dipesan'
                $eksemplar->update([
                    'status_eksemplar' => EksemplarBuku::STATUS_DIPESAN,
                ]);

                // Save exemplar reference and update detail status to 'dipesan'
                $detail->update([
                    'id_eksemplar_buku' => $eksemplar->id_eksemplar_buku,
                    'status_detail' => 'dipesan',
                ]);

                // Determine due date
                $lamaPinjam = $peminjaman->aturanPeminjaman?->lama_pinjam_hari ?? 14;
                $tanggalJatuhTempo = Carbon::parse($validated['tanggal_pengambilan'])->addDays($lamaPinjam)->toDateString();

                $peminjaman->update([
                    'status_peminjaman' => 'siap_diambil',
                    'id_petugas' => $petugas?->id_petugas,
                    'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
                ]);

                // Save pickup schedule
                $jadwal = JadwalPengambilan::updateOrCreate(
                    ['id_peminjaman' => $peminjaman->id_peminjaman],
                    [
                        'id_petugas' => $petugas?->id_petugas,
                        'tanggal_pengambilan' => $validated['tanggal_pengambilan'],
                        'jam_mulai' => $validated['jam_pengambilan'],
                        'jam_selesai' => date('H:i', strtotime($validated['jam_pengambilan']) + 3600),
                        'lokasi_pengambilan' => $validated['lokasi_pengambilan'],
                        'pesan' => $validated['pesan'] ?? null,
                        'status_jadwal' => 'disetujui',
                        'dikirim_pada' => now(),
                    ]
                );

                // Create notification for member
                if ($peminjaman->anggota?->user) {
                    $bukuJudul = $detail->buku?->judul ?? 'buku';
                    $tanggalFormatted = Carbon::parse($validated['tanggal_pengambilan'])->translatedFormat('d M Y');
                    Notifikasi::create([
                        'id_user' => $peminjaman->anggota->user->id_user,
                        'id_peminjaman' => $peminjaman->id_peminjaman,
                        'id_jadwal_pengambilan' => $jadwal->id_jadwal_pengambilan,
                        'judul' => 'Peminjaman Disetujui',
                        'isi' => "Buku '{$bukuJudul}' siap diambil pada {$tanggalFormatted} pukul {$validated['jam_pengambilan']} WIB di {$validated['lokasi_pengambilan']}.",
                        'jenis_notifikasi' => 'peminjaman_disetujui',
                        'status_notifikasi' => 'terkirim',
                        'status_baca' => 'belum_dibaca',
                        'dikirim_pada' => now(),
                    ]);
                }
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('petugas.peminjaman.index')
            ->with('success', 'Pengajuan peminjaman #'.$peminjaman->kode_peminjaman.' berhasil disetujui dan jadwal pengambilan telah diatur.');
    }

    /**
     * Mark borrowing as active when user picks up the book.
     */
    public function markAsPickedUp(Peminjaman $peminjaman): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $petugas = $user->petugas;

        if ($peminjaman->status_peminjaman !== 'siap_diambil') {
            return back()->with('error', 'Peminjaman harus berstatus siap diambil.');
        }

        DB::transaction(function () use ($peminjaman, $petugas) {
            $lamaPinjam = $peminjaman->aturanPeminjaman?->lama_pinjam_hari ?? 14;
            $tanggalDiambil = now();
            $tanggalJatuhTempo = $tanggalDiambil->copy()->addDays($lamaPinjam)->toDateString();

            $peminjaman->update([
                'status_peminjaman' => 'aktif',
                'tanggal_diambil' => $tanggalDiambil,
                'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
                'id_petugas' => $petugas?->id_petugas,
            ]);

            // Update details and exemplars
            foreach ($peminjaman->detailPeminjaman as $detail) {
                $detail->update([
                    'status_detail' => 'dipinjam',
                ]);

                if ($detail->id_eksemplar_buku) {
                    EksemplarBuku::where('id_eksemplar_buku', $detail->id_eksemplar_buku)->update([
                        'status_eksemplar' => EksemplarBuku::STATUS_DIPINJAM,
                    ]);
                }
            }
        });

        return redirect()
            ->route('petugas.peminjaman.show', $peminjaman->id_peminjaman)
            ->with('success', 'Buku telah berhasil ditandai sebagai diambil. Peminjaman kini aktif.');
    }

    /**
     * Cancel a ready-for-pickup borrowing when the member does not pick up the book.
     */
    public function cancelPickup(Peminjaman $peminjaman): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $petugas = $user->petugas;

        if ($peminjaman->status_peminjaman !== 'siap_diambil') {
            return back()->with('error', 'Peminjaman harus berstatus siap diambil untuk dibatalkan.');
        }

        $peminjaman->load(['anggota.user', 'detailPeminjaman']);

        DB::transaction(function () use ($peminjaman, $petugas): void {
            $peminjaman->update([
                'status_peminjaman' => 'dibatalkan',
                'tanggal_diambil' => null,
                'tanggal_jatuh_tempo' => null,
                'id_petugas' => $petugas?->id_petugas,
                'catatan_admin' => 'Pengambilan dibatalkan karena anggota tidak mengambil buku sesuai jadwal.',
            ]);

            foreach ($peminjaman->detailPeminjaman as $detail) {
                $detail->update([
                    'status_detail' => 'dibatalkan',
                ]);

                if ($detail->id_eksemplar_buku) {
                    EksemplarBuku::where('id_eksemplar_buku', $detail->id_eksemplar_buku)->update([
                        'status_eksemplar' => EksemplarBuku::STATUS_TERSEDIA,
                    ]);
                }
            }

            if ($peminjaman->anggota?->user) {
                Notifikasi::create([
                    'id_user' => $peminjaman->anggota->user->id_user,
                    'id_peminjaman' => $peminjaman->id_peminjaman,
                    'judul' => 'Peminjaman Dibatalkan',
                    'isi' => 'Pengambilan buku untuk kode '.$peminjaman->kode_peminjaman.' telah dibatalkan karena tidak diambil sesuai jadwal.',
                    'jenis_notifikasi' => 'peminjaman_dibatalkan',
                    'status_notifikasi' => 'terkirim',
                    'status_baca' => 'belum_dibaca',
                    'dikirim_pada' => now(),
                ]);
            }
        });

        return redirect()
            ->route('petugas.peminjaman.show', $peminjaman->id_peminjaman)
            ->with('success', 'Pengambilan buku berhasil dibatalkan dan eksemplar kembali tersedia.');
    }

    /**
     * Export the loan applications list to CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        return response()->streamDownload(function () use ($request): void {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['Kode Peminjaman', 'No Anggota', 'Nama Anggota', 'Judul Buku', 'ISBN', 'Tanggal Pengajuan', 'Status Peminjaman']);

            $statusFilter = strtolower($request->query('status', 'diajukan'));
            $search = $request->query('search');
            $idAnggota = $request->filled('id_anggota') ? $request->integer('id_anggota') : null;

            $query = $this->loanApplicationsQuery($search, $idAnggota);

            if ($statusFilter !== 'semua') {
                $query->where('status_peminjaman', $statusFilter);
            }

            $query->orderBy('id_peminjaman', 'desc')->each(function (Peminjaman $peminjaman) use ($output): void {
                fputcsv($output, $this->loanApplicationCsvRow($peminjaman));
            });

            fclose($output);
        }, 'laporan_peminjaman.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * @return Builder<Peminjaman>
     */
    private function loanApplicationsQuery(mixed $search = null, ?int $idAnggota = null): Builder
    {
        $query = Peminjaman::with(['anggota', 'detailPeminjaman.buku']);

        if ($idAnggota) {
            $query->where('id_anggota', $idAnggota);
        }

        if ($search) {
            $this->applyLoanApplicationSearch($query, $search);
        }

        return $query;
    }

    /**
     * @param  Builder<Peminjaman>  $query
     */
    private function applyLoanApplicationSearch(Builder $query, mixed $search): void
    {
        $query->where(function (Builder $query) use ($search): void {
            $query->where('kode_peminjaman', 'like', '%'.$search.'%')
                ->orWhereHas('anggota', function (Builder $query) use ($search): void {
                    $query->where('nama_lengkap', 'like', '%'.$search.'%')
                        ->orWhere('no_anggota', 'like', '%'.$search.'%');
                })
                ->orWhereHas('detailPeminjaman.buku', function (Builder $query) use ($search): void {
                    $query->where('judul', 'like', '%'.$search.'%');
                });
        });
    }

    /**
     * @return array{menunggu: int, disetujui_hari_ini: int, ditolak_hari_ini: int, total_sirkulasi: int}
     */
    private function loanApplicationStats(): array
    {
        return [
            'menunggu' => Peminjaman::where('status_peminjaman', 'diajukan')->count(),
            'disetujui_hari_ini' => Peminjaman::where('status_peminjaman', 'siap_diambil')->whereDate('updated_at', today())->count(),
            'ditolak_hari_ini' => Peminjaman::where('status_peminjaman', 'ditolak')->whereDate('updated_at', today())->count(),
            'total_sirkulasi' => Peminjaman::whereIn('status_peminjaman', ['aktif', 'terlambat'])->count(),
        ];
    }

    /**
     * @return array<int, string|null>
     */
    private function loanApplicationCsvRow(Peminjaman $peminjaman): array
    {
        return [
            $peminjaman->kode_peminjaman,
            $peminjaman->anggota?->no_anggota ?? '-',
            $peminjaman->anggota?->nama_lengkap ?? '-',
            $peminjaman->detailPeminjaman->first()?->buku?->judul ?? '-',
            $peminjaman->detailPeminjaman->first()?->buku?->isbn ?? '-',
            $peminjaman->tanggal_pengajuan ? $peminjaman->tanggal_pengajuan->format('Y-m-d H:i:s') : ($peminjaman->created_at ? $peminjaman->created_at->format('Y-m-d H:i:s') : '-'),
            $peminjaman->status_peminjaman,
        ];
    }
}
