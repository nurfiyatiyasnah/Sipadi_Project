<?php

namespace Tests\Feature;

use App\Livewire\LayananStats;
use App\Models\Kunjungan;
use App\Models\Layanan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LayananStatsTest extends TestCase
{
    use RefreshDatabase;

    public function test_statistik_layanan_mengambil_total_akses_dari_kunjungan_terbaru(): void
    {
        Layanan::create([
            'nama_layanan' => 'Akses Jurnal Digital',
            'slug' => 'akses-jurnal-digital',
            'status_layanan' => 'aktif',
        ]);

        Layanan::create([
            'nama_layanan' => 'Layanan Dalam Review',
            'slug' => 'layanan-dalam-review',
            'status_layanan' => 'review',
        ]);

        Kunjungan::create([
            'kode_kunjungan' => 'KJG-001',
            'jumlah_kunjungan' => 2,
        ]);

        Kunjungan::create([
            'kode_kunjungan' => 'KJG-002',
            'jumlah_kunjungan' => 3,
        ]);

        Livewire::test(LayananStats::class)
            ->assertSee('Total Layanan')
            ->assertSee('Aktif')
            ->assertSee('Perlu Review')
            ->assertSee('Total Akses')
            ->assertSee('5');
    }

    public function test_statistik_layanan_membaca_data_terbaru_saat_render_ulang(): void
    {
        Livewire::test(LayananStats::class)
            ->assertSee('Total Akses')
            ->assertSee('0');

        Kunjungan::create([
            'kode_kunjungan' => 'KJG-003',
            'jumlah_kunjungan' => 7,
        ]);

        Livewire::test(LayananStats::class)
            ->assertSee('Total Akses')
            ->assertSee('7');
    }
}
