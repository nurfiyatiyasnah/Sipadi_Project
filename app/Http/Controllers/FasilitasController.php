<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FasilitasController extends Controller
{
    public function index(Request $request)
    {
        $query = Fasilitas::query();

        // Filter kategori
        $kategori = $request->get('kategori', 'Semua');
        if ($kategori !== 'Semua') {
            $query->where('kategori', $kategori);
        }

        // Search
        if ($search = $request->get('search')) {
            $query->where('nama_fasilitas', 'like', "%{$search}%")
                ->orWhere('lokasi', 'like', "%{$search}%");
        }

        $fasilitas = $query->latest('id_fasilitas')->paginate(12);

        // Statistik
        $stats = [
            'total_ruangan' => Fasilitas::where('kategori', 'Ruangan')->count(),
            'perangkat_it' => Fasilitas::where('kategori', 'Elektronik')->count(),
            'perlu_perbaikan' => Fasilitas::where('status_fasilitas', 'maintenance')->orWhere('status_fasilitas', 'perbaikan')->count(),
            'status_baik' => Fasilitas::count() > 0 ? round((Fasilitas::whereIn('status_fasilitas', ['tersedia', 'aktif', 'digunakan'])->count() / Fasilitas::count()) * 100) : 0,
        ];

        return view('petugas.fasilitas.index', compact('fasilitas', 'kategori', 'stats'));
    }

    public function create()
    {
        return view('petugas.fasilitas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_fasilitas' => 'required|string|max:150',
            'kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'jumlah_unit' => 'nullable|integer',
            'satuan_kapasitas' => 'nullable|string|max:50',
            'lokasi' => 'nullable|string|max:150',
            'status_fasilitas' => 'nullable|string|max:20',
            'tampilkan_publik' => 'boolean',
            'aktifkan_reservasi' => 'boolean',
            'metode_peminjaman' => 'nullable|string|max:100',
            'durasi_maksimal' => 'nullable|integer',
            'kelengkapan' => 'nullable|array',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['nama_fasilitas']);
        $validated['created_by'] = auth()->user()->petugas?->id_petugas;
        $validated['tampilkan_publik'] = $request->has('tampilkan_publik');
        $validated['aktifkan_reservasi'] = $request->has('aktifkan_reservasi');

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('fasilitas', 'public');
            $validated['gambar'] = $path;
        }

        Fasilitas::create($validated);

        return redirect()->route('petugas.fasilitas.index')->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function show(Fasilitas $fasilita)
    {
        return view('petugas.fasilitas.show', compact('fasilita'));
    }

    public function edit(Fasilitas $fasilita)
    {
        return view('petugas.fasilitas.edit', compact('fasilita'));
    }

    public function update(Request $request, Fasilitas $fasilita)
    {
        $validated = $request->validate([
            'nama_fasilitas' => 'required|string|max:150',
            'kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'jumlah_unit' => 'nullable|integer',
            'satuan_kapasitas' => 'nullable|string|max:50',
            'lokasi' => 'nullable|string|max:150',
            'status_fasilitas' => 'nullable|string|max:20',
            'tampilkan_publik' => 'boolean',
            'aktifkan_reservasi' => 'boolean',
            'metode_peminjaman' => 'nullable|string|max:100',
            'durasi_maksimal' => 'nullable|integer',
            'kelengkapan' => 'nullable|array',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['nama_fasilitas']);
        $validated['tampilkan_publik'] = $request->has('tampilkan_publik');
        $validated['aktifkan_reservasi'] = $request->has('aktifkan_reservasi');

        if (! $request->has('kelengkapan')) {
            $validated['kelengkapan'] = [];
        }

        if ($request->hasFile('gambar')) {
            if ($fasilita->gambar) {
                Storage::disk('public')->delete($fasilita->gambar);
            }
            $path = $request->file('gambar')->store('fasilitas', 'public');
            $validated['gambar'] = $path;
        }

        $fasilita->update($validated);

        return redirect()->route('petugas.fasilitas.index')->with('success', 'Data fasilitas berhasil diperbarui.');
    }

    public function destroy(Fasilitas $fasilita)
    {
        if ($fasilita->gambar) {
            Storage::disk('public')->delete($fasilita->gambar);
        }
        $fasilita->delete();

        return redirect()->route('petugas.fasilitas.index')->with('success', 'Fasilitas berhasil dihapus.');
    }
}
