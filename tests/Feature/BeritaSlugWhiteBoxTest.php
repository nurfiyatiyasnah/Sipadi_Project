<?php

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\KategoriBerita;
use App\Models\Petugas;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BeritaSlugWhiteBoxTest extends TestCase
{
    use RefreshDatabase;

    public function test_slug_berita_dibuat_unik_saat_judul_duplikat(): void
    {
        $petugas = $this->createPetugasUser();
        $kategori = KategoriBerita::factory()->create();

        Berita::factory()->create([
            'id_kategori_berita' => $kategori->id_kategori_berita,
            'id_petugas' => $petugas->petugas->id_petugas,
            'judul' => 'Program Literasi Kota',
            'slug' => 'program-literasi-kota',
        ]);

        $response = $this->actingAs($petugas)
            ->post(route('petugas.berita.store'), [
                'judul' => 'Program Literasi Kota',
                'isi' => 'Konten berita literasi kota.',
                'id_kategori_berita' => $kategori->id_kategori_berita,
                'status_berita' => 'draft',
            ]);

        $response->assertRedirect(route('petugas.berita.index'));

        $this->assertDatabaseHas('berita', [
            'judul' => 'Program Literasi Kota',
            'slug' => 'program-literasi-kota-2',
        ]);
    }

    public function test_slug_berita_menggunakan_fallback_acak_saat_judul_tidak_menghasilkan_slug(): void
    {
        $petugas = $this->createPetugasUser();
        $kategori = KategoriBerita::factory()->create();

        $response = $this->actingAs($petugas)
            ->post(route('petugas.berita.store'), [
                'judul' => '!!!',
                'isi' => 'Konten berita dengan judul simbol.',
                'id_kategori_berita' => $kategori->id_kategori_berita,
                'status_berita' => 'draft',
            ]);

        $response->assertRedirect(route('petugas.berita.index'));

        $berita = Berita::where('judul', '!!!')->firstOrFail();

        $this->assertMatchesRegularExpression('/^[A-Za-z0-9]{8}$/', $berita->slug);
    }

    public function test_slug_update_berita_mengabaikan_record_yang_sedang_diedit(): void
    {
        $petugas = $this->createPetugasUser();
        $kategori = KategoriBerita::factory()->create();

        Berita::factory()->create([
            'id_kategori_berita' => $kategori->id_kategori_berita,
            'id_petugas' => $petugas->petugas->id_petugas,
            'judul' => 'Judul Baru',
            'slug' => 'judul-baru',
        ]);

        $berita = Berita::factory()->create([
            'id_kategori_berita' => $kategori->id_kategori_berita,
            'id_petugas' => $petugas->petugas->id_petugas,
            'judul' => 'Judul Lama',
            'slug' => 'judul-lama',
        ]);

        $response = $this->actingAs($petugas)
            ->put(route('petugas.berita.update', $berita), [
                'judul' => 'Judul Baru',
                'isi' => 'Konten berita setelah judul diperbarui.',
                'id_kategori_berita' => $kategori->id_kategori_berita,
                'status_berita' => 'draft',
            ]);

        $response->assertRedirect(route('petugas.berita.index'));

        $this->assertDatabaseHas('berita', [
            'id_berita' => $berita->id_berita,
            'slug' => 'judul-baru-2',
        ]);
    }

    private function createPetugasUser(): User
    {
        $role = Role::create(['nama_role' => 'Petugas']);
        $user = User::factory()->create(['id_role' => $role->id_role]);
        Petugas::factory()->create(['id_user' => $user->id_user]);

        return $user->load('petugas');
    }
}
