<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAgendaEventRequest;
use App\Http\Requests\UpdateAgendaEventRequest;
use App\Models\AgendaEvent;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AgendaEventController extends Controller
{
    /**
     * Display a listing of the agenda events.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $events = AgendaEvent::query()
            ->with('createdBy')
            ->when($search, function ($query, $search) {
                return $query->where('judul_event', 'like', '%'.$search.'%');
            })
            ->when($status, function ($query, $status) {
                return $query->where('status_event', $status);
            })
            ->latest('id_event')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => AgendaEvent::count(),
            'terbit' => AgendaEvent::where('status_event', 'terbit')->count(),
            'draft' => AgendaEvent::where('status_event', 'draft')->count(),
        ];

        return view('agenda.index', compact('events', 'stats'));
    }

    /**
     * Show the form for creating a new agenda event.
     */
    public function create(): View
    {
        return view('agenda.create');
    }

    /**
     * Store a newly created agenda event in storage.
     */
    public function store(StoreAgendaEventRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $validated['slug'] = $this->uniqueSlug($validated['judul_event']);
        $validated['tampilkan_beranda'] = $request->boolean('tampilkan_beranda');
        $validated['created_by'] = $request->user()->petugas?->id_petugas;

        if ($request->filled('tanggal_waktu')) {
            $dateTime = Carbon::parse($request->input('tanggal_waktu'));
            $validated['tanggal_mulai'] = $dateTime->toDateString();
            $validated['jam_mulai'] = $dateTime->toTimeString();
        }

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('agenda', 'public');
        }

        AgendaEvent::create($validated);

        $message = $validated['status_event'] === 'draft'
            ? 'Agenda berhasil disimpan sebagai draft.'
            : 'Agenda berhasil diterbitkan.';

        return redirect()->route('petugas.agenda.index')->with('success', $message);
    }

    /**
     * Display the specified agenda event.
     */
    public function show(AgendaEvent $agenda): View
    {
        // Load related events (other upcoming events, latest)
        $relatedEvents = AgendaEvent::query()
            ->where('id_event', '!=', $agenda->id_event)
            ->latest('id_event')
            ->limit(4)
            ->get();

        return view('agenda.show', compact('agenda', 'relatedEvents'));
    }

    /**
     * Show the form for editing the specified agenda event.
     */
    public function edit(AgendaEvent $agenda): View
    {
        return view('agenda.edit', compact('agenda'));
    }

    /**
     * Update the specified agenda event in storage.
     */
    public function update(UpdateAgendaEventRequest $request, AgendaEvent $agenda): RedirectResponse
    {
        $validated = $request->validated();
        $oldImage = $agenda->gambar;

        if ($agenda->judul_event !== $validated['judul_event']) {
            $validated['slug'] = $this->uniqueSlug($validated['judul_event'], $agenda);
        }

        $validated['tampilkan_beranda'] = $request->boolean('tampilkan_beranda');

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('agenda', 'public');
        }

        $agenda->update($validated);

        if ($request->hasFile('gambar') && $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect()->route('petugas.agenda.index')->with('success', 'Agenda berhasil diperbarui.');
    }

    /**
     * Remove the specified agenda event from storage.
     */
    public function destroy(AgendaEvent $agenda): RedirectResponse
    {
        if ($agenda->gambar) {
            Storage::disk('public')->delete($agenda->gambar);
        }

        $agenda->delete();

        return redirect()->route('petugas.agenda.index')->with('success', 'Agenda berhasil dihapus.');
    }

    /**
     * Generate a unique slug for the agenda event.
     */
    private function uniqueSlug(string $title, ?AgendaEvent $ignore = null): string
    {
        $base = Str::slug($title) ?: Str::random(8);
        $slug = $base;
        $counter = 2;

        while (AgendaEvent::query()
            ->where('slug', $slug)
            ->when($ignore, fn ($query) => $query->where('id_event', '!=', $ignore->id_event))
            ->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
