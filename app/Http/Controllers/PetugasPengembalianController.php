<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\AturanPeminjaman;
use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\DetailPengembalian;
use App\Models\EksemplarBuku;
use App\Models\Keterlambatan;
use App\Models\Notifikasi;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\SanksiAnggota;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PetugasPengembalianController extends Controller
{
    /**
     * Display a listing of active book loans.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $statusFilter = strtolower($request->input('status', 'semua'));

        $query = Peminjaman::with(['anggota', 'detailPeminjaman.buku.eksemplar'])
            ->whereIn('status_peminjaman', ['disetujui', 'Disetujui', 'aktif', 'dipinjam', 'Dipinjam', 'terlambat', 'Terlambat']);

        // Filter by status tab
        if ($statusFilter !== 'semua') {
            if ($statusFilter === 'terlambat') {
                $query->where(function ($q) {
                    $q->where('status_peminjaman', 'terlambat')
                      ->orWhere(function ($q2) {
                          $q2->whereIn('status_peminjaman', ['disetujui', 'Disetujui', 'aktif', 'dipinjam', 'Dipinjam'])
                             ->where('tanggal_jatuh_tempo', '<', today());
                      });
                });
            } elseif ($statusFilter === 'sedang dipinjam') {
                $query->whereIn('status_peminjaman', ['disetujui', 'Disetujui', 'aktif', 'dipinjam', 'Dipinjam'])
                      ->where('tanggal_jatuh_tempo', '>=', today());
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
            'total_aktif' => Peminjaman::whereIn('status_peminjaman', ['disetujui', 'Disetujui', 'aktif', 'dipinjam', 'Dipinjam', 'terlambat', 'Terlambat'])->count(),
            'total_terlambat' => Peminjaman::whereIn('status_peminjaman', ['disetujui', 'Disetujui', 'aktif', 'dipinjam', 'Dipinjam', 'terlambat', 'Terlambat'])
                ->where(function ($q) {
                    $q->where('status_peminjaman', 'terlambat')
                      ->orWhere('tanggal_jatuh_tempo', '<', today());
                })->count(),
            'buku_beredar' => DetailPeminjaman::whereHas('peminjaman', function ($q) {
                $q->whereIn('status_peminjaman', ['disetujui', 'Disetujui', 'aktif', 'dipinjam', 'Dipinjam', 'terlambat', 'Terlambat']);
            })->sum('jumlah'),
        ];

        return view('petugas.pengembalian.index', compact('peminjamans', 'stats', 'statusFilter', 'search'));
    }

    /**
     * Display details of a specific active loan.
     */
    public function show(Peminjaman $peminjaman): View
    {
        $peminjaman->load(['anggota', 'detailPeminjaman.buku.eksemplar', 'petugas']);
        $anggota = $peminjaman->anggota;

        // Calculate active loan count for this member
        $bukuDipinjamCount = Peminjaman::where('id_anggota', $anggota->id_anggota)
            ->whereIn('status_peminjaman', ['disetujui', 'Disetujui', 'aktif', 'dipinjam', 'Dipinjam', 'terlambat', 'Terlambat'])
            ->count();

        // Check delay days
        $hariTerlambat = 0;
        $isTerlambat = false;
        if ($peminjaman->tanggal_jatuh_tempo && today()->greaterThan($peminjaman->tanggal_jatuh_tempo)) {
            $hariTerlambat = (int) abs(today()->diffInDays($peminjaman->tanggal_jatuh_tempo));
            $isTerlambat = true;
        } elseif (strtolower($peminjaman->status_peminjaman) === 'terlambat') {
            $isTerlambat = true;
            $hariTerlambat = $peminjaman->tanggal_jatuh_tempo ? (int) abs(today()->diffInDays($peminjaman->tanggal_jatuh_tempo)) : 1;
        }

        return view('petugas.pengembalian.show', compact('peminjaman', 'anggota', 'bukuDipinjamCount', 'hariTerlambat', 'isTerlambat'));
    }

    /**
     * Display the return form step.
     */
    public function prosesForm(Peminjaman $peminjaman): View
    {
        $peminjaman->load(['anggota', 'detailPeminjaman.buku.eksemplar']);
        return view('petugas.pengembalian.proses', compact('peminjaman'));
    }

    /**
     * Handle the intermediate page to calculate & preview sanctions before confirmation.
     */
    public function prosesSanksi(Request $request, Peminjaman $peminjaman): View
    {
        $request->validate([
            'tanggal_pengembalian' => 'required|date',
            'keadaan_buku' => 'required|string|in:Baik,Rusak Ringan,Rusak Berat,Hilang',
            'catatan_kondisi' => 'nullable|string',
            'foto_kondisi' => 'nullable|image|max:5120', // Max 5MB
        ]);

        $peminjaman->load(['anggota', 'detailPeminjaman.buku.eksemplar']);

        $tanggalJatuhTempo = Carbon::parse($peminjaman->tanggal_jatuh_tempo);
        $tanggalKembali = Carbon::parse($request->tanggal_pengembalian);
        $hariTerlambat = 0;

        if ($tanggalKembali->greaterThan($tanggalJatuhTempo)) {
            $hariTerlambat = (int) abs($tanggalKembali->diffInDays($tanggalJatuhTempo));
        }

        // Store photo in session or storage temporary path if uploaded
        $photoPath = null;
        if ($request->hasFile('foto_kondisi')) {
            $photoPath = $request->file('foto_kondisi')->store('pengembalian_temp', 'public');
        }

        // Return form details to confirm
        $keadaanBuku = $request->keadaan_buku;
        $catatanKondisi = $request->catatan_kondisi;

        // Calculate sanksi using database rules
        $aturan = $peminjaman->aturanPeminjaman ?? AturanPeminjaman::where('status_aktif', true)->first();
        $multiplier = $aturan?->masa_suspend_per_hari_terlambat ?? 1;
        $sanksiHari = $hariTerlambat * $multiplier;
        $isRusakAtauHilang = in_array($keadaanBuku, ['Rusak Ringan', 'Rusak Berat', 'Hilang']);

        return view('petugas.pengembalian.sanksi', compact(
            'peminjaman',
            'tanggalKembali',
            'hariTerlambat',
            'keadaanBuku',
            'catatanKondisi',
            'photoPath',
            'sanksiHari',
            'multiplier',
            'isRusakAtauHilang'
        ));
    }

    /**
     * Save the return data, update inventory and sanctions, then redirect.
     */
    public function store(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $petugas = $user->petugas;

        $tanggalKembali = Carbon::parse($request->input('tanggal_pengembalian'));
        $hariTerlambat = (int) $request->input('hari_terlambat', 0);
        $keadaanBuku = $request->input('keadaan_buku');
        $catatanKondisi = $request->input('catatan_kondisi');
        $photoPath = $request->input('photo_path');

        DB::transaction(function () use ($peminjaman, $petugas, $tanggalKembali, $hariTerlambat, $keadaanBuku, $catatanKondisi, $photoPath) {
            // Move temp photo to permanent directory if exists
            $finalPhotoPath = null;
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                $finalPhotoPath = str_replace('pengembalian_temp/', 'pengembalian/', $photoPath);
                Storage::disk('public')->move($photoPath, $finalPhotoPath);
            }

            // 1. Create Pengembalian record
            $statusPengembalian = $hariTerlambat > 0 ? 'Terlambat' : 'Tepat Waktu';
            
            // Format notes as JSON to store both photo path and notes
            $catatanData = [
                'catatan' => $catatanKondisi,
                'foto' => $finalPhotoPath
            ];

            $pengembalian = Pengembalian::create([
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'id_petugas' => $petugas?->id_petugas,
                'tanggal_pengembalian' => $tanggalKembali,
                'total_hari_terlambat' => $hariTerlambat,
                'status_pengembalian' => $statusPengembalian,
                'catatan' => json_encode($catatanData),
            ]);

            // 2. Create Detail Pengembalian records and update Eksemplar status
            foreach ($peminjaman->detailPeminjaman as $detailPem) {
                DetailPengembalian::create([
                    'id_pengembalian' => $pengembalian->id_pengembalian,
                    'id_detail_peminjaman' => $detailPem->id_detail_peminjaman,
                    'jumlah_dikembalikan' => $detailPem->jumlah,
                    'kondisi_buku' => $keadaanBuku,
                    'catatan' => $catatanKondisi,
                ]);

                // Update first exemplar status based on condition
                $eksemplar = EksemplarBuku::where('id_buku', $detailPem->id_buku)->first();
                if ($eksemplar) {
                    if ($keadaanBuku === 'Baik') {
                        $eksemplar->update([
                            'status_eksemplar' => 'tersedia',
                            'kondisi_eksemplar' => 'Baik',
                        ]);
                    } elseif (in_array($keadaanBuku, ['Rusak Ringan', 'Rusak Berat'])) {
                        $eksemplar->update([
                            'status_eksemplar' => 'rusak',
                            'kondisi_eksemplar' => 'Rusak',
                        ]);
                    } elseif ($keadaanBuku === 'Hilang') {
                        $eksemplar->update([
                            'status_eksemplar' => 'hilang',
                            'kondisi_eksemplar' => 'Hilang',
                        ]);
                    }
                }
            }

            // 3. Create Keterlambatan record if overdue
            $idKeterlambatan = null;
            if ($hariTerlambat > 0) {
                $keterlambatan = Keterlambatan::create([
                    'id_peminjaman' => $peminjaman->id_peminjaman,
                    'id_anggota' => $peminjaman->id_anggota,
                    'tanggal_jatuh_tempo' => $peminjaman->tanggal_jatuh_tempo,
                    'tanggal_dihitung' => $tanggalKembali,
                    'hari_terlambat' => $hariTerlambat,
                    'status_perhitungan' => 'Selesai',
                ]);
                $idKeterlambatan = $keterlambatan->id_keterlambatan;
            }

            // 4. Create Sanksi record if overdue
            if ($hariTerlambat > 0) {
                $aturan = $peminjaman->aturanPeminjaman ?? AturanPeminjaman::where('status_aktif', true)->first();
                $multiplier = $aturan?->masa_suspend_per_hari_terlambat ?? 1;
                $sanksiHari = $hariTerlambat * $multiplier;

                SanksiAnggota::create([
                    'id_anggota' => $peminjaman->id_anggota,
                    'id_peminjaman' => $peminjaman->id_peminjaman,
                    'id_keterlambatan' => $idKeterlambatan,
                    'jenis_sanksi' => 'Skorsing ' . $sanksiHari . ' Hari',
                    'alasan' => 'Keterlambatan pengembalian buku selama ' . $hariTerlambat . ' hari.',
                    'tanggal_mulai' => $tanggalKembali,
                    'tanggal_selesai' => $tanggalKembali->copy()->addDays($sanksiHari),
                    'status_sanksi' => 'aktif',
                ]);
            }

            // 5. Update Peminjaman status to selesai
            $peminjaman->update([
                'status_peminjaman' => 'selesai',
            ]);

            // 6. Send notification to member
            if ($peminjaman->anggota?->user) {
                Notifikasi::create([
                    'id_user' => $peminjaman->anggota->user->id_user,
                    'id_peminjaman' => $peminjaman->id_peminjaman,
                    'judul' => 'Pengembalian Buku Berhasil',
                    'isi' => 'Buku untuk transaksi ' . $peminjaman->kode_peminjaman . ' telah berhasil dikembalikan dengan kondisi ' . $keadaanBuku . '.',
                    'jenis_notifikasi' => 'Sistem',
                    'status_notifikasi' => 'Terkirim',
                    'status_baca' => 'Belum Dibaca',
                    'dikirim_pada' => now(),
                ]);
            }
        });

        return redirect()->route('petugas.pengembalian.riwayat')
            ->with('success', 'Transaksi pengembalian buku berhasil diproses dan dicatat.');
    }

    /**
     * Display return history log.
     */
    public function riwayat(Request $request): View
    {
        $statusPeminjaman = $request->input('status_peminjaman');
        $statusPengembalian = $request->input('status_pengembalian');
        $statusSanksi = $request->input('status_sanksi');
        $kondisiBuku = $request->input('kondisi_buku');
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $query = Pengembalian::with(['peminjaman.anggota', 'peminjaman.detailPeminjaman.buku.eksemplar', 'peminjaman.sanksiAnggota', 'detailPengembalian']);

        // Search by keyword
        if ($search = $request->input('search')) {
            $query->whereHas('peminjaman', function ($q) use ($search) {
                $q->where('kode_peminjaman', 'like', '%' . $search . '%')
                  ->orWhereHas('anggota', function ($q2) use ($search) {
                      $q2->where('nama_lengkap', 'like', '%' . $search . '%')
                         ->orWhere('no_anggota', 'like', '%' . $search . '%');
                  });
            });
        }

        // Apply filters
        if ($statusPeminjaman) {
            $query->whereHas('peminjaman', function ($q) use ($statusPeminjaman) {
                $q->where('status_peminjaman', $statusPeminjaman);
            });
        }
        if ($statusPengembalian) {
            $query->where('status_pengembalian', $statusPengembalian);
        }
        if ($statusSanksi) {
            if ($statusSanksi === 'Ada Sanksi') {
                $query->whereHas('peminjaman.sanksiAnggota');
            } else {
                $query->whereDoesntHave('peminjaman.sanksiAnggota');
            }
        }
        if ($kondisiBuku) {
            $query->whereHas('detailPengembalian', function ($q) use ($kondisiBuku) {
                $q->where('kondisi_buku', $kondisiBuku);
            });
        }
        if ($tanggalMulai) {
            $query->whereDate('tanggal_pengembalian', '>=', $tanggalMulai);
        }
        if ($tanggalSelesai) {
            $query->whereDate('tanggal_pengembalian', '<=', $tanggalSelesai);
        }

        $pengembalians = $query->latest('id_pengembalian')->paginate(10)->withQueryString();

        return view('petugas.pengembalian.riwayat', compact('pengembalians'));
    }

    /**
     * Export return history to CSV format.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $statusPeminjaman = $request->input('status_peminjaman');
        $statusPengembalian = $request->input('status_pengembalian');
        $statusSanksi = $request->input('status_sanksi');
        $kondisiBuku = $request->input('kondisi_buku');
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $query = Pengembalian::with(['peminjaman.anggota', 'peminjaman.detailPeminjaman.buku.eksemplar', 'peminjaman.sanksiAnggota', 'detailPengembalian']);

        if ($statusPeminjaman) {
            $query->whereHas('peminjaman', function ($q) use ($statusPeminjaman) {
                $q->where('status_peminjaman', $statusPeminjaman);
            });
        }
        if ($statusPengembalian) {
            $query->where('status_pengembalian', $statusPengembalian);
        }
        if ($statusSanksi) {
            if ($statusSanksi === 'Ada Sanksi') {
                $query->whereHas('peminjaman.sanksiAnggota');
            } else {
                $query->whereDoesntHave('peminjaman.sanksiAnggota');
            }
        }
        if ($kondisiBuku) {
            $query->whereHas('detailPengembalian', function ($q) use ($kondisiBuku) {
                $q->where('kondisi_buku', $kondisiBuku);
            });
        }
        if ($tanggalMulai) {
            $query->whereDate('tanggal_pengembalian', '>=', $tanggalMulai);
        }
        if ($tanggalSelesai) {
            $query->whereDate('tanggal_pengembalian', '<=', $tanggalSelesai);
        }

        $pengembalians = $query->latest('id_pengembalian')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="laporan_pengembalian_buku.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function () use ($pengembalians) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel alignment
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'Kode Transaksi',
                'Nama Anggota',
                'Judul Buku',
                'Tanggal Pinjam',
                'Tanggal Pengembalian',
                'Status Pengembalian',
                'Keterlambatan (Hari)',
                'Kondisi Buku',
                'Sanksi (Hari)'
            ]);

            foreach ($pengembalians as $pengembalian) {
                $peminjaman = $pengembalian->peminjaman;
                $anggota = $peminjaman?->anggota?->nama_lengkap ?? 'Anggota';
                $judulBuku = $peminjaman?->detailPeminjaman?->first()?->buku?->judul ?? 'Buku';
                $tanggalPinjam = $peminjaman?->tanggal_diambil ? $peminjaman->tanggal_diambil->format('d-m-Y') : '';
                $tanggalKembali = $pengembalian->tanggal_pengembalian ? $pengembalian->tanggal_pengembalian->format('d-m-Y') : '';
                $sanksi = $peminjaman?->sanksiAnggota ? (int) abs($peminjaman->sanksiAnggota->tanggal_mulai->diffInDays($peminjaman->sanksiAnggota->tanggal_selesai)) : 0;

                fputcsv($file, [
                    $peminjaman?->kode_peminjaman ?? '',
                    $anggota,
                    $judulBuku,
                    $tanggalPinjam,
                    $tanggalKembali,
                    $pengembalian->status_pengembalian,
                    $pengembalian->total_hari_terlambat,
                    $pengembalian->detailPengembalian?->first()?->kondisi_buku ?? 'Baik',
                    $sanksi . ' Hari'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
