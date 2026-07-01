<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLayananRequest;
use App\Http\Requests\UpdateLayananRequest;
use App\Models\Layanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LayananController extends Controller
{
    public function index(Request $request): View
    {
        $layanan = Layanan::query()
            ->with('createdBy')
            ->when($request->query('search'), function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('nama_layanan', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            })
            ->when($request->query('status'), fn ($query, string $status) => $query->where('status_layanan', $status))
            ->latest('id_layanan')
            ->paginate(8)
            ->withQueryString();

        return view('layanan.index', compact('layanan'));
    }

    public function create(): View
    {
        return view('layanan.create');
    }

    public function store(StoreLayananRequest $request): RedirectResponse
    {
        $data = $this->layananPayload($request);
        $data['created_by'] = $request->user()->petugas?->id_petugas;
        $data['slug'] = $this->uniqueSlug($data['nama_layanan']);

        Layanan::create($data);

        return redirect()
            ->route('petugas.layanan.index')
            ->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function show(Layanan $layanan): View
    {
        $layanan->load('createdBy');

        return view('layanan.show', compact('layanan'));
    }

    public function edit(Layanan $layanan): View
    {
        return view('layanan.edit', compact('layanan'));
    }

    public function update(UpdateLayananRequest $request, Layanan $layanan): RedirectResponse
    {
        $oldImage = $layanan->gambar;
        $data = $this->layananPayload($request);

        if ($layanan->nama_layanan !== $data['nama_layanan']) {
            $data['slug'] = $this->uniqueSlug($data['nama_layanan'], $layanan);
        }

        $layanan->update($data);

        if (array_key_exists('gambar', $data) && $oldImage && $oldImage !== $data['gambar']) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect()
            ->route('petugas.layanan.show', $layanan)
            ->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Layanan $layanan): RedirectResponse
    {
        if ($layanan->gambar) {
            Storage::disk('public')->delete($layanan->gambar);
        }

        $layanan->delete();

        return redirect()
            ->route('petugas.layanan.index')
            ->with('success', 'Layanan berhasil dihapus.');
    }

    /**
     * @return array{
     *     nama_layanan: string,
     *     deskripsi: string|null,
     *     persyaratan: string|null,
     *     prosedur: string|null,
     *     jam_layanan: string|null,
     *     biaya: string|null,
     *     kontak_layanan: string|null,
     *     status_layanan: string,
     *     gambar?: string
     * }
     */
    private function layananPayload(StoreLayananRequest|UpdateLayananRequest $request): array
    {
        $validated = $request->validated();

        $data = [
            'nama_layanan' => $validated['nama_layanan'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'persyaratan' => $this->linesFromArray($validated['persyaratan'] ?? []),
            'prosedur' => $this->linesFromArray($validated['prosedur'] ?? []),
            'jam_layanan' => $validated['jam_layanan'] ?? null,
            'biaya' => $validated['biaya'] ?? null,
            'kontak_layanan' => $validated['kontak_layanan'] ?? null,
            'status_layanan' => $validated['status_layanan'],
        ];

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('layanan', 'public');
        }

        return $data;
    }

    private function linesFromArray(array $items): ?string
    {
        $lines = collect($items)
            ->map(fn (?string $item): string => trim((string) $item))
            ->filter()
            ->values();

        return $lines->isEmpty() ? null : $lines->implode(PHP_EOL);
    }

    private function uniqueSlug(string $name, ?Layanan $ignore = null): string
    {
        $base = Str::slug($name) ?: Str::random(8);
        $slug = $base;
        $counter = 2;

        while (Layanan::query()
            ->where('slug', $slug)
            ->when($ignore, fn ($query) => $query->whereKeyNot($ignore->getKey()))
            ->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
