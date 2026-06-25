<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAgendaEventRequest;
use App\Models\AgendaEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AgendaEventController extends Controller
{
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

        $validated['slug'] = Str::slug($validated['judul_event']);
        $validated['tampilkan_beranda'] = $request->boolean('tampilkan_beranda');

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('agenda', 'public');
        }

        AgendaEvent::create($validated);

        $message = $validated['status_event'] === 'draft'
            ? 'Agenda berhasil disimpan sebagai draft.'
            : 'Agenda berhasil diterbitkan.';

        return redirect()->route('agenda.create')->with('success', $message);
    }
}
