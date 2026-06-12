<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\EKartuAnggota;
use Barryvdh\DomPDF\Facade\Pdf;
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

    public function download(Request $request): Response
    {
        $anggota = $this->anggota($request);
        $eKartu = $this->eKartu($anggota);

        return Pdf::loadView('e-kartu.pdf', compact('anggota', 'eKartu'))
            ->setPaper('a5', 'landscape')
            ->download("e-kartu-{$anggota->no_anggota}.pdf");
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
            'no_anggota' => $anggota->no_anggota,
            'kalangan' => config('sipadi.keanggotaan.kalangan_default'),
            'barcode' => (string) Str::uuid(),
            'masa_berlaku' => now()->addYears((int) config('sipadi.keanggotaan.masa_berlaku_tahun')),
        ]);
    }
}
