<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class BukuController extends Controller
{
    public function create(): View
    {
        return view('buku.create');
    }

    public function show(int $id): View
    {
        return view('buku.show', compact('id'));
    }

    public function edit(int $id): View
    {
        return view('buku.edit', compact('id'));
    }

    public function tambahStok(int $id): View
    {
        return view('buku.tambah-stok', compact('id'));
    }
}
