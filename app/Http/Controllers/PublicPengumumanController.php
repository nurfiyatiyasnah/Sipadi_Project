<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicPengumumanController extends Controller
{
    /**
     * Display a listing of active public announcements.
     */
    public function index(Request $request): View
    {
        $today = now()->toDateString();

        $query = Pengumuman::query()
            ->where('status_pengumuman', 'terbit')
            ->where('tanggal_mulai', '<=', $today)
            ->where('tanggal_selesai', '>=', $today)
            ->with('petugas');

        // Filter search term
        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('isi', 'like', "%{$search}%");
            });
        }

        // Filter target group
        if ($request->filled('target')) {
            $target = $request->query('target');
            if ($target !== 'Semua') {
                $query->where('target_pengguna', $target);
            }
        }

        // Filter monthly archive (format: YYYY-MM)
        if ($request->filled('bulan')) {
            $bulan = $request->query('bulan');
            try {
                $startOfMonth = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth()->toDateString();
                $endOfMonth = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth()->toDateString();

                $query->where(function ($q) use ($startOfMonth, $endOfMonth) {
                    $q->whereBetween('tanggal_mulai', [$startOfMonth, $endOfMonth])
                        ->orWhereBetween('tanggal_selesai', [$startOfMonth, $endOfMonth])
                        ->orWhere(function ($sq) use ($startOfMonth, $endOfMonth) {
                            $sq->where('tanggal_mulai', '<=', $startOfMonth)
                                ->where('tanggal_selesai', '>=', $endOfMonth);
                        });
                });
            } catch (\Exception $e) {
                // Ignore invalid date format
            }
        }

        // Calculate count for target categories in sidebar
        $activeBase = Pengumuman::query()
            ->where('status_pengumuman', 'terbit')
            ->where('tanggal_mulai', '<=', $today)
            ->where('tanggal_selesai', '>=', $today);

        $totalCount = (clone $activeBase)->count();
        $siswaCount = (clone $activeBase)->where('target_pengguna', 'Siswa / Mahasiswa')->count();
        $dosenCount = (clone $activeBase)->where('target_pengguna', 'Dosen')->count();
        $petugasCount = (clone $activeBase)->where('target_pengguna', 'Petugas')->count();

        $targets = [
            ['name' => 'Semua Pengumuman', 'value' => 'Semua', 'count' => $totalCount],
            ['name' => 'Siswa / Mahasiswa', 'value' => 'Siswa / Mahasiswa', 'count' => $siswaCount],
            ['name' => 'Dosen', 'value' => 'Dosen', 'count' => $dosenCount],
            ['name' => 'Petugas', 'value' => 'Petugas', 'count' => $petugasCount],
        ];

        // Gather list of months for the monthly archive dropdown
        $monthsList = Pengumuman::query()
            ->where('status_pengumuman', 'terbit')
            ->where('tanggal_mulai', '<=', $today)
            ->where('tanggal_selesai', '>=', $today)
            ->orderBy('tanggal_mulai', 'desc')
            ->get(['tanggal_mulai'])
            ->map(function ($item) {
                if ($item->tanggal_mulai === null) {
                    return null;
                }
                $date = Carbon::parse($item->tanggal_mulai);

                return [
                    'value' => $date->format('Y-m'),
                    'label' => $date->locale('id')->translatedFormat('F Y'),
                ];
            })
            ->filter()
            ->unique('value')
            ->values();

        // Retrieve announcements, prioritizing 'Penting' and then sorting by tanggal_mulai descending
        $announcementsPaginated = $query->orderByRaw("CASE WHEN prioritas = 'Penting' THEN 0 ELSE 1 END")
            ->orderBy('tanggal_mulai', 'desc')
            ->orderBy('id_pengumuman', 'desc')
            ->paginate(5)
            ->withQueryString();

        $featured = null;
        $items = collect($announcementsPaginated->items());

        // If page 1, set the first 'Penting' announcement as the featured item
        if ($announcementsPaginated->currentPage() === 1 && $items->isNotEmpty()) {
            if ($items->first()->prioritas === 'Penting') {
                $featured = $items->shift();
            }
        }

        return view('landing.pengumuman', compact(
            'announcementsPaginated',
            'items',
            'featured',
            'targets',
            'monthsList',
            'totalCount'
        ));
    }

    /**
     * Display a detailed view of a single announcement.
     */
    public function show(string $slug): View
    {
        $today = now()->toDateString();

        $pengumuman = Pengumuman::query()
            ->where('status_pengumuman', 'terbit')
            ->where('tanggal_mulai', '<=', $today)
            ->where('tanggal_selesai', '>=', $today)
            ->where('slug', $slug)
            ->firstOrFail();

        $pengumuman->increment('total_views');

        // Get other recent active announcements for sidebar
        $recentPengumuman = Pengumuman::query()
            ->where('status_pengumuman', 'terbit')
            ->where('tanggal_mulai', '<=', $today)
            ->where('tanggal_selesai', '>=', $today)
            ->where('id_pengumuman', '!=', $pengumuman->id_pengumuman)
            ->latest('tanggal_mulai')
            ->limit(5)
            ->get();

        return view('landing.pengumuman-detail', compact('pengumuman', 'recentPengumuman'));
    }
}
