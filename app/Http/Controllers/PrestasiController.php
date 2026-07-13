<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePrestasiRequest;
use App\Http\Requests\UpdatePrestasiRequest;
use App\Models\Prestasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PrestasiController extends Controller
{
    public function index(Request $request): View
    {
        $prestasi = Prestasi::query()
            ->with('createdBy')
            ->filter(
                search: $request->query('search'),
                tingkat: $request->query('tingkat'),
                status: $request->query('status'),
                tahun: $request->query('tahun'),
            )
            ->latest('id_prestasi')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Prestasi::count(),
            'terbit' => Prestasi::where('status_prestasi', Prestasi::STATUS_PUBLISHED)->count(),
            'draft' => Prestasi::where('status_prestasi', Prestasi::STATUS_DRAFT)->count(),
            'nonaktif' => Prestasi::where('status_prestasi', Prestasi::STATUS_INACTIVE)->count(),
        ];

        $tahunList = Prestasi::query()
            ->whereNotNull('tanggal_prestasi')
            ->get(['tanggal_prestasi'])
            ->map(fn (Prestasi $prestasi): int => $prestasi->tanggal_prestasi->year)
            ->unique()
            ->sortDesc()
            ->values();

        return view('prestasi.index', compact('prestasi', 'stats', 'tahunList'));
    }

    public function create(): View
    {
        return view('prestasi.create');
    }

    public function store(StorePrestasiRequest $request): RedirectResponse
    {
        $data = $this->prestasiPayload($request);
        $data['created_by'] = $request->user()->petugas?->id_petugas;
        $data['slug'] = $this->uniqueSlug($data['judul_prestasi']);

        Prestasi::create($data);

        return redirect()
            ->route('petugas.prestasi.index')
            ->with('success', 'Prestasi berhasil ditambahkan.');
    }

    public function show(Prestasi $prestasi): View
    {
        $prestasi->load('createdBy');

        return view('prestasi.show', compact('prestasi'));
    }

    public function edit(Prestasi $prestasi): View
    {
        return view('prestasi.edit', compact('prestasi'));
    }

    public function update(UpdatePrestasiRequest $request, Prestasi $prestasi): RedirectResponse
    {
        $oldImage = $prestasi->gambar;
        $oldAttachment = $prestasi->file_lampiran;
        $data = $this->prestasiPayload($request);

        if ($prestasi->judul_prestasi !== $data['judul_prestasi']) {
            $data['slug'] = $this->uniqueSlug($data['judul_prestasi'], $prestasi);
        }

        $prestasi->update($data);

        if (array_key_exists('gambar', $data) && $oldImage && $oldImage !== $data['gambar']) {
            Storage::disk('public')->delete($oldImage);
        }

        if (array_key_exists('file_lampiran', $data) && $oldAttachment && $oldAttachment !== $data['file_lampiran']) {
            Storage::disk('public')->delete($oldAttachment);
        }

        return redirect()
            ->route('petugas.prestasi.show', $prestasi)
            ->with('success', 'Prestasi berhasil diperbarui.');
    }

    public function destroy(Prestasi $prestasi): RedirectResponse
    {
        if ($prestasi->gambar) {
            Storage::disk('public')->delete($prestasi->gambar);
        }

        if ($prestasi->file_lampiran) {
            Storage::disk('public')->delete($prestasi->file_lampiran);
        }

        $prestasi->delete();

        return redirect()
            ->route('petugas.prestasi.index')
            ->with('success', 'Prestasi berhasil dihapus.');
    }

    /**
     * @return array{
     *     judul_prestasi: string,
     *     deskripsi: string|null,
     *     tingkat_prestasi: string,
     *     penyelenggara: string|null,
     *     nomor_sertifikat: string|null,
     *     tanggal_prestasi: string|null,
     *     status_prestasi: string,
     *     gambar?: string,
     *     file_lampiran?: string
     * }
     */
    private function prestasiPayload(StorePrestasiRequest|UpdatePrestasiRequest $request): array
    {
        $validated = $request->validated();

        $data = [
            'judul_prestasi' => $validated['judul_prestasi'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'tingkat_prestasi' => $validated['tingkat_prestasi'],
            'penyelenggara' => $validated['penyelenggara'] ?? null,
            'nomor_sertifikat' => $validated['nomor_sertifikat'] ?? null,
            'tanggal_prestasi' => $validated['tanggal_prestasi'] ?? null,
            'status_prestasi' => $validated['status_prestasi'],
        ];

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('prestasi', 'public');
        }

        if ($request->hasFile('file_lampiran')) {
            $data['file_lampiran'] = $request->file('file_lampiran')->store('prestasi/lampiran', 'public');
        }

        return $data;
    }

    private function uniqueSlug(string $title, ?Prestasi $ignore = null): string
    {
        $base = Str::slug($title) ?: Str::random(8);
        $slug = $base;
        $counter = 2;

        while (Prestasi::query()
            ->where('slug', $slug)
            ->when($ignore, fn ($query) => $query->whereKeyNot($ignore->getKey()))
            ->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
