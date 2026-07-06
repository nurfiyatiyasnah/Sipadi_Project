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
            'sanksi' => function ($q) {
                $q->where('status_sanksi', 'aktif');
            },
            'peminjaman' => function ($q) {
                $q->with(['detailPeminjaman.buku', 'pengembalian'])->latest('id_peminjaman');
            },
        ]);

        // Statistics
        $totalPinjam = $anggota->peminjaman()
            ->whereIn('status_peminjaman', ['aktif', 'terlambat'])
            ->count();
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

        $activeSanksi = $anggota->sanksi()
            ->where('status_sanksi', 'aktif')
            ->where(function ($q) {
                $q->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', today());
            })
            ->latest('id_sanksi_anggota')
            ->first();

        $statusAnggota = $this->resolveAnggotaStatus($anggota);
        $statusSanksi = $this->resolveSanksiStatus($activeSanksi);

        return view('anggota.edit', compact('anggota', 'statusAnggota', 'statusSanksi'));
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
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Update User email
        $user = $anggota->user;
        $user->update([
            'email' => $validated['email'],
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
        $anggota->save();

        if ($request->input('redirect_to') === 'index') {
            return redirect()->route('petugas.anggota.index')
                ->with('success', 'Data anggota berhasil diperbarui');
        }

        return redirect()->route('petugas.anggota.show', $anggota->id_anggota)
            ->with('success', 'Data berhasil diperbarui');
    }

    /**
     * @return array{label: string, class: string, description: string}
     */
    private function resolveAnggotaStatus(Anggota $anggota): array
    {
        if (strtolower((string) $anggota->status_anggota) === 'aktif') {
            return [
                'label' => 'Aktif',
                'class' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                'description' => 'Akun anggota aktif.',
            ];
        }

        return [
            'label' => 'Nonaktif',
            'class' => 'bg-rose-50 text-rose-700 border-rose-100',
            'description' => 'Akun anggota sedang nonaktif.',
        ];
    }

    /**
     * @return array{label: string, class: string, description: string}
     */
    private function resolveSanksiStatus(?SanksiAnggota $activeSanksi): array
    {
        if (! $activeSanksi) {
            return [
                'label' => 'Bebas Sanksi',
                'class' => 'bg-slate-100 text-slate-600 border-slate-200',
                'description' => 'Tidak ada sanksi peminjaman aktif.',
            ];
        }

        if (stripos((string) $activeSanksi->jenis_sanksi, 'Blokir') !== false) {
            return [
                'label' => 'Diblokir',
                'class' => 'bg-rose-50 text-rose-700 border-rose-100',
                'description' => 'Anggota sedang diblokir dari layanan peminjaman.',
            ];
        }

        $jenisSanksi = $activeSanksi->jenis_sanksi ?: 'Sanksi peminjaman';
        $description = $activeSanksi->tanggal_selesai
            ? $jenisSanksi.' sampai '.$activeSanksi->tanggal_selesai->locale('id')->translatedFormat('d F Y')
            : $jenisSanksi;

        return [
            'label' => 'Sedang Sanksi',
            'class' => 'bg-amber-50 text-amber-700 border-amber-100',
            'description' => $description,
        ];
    }
}
