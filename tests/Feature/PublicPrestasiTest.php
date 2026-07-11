<?php

namespace Tests\Feature;

use App\Models\Prestasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPrestasiTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengunjung_dapat_melihat_daftar_prestasi_terbit(): void
    {
        Prestasi::create([
            'judul_prestasi' => 'Juara Literasi Nasional 2026',
            'slug' => 'juara-literasi-nasional-2026',
            'deskripsi' => 'Penghargaan literasi nasional.',
            'tingkat_prestasi' => 'nasional',
            'penyelenggara' => 'Perpustakaan Nasional Republik Indonesia',
            'tanggal_prestasi' => '2026-04-15',
            'status_prestasi' => Prestasi::STATUS_PUBLISHED,
        ]);

        Prestasi::create([
            'judul_prestasi' => 'Draft Prestasi Internal',
            'slug' => 'draft-prestasi-internal',
            'tingkat_prestasi' => 'lokal',
            'status_prestasi' => Prestasi::STATUS_DRAFT,
        ]);

        $response = $this->get(route('prestasi.public.index'));

        $response
            ->assertOk()
            ->assertSee('Prestasi &amp; Penghargaan', false)
            ->assertSee('Juara Literasi Nasional 2026')
            ->assertSee('Perpustakaan Nasional Republik Indonesia')
            ->assertDontSee('Draft Prestasi Internal');
    }

    public function test_pengunjung_dapat_mencari_prestasi(): void
    {
        Prestasi::create([
            'judul_prestasi' => 'Perpustakaan Digital Terbaik',
            'slug' => 'perpustakaan-digital-terbaik',
            'deskripsi' => 'Inovasi digital.',
            'tingkat_prestasi' => 'internasional',
            'status_prestasi' => Prestasi::STATUS_PUBLISHED,
        ]);

        Prestasi::create([
            'judul_prestasi' => 'Pusat Literasi Komunitas',
            'slug' => 'pusat-literasi-komunitas',
            'deskripsi' => 'Program literasi komunitas.',
            'tingkat_prestasi' => 'lokal',
            'status_prestasi' => Prestasi::STATUS_PUBLISHED,
        ]);

        $response = $this->get(route('prestasi.public.index', ['search' => 'Digital']));

        $response
            ->assertOk()
            ->assertSee('Perpustakaan Digital Terbaik')
            ->assertDontSee('Pusat Literasi Komunitas');
    }

    public function test_pengunjung_dapat_filter_prestasi_berdasarkan_tingkat(): void
    {
        Prestasi::create([
            'judul_prestasi' => 'Prestasi Nasional',
            'slug' => 'prestasi-nasional',
            'tingkat_prestasi' => 'nasional',
            'status_prestasi' => Prestasi::STATUS_PUBLISHED,
        ]);

        Prestasi::create([
            'judul_prestasi' => 'Prestasi Lokal',
            'slug' => 'prestasi-lokal',
            'tingkat_prestasi' => 'lokal',
            'status_prestasi' => Prestasi::STATUS_PUBLISHED,
        ]);

        $response = $this->get(route('prestasi.public.index', ['tingkat' => 'nasional']));

        $response
            ->assertOk()
            ->assertSee('Prestasi Nasional')
            ->assertDontSee('Prestasi Lokal');
    }

    public function test_pengunjung_dapat_melihat_detail_prestasi_terbit(): void
    {
        $prestasi = Prestasi::create([
            'judul_prestasi' => 'Juara Literasi Nasional 2026',
            'slug' => 'juara-literasi-nasional-2026',
            'deskripsi' => 'Penghargaan tertinggi atas komitmen literasi.',
            'tingkat_prestasi' => 'nasional',
            'penyelenggara' => 'Perpustakaan Nasional Republik Indonesia',
            'nomor_sertifikat' => 'SK-PRESTASI/2026/001',
            'tanggal_prestasi' => '2026-04-15',
            'file_lampiran' => 'prestasi/lampiran/sertifikat.pdf',
            'status_prestasi' => Prestasi::STATUS_PUBLISHED,
        ]);

        $response = $this->get(route('prestasi.public.show', $prestasi->slug));

        $response
            ->assertOk()
            ->assertSee('Juara Literasi Nasional 2026')
            ->assertSee('Penghargaan tertinggi atas komitmen literasi.')
            ->assertSee('SK-PRESTASI/2026/001')
            ->assertSee('Pratinjau Lampiran')
            ->assertSee('Tutup pratinjau lampiran')
            ->assertSee('Prestasi Lainnya');
    }

    public function test_pengunjung_tidak_dapat_melihat_detail_prestasi_draft(): void
    {
        $prestasi = Prestasi::create([
            'judul_prestasi' => 'Draft Prestasi',
            'slug' => 'draft-prestasi',
            'tingkat_prestasi' => 'lokal',
            'status_prestasi' => Prestasi::STATUS_DRAFT,
        ]);

        $this->get(route('prestasi.public.show', $prestasi->slug))
            ->assertNotFound();
    }
}
