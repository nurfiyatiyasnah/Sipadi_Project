<?php

namespace App\Http\Controllers;

use App\Models\Prestasi;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicPrestasiController extends Controller
{
    public function index(Request $request): View
    {
        $baseQuery = Prestasi::query()
            ->where('status_prestasi', Prestasi::STATUS_PUBLISHED)
            ->whereNotNull('slug');

        $prestasiPaginated = (clone $baseQuery)
            ->filter(
                search: $request->query('search'),
                tingkat: $request->query('tingkat'),
                tahun: $request->query('tahun'),
            )
            ->latest('tanggal_prestasi')
            ->latest('id_prestasi')
            ->paginate(7)
            ->withQueryString();

        $items = collect($prestasiPaginated->items());
        $featured = null;

        if ($prestasiPaginated->currentPage() === 1 && $items->isNotEmpty()) {
            $featured = $items->shift();
        }

        $tahunList = (clone $baseQuery)
            ->whereNotNull('tanggal_prestasi')
            ->get(['tanggal_prestasi'])
            ->map(fn (Prestasi $prestasi): int => $prestasi->tanggal_prestasi->year)
            ->unique()
            ->sortDesc()
            ->values();

        $tingkatCounts = [
            'semua' => (clone $baseQuery)->count(),
            'lokal' => (clone $baseQuery)->where('tingkat_prestasi', 'lokal')->count(),
            'nasional' => (clone $baseQuery)->where('tingkat_prestasi', 'nasional')->count(),
            'internasional' => (clone $baseQuery)->where('tingkat_prestasi', 'internasional')->count(),
        ];

        return view('landing.prestasi.index', compact(
            'prestasiPaginated',
            'items',
            'featured',
            'tahunList',
            'tingkatCounts'
        ));
    }

    public function show(Prestasi $prestasi): View
    {
        abort_unless($prestasi->status_prestasi === Prestasi::STATUS_PUBLISHED && $prestasi->slug, 404);

        $relatedPrestasi = Prestasi::query()
            ->where('status_prestasi', Prestasi::STATUS_PUBLISHED)
            ->whereNotNull('slug')
            ->whereKeyNot($prestasi->getKey())
            ->latest('tanggal_prestasi')
            ->latest('id_prestasi')
            ->limit(3)
            ->get();

        return view('landing.prestasi.show', compact('prestasi', 'relatedPrestasi'));
    }
}
