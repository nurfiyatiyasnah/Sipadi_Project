<?php

namespace App;

use App\Models\Anggota;
use App\Models\EKartuAnggota;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class EKartuPdfRenderer
{
    public function download(Anggota $anggota, EKartuAnggota $eKartu): Response
    {
        $filename = "e-kartu-{$anggota->no_anggota}-satu-lembar.pdf";

        return Pdf::loadView('e-kartu.pdf', compact('anggota', 'eKartu'))
            ->setPaper('a4', 'landscape')
            ->download($filename)
            ->header('Cache-Control', 'private, no-store, no-cache, must-revalidate')
            ->header('Pragma', 'no-cache');
    }
}
