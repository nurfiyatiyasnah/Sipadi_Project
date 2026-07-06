<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\EksemplarBuku;
use App\Models\KategoriBuku;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BookController extends Controller
{
    public function create(): View
    {
        return view('admin.books.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:200'],
            'penulis' => ['required', 'string', 'max:150'],
            'penerbit' => ['nullable', 'string', 'max:150'],
            'tahun' => ['nullable', 'integer', 'digits:4'],
            'isbn' => ['nullable', 'string', 'max:30'],
            'kategori' => ['required', 'string', 'max:50'],
            'lokasi' => ['nullable', 'string', 'max:100'],
            'stok' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'string', 'max:20'],
            'sinopsis' => ['nullable', 'string'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $cover = null;

        if ($request->hasFile('cover')) {
            $cover = $request->file('cover')
                ->store('covers', 'public');
        }

        $kategori = KategoriBuku::firstOrCreate([
            'nama_kategori' => $validated['kategori'],
        ]);

        $buku = Buku::create([
            'id_kategori' => $kategori->getKey(),
            'kode_buku' => 'BK-'.Str::upper(Str::random(8)),
            'judul' => $validated['judul'],
            'penulis' => $validated['penulis'],
            'penerbit' => $validated['penerbit'] ?? null,
            'tahun_terbit' => $validated['tahun'] ?? null,
            'isbn' => $validated['isbn'] ?? null,
            'deskripsi' => $validated['sinopsis'] ?? null,
            'gambar_cover' => $cover,
            'status_katalog' => $validated['status'],
        ]);

        for ($number = 1; $number <= $validated['stok']; $number++) {
            EksemplarBuku::create([
                'id_buku' => $buku->getKey(),
                'kode_eksemplar' => $buku->kode_buku.'-'.$number,
                'status_eksemplar' => $validated['status'],
                'lokasi_rak' => $validated['lokasi'] ?? null,
                'tanggal_masuk' => now(),
            ]);
        }

        return redirect()
            ->route('admin.dashboard.koleksi')
            ->with('success', 'Buku berhasil ditambahkan');
    }

    public function show(Buku $buku): RedirectResponse
    {
        return redirect()
            ->route('admin.dashboard.koleksi')
            ->with('success', "Detail buku {$buku->judul} belum memiliki halaman khusus.");
    }
}
