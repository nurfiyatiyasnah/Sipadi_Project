<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function create()
    {
        return view('admin.books.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required',
            'isbn' => 'nullable',
            'kategori' => 'required',
            'lokasi_rak' => 'required',
            'stok' => 'required|integer',
            'status' => 'required',
            'sinopsis' => 'nullable',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $cover = null;

        if ($request->hasFile('cover')) {
            $cover = $request->file('cover')
                ->store('covers', 'public');
        }

        Book::create([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'penerbit' => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit,
            'isbn' => $request->isbn,
            'kategori' => $request->kategori,
            'lokasi_rak' => $request->lokasi_rak,
            'stok' => $request->stok,
            'status' => $request->status,
            'sinopsis' => $request->sinopsis,
            'cover' => $cover
        ]);

        return redirect()
            ->route('books.index')
            ->with('success', 'Buku berhasil ditambahkan');
    }
}