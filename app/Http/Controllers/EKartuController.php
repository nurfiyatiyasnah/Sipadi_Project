<?php

namespace App\Http\Controllers;

use App\EKartuPngRenderer;
use App\Models\Anggota;
use App\Models\EKartuAnggota;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class EKartuController extends Controller
{
    public function show(Request $request): View
    {
        $anggota = $this->anggota($request);
        $eKartu = $this->eKartu($anggota);

        return view('e-kartu.show', compact('anggota', 'eKartu'));
    }

    public function download(Request $request, EKartuPngRenderer $renderer): Response
    {
        $anggota = $this->anggota($request);
        $eKartu = $this->eKartu($anggota);

        return response($renderer->render($anggota, $eKartu), 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => "attachment; filename=\"e-kartu-{$anggota->no_anggota}.png\"",
            'Cache-Control' => 'private, no-store',
        ]);
    }

    private function anggota(Request $request): Anggota
    {
        return $request->user()
            ->anggota()
            ->with('eKartuAnggota')
            ->firstOrFail();
    }

    private function eKartu(Anggota $anggota): EKartuAnggota
    {
        return $anggota->eKartuAnggota()->firstOrCreate([], [
            'no_anggota' => $anggota->nik,
            'kalangan' => config('sipadi.keanggotaan.kalangan_default'),
            'barcode' => (string) Str::uuid(),
            'masa_berlaku' => now()->addYears((int) config('sipadi.keanggotaan.masa_berlaku_tahun')),
        ]);
    }
}
