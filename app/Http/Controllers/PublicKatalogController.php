<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\EksemplarBuku;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PublicKatalogController extends Controller
{
    public function index(Request $request): View
    {
        // 1. Fetch categories for sidebar filter
        $categories = KategoriBuku::orderBy('nama_kategori')->get();

        // 3. Build query
        $query = Buku::query()
            ->aktif()
            ->with('kategori')
            ->withKetersediaanCounts();

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->query('search');
            $likeOperator = DB::connection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';
            $query->where(function ($q) use ($search, $likeOperator) {
                $q->where('judul', $likeOperator, "%{$search}%")
                    ->orWhere('penulis', $likeOperator, "%{$search}%")
                    ->orWhere('isbn', $likeOperator, "%{$search}%");
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
                    $q->whereIn('status_eksemplar', EksemplarBuku::AVAILABLE_COPY_STATUSES);
                });
            } elseif ($status === 'dipinjam') {
                $query->whereDoesntHave('eksemplar', function ($q) {
                    $q->whereIn('status_eksemplar', EksemplarBuku::AVAILABLE_COPY_STATUSES);
                })->whereHas('eksemplar', function ($q) {
                    $q->whereIn('status_eksemplar', EksemplarBuku::BORROWED_COPY_STATUSES);
                });
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
        abort_unless($buku->isKatalogAktif(), 404);

        $buku->load(['kategori', 'eksemplar']);

        $lokasi_rak = $buku->lokasiRakEksemplarLabel();

        // Related recommendations from same category, excluding current book
        $recommendations = Buku::query()
            ->aktif()
            ->where('id_kategori', $buku->id_kategori)
            ->where('id_buku', '!=', $buku->id_buku)
            ->withKetersediaanCounts()
            ->limit(5)
            ->get();

        return view('landing.katalog-detail', compact('buku', 'lokasi_rak', 'recommendations'));
    }
}
