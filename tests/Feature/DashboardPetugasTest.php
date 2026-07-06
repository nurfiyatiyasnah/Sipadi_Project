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
            ->assertSee('Selamat Datang, Administrator')
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

    public function test_koleksi_filter_berdasarkan_kategori(): void
    {
        $petugas = $this->createUserWithRole('Petugas');
        $teknologi = KategoriBuku::factory()->create(['nama_kategori' => 'Teknologi']);
        $sejarah = KategoriBuku::factory()->create(['nama_kategori' => 'Sejarah']);
        $bukuTeknologi = Buku::factory()->for($teknologi, 'kategori')->create(['judul' => 'Belajar PHP']);
        Buku::factory()->for($sejarah, 'kategori')->create(['judul' => 'Sejarah Dunia']);

        $response = $this->actingAs($petugas)
            ->get(route('petugas.koleksi', ['kategori' => $teknologi->id_kategori]));

        $response
            ->assertOk()
            ->assertSee('Belajar PHP')
            ->assertDontSee('Sejarah Dunia');
    }

    public function test_koleksi_filter_status_katalog_case_insensitive(): void
    {
        $petugas = $this->createUserWithRole('Petugas');
        $kategori = KategoriBuku::factory()->create();

        // Buku with TitleCase 'Aktif' (like seeder)
        Buku::factory()->for($kategori, 'kategori')->create([
            'judul' => 'Buku Aktif TitleCase',
            'status_katalog' => 'Aktif',
        ]);

        // Buku with lowercase 'aktif' (like factory default)
        Buku::factory()->for($kategori, 'kategori')->create([
            'judul' => 'Buku Aktif Lowercase',
            'status_katalog' => 'aktif',
        ]);

        // Buku with nonaktif status
        Buku::factory()->for($kategori, 'kategori')->create([
            'judul' => 'Buku Nonaktif',
            'status_katalog' => 'nonaktif',
        ]);

        // Filter with lowercase 'aktif' (as sent by the HTML form)
        $response = $this->actingAs($petugas)
            ->get(route('petugas.koleksi', ['status' => 'aktif']));

        $response
            ->assertOk()
            ->assertSee('Buku Aktif TitleCase')
            ->assertSee('Buku Aktif Lowercase')
            ->assertDontSee('Buku Nonaktif');
    }

    public function test_ekspor_csv_menghormati_filter_aktif(): void
    {
        $petugas = $this->createUserWithRole('Petugas');
        $teknologi = KategoriBuku::factory()->create(['nama_kategori' => 'Teknologi']);
        $sejarah = KategoriBuku::factory()->create(['nama_kategori' => 'Sejarah']);
        Buku::factory()->for($teknologi, 'kategori')->create(['judul' => 'Buku Teknologi']);
        Buku::factory()->for($sejarah, 'kategori')->create(['judul' => 'Buku Sejarah']);

        $response = $this->actingAs($petugas)
            ->get(route('petugas.koleksi.export', ['kategori' => $teknologi->id_kategori]));

        $response->assertOk()->assertDownload('koleksi_buku.csv');

        $content = $response->streamedContent();

        $this->assertStringContainsString('Buku Teknologi', $content);
        $this->assertStringNotContainsString('Buku Sejarah', $content);
    }

    private function createUserWithRole(string $namaRole): User
    {
        $role = Role::create(['nama_role' => $namaRole]);

        return User::factory()->create(['id_role' => $role->id_role]);
    }
}
