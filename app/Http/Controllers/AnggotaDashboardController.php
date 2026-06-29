<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Notifikasi;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AnggotaDashboardController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $anggota = $user->anggota;

        // Determine greeting based on time of day
        $hour = now()->format('H');
        if ($hour >= 5 && $hour < 11) {
            $sapaan = 'SELAMAT PAGI';
        } elseif ($hour >= 11 && $hour < 15) {
            $sapaan = 'SELAMAT SIANG';
        } elseif ($hour >= 15 && $hour < 18) {
            $sapaan = 'SELAMAT SORE';
        } else {
            $sapaan = 'SELAMAT MALAM';
        }

        $namaUser = $anggota->nama_lengkap ?? $user->email;

        // Currently borrowed books (status Dipinjam)
        $bukuDipinjam = collect();
        if ($anggota) {
            $bukuDipinjam = Peminjaman::where('id_anggota', $anggota->id_anggota)
                ->where('status_peminjaman', 'Dipinjam')
                ->with(['detailPeminjaman.buku'])
                ->latest('tanggal_diambil')
                ->get()
                ->map(function ($peminjaman) {
                    $detail = $peminjaman->detailPeminjaman->first();
                    $buku = $detail?->buku;
                    $jatuhTempo = $peminjaman->tanggal_jatuh_tempo;
                    $sisaHari = $jatuhTempo ? now()->startOfDay()->diffInDays($jatuhTempo, false) : null;

                    return (object) [
                        'id_peminjaman' => $peminjaman->id_peminjaman,
                        'judul' => $buku?->judul ?? 'Buku tidak ditemukan',
                        'penulis' => $buku?->penulis ?? '-',
                        'gambar_cover' => $buku?->gambar_cover,
                        'tanggal_dipinjam' => $peminjaman->tanggal_diambil,
                        'tanggal_jatuh_tempo' => $jatuhTempo,
                        'sisa_hari' => $sisaHari,
                    ];
                });
        }

        // Books waiting for pickup (status Disetujui)
        $menungguPengambilan = collect();
        if ($anggota) {
            $menungguPengambilan = Peminjaman::where('id_anggota', $anggota->id_anggota)
                ->where('status_peminjaman', 'Disetujui')
                ->with(['jadwalPengambilan', 'detailPeminjaman.buku'])
                ->latest('tanggal_pengajuan')
                ->get()
                ->map(function ($peminjaman) {
                    $detail = $peminjaman->detailPeminjaman->first();
                    $buku = $detail?->buku;
                    $jadwal = $peminjaman->jadwalPengambilan;

                    return (object) [
                        'judul' => $buku?->judul ?? 'Buku tidak ditemukan',
                        'lokasi' => $jadwal?->lokasi_pengambilan ?? '-',
                        'jumlah_antrian' => 1,
                    ];
                });
        }

        // Unread notifications / messages
        $pesanBaru = Notifikasi::where('id_user', $user->id_user)
            ->where('status_baca', 'Belum Dibaca')
            ->latest('dikirim_pada')
            ->take(5)
            ->get();

        // Book recommendations (latest active books)
        $rekomendasi = Buku::whereIn('status_katalog', ['aktif', 'Aktif'])
            ->latest('created_at')
            ->take(5)
            ->get()
            ->map(function ($buku) {
                $totalEksemplar = $buku->eksemplar()->count();
                $tersedia = $buku->eksemplar()->whereIn('status_eksemplar', ['tersedia', 'Tersedia'])->count();

                return (object) [
                    'id_buku' => $buku->id_buku,
                    'judul' => $buku->judul,
                    'penulis' => $buku->penulis,
                    'gambar_cover' => $buku->gambar_cover,
                    'status' => $tersedia > 0 ? 'Tersedia' : ($totalEksemplar > 0 ? 'Dipinjam' : 'Tidak Tersedia'),
                ];
            });

        return view('anggota.dashboard', compact(
            'sapaan',
            'namaUser',
            'bukuDipinjam',
            'menungguPengambilan',
            'pesanBaru',
            'rekomendasi'
        ));
    }
}
