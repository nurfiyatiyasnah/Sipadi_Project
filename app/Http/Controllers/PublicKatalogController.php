<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicKatalogController extends Controller
{
    public function index(Request $request): View
    {
        // 1. Fetch categories for sidebar filter
        $categories = KategoriBuku::orderBy('nama_kategori')->get();

        // 3. Build query
        $query = Buku::query()
            ->with('kategori')
            ->withCount('eksemplar')
            ->withCount([
                'eksemplar as eksemplar_tersedia_count' => fn ($q) => $q
                    ->whereIn('status_eksemplar', ['tersedia', 'Tersedia', 'aktif', 'Aktif']),
            ]);

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('penulis', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        // Category Filter
        if ($request->filled('kategori')) {
            $kategoriIds = $request->query('kategori');
            if (is_array($kategoriIds)) {
                $query->whereIn('id_kategori', $kategoriIds);
            } else {
                $query->where('id_kategori', $kategoriIds);
            }
        }

        // Status Filter
        if ($request->filled('status')) {
            $status = $request->query('status');
            if ($status === 'tersedia') {
                $query->whereHas('eksemplar', function ($q) {
                    $q->whereIn('status_eksemplar', ['tersedia', 'Tersedia']);
                });
            } elseif ($status === 'dipinjam') {
                $query->whereDoesntHave('eksemplar', function ($q) {
                    $q->whereIn('status_eksemplar', ['tersedia', 'Tersedia']);
                })->whereHas('eksemplar');
            }
        }

        // Year Filter
        if ($request->filled('tahun_dari')) {
            $query->where('tahun_terbit', '>=', $request->query('tahun_dari'));
        }
        if ($request->filled('tahun_ke')) {
            $query->where('tahun_terbit', '<=', $request->query('tahun_ke'));
        }

        // Sorting
        $sort = $request->query('sort', 'terbaru');
        if ($sort === 'terlama') {
            $query->orderBy('tahun_terbit', 'asc')->orderBy('id_buku', 'asc');
        } elseif ($sort === 'a-z') {
            $query->orderBy('judul', 'asc');
        } elseif ($sort === 'z-a') {
            $query->orderBy('judul', 'desc');
        } else { // terbaru
            $query->orderBy('tahun_terbit', 'desc')->orderBy('id_buku', 'desc');
        }

        // Paginate
        $books = $query->paginate(12)->withQueryString();

        return view('landing.katalog', compact('books', 'categories'));
    }

    public function show(Buku $buku): View
    {
        $buku->load(['kategori', 'eksemplar']);

        // Retrieve physical location from any copy
        $lokasi_rak = $buku->eksemplar->first()?->lokasi_rak ?? 'Lantai 2 - Rak A-12';

        // Calculate availability status
        $totalEksemplar = $buku->eksemplar->count();
        $tersediaCount = $buku->eksemplar->whereIn('status_eksemplar', ['tersedia', 'Tersedia'])->count();
        $status = $tersediaCount > 0 ? 'Tersedia' : ($totalEksemplar > 0 ? 'Sedang Dipinjam' : 'Tidak Tersedia');

        // Related recommendations from same category, excluding current book
        $recommendations = Buku::where('id_kategori', $buku->id_kategori)
            ->where('id_buku', '!=', $buku->id_buku)
            ->withCount('eksemplar')
            ->withCount([
                'eksemplar as eksemplar_tersedia_count' => fn ($q) => $q
                    ->whereIn('status_eksemplar', ['tersedia', 'Tersedia']),
            ])
            ->limit(5)
            ->get();

        return view('landing.katalog-detail', compact('buku', 'lokasi_rak', 'status', 'recommendations'));
    }
}
