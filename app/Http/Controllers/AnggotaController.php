<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\SanksiAnggota;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AnggotaController extends Controller
{
    /**
     * Display a listing of the members.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $sanksi = $request->query('sanksi');

        $query = Anggota::query()
            ->with(['user', 'sanksi' => function ($q) {
                $q->where('status_sanksi', 'aktif');
            }]);

        // Search name or NIK
        if ($request->filled('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->filled('status') && $status !== 'all') {
            $query->where('status_anggota', $status);
        }

        // Filter sanksi
        if ($request->filled('sanksi') && $sanksi !== 'all') {
            if ($sanksi === 'Bebas') {
                $query->whereDoesHave('sanksi', function ($q) {
                    $q->where('status_sanksi', 'aktif');
                });
            } elseif ($sanksi === 'Sanksi') {
                $query->whereHas('sanksi', function ($q) {
                    $q->where('status_sanksi', 'aktif')->where('jenis_sanksi', 'not like', '%Blokir%');
                });
            } elseif ($sanksi === 'Diblokir') {
                $query->whereHas('sanksi', function ($q) {
                    $q->where('status_sanksi', 'aktif')->where('jenis_sanksi', 'like', '%Blokir%');
                });
            }
        }

        $anggota = $query->latest('id_anggota')->paginate(10)->withQueryString();

        return view('anggota.index', compact('anggota'));
    }

    /**
     * Display the specified member.
     */
    public function show(Anggota $anggota): View
    {
        $anggota->load([
            'user',
            'eKartuAnggota',
            'peminjaman' => function ($q) {
                $q->with('detailPeminjaman.buku')->latest('id_peminjaman');
            },
        ]);

        // Statistics
        $totalPinjam = $anggota->peminjaman()->count();
        $totalTerlambat = $anggota->peminjaman()->where('status_peminjaman', 'terlambat')->count();

        // Recent loans
        $riwayatPeminjaman = $anggota->peminjaman;

        return view('anggota.show', compact('anggota', 'totalPinjam', 'totalTerlambat', 'riwayatPeminjaman'));
    }

    /**
     * Show the form for editing the specified member.
     */
    public function edit(Anggota $anggota): View
    {
        $anggota->load(['user', 'eKartuAnggota']);

        // Check active sanksi to pre-select dropdown
        $activeSanksi = $anggota->sanksi()->where('status_sanksi', 'aktif')->first();
        $currentSanksi = 'Bersih';
        if ($activeSanksi) {
            if (stripos($activeSanksi->jenis_sanksi, 'Blokir') !== false) {
                $currentSanksi = 'Diblokir';
            } else {
                $currentSanksi = 'Sanksi 15 Hari';
            }
        }

        return view('anggota.edit', compact('anggota', 'currentSanksi'));
    }

    /**
     * Update the specified member in storage.
     */
    public function update(Request $request, Anggota $anggota): RedirectResponse
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'email' => 'required|email|max:50|unique:users,email,'.$anggota->id_user.',id_user',
            'no_telepon' => 'required|string|max:20',
            'alamat' => 'nullable|string',
            'status_anggota' => 'required|string|in:aktif,nonaktif,Aktif,Nonaktif',
            'status_sanksi' => 'required|string|in:Bersih,Sanksi 15 Hari,Diblokir',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Force consistency between Status Anggota and Status Sanksi
        $statusSanksi = $validated['status_sanksi'];
        $statusAnggota = strtolower($validated['status_anggota']);

        if ($statusSanksi === 'Diblokir') {
            $statusAnggota = 'nonaktif';
        } elseif ($statusAnggota === 'aktif' && $statusSanksi === 'Diblokir') {
            $statusSanksi = 'Bersih';
        }

        // Update User email & status_akun
        $user = $anggota->user;
        $user->update([
            'email' => $validated['email'],
            'status_akun' => $statusAnggota,
        ]);

        // Handle profile photo upload
        if ($request->hasFile('foto')) {
            if ($anggota->foto) {
                Storage::disk('public')->delete($anggota->foto);
            }
            $path = $request->file('foto')->store('anggota', 'public');
            $anggota->foto = $path;
        }

        // Update member fields
        $anggota->nama_lengkap = $validated['nama_lengkap'];
        $anggota->no_telepon = $validated['no_telepon'];
        if (array_key_exists('alamat', $validated)) {
            $anggota->alamat = $validated['alamat'];
        }
        $anggota->status_anggota = $statusAnggota;
        $anggota->save();

        // Handle sanksi updates
        if ($statusSanksi === 'Bersih') {
            // Set all active sanksi to selesai
            $anggota->sanksi()->where('status_sanksi', 'aktif')->update([
                'status_sanksi' => 'selesai',
                'tanggal_selesai' => now(),
            ]);
        } else {
            // Update or create active sanksi
            $jenisSanksi = $statusSanksi;
            $duration = null;
            if ($statusSanksi === 'Sanksi 15 Hari') {
                $jenisSanksi = 'Sanksi: 15 Hari';
                $duration = 15;
            }

            $activeSanksi = $anggota->sanksi()->where('status_sanksi', 'aktif')->first();

            if ($activeSanksi) {
                $activeSanksi->update([
                    'jenis_sanksi' => $jenisSanksi,
                    'tanggal_mulai' => $activeSanksi->tanggal_mulai ?? now(),
                    'tanggal_selesai' => $duration ? now()->addDays($duration) : null,
                ]);
            } else {
                // If there's a peminjaman, we can associate with the latest one, otherwise leave it null (which we made nullable in migrations!)
                $latestPeminjaman = $anggota->peminjaman()->latest('id_peminjaman')->first();

                SanksiAnggota::create([
                    'id_anggota' => $anggota->id_anggota,
                    'id_peminjaman' => $latestPeminjaman?->id_peminjaman ?? null,
                    'jenis_sanksi' => $jenisSanksi,
                    'alasan' => 'Diberikan oleh administrator',
                    'tanggal_mulai' => now(),
                    'tanggal_selesai' => $duration ? now()->addDays($duration) : null,
                    'status_sanksi' => 'aktif',
                ]);
            }
        }

        if ($request->input('redirect_to') === 'index') {
            return redirect()->route('petugas.anggota.index')
                ->with('success', 'Status anggota berhasil diperbarui');
        }

        return redirect()->route('petugas.anggota.show', $anggota->id_anggota)
            ->with('success', 'Data berhasil diperbarui');
    }
}
