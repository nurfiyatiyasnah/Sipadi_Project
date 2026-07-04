<?php

namespace App\Http\Controllers;

use App\Models\StrukturOrganisasi;
use Illuminate\Http\Request;

class StrukturOrganisasiController extends Controller
{
    public function index()
    {
        $organisasi = StrukturOrganisasi::query()
            ->orderBy('urutan')
            ->orderBy('nama')
            ->paginate(10);

        return view('organisasi.index', compact('organisasi'));
    }

    public function create()
    {
        return view('organisasi.create');
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
            $validated['foto'] = $request->file('foto')->store('organisasi', 'public');
        }

        if (!isset($validated['urutan'])) {
            $validated['urutan'] = 0;
        }

        StrukturOrganisasi::create($validated);

        return redirect()->route('petugas.organisasi.index')->with('success', 'Anggota organisasi berhasil ditambahkan.');
    }

    public function edit(StrukturOrganisasi $organisasi)
    {
        return view('organisasi.edit', compact('organisasi'));
    }

    public function update(Request $request, StrukturOrganisasi $organisasi)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'urutan' => 'nullable|integer',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($organisasi->foto) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($organisasi->foto);
            }
            $validated['foto'] = $request->file('foto')->store('organisasi', 'public');
        }

        if (!isset($validated['urutan'])) {
            $validated['urutan'] = 0;
        }

        $organisasi->update($validated);

        return redirect()->route('petugas.organisasi.index')->with('success', 'Anggota organisasi berhasil diperbarui.');
    }

    public function destroy(StrukturOrganisasi $organisasi)
    {
        if ($organisasi->foto) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($organisasi->foto);
        }
        
        $organisasi->delete();

        return redirect()->route('petugas.organisasi.index')->with('success', 'Anggota organisasi berhasil dihapus.');
    }
}
