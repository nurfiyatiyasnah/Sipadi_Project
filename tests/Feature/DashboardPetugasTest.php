<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\EksemplarBuku;
use App\Models\KategoriBuku;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardPetugasTest extends TestCase
{
    use RefreshDatabase;

    public function test_petugas_dapat_melihat_dashboard(): void
    {
        $petugas = $this->createUserWithRole('Petugas');
        $kategori = KategoriBuku::factory()->create();
        Anggota::factory()->count(2)->create();
        Buku::factory()->for($kategori, 'kategori')->create();

        $response = $this->actingAs($petugas)
            ->get(route('petugas.dashboard'));

        $response
            ->assertOk()
            ->assertSee('Selamat Pagi, Administrator')
            ->assertSee('Panel Operasional Admin')
            ->assertSee('Aksi Cepat')
            ->assertSee('Status Layanan')
            ->assertSee('Prioritas Hari Ini')
            ->assertSee('Setting')
            ->assertSee('Log Out')
            ->assertSee(route('logout', absolute: false), false)
            ->assertViewHas('stats', fn (array $stats): bool => $stats['total_anggota'] === 2
                && $stats['koleksi_buku'] === 1)
            ->assertViewHas('status_layanan')
            ->assertViewHas('prioritas_hari_ini');
    }

    public function test_petugas_dapat_melihat_koleksi_dengan_statistik_eksemplar(): void
    {
        $petugas = $this->createUserWithRole('Petugas');
        $kategori = KategoriBuku::factory()->create(['nama_kategori' => 'Teknologi']);
        $buku = Buku::factory()->for($kategori, 'kategori')->create(['judul' => 'Pemrograman Laravel']);
        EksemplarBuku::factory()->for($buku, 'buku')->create(['status_eksemplar' => 'tersedia']);
        EksemplarBuku::factory()->for($buku, 'buku')->create(['status_eksemplar' => 'dipinjam']);

        $response = $this->actingAs($petugas)->get(route('petugas.koleksi'));

        $response
            ->assertOk()
            ->assertSee('Pemrograman Laravel')
            ->assertSee('Teknologi')
            ->assertViewHas('stats', fn (array $stats): bool => $stats['judul'] === 1
                && $stats['eksemplar'] === 2
                && $stats['tersedia'] === 1
                && $stats['dipinjam'] === 1);
    }

    public function test_anggota_tidak_dapat_mengakses_dashboard_petugas(): void
    {
        $anggota = $this->createUserWithRole('Anggota');

        $this->actingAs($anggota)
            ->get(route('petugas.dashboard'))
            ->assertForbidden();
    }

    public function test_petugas_dapat_mengekspor_koleksi_ke_csv(): void
    {
        $petugas = $this->createUserWithRole('Petugas');
        $kategori = KategoriBuku::factory()->create(['nama_kategori' => 'Sejarah']);
        $buku = Buku::factory()->for($kategori, 'kategori')->create(['judul' => 'Sejarah Bukittinggi']);
        EksemplarBuku::factory()->for($buku, 'buku')->create();

        $response = $this->actingAs($petugas)->get(route('petugas.koleksi.export'));

        $response
            ->assertOk()
            ->assertDownload('koleksi_buku.csv');

        $content = $response->streamedContent();

        $this->assertStringContainsString('Sejarah Bukittinggi', $content);
        $this->assertStringContainsString('Sejarah', $content);
    }

    private function createUserWithRole(string $namaRole): User
    {
        $role = Role::create(['nama_role' => $namaRole]);

        return User::factory()->create(['id_role' => $role->id_role]);
    }
}
