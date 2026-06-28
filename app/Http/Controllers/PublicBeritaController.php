<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\KategoriBerita;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicBeritaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Berita::query()
            ->published()
            ->with(['kategoriBerita', 'petugas'])
            ->latest('tanggal_terbit')
            ->latest('id_berita');

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->query('search');
            $cleanSearch = ltrim($search, '#');
            $query->where(function ($q) use ($cleanSearch) {
                $q->where('judul', 'like', "%{$cleanSearch}%")
                  ->orWhere('isi', 'like', "%{$cleanSearch}%");
            });
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('id_kategori_berita', $request->query('kategori'));
        }

        // Paginate berita
        $perPage = 5;
        $beritaPaginated = $query->paginate($perPage)->withQueryString();

        $featured = null;
        $beritaList = collect($beritaPaginated->items());

        // Jika halaman 1 dan tidak kosong, jadikan item pertama sebagai berita utama (featured)
        if ($beritaPaginated->currentPage() === 1 && $beritaList->isNotEmpty()) {
            $featured = $beritaList->shift();
        }

        // Ambil daftar kategori beserta jumlah berita terbit di masing-masing
        $kategoriList = KategoriBerita::query()
            ->withCount(['berita' => function ($q) {
                $q->published();
            }])
            ->orderBy('nama_kategori')
            ->get();

        $totalBeritaCount = Berita::published()->count();

        return view('landing.berita', compact(
            'beritaPaginated',
            'beritaList',
            'featured',
            'kategoriList',
            'totalBeritaCount'
        ));
    }

    public function show(string $slug): View
    {
        $berita = Berita::query()
            ->published()
            ->with(['kategoriBerita', 'petugas'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Ambil berita terbaru lainnya untuk sidebar
        $recentBerita = Berita::query()
            ->published()
            ->with('kategoriBerita')
            ->where('id_berita', '!=', $berita->id_berita)
            ->latest('tanggal_terbit')
            ->latest('id_berita')
            ->limit(5)
            ->get();

        // Ambil daftar kategori beserta jumlah berita terbit untuk sidebar
        $kategoriList = KategoriBerita::query()
            ->withCount(['berita' => function ($q) {
                $q->published();
            }])
            ->orderBy('nama_kategori')
            ->get();

        $totalBeritaCount = Berita::published()->count();

        return view('landing.berita-detail', compact(
            'berita',
            'recentBerita',
            'kategoriList',
            'totalBeritaCount'
        ));
    }
}
