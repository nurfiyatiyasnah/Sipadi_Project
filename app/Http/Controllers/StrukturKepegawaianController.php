<?php

namespace App\Http\Controllers;

use App\Models\StrukturKepegawaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StrukturKepegawaianController extends Controller
{
    public function index()
    {
        $kepegawaian = StrukturKepegawaian::query()
            ->orderBy('urutan')
            ->orderBy('nama')
            ->paginate(10);

        return view('kepegawaian.index', compact('kepegawaian'));
    }

    public function create()
    {
        return view('kepegawaian.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'urutan' => 'nullable|integer',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('kepegawaian', 'public');
        }

        if (! isset($validated['urutan'])) {
            $validated['urutan'] = 0;
        }

        StrukturKepegawaian::create($validated);

        return redirect()->route('petugas.kepegawaian.index')->with('success', 'Anggota kepegawaian berhasil ditambahkan.');
    }

    public function edit(StrukturKepegawaian $kepegawaian)
    {
        return view('kepegawaian.edit', compact('kepegawaian'));
    }

    public function update(Request $request, StrukturKepegawaian $kepegawaian)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'urutan' => 'nullable|integer',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($kepegawaian->foto) {
                Storage::disk('public')->delete($kepegawaian->foto);
            }
            $validated['foto'] = $request->file('foto')->store('kepegawaian', 'public');
        }

        if (! isset($validated['urutan'])) {
            $validated['urutan'] = 0;
        }

        $kepegawaian->update($validated);

        return redirect()->route('petugas.kepegawaian.index')->with('success', 'Anggota kepegawaian berhasil diperbarui.');
    }

    public function destroy(StrukturKepegawaian $kepegawaian)
    {
        if ($kepegawaian->foto) {
            Storage::disk('public')->delete($kepegawaian->foto);
        }

        $kepegawaian->delete();

        return redirect()->route('petugas.kepegawaian.index')->with('success', 'Anggota kepegawaian berhasil dihapus.');
    }
}
