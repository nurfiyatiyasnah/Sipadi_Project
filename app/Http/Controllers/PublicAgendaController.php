<?php

namespace App\Http\Controllers;

use App\Models\AgendaEvent;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicAgendaController extends Controller
{
    /**
     * Display a listing of public agenda events.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $filter = $request->query('filter', 'semua'); // semua, akan_datang, berlangsung, selesai
        $today = now()->toDateString();

        $events = AgendaEvent::query()
            ->where('status_event', 'terbit')
            ->when($search, function ($query, $search) {
                return $query->where('judul_event', 'like', '%'.$search.'%');
            })
            ->when($filter !== 'semua', function ($query) use ($filter, $today) {
                if ($filter === 'akan_datang') {
                    return $query->where('tanggal_mulai', '>', $today);
                } elseif ($filter === 'berlangsung') {
                    return $query->where(function ($q) use ($today) {
                        $q->where('tanggal_mulai', '=', $today)
                            ->orWhere(function ($q2) use ($today) {
                                $q2->where('tanggal_mulai', '<=', $today)
                                    ->where('tanggal_selesai', '>=', $today);
                            });
                    });
                } elseif ($filter === 'selesai') {
                    return $query->where(function ($q) use ($today) {
                        $q->where('tanggal_mulai', '<', $today)
                            ->where(function ($q2) use ($today) {
                                $q2->whereNull('tanggal_selesai')
                                    ->orWhere('tanggal_selesai', '<', $today);
                            });
                    });
                }
            })
            ->latest('id_event')
            ->paginate(6)
            ->withQueryString();

        return view('landing.agenda.index', compact('events'));
    }

    /**
     * Display the specified public agenda event.
     */
    public function show(string $slug): View
    {
        $agenda = AgendaEvent::query()
            ->where('slug', $slug)
            ->where('status_event', 'terbit')
            ->firstOrFail();

        // Fetch 2 books from collection for dynamic display in "Koleksi Terkait"
        $relatedBooks = Buku::query()
            ->with('kategori')
            ->latest('id_buku')
            ->limit(2)
            ->get();

        // Fetch 3 other published events for "Agenda Terkait"
        $relatedEvents = AgendaEvent::query()
            ->where('id_event', '!=', $agenda->id_event)
            ->where('status_event', 'terbit')
            ->latest('id_event')
            ->limit(3)
            ->get();

        return view('landing.agenda.show', compact('agenda', 'relatedBooks', 'relatedEvents'));
    }
}
