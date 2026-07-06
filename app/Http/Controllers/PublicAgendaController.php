<?php

namespace App\Http\Controllers;

use App\Models\AgendaEvent;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicAgendaController extends Controller
{
    /**
     * Display a listing of public agenda events.
     */
    public function index(Request $request): View
    {
        // Fetch all published events with details for Alpine.js rendering
        $allEvents = AgendaEvent::query()
            ->where('status_event', 'terbit')
            ->latest('id_event')
            ->get(['id_event', 'judul_event', 'slug', 'tanggal_mulai', 'tanggal_selesai', 'jam_mulai', 'jam_selesai', 'lokasi', 'deskripsi', 'kategori', 'gambar'])
            ->map(function ($event) {
                return [
                    'id_event' => $event->id_event,
                    'judul_event' => $event->judul_event,
                    'slug' => $event->slug,
                    'url' => route('agenda.show', $event->slug),
                    'tanggal_mulai' => $event->tanggal_mulai ? $event->tanggal_mulai->toDateString() : null,
                    'tanggal_selesai' => $event->tanggal_selesai ? $event->tanggal_selesai->toDateString() : null,
                    'jam_mulai' => $event->jam_mulai ? substr($event->jam_mulai, 0, 5) : null,
                    'jam_selesai' => $event->jam_selesai ? substr($event->jam_selesai, 0, 5) : null,
                    'lokasi' => $event->lokasi,
                    'deskripsi' => $event->deskripsi,
                    'kategori' => $event->kategori ?? 'Kegiatan',
                    'gambar' => $event->gambar ? \Storage::url($event->gambar) : null,
                ];
            })
            ->filter(fn ($event) => ! is_null($event['tanggal_mulai']))
            ->values();

        return view('landing.agenda.index', compact('allEvents'));
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

        // Fetch all published events for calendar mapping
        $allEvents = AgendaEvent::query()
            ->where('status_event', 'terbit')
            ->orderBy('tanggal_mulai')
            ->orderBy('jam_mulai')
            ->get(['judul_event', 'slug', 'tanggal_mulai', 'jam_mulai', 'jam_selesai'])
            ->map(function ($event) {
                return [
                    'judul_event' => $event->judul_event,
                    'url' => route('agenda.show', $event->slug),
                    'tanggal_mulai' => $event->tanggal_mulai ? $event->tanggal_mulai->toDateString() : null,
                    'jam_mulai' => $event->jam_mulai ? substr($event->jam_mulai, 0, 5) : null,
                    'jam_selesai' => $event->jam_selesai ? substr($event->jam_selesai, 0, 5) : null,
                ];
            })
            ->filter(fn ($event) => ! is_null($event['tanggal_mulai']))
            ->values();

        // Parse Map URLs (including shorteners)
        $isUrl = filter_var($agenda->lokasi, FILTER_VALIDATE_URL);
        $mapUrl = $isUrl ? $agenda->lokasi : 'https://maps.google.com/?q='.urlencode($agenda->lokasi);
        $embedUrl = $isUrl
            ? $this->getEmbeddableMapUrl($agenda->lokasi)
            : 'https://maps.google.com/maps?q='.urlencode($agenda->lokasi).'&t=&z=15&ie=UTF8&iwloc=&output=embed';

        // Fetch 3 other published events for "Agenda Lainnya"
        $otherEvents = AgendaEvent::query()
            ->where('id_event', '!=', $agenda->id_event)
            ->where('status_event', 'terbit')
            ->latest('id_event')
            ->limit(3)
            ->get();

        return view('landing.agenda.show', compact('agenda', 'allEvents', 'embedUrl', 'mapUrl', 'otherEvents'));
    }

    /**
     * Resolve Google Maps redirected URL and convert it to an embeddable URL.
     */
    private function getEmbeddableMapUrl(string $url): string
    {
        // Resolve redirect if it is a shortened URL
        if (str_contains($url, 'maps.app.goo.gl') || str_contains($url, 'goo.gl/maps')) {
            try {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_NOBODY, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 3);
                curl_exec($ch);
                $effectiveUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
                curl_close($ch);
                if ($effectiveUrl) {
                    $url = $effectiveUrl;
                }
            } catch (\Exception $e) {
                // Fail silently and use the original URL
            }
        }

        // Try to match the place name: /maps/place/Place+Name/
        if (preg_match('/\/maps\/place\/([^\/]+)/', $url, $matches)) {
            $place = urldecode($matches[1]);

            return 'https://maps.google.com/maps?q='.urlencode($place).'&t=&z=15&ie=UTF8&iwloc=&output=embed';
        }

        // Try to match coordinates: /@latitude,longitude
        if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $matches)) {
            return 'https://maps.google.com/maps?q='.$matches[1].','.$matches[2].'&t=&z=15&ie=UTF8&iwloc=&output=embed';
        }

        // Fallback: search for the original URL
        return 'https://maps.google.com/maps?q='.urlencode($url).'&t=&z=15&ie=UTF8&iwloc=&output=embed';
    }
}
