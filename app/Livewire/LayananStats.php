<?php

namespace App\Livewire;

use App\Models\Kunjungan;
use App\Models\Layanan;
use Illuminate\View\View;
use Livewire\Component;

class LayananStats extends Component
{
    public function render(): View
    {
        $stats = [
            'total' => Layanan::query()->count(),
            'aktif' => Layanan::query()->where('status_layanan', 'aktif')->count(),
            'review' => Layanan::query()->where('status_layanan', 'review')->count(),
            'akses' => (int) Kunjungan::query()->sum('jumlah_kunjungan'),
        ];

        return view('livewire.layanan-stats', compact('stats'));
    }
}
