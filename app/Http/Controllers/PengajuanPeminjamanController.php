<?php

namespace App\Http\Controllers;

use App\Models\AturanPeminjaman;
use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\SanksiAnggota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengajuanPeminjamanController extends Controller
{
    /**
     * Show the loan application form for a specific book.
     */
    public function create(Buku $buku)
    {
        /** @var User $user */
        $user = Auth::user();
        $anggota = $user?->anggota;

        if (! $anggota) {
            return redirect()->route('katalog.show', $buku->id_buku)->with('error', 'Anda harus terdaftar sebagai anggota untuk meminjam buku.');
        }

        if ($anggota->status_anggota !== 'aktif') {
            return redirect()->route('katalog.show', $buku->id_buku)->with('error', 'Status keanggotaan Anda tidak aktif.');
        }

        // Check active sanctions
        $hasActiveSanction = SanksiAnggota::where('id_anggota', $anggota->id_anggota)
            ->where('status_sanksi', 'aktif')
            ->where('tanggal_selesai', '>=', today()->toDateString())
            ->exists();
        if ($hasActiveSanction) {
            return redirect()->route('katalog.show', $buku->id_buku)->with('error', 'Anda tidak dapat mengajukan peminjaman karena sedang dalam masa sanksi.');
        }

        // Check duplicate active loans for this book
        $existingPeminjaman = Peminjaman::where('id_anggota', $anggota->id_anggota)
            ->whereIn('status_peminjaman', ['diajukan', 'siap_diambil', 'aktif', 'terlambat'])
            ->whereHas('detailPeminjaman', function ($q) use ($buku) {
                $q->where('id_buku', $buku->id_buku);
            })
            ->exists();
        if ($existingPeminjaman) {
            return redirect()->route('katalog.show', $buku->id_buku)->with('error', 'Anda masih memiliki pengajuan atau peminjaman aktif untuk buku ini.');
        }

        // Get the active borrowing rule
        $aturan = AturanPeminjaman::where('status_aktif', true)->first();
        if (! $aturan) {
            return redirect()->route('katalog.show', $buku->id_buku)->with('error', 'Aturan peminjaman perpustakaan belum dikonfigurasi.');
        }

        // Check active borrowing limit
        $activeLoansCount = Peminjaman::where('id_anggota', $anggota->id_anggota)
            ->whereIn('status_peminjaman', ['diajukan', 'siap_diambil', 'aktif', 'terlambat'])
            ->count();
        if ($activeLoansCount >= $aturan->maksimal_peminjaman_aktif) {
            return redirect()->route('katalog.show', $buku->id_buku)->with('error', 'Anda telah mencapai batas maksimal peminjaman aktif ('.$aturan->maksimal_peminjaman_aktif.' buku).');
        }

        $buku->load(['kategori', 'eksemplar']);

        // Count available copies (using lowercase status_eksemplar)
        $tersediaCount = $buku->eksemplar
            ->where('status_eksemplar', 'tersedia')
            ->count();

        if ($tersediaCount <= 0) {
            return redirect()->route('katalog.show', $buku->id_buku)->with('error', 'Maaf, buku ini sedang tidak tersedia untuk dipinjam.');
        }

        return view('landing.pengajuan-peminjaman', compact(
            'buku',
            'anggota',
            'user',
            'aturan',
            'tersediaCount',
        ));
    }

    /**
     * Store a new loan application.
     */
    public function store(Request $request, Buku $buku)
    {
        $request->validate([
            'catatan_pengajuan' => 'nullable|string',
            'setuju_syarat' => 'accepted',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $anggota = $user?->anggota;

        if (! $anggota) {
            return redirect()->route('katalog.show', $buku->id_buku)->with('error', 'Anda harus terdaftar sebagai anggota untuk meminjam buku.');
        }

        if ($anggota->status_anggota !== 'aktif') {
            return redirect()->route('katalog.show', $buku->id_buku)->with('error', 'Status keanggotaan Anda tidak aktif.');
        }

        // Check active sanctions
        $hasActiveSanction = SanksiAnggota::where('id_anggota', $anggota->id_anggota)
            ->where('status_sanksi', 'aktif')
            ->where('tanggal_selesai', '>=', today()->toDateString())
            ->exists();
        if ($hasActiveSanction) {
            return redirect()->route('katalog.show', $buku->id_buku)->with('error', 'Anda tidak dapat mengajukan peminjaman karena sedang dalam masa sanksi.');
        }

        // Check duplicate active loans for this book
        $existingPeminjaman = Peminjaman::where('id_anggota', $anggota->id_anggota)
            ->whereIn('status_peminjaman', ['diajukan', 'siap_diambil', 'aktif', 'terlambat'])
            ->whereHas('detailPeminjaman', function ($q) use ($buku) {
                $q->where('id_buku', $buku->id_buku);
            })
            ->exists();
        if ($existingPeminjaman) {
            return redirect()->route('katalog.show', $buku->id_buku)->with('error', 'Anda masih memiliki pengajuan atau peminjaman aktif untuk buku ini.');
        }

        // Get the active borrowing rule
        $aturan = AturanPeminjaman::where('status_aktif', true)->first();
        if (! $aturan) {
            return redirect()->route('katalog.show', $buku->id_buku)->with('error', 'Aturan peminjaman perpustakaan belum dikonfigurasi.');
        }

        // Check active borrowing limit
        $activeLoansCount = Peminjaman::where('id_anggota', $anggota->id_anggota)
            ->whereIn('status_peminjaman', ['diajukan', 'siap_diambil', 'aktif', 'terlambat'])
            ->count();
        if ($activeLoansCount >= $aturan->maksimal_peminjaman_aktif) {
            return redirect()->route('katalog.show', $buku->id_buku)->with('error', 'Anda telah mencapai batas maksimal peminjaman aktif ('.$aturan->maksimal_peminjaman_aktif.' buku).');
        }

        // Verify there is an available copy
        $buku->load('eksemplar');
        $tersediaCount = $buku->eksemplar
            ->where('status_eksemplar', 'tersedia')
            ->count();

        if ($tersediaCount <= 0) {
            return redirect()->route('katalog.show', $buku->id_buku)->with('error', 'Maaf, buku ini sedang tidak tersedia untuk dipinjam.');
        }

        DB::transaction(function () use ($request, $buku, $anggota, $aturan) {
            // Generate a unique loan code
            $kodePeminjaman = 'PJM-'.date('Ymd').'-'.str_pad(
                Peminjaman::whereDate('tanggal_pengajuan', today())->count() + 1,
                4,
                '0',
                STR_PAD_LEFT
            );

            // Create the loan record
            $peminjaman = Peminjaman::create([
                'kode_peminjaman' => $kodePeminjaman,
                'id_anggota' => $anggota->id_anggota,
                'id_aturan' => $aturan?->id_aturan_peminjaman,
                'tanggal_pengajuan' => now(),
                'deskripsi_pengajuan' => $request->catatan_pengajuan,
                'status_peminjaman' => 'diajukan',
            ]);

            // Create the detail
            DetailPeminjaman::create([
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'id_buku' => $buku->id_buku,
                'jumlah' => 1,
                'status_detail' => 'diajukan',
            ]);
        });

        return redirect()
            ->route('katalog.show', $buku->id_buku)
            ->with('success', 'Pengajuan peminjaman berhasil! Silakan tunggu konfirmasi dari petugas.');
    }
}
