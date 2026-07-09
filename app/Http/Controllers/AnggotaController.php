<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\SanksiAnggota;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                $this->applyActiveSanksiFilter($q);
                $q->latest('id_sanksi_anggota');
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
                $query->whereDoesntHave('sanksi', function ($q) {
                    $this->applyActiveSanksiFilter($q);
                });
            } elseif ($sanksi === 'Sanksi') {
                $query->whereHas('sanksi', function ($q) {
                    $this->applyActiveSanksiFilter($q);
                    $this->applyNonBorrowingBlockTypeFilter($q);
                })->whereDoesntHave('sanksi', function ($q) {
                    $this->applyActiveSanksiFilter($q);
                    $this->applyBorrowingBlockTypeFilter($q);
                });
            } elseif ($sanksi === 'Diblokir') {
                $query->whereHas('sanksi', function ($q) {
                    $this->applyActiveSanksiFilter($q);
                    $this->applyBorrowingBlockTypeFilter($q);
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

        $totalPinjam = $anggota->peminjaman()
            ->whereIn('status_peminjaman', ['aktif', 'terlambat'])
            ->count();
        $totalTerlambat = $anggota->keterlambatan()->count();
        $totalHariTelat = (int) $anggota->keterlambatan()->sum('hari_terlambat');
        $terakhirTelat = $anggota->keterlambatan()
            ->whereNotNull('tanggal_dihitung')
            ->latest('tanggal_dihitung')
            ->latest('id_keterlambatan')
            ->first(['tanggal_dihitung']);
        $sanksiAktif = $anggota->sanksi()
            ->where(function ($q) {
                $this->applyActiveSanksiFilter($q);
            })
            ->count();

        $statistikPeminjaman = [
            'buku_dipinjam' => $totalPinjam,
            'keterlambatan' => $totalTerlambat,
            'total_hari_telat' => $totalHariTelat,
            'status_risiko' => $totalTerlambat >= 3 ? 'Perlu Review' : 'Aman',
            'sanksi_aktif' => $sanksiAktif,
            'terakhir_telat' => $terakhirTelat?->tanggal_dihitung
                ? $terakhirTelat->tanggal_dihitung->locale('id')->translatedFormat('d M Y')
                : '-',
        ];

        $sanksiBadge = $this->resolveProfileSanksiBadge($this->activeSanksiForDisplay($anggota));

        // Recent loans
        $riwayatPeminjaman = $anggota->peminjaman;

        return view('anggota.show', compact('anggota', 'statistikPeminjaman', 'sanksiBadge', 'riwayatPeminjaman'));
    }

    /**
     * Show the form for editing the specified member.
     */
    public function edit(Anggota $anggota): View
    {
        $anggota->load(['user', 'eKartuAnggota']);

        $activeSanksi = $this->activeSanksiForDisplay($anggota);

        $statusAnggota = $this->resolveAnggotaStatus($anggota);
        $statusSanksi = $this->resolveSanksiStatus($activeSanksi);
        $isBorrowingBlocked = $this->hasActiveBorrowingBlock($anggota);
        $isAnggotaAktif = strtolower((string) $anggota->status_anggota) === 'aktif';

        return view('anggota.edit', compact('anggota', 'statusAnggota', 'statusSanksi', 'isBorrowingBlocked', 'isAnggotaAktif'));
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
     * Deactivate the member account from the administrative action panel.
     */
    public function deactivate(Request $request, Anggota $anggota): RedirectResponse
    {
        $validated = $request->validate([
            'alasan_nonaktif' => ['required', 'string', 'max:1000'],
            'administrative_action' => ['nullable', 'string'],
        ]);

        if (strtolower((string) $anggota->status_anggota) !== 'aktif') {
            return redirect()->route('petugas.anggota.edit', $anggota->id_anggota)
                ->withErrors(['alasan_nonaktif' => 'Anggota sudah berstatus nonaktif.'])
                ->withInput();
        }

        DB::transaction(function () use ($anggota, $validated) {
            $anggota->update([
                'status_anggota' => 'nonaktif',
                'alasan_nonaktif' => $validated['alasan_nonaktif'],
            ]);

            $anggota->user()->update([
                'status_akun' => 'nonaktif',
            ]);
        });

        return redirect()->route('petugas.anggota.edit', $anggota->id_anggota)
            ->with('success', 'Anggota berhasil dinonaktifkan.');
    }

    /**
     * Reactivate the member account from the administrative action panel.
     */
    public function activate(Request $request, Anggota $anggota): RedirectResponse
    {
        $request->validate([
            'administrative_action' => ['nullable', 'string'],
        ]);

        if (strtolower((string) $anggota->status_anggota) === 'aktif') {
            return redirect()->route('petugas.anggota.edit', $anggota->id_anggota)
                ->withErrors(['administrative_action' => 'Anggota sudah berstatus aktif.'])
                ->withInput();
        }

        DB::transaction(function () use ($anggota) {
            $anggota->update([
                'status_anggota' => 'aktif',
                'alasan_nonaktif' => null,
            ]);

            $anggota->user()->update([
                'status_akun' => 'aktif',
            ]);
        });

        return redirect()->route('petugas.anggota.edit', $anggota->id_anggota)
            ->with('success', 'Anggota berhasil diaktifkan kembali.');
    }

    /**
     * Block borrowing access from the administrative action panel.
     */
    public function blockBorrowing(Request $request, Anggota $anggota): RedirectResponse
    {
        $validated = $request->validate([
            'alasan_blokir' => ['required', 'string', 'max:1000'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:today'],
            'administrative_action' => ['nullable', 'string'],
        ]);

        if ($this->hasActiveBorrowingBlock($anggota)) {
            return redirect()->route('petugas.anggota.edit', $anggota->id_anggota)
                ->withErrors(['alasan_blokir' => 'Peminjaman anggota ini sudah diblokir.'])
                ->withInput();
        }

        SanksiAnggota::create([
            'id_anggota' => $anggota->id_anggota,
            'id_peminjaman' => null,
            'id_keterlambatan' => null,
            'jenis_sanksi' => 'Diblokir',
            'alasan' => $validated['alasan_blokir'],
            'tanggal_mulai' => today(),
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
            'status_sanksi' => 'aktif',
        ]);

        return redirect()->route('petugas.anggota.edit', $anggota->id_anggota)
            ->with('success', 'Peminjaman anggota berhasil diblokir.');
    }

    /**
     * End active borrowing blocks from the administrative action panel.
     */
    public function unblockBorrowing(Request $request, Anggota $anggota): RedirectResponse
    {
        $validated = $request->validate([
            'catatan_buka_blokir' => ['nullable', 'string', 'max:1000'],
            'administrative_action' => ['nullable', 'string'],
        ]);

        $activeBlocks = $this->activeBorrowingBlocks($anggota)->get();

        if ($activeBlocks->isEmpty()) {
            return redirect()->route('petugas.anggota.edit', $anggota->id_anggota)
                ->withErrors(['catatan_buka_blokir' => 'Tidak ada blokir peminjaman aktif untuk anggota ini.'])
                ->withInput();
        }

        foreach ($activeBlocks as $activeBlock) {
            $alasan = $activeBlock->alasan;

            if (! empty($validated['catatan_buka_blokir'])) {
                $alasan = trim(((string) $alasan)."\n\nCatatan buka blokir: ".$validated['catatan_buka_blokir']);
            }

            $activeBlock->update([
                'alasan' => $alasan,
                'tanggal_selesai' => today(),
                'status_sanksi' => 'selesai',
            ]);
        }

        return redirect()->route('petugas.anggota.edit', $anggota->id_anggota)
            ->with('success', 'Blokir peminjaman berhasil dibuka.');
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

    /**
     * @return array{label: string, class: string}
     */
    private function resolveProfileSanksiBadge(?SanksiAnggota $activeSanksi): array
    {
        if (! $activeSanksi) {
            return [
                'label' => 'Bebas Sanksi',
                'class' => 'bg-slate-100 text-slate-600 border-slate-200',
            ];
        }

        if (stripos((string) $activeSanksi->jenis_sanksi, 'Blokir') !== false) {
            return [
                'label' => 'Diblokir',
                'class' => 'bg-rose-50 text-rose-600 border-rose-100',
            ];
        }

        return [
            'label' => $activeSanksi->jenis_sanksi ?: 'Sedang Sanksi',
            'class' => 'bg-amber-50 text-amber-700 border-amber-100',
        ];
    }

    private function activeSanksiForDisplay(Anggota $anggota): ?SanksiAnggota
    {
        $activeSanksi = $anggota->sanksi()
            ->where(function ($q) {
                $this->applyActiveSanksiFilter($q);
            })
            ->latest('id_sanksi_anggota')
            ->get();

        return $activeSanksi->first(
            fn (SanksiAnggota $sanksi): bool => stripos((string) $sanksi->jenis_sanksi, 'Blokir') !== false
        ) ?? $activeSanksi->first();
    }

    private function hasActiveBorrowingBlock(Anggota $anggota): bool
    {
        return $this->activeBorrowingBlocks($anggota)->exists();
    }

    private function activeBorrowingBlocks(Anggota $anggota)
    {
        return $anggota->sanksi()
            ->where(function ($q) {
                $this->applyActiveSanksiFilter($q);
            })
            ->where(function ($q) {
                $this->applyBorrowingBlockTypeFilter($q);
            });
    }

    private function applyActiveSanksiFilter($query): void
    {
        $query->where('status_sanksi', 'aktif')
            ->where(function ($q) {
                $q->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', today());
            });
    }

    private function applyBorrowingBlockTypeFilter($query): void
    {
        $query->where(function ($q) {
            $q->where('jenis_sanksi', 'Diblokir')
                ->orWhere('jenis_sanksi', 'like', '%blokir%')
                ->orWhere('jenis_sanksi', 'like', '%Blokir%');
        });
    }

    private function applyNonBorrowingBlockTypeFilter($query): void
    {
        $query->where(function ($q) {
            $q->whereNull('jenis_sanksi')
                ->orWhere(function ($q) {
                    $q->where('jenis_sanksi', '!=', 'Diblokir')
                        ->where('jenis_sanksi', 'not like', '%blokir%')
                        ->where('jenis_sanksi', 'not like', '%Blokir%');
                });
        });
    }
}
