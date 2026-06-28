<?php

namespace App\Http\Controllers;

use App\Models\AturanPeminjaman;
use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\JadwalPengambilan;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PengajuanPeminjamanController extends Controller
{
    /**
     * Show the loan application form for a specific book.
     */
    public function create(Buku $buku): View
    {
        $buku->load(['kategori', 'eksemplar']);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $anggota = $user->anggota;

        // Get the active borrowing rule
        $aturan = AturanPeminjaman::where('status_aktif', true)->first();

        // Count available copies
        $tersediaCount = $buku->eksemplar
            ->whereIn('status_eksemplar', ['tersedia', 'Tersedia'])
            ->count();

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
            'tanggal_pengambilan' => 'required|date|after_or_equal:today',
            'jam_pengambilan'     => 'required|string',
            'setuju_syarat'       => 'accepted',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $anggota = $user->anggota;

        // Get the active borrowing rule
        $aturan = AturanPeminjaman::where('status_aktif', true)->first();

        // Verify there is an available copy
        $buku->load('eksemplar');
        $tersediaCount = $buku->eksemplar
            ->whereIn('status_eksemplar', ['tersedia', 'Tersedia'])
            ->count();

        if ($tersediaCount <= 0) {
            return back()->with('error', 'Maaf, buku ini sedang tidak tersedia untuk dipinjam.');
        }

        DB::transaction(function () use ($request, $buku, $anggota, $aturan) {
            // Generate a unique loan code
            $kodePeminjaman = 'PJM-' . date('Ymd') . '-' . str_pad(
                Peminjaman::whereDate('tanggal_pengajuan', today())->count() + 1,
                4,
                '0',
                STR_PAD_LEFT
            );

            // Create the loan record
            $peminjaman = Peminjaman::create([
                'kode_peminjaman'    => $kodePeminjaman,
                'id_anggota'         => $anggota->id_anggota,
                'id_aturan'          => $aturan?->id_aturan_peminjaman,
                'tanggal_pengajuan'  => now(),
                'status_peminjaman'  => 'menunggu',
            ]);

            // Create the detail
            DetailPeminjaman::create([
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'id_buku'       => $buku->id_buku,
                'jumlah'        => 1,
                'status_detail' => 'menunggu',
            ]);

            // Create the pickup schedule
            JadwalPengambilan::create([
                'id_peminjaman'       => $peminjaman->id_peminjaman,
                'tanggal_pengambilan' => $request->tanggal_pengambilan,
                'jam_mulai'           => $request->jam_pengambilan,
                'jam_selesai'         => date('H:i', strtotime($request->jam_pengambilan) + 3600),
                'lokasi_pengambilan'  => 'Meja Sirkulasi Lantai 1',
                'status_jadwal'       => 'menunggu',
            ]);
        });

        return redirect()
            ->route('katalog.show', $buku->id_buku)
            ->with('success', 'Pengajuan peminjaman berhasil! Silakan tunggu konfirmasi dari petugas.');
    }
}
