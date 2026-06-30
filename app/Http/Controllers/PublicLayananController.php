<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class PublicLayananController extends Controller
{
    public function index(): View
    {
        $layanan = Layanan::query()
            ->where('status_layanan', 'aktif')
            ->whereNotNull('slug')
            ->latest('id_layanan')
            ->get();

        return view('landing.layanan.index', compact('layanan'));
    }

    public function show(Layanan $layanan): View
    {
        abort_unless($layanan->status_layanan === 'aktif', 404);

        $requirements = $this->lines($layanan->persyaratan);
        $procedures = $this->lines($layanan->prosedur);
        $relatedLayanan = Layanan::query()
            ->where('status_layanan', 'aktif')
            ->whereNotNull('slug')
            ->whereKeyNot($layanan->getKey())
            ->latest('id_layanan')
            ->limit(3)
            ->get();

        return view('landing.layanan.detail', compact(
            'layanan',
            'requirements',
            'procedures',
            'relatedLayanan'
        ));
    }

    /**
     * @return Collection<int, string>
     */
    private function lines(?string $value): Collection
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $value))
            ->map(fn (string $line): string => trim($line))
            ->filter()
            ->values();
    }
}
