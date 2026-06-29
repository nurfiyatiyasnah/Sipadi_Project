<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Notifikasi;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
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

        // Currently borrowed books (status aktif atau terlambat)
        $bukuDipinjam = collect();
        if ($anggota) {
            $bukuDipinjam = Peminjaman::where('id_anggota', $anggota->id_anggota)
                ->whereIn('status_peminjaman', ['aktif', 'terlambat'])
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

        // Books waiting for pickup (status siap_diambil)
        $menungguPengambilan = collect();
        if ($anggota) {
            $menungguPengambilan = Peminjaman::where('id_anggota', $anggota->id_anggota)
                ->where('status_peminjaman', 'siap_diambil')
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
            ->whereIn('status_baca', ['belum_dibaca', 'Belum Dibaca'])
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
                $tersedia = $buku->eksemplar()->where('status_eksemplar', 'tersedia')->count();

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

    public function peminjamanSaya()
    {
        /** @var User $user */
        $user = Auth::user();
        $anggota = $user->anggota;

        if (! $anggota) {
            return redirect()->route('landing')->with('error', 'Data anggota tidak ditemukan.');
        }

        $peminjamans = Peminjaman::where('id_anggota', $anggota->id_anggota)
            ->with(['detailPeminjaman.buku.eksemplar', 'jadwalPengambilan'])
            ->latest('id_peminjaman')
            ->paginate(10);

        // Check if there is an auto-open ticket requested
        $autoOpenTicket = null;
        if (request()->has('ticket')) {
            $ticketCode = request()->get('ticket');
            $p = Peminjaman::where('id_anggota', $anggota->id_anggota)
                ->where('kode_peminjaman', $ticketCode)
                ->with(['detailPeminjaman.buku', 'jadwalPengambilan'])
                ->first();

            if ($p && $p->status_peminjaman === 'siap_diambil') {
                $detail = $p->detailPeminjaman->first();
                $buku = $detail?->buku;
                $autoOpenTicket = [
                    'kode' => $p->kode_peminjaman,
                    'judul' => $buku->judul ?? 'Buku',
                    'penulis' => $buku->penulis ?? '-',
                    'tanggal' => $p->jadwalPengambilan ? Carbon::parse($p->jadwalPengambilan->tanggal_pengambilan)->translatedFormat('d F Y') : '-',
                    'waktu' => $p->jadwalPengambilan ? date('H:i', strtotime($p->jadwalPengambilan->jam_mulai)).' - '.date('H:i', strtotime($p->jadwalPengambilan->jam_selesai)).' WIB' : '-',
                    'lokasi' => $p->jadwalPengambilan?->lokasi_pengambilan ?? 'Meja Sirkulasi',
                    'pesan' => $p->jadwalPengambilan?->pesan ?? 'Harap tunjukkan tiket ini ke petugas.',
                ];
            }
        }

        return view('anggota.peminjaman-saya', compact('peminjamans', 'autoOpenTicket'));
    }

    public function readNotifikasi(Notifikasi $notifikasi)
    {
        // Safety check: owner-only
        if ($notifikasi->id_user !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke notifikasi ini.');
        }

        // Mark as read
        $notifikasi->update([
            'status_baca' => 'dibaca',
            'dibaca_pada' => now(),
        ]);

        // Redirect based on jenis_notifikasi
        if ($notifikasi->jenis_notifikasi === 'peminjaman_disetujui' && $notifikasi->id_peminjaman) {
            $peminjaman = Peminjaman::find($notifikasi->id_peminjaman);
            if ($peminjaman && ($peminjaman->status_peminjaman === 'siap_diambil' || $peminjaman->jadwalPengambilan()->exists())) {
                return redirect()->route('anggota.peminjaman-saya', ['ticket' => $peminjaman->kode_peminjaman]);
            }

            return redirect()->route('anggota.notifikasi.index')->with('error', 'Target tiket peminjaman tidak valid atau telah kadaluarsa.');
        }

        return redirect()->route('anggota.peminjaman-saya');
    }

    public function indexNotifikasi()
    {
        /** @var User $user */
        $user = Auth::user();

        $notifikasis = Notifikasi::where('id_user', $user->id_user)
            ->latest('dikirim_pada')
            ->latest('id_notifikasi')
            ->paginate(15);

        return view('anggota.notifikasi.index', compact('notifikasis'));
    }
}
