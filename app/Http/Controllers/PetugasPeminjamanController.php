<?php

namespace App\Http\Controllers;

use App\Models\JadwalPengambilan;
use App\Models\Notifikasi;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PetugasPeminjamanController extends Controller
{
    /**
     * Display a listing of loan applications.
     */
    public function index(Request $request): View
    {
        $statusFilter = strtolower($request->query('status', 'semua'));
        $search = $request->query('search');

        // Query loan applications
        $query = Peminjaman::with(['anggota', 'detailPeminjaman.buku']);

        // Filter status
        if ($statusFilter !== 'semua') {
            if ($statusFilter === 'menunggu') {
                $query->whereIn('status_peminjaman', ['menunggu', 'pending', 'pengajuan', 'diajukan']);
            } elseif ($statusFilter === 'disetujui') {
                $query->whereIn('status_peminjaman', ['disetujui', 'Disetujui']);
            } elseif ($statusFilter === 'ditolak') {
                $query->whereIn('status_peminjaman', ['ditolak', 'Ditolak']);
            } else {
                $query->where('status_peminjaman', $statusFilter);
            }
        }

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_peminjaman', 'like', '%' . $search . '%')
                  ->orWhereHas('anggota', function ($q2) use ($search) {
                      $q2->where('nama_lengkap', 'like', '%' . $search . '%')
                         ->orWhere('no_anggota', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('detailPeminjaman.buku', function ($q3) use ($search) {
                      $q3->where('judul', 'like', '%' . $search . '%');
                  });
            });
        }

        $peminjamans = $query->latest('id_peminjaman')->paginate(10)->withQueryString();

        // Calculate statistics
        $stats = [
            'menunggu' => Peminjaman::whereIn('status_peminjaman', ['menunggu', 'pending', 'pengajuan', 'diajukan'])->count(),
            'disetujui_hari_ini' => Peminjaman::whereIn('status_peminjaman', ['disetujui', 'Disetujui'])->whereDate('updated_at', today())->count(),
            'ditolak_hari_ini' => Peminjaman::whereIn('status_peminjaman', ['ditolak', 'Ditolak'])->whereDate('updated_at', today())->count(),
            'total_sirkulasi' => Peminjaman::whereIn('status_peminjaman', ['aktif', 'Aktif', 'Dipinjam', 'dipinjam', 'terlambat'])->count(),
        ];

        return view('petugas.peminjaman.index', compact('peminjamans', 'stats', 'statusFilter', 'search'));
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
            ->whereIn('status_peminjaman', ['aktif', 'Aktif', 'Dipinjam', 'dipinjam', 'terlambat'])
            ->count();

        return view('petugas.peminjaman.show', compact('peminjaman', 'anggota', 'bukuDipinjamCount'));
    }

    /**
     * Reject the specified loan application.
     */
    public function reject(Peminjaman $peminjaman): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $petugas = $user->petugas;

        DB::transaction(function () use ($peminjaman, $petugas) {
            $peminjaman->update([
                'status_peminjaman' => 'ditolak',
                'id_petugas' => $petugas?->id_petugas,
            ]);

            // Create notification for member
            if ($peminjaman->anggota?->user) {
                Notifikasi::create([
                    'id_user' => $peminjaman->anggota->user->id_user,
                    'id_peminjaman' => $peminjaman->id_peminjaman,
                    'judul' => 'Pengajuan Peminjaman Ditolak',
                    'isi' => 'Pengajuan peminjaman Anda dengan kode ' . $peminjaman->kode_peminjaman . ' telah ditolak oleh petugas.',
                    'jenis_notifikasi' => 'Sistem',
                    'status_notifikasi' => 'Terkirim',
                    'status_baca' => 'Belum Dibaca',
                    'dikirim_pada' => now(),
                ]);
            }
        });

        return redirect()
            ->route('petugas.peminjaman.index')
            ->with('success', 'Pengajuan peminjaman #' . $peminjaman->kode_peminjaman . ' berhasil ditolak.');
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
    public function approve(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        $request->validate([
            'tanggal_pengambilan' => 'required|date|after_or_equal:today',
            'jam_pengambilan' => 'required|string',
            'lokasi_pengambilan' => 'required|string|max:100',
            'pesan' => 'nullable|string',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $petugas = $user->petugas;

        DB::transaction(function () use ($request, $peminjaman, $petugas) {
            // Determine due date
            $lamaPinjam = $peminjaman->aturanPeminjaman?->lama_pinjam_hari ?? 14;
            $tanggalJatuhTempo = Carbon::parse($request->tanggal_pengambilan)->addDays($lamaPinjam)->toDateString();

            $peminjaman->update([
                'status_peminjaman' => 'disetujui',
                'id_petugas' => $petugas?->id_petugas,
                'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
            ]);

            // Save pickup schedule
            $jadwal = JadwalPengambilan::updateOrCreate(
                ['id_peminjaman' => $peminjaman->id_peminjaman],
                [
                    'id_petugas' => $petugas?->id_petugas,
                    'tanggal_pengambilan' => $request->tanggal_pengambilan,
                    'jam_mulai' => $request->jam_pengambilan,
                    'jam_selesai' => date('H:i', strtotime($request->jam_pengambilan) + 3600),
                    'lokasi_pengambilan' => $request->lokasi_pengambilan,
                    'pesan' => $request->pesan,
                    'status_jadwal' => 'disetujui',
                    'dikirim_pada' => now(),
                ]
            );

            // Create notification for member
            if ($peminjaman->anggota?->user) {
                $bukuJudul = $peminjaman->detailPeminjaman->first()?->buku?->judul ?? 'buku';
                $tanggalFormatted = Carbon::parse($request->tanggal_pengambilan)->translatedFormat('d M Y');
                Notifikasi::create([
                    'id_user' => $peminjaman->anggota->user->id_user,
                    'id_peminjaman' => $peminjaman->id_peminjaman,
                    'id_jadwal_pengambilan' => $jadwal->id_jadwal_pengambilan,
                    'judul' => 'Pengajuan Peminjaman Disetujui',
                    'isi' => "Pengajuan peminjaman Anda untuk buku '{$bukuJudul}' telah disetujui. Silakan ambil buku pada {$tanggalFormatted} pukul {$request->jam_pengambilan} WIB di {$request->lokasi_pengambilan}.",
                    'jenis_notifikasi' => 'Sistem',
                    'status_notifikasi' => 'Terkirim',
                    'status_baca' => 'Belum Dibaca',
                    'dikirim_pada' => now(),
                ]);
            }
        });

        return redirect()
            ->route('petugas.peminjaman.index')
            ->with('success', 'Pengajuan peminjaman #' . $peminjaman->kode_peminjaman . ' berhasil disetujui dan jadwal pengambilan telah diatur.');
    }

    /**
     * Export the loan applications list to CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        return response()->streamDownload(function () use ($request): void {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['Kode Peminjaman', 'No Anggota', 'Nama Anggota', 'Judul Buku', 'ISBN', 'Tanggal Pengajuan', 'Status Peminjaman']);

            $statusFilter = strtolower($request->query('status', 'semua'));
            $search = $request->query('search');

            $query = Peminjaman::with(['anggota', 'detailPeminjaman.buku']);

            if ($statusFilter !== 'semua') {
                if ($statusFilter === 'menunggu') {
                    $query->whereIn('status_peminjaman', ['menunggu', 'pending', 'pengajuan', 'diajukan']);
                } elseif ($statusFilter === 'disetujui') {
                    $query->whereIn('status_peminjaman', ['disetujui', 'Disetujui']);
                } elseif ($statusFilter === 'ditolak') {
                    $query->whereIn('status_peminjaman', ['ditolak', 'Ditolak']);
                } else {
                    $query->where('status_peminjaman', $statusFilter);
                }
            }

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_peminjaman', 'like', '%' . $search . '%')
                      ->orWhereHas('anggota', function ($q2) use ($search) {
                          $q2->where('nama_lengkap', 'like', '%' . $search . '%')
                             ->orWhere('no_anggota', 'like', '%' . $search . '%');
                      })
                      ->orWhereHas('detailPeminjaman.buku', function ($q3) use ($search) {
                          $q3->where('judul', 'like', '%' . $search . '%');
                      });
                });
            }

            $query->orderBy('id_peminjaman', 'desc')->each(function (Peminjaman $p) use ($output): void {
                fputcsv($output, [
                    $p->kode_peminjaman,
                    $p->anggota?->no_anggota ?? '-',
                    $p->anggota?->nama_lengkap ?? '-',
                    $p->detailPeminjaman->first()?->buku?->judul ?? '-',
                    $p->detailPeminjaman->first()?->buku?->isbn ?? '-',
                    $p->tanggal_pengajuan ? $p->tanggal_pengajuan->format('Y-m-d H:i:s') : ($p->created_at ? $p->created_at->format('Y-m-d H:i:s') : '-'),
                    $p->status_peminjaman,
                ]);
            });

            fclose($output);
        }, 'laporan_peminjaman.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
