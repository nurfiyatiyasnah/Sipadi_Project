<?php

namespace Tests\Feature;

use App\Models\Layanan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicLayananTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_layanan_index_shows_active_admin_layanan(): void
    {
        $this->withoutVite();

        $aktif = Layanan::create([
            'nama_layanan' => 'Akses Jurnal Digital',
            'slug' => 'akses-jurnal-digital',
            'deskripsi' => 'Akses database e-library untuk anggota.',
            'status_layanan' => 'aktif',
        ]);

        Layanan::create([
            'nama_layanan' => 'Layanan Dalam Review',
            'slug' => 'layanan-dalam-review',
            'deskripsi' => 'Belum boleh tampil ke pengguna.',
            'status_layanan' => 'review',
        ]);

        $response = $this->get(route('layanan.index'));

        $response->assertStatus(200);
        $response->assertSee('Layanan Perpustakaan');
        $response->assertSee('Akses Jurnal Digital');
        $response->assertSee(route('layanan.show', $aktif->slug), false);
        $response->assertDontSee('Layanan Dalam Review');
    }

    public function test_public_layanan_detail_uses_admin_layanan_data(): void
    {
        $this->withoutVite();

        $layanan = Layanan::create([
            'nama_layanan' => 'Peminjaman Buku Fisik',
            'slug' => 'peminjaman-buku-fisik',
            'deskripsi' => 'Sirkulasi bahan pustaka untuk anggota aktif.',
            'persyaratan' => "Kartu anggota aktif\nTidak memiliki tanggungan",
            'prosedur' => "Pilih buku\nBawa ke petugas\nProses peminjaman",
            'jam_layanan' => '08:00 - 15:30 WIB',
            'biaya' => 'Gratis',
            'kontak_layanan' => 'Meja Sirkulasi',
            'status_layanan' => 'aktif',
        ]);

        $response = $this->get(route('layanan.show', $layanan->slug));

        $response->assertStatus(200);
        $response->assertSee('Peminjaman Buku Fisik');
        $response->assertSee('Sirkulasi bahan pustaka untuk anggota aktif.');
        $response->assertSee('Kartu anggota aktif');
        $response->assertSee('Pilih buku');
        $response->assertSee('08:00 - 15:30 WIB');
        $response->assertSee('Meja Sirkulasi');
        $response->assertSee('Syarat & Ketentuan', false);
    }

    public function test_public_layanan_detail_does_not_show_inactive_layanan(): void
    {
        $this->withoutVite();

        $layanan = Layanan::create([
            'nama_layanan' => 'Layanan Nonaktif',
            'slug' => 'layanan-nonaktif',
            'status_layanan' => 'nonaktif',
        ]);

        $this->get(route('layanan.show', $layanan->slug))
            ->assertNotFound();
    }
}
