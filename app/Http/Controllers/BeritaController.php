<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBeritaRequest;
use App\Http\Requests\UpdateBeritaRequest;
use App\Models\Berita;
use App\Models\KategoriBerita;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BeritaController extends Controller
{
    public function index(Request $request): View
    {
        $kategoriList = KategoriBerita::query()
            ->orderBy('nama_kategori')
            ->get(['id_kategori_berita', 'nama_kategori']);

        $berita = Berita::query()
            ->with(['kategoriBerita', 'petugas'])
            ->filter(
                search: $request->query('search'),
                kategori: $request->query('kategori'),
                status: $request->query('status'),
            )
            ->latest('id_berita')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Berita::count(),
            'terbit' => Berita::published()->count(),
            'draft' => Berita::draft()->count(),
        ];

        return view('berita.index', compact('berita', 'kategoriList', 'stats'));
    }

    public function create(): View
    {
        $kategoriList = KategoriBerita::query()
            ->orderBy('nama_kategori')
            ->get(['id_kategori_berita', 'nama_kategori']);

        return view('berita.create', compact('kategoriList'));
    }

    public function store(StoreBeritaRequest $request): RedirectResponse
    {
        $data = $this->beritaPayload($request);
        $data['id_petugas'] = $request->user()->petugas?->id_petugas;
        $data['slug'] = $this->uniqueSlug($data['judul']);

        Berita::create($data);

        $message = $data['status_berita'] === Berita::STATUS_PUBLISHED
            ? 'Berita berhasil diterbitkan.'
            : 'Berita berhasil disimpan sebagai draft.';

        return redirect()->route('petugas.berita.index')->with('success', $message);
    }

    public function edit(Berita $berita): View
    {
        $kategoriList = KategoriBerita::query()
            ->orderBy('nama_kategori')
            ->get(['id_kategori_berita', 'nama_kategori']);

        return view('berita.edit', compact('berita', 'kategoriList'));
    }

    public function update(UpdateBeritaRequest $request, Berita $berita): RedirectResponse
    {
        $oldImage = $berita->gambar;
        $data = $this->beritaPayload($request, $berita);

        if ($berita->judul !== $data['judul']) {
            $data['slug'] = $this->uniqueSlug($data['judul'], $berita);
        }

        $berita->update($data);

        if (array_key_exists('gambar', $data) && $oldImage && $oldImage !== $data['gambar']) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect()->route('petugas.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function publish(Berita $berita): RedirectResponse
    {
        $berita->update([
            'status_berita' => Berita::STATUS_PUBLISHED,
            'tanggal_terbit' => $berita->tanggal_terbit ?? now(),
        ]);

        return redirect()->route('petugas.berita.index')->with('success', 'Berita berhasil diterbitkan.');
    }

    public function destroy(Berita $berita): RedirectResponse
    {
        if ($berita->gambar) {
            Storage::disk('public')->delete($berita->gambar);
        }

        $berita->delete();

        return redirect()->route('petugas.berita.index')->with('success', 'Berita berhasil dihapus.');
    }

    private function beritaPayload(StoreBeritaRequest|UpdateBeritaRequest $request, ?Berita $berita = null): array
    {
        $validated = $request->validated();

        $data = [
            'id_kategori_berita' => $validated['id_kategori_berita'],
            'judul' => $validated['judul'],
            'isi' => $validated['isi'] ?? null,
            'status_berita' => $validated['status_berita'],
            'tanggal_terbit' => $validated['status_berita'] === Berita::STATUS_PUBLISHED
                ? ($berita?->tanggal_terbit ?? now())
                : null,
        ];

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        return $data;
    }

    private function uniqueSlug(string $title, ?Berita $ignore = null): string
    {
        $base = Str::slug($title) ?: Str::random(8);
        $slug = $base;
        $counter = 2;

        while (Berita::query()
            ->where('slug', $slug)
            ->when($ignore, fn ($query) => $query->whereKeyNot($ignore->getKey()))
            ->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
