<?php

namespace Tests\Feature;

use App\Models\Petugas;
use App\Models\Prestasi;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PrestasiManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_petugas_dapat_melihat_daftar_prestasi(): void
    {
        $petugas = $this->createPetugasUser();
        Prestasi::create([
            'judul_prestasi' => 'Perpustakaan Digital Terbaik',
            'slug' => 'perpustakaan-digital-terbaik',
            'deskripsi' => 'Penghargaan inovasi perpustakaan digital.',
            'tingkat_prestasi' => 'nasional',
            'tanggal_prestasi' => '2026-07-01',
            'status_prestasi' => Prestasi::STATUS_PUBLISHED,
        ]);

        $response = $this->actingAs($petugas)->get(route('petugas.prestasi.index'));

        $response
            ->assertOk()
            ->assertSee('Daftar Prestasi')
            ->assertSee('Perpustakaan Digital Terbaik')
            ->assertSee('Tambah Prestasi')
            ->assertDontSee('Profil Perpustakaan')
            ->assertDontSee('Laporan');
    }

    public function test_petugas_dapat_membuka_form_tambah_prestasi(): void
    {
        $petugas = $this->createPetugasUser();

        $this->actingAs($petugas)
            ->get(route('petugas.prestasi.create'))
            ->assertOk()
            ->assertSee('Tambah Prestasi')
            ->assertSee('Informasi Prestasi');
    }

    public function test_petugas_dapat_menyimpan_prestasi(): void
    {
        Storage::fake('public');

        $petugas = $this->createPetugasUser();

        $response = $this->actingAs($petugas)->post(route('petugas.prestasi.store'), [
            'judul_prestasi' => 'Juara Literasi Nasional',
            'deskripsi' => 'Penghargaan atas program literasi masyarakat.',
            'tingkat_prestasi' => 'nasional',
            'penyelenggara' => 'Kementerian Pendidikan',
            'nomor_sertifikat' => 'SK-PRESTASI/2026/001',
            'tanggal_prestasi' => '2026-07-12',
            'status_prestasi' => Prestasi::STATUS_PUBLISHED,
            'file_lampiran' => UploadedFile::fake()->create('sertifikat.pdf', 128, 'application/pdf'),
        ]);

        $response
            ->assertRedirect(route('petugas.prestasi.index'))
            ->assertSessionHas('success', 'Prestasi berhasil ditambahkan.');

        $prestasi = Prestasi::firstWhere('judul_prestasi', 'Juara Literasi Nasional');

        $this->assertNotNull($prestasi);
        $this->assertSame('juara-literasi-nasional', $prestasi->slug);
        $this->assertSame('SK-PRESTASI/2026/001', $prestasi->nomor_sertifikat);
        Storage::disk('public')->assertExists($prestasi->file_lampiran);
    }

    public function test_petugas_dapat_memperbarui_prestasi(): void
    {
        $petugas = $this->createPetugasUser();
        $prestasi = Prestasi::create([
            'judul_prestasi' => 'Pusat Literasi Komunitas',
            'slug' => 'pusat-literasi-komunitas',
            'deskripsi' => 'Penghargaan tingkat kota.',
            'tingkat_prestasi' => 'lokal',
            'status_prestasi' => Prestasi::STATUS_DRAFT,
        ]);

        $response = $this->actingAs($petugas)->put(route('petugas.prestasi.update', $prestasi), [
            'judul_prestasi' => 'Pusat Literasi Komunitas Terbaik',
            'deskripsi' => 'Penghargaan tingkat kota yang diperbarui.',
            'tingkat_prestasi' => 'lokal',
            'penyelenggara' => 'Pemerintah Kota',
            'nomor_sertifikat' => 'SK-KOTA/2026/014',
            'tanggal_prestasi' => '2026-06-20',
            'status_prestasi' => Prestasi::STATUS_PUBLISHED,
        ]);

        $response
            ->assertRedirect(route('petugas.prestasi.show', $prestasi))
            ->assertSessionHas('success', 'Prestasi berhasil diperbarui.');

        $this->assertDatabaseHas('prestasi', [
            'id_prestasi' => $prestasi->id_prestasi,
            'judul_prestasi' => 'Pusat Literasi Komunitas Terbaik',
            'slug' => 'pusat-literasi-komunitas-terbaik',
            'status_prestasi' => Prestasi::STATUS_PUBLISHED,
        ]);
    }

    public function test_petugas_dapat_menghapus_prestasi(): void
    {
        $petugas = $this->createPetugasUser();
        $prestasi = Prestasi::create([
            'judul_prestasi' => 'Excellence in Resource Management',
            'slug' => 'excellence-in-resource-management',
            'tingkat_prestasi' => 'internasional',
            'status_prestasi' => Prestasi::STATUS_PUBLISHED,
        ]);

        $response = $this->actingAs($petugas)->delete(route('petugas.prestasi.destroy', $prestasi));

        $response
            ->assertRedirect(route('petugas.prestasi.index'))
            ->assertSessionHas('success', 'Prestasi berhasil dihapus.');

        $this->assertDatabaseMissing('prestasi', [
            'id_prestasi' => $prestasi->id_prestasi,
        ]);
    }

    private function createPetugasUser(): User
    {
        $role = Role::create(['nama_role' => 'Petugas']);
        $user = User::factory()->create(['id_role' => $role->id_role]);

        Petugas::factory()->create(['id_user' => $user->id_user]);

        return $user;
    }
}
