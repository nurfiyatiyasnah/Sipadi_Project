<?php

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\KategoriBerita;
use App\Models\Petugas;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BeritaTest extends TestCase
{
    use RefreshDatabase;

    public function test_petugas_dapat_melihat_daftar_berita(): void
    {
        $petugas = $this->createPetugasUser();
        $kategori = KategoriBerita::factory()->create(['nama_kategori' => 'Kategori A']);

        $beritaTerbit = Berita::factory()->published()->create([
            'judul' => 'Berita Terbit A',
            'id_kategori_berita' => $kategori->id_kategori_berita,
            'id_petugas' => $petugas->petugas->id_petugas,
        ]);

        $beritaDraft = Berita::factory()->draft()->create([
            'judul' => 'Berita Draft B',
            'id_kategori_berita' => $kategori->id_kategori_berita,
            'id_petugas' => $petugas->petugas->id_petugas,
        ]);

        $response = $this->actingAs($petugas)
            ->get(route('petugas.berita.index'));

        $response->assertOk()
            ->assertSee('Berita Terbit A')
            ->assertSee('Berita Draft B')
            ->assertSee('Kategori A')
            ->assertSee('TERBIT')
            ->assertSee('DRAFT');
    }

    public function test_petugas_dapat_melihat_daftar_berita_dengan_filter_dan_pencarian(): void
    {
        $petugas = $this->createPetugasUser();
        $kategoriA = KategoriBerita::factory()->create(['nama_kategori' => 'Kategori A']);
        $kategoriB = KategoriBerita::factory()->create(['nama_kategori' => 'Kategori B']);

        Berita::factory()->published()->create([
            'judul' => 'Pemrograman PHP Terbaru',
            'id_kategori_berita' => $kategoriA->id_kategori_berita,
            'id_petugas' => $petugas->petugas->id_petugas,
        ]);

        Berita::factory()->draft()->create([
            'judul' => 'Panduan Laravel 12',
            'id_kategori_berita' => $kategoriB->id_kategori_berita,
            'id_petugas' => $petugas->petugas->id_petugas,
        ]);

        // Cari berdasarkan kata 'PHP'
        $response = $this->actingAs($petugas)
            ->get(route('petugas.berita.index', ['search' => 'PHP']));
        $response->assertSee('Pemrograman PHP Terbaru')
            ->assertDontSee('Panduan Laravel 12');

        // Filter berdasarkan kategori A
        $response = $this->actingAs($petugas)
            ->get(route('petugas.berita.index', ['kategori' => $kategoriA->id_kategori_berita]));
        $response->assertSee('Pemrograman PHP Terbaru')
            ->assertDontSee('Panduan Laravel 12');

        // Filter berdasarkan status draft
        $response = $this->actingAs($petugas)
            ->get(route('petugas.berita.index', ['status' => 'draft']));
        $response->assertSee('Panduan Laravel 12')
            ->assertDontSee('Pemrograman PHP Terbaru');
    }

    public function test_petugas_dapat_menambah_berita_sebagai_draft(): void
    {
        Storage::fake('public');
        $petugas = $this->createPetugasUser();
        $kategori = KategoriBerita::factory()->create();
        $gambar = $this->fakeImage('thumbnail.png');

        $response = $this->actingAs($petugas)
            ->post(route('petugas.berita.store'), [
                'judul' => 'Judul Berita Draft Baru',
                'isi' => 'Konten isi berita draft baru yang sangat bermanfaat.',
                'id_kategori_berita' => $kategori->id_kategori_berita,
                'gambar' => $gambar,
                'status_berita' => 'draft',
            ]);

        $response->assertRedirect(route('petugas.berita.index'));
        $this->assertDatabaseHas('berita', [
            'judul' => 'Judul Berita Draft Baru',
            'status_berita' => 'draft',
            'tanggal_terbit' => null,
        ]);

        $berita = Berita::where('judul', 'Judul Berita Draft Baru')->first();
        $this->assertNotNull($berita->gambar);
        Storage::disk('public')->assertExists($berita->gambar);
    }

    public function test_petugas_dapat_menambah_berita_langsung_terbit(): void
    {
        $petugas = $this->createPetugasUser();
        $kategori = KategoriBerita::factory()->create();

        $response = $this->actingAs($petugas)
            ->post(route('petugas.berita.store'), [
                'judul' => 'Judul Berita Langsung Terbit',
                'isi' => 'Konten berita terbit.',
                'id_kategori_berita' => $kategori->id_kategori_berita,
                'status_berita' => 'terbit',
            ]);

        $response->assertRedirect(route('petugas.berita.index'));
        $this->assertDatabaseHas('berita', [
            'judul' => 'Judul Berita Langsung Terbit',
            'status_berita' => 'terbit',
        ]);

        $berita = Berita::where('judul', 'Judul Berita Langsung Terbit')->first();
        $this->assertNotNull($berita->tanggal_terbit);
    }

    public function test_petugas_dapat_menerbitkan_berita_draft(): void
    {
        $petugas = $this->createPetugasUser();
        $berita = Berita::factory()->draft()->create([
            'id_petugas' => $petugas->petugas->id_petugas,
        ]);

        $response = $this->actingAs($petugas)
            ->patch(route('petugas.berita.publish', $berita));

        $response->assertRedirect(route('petugas.berita.index'));
        $this->assertDatabaseHas('berita', [
            'id_berita' => $berita->id_berita,
            'status_berita' => 'terbit',
        ]);
        $this->assertNotNull($berita->fresh()->tanggal_terbit);
    }

    public function test_petugas_dapat_melihat_form_edit_berita(): void
    {
        $petugas = $this->createPetugasUser();
        $kategori = KategoriBerita::factory()->create(['nama_kategori' => 'Informasi']);
        $berita = Berita::factory()->draft()->create([
            'judul' => 'Berita yang Akan Diedit',
            'id_kategori_berita' => $kategori->id_kategori_berita,
            'id_petugas' => $petugas->petugas->id_petugas,
        ]);

        $response = $this->actingAs($petugas)
            ->get(route('petugas.berita.edit', $berita));

        $response->assertOk()
            ->assertSee('Edit Berita')
            ->assertSee('Berita yang Akan Diedit')
            ->assertSee('Informasi');
    }

    public function test_petugas_dapat_memperbarui_berita(): void
    {
        Storage::fake('public');
        $petugas = $this->createPetugasUser();
        $kategoriLama = KategoriBerita::factory()->create();
        $kategoriBaru = KategoriBerita::factory()->create();
        $gambarLama = Storage::disk('public')->putFile('berita', $this->fakeImage('lama.png'));
        $gambarBaru = $this->fakeImage('baru.png');

        $berita = Berita::factory()->draft()->create([
            'judul' => 'Judul Lama',
            'id_kategori_berita' => $kategoriLama->id_kategori_berita,
            'id_petugas' => $petugas->petugas->id_petugas,
            'gambar' => $gambarLama,
        ]);

        $response = $this->actingAs($petugas)
            ->put(route('petugas.berita.update', $berita), [
                'judul' => 'Judul Berita Diperbarui',
                'isi' => 'Isi berita yang sudah diperbarui.',
                'id_kategori_berita' => $kategoriBaru->id_kategori_berita,
                'gambar' => $gambarBaru,
                'status_berita' => 'terbit',
            ]);

        $response->assertRedirect(route('petugas.berita.index'));
        $this->assertDatabaseHas('berita', [
            'id_berita' => $berita->id_berita,
            'judul' => 'Judul Berita Diperbarui',
            'id_kategori_berita' => $kategoriBaru->id_kategori_berita,
            'status_berita' => 'terbit',
        ]);

        $berita->refresh();
        $this->assertNotNull($berita->tanggal_terbit);
        $this->assertNotSame($gambarLama, $berita->gambar);
        Storage::disk('public')->assertMissing($gambarLama);
        Storage::disk('public')->assertExists($berita->gambar);
    }

    public function test_petugas_dapat_menghapus_berita(): void
    {
        Storage::fake('public');
        $petugas = $this->createPetugasUser();
        $gambarPath = Storage::disk('public')->putFile('berita', $this->fakeImage('thumbnail.png'));

        $berita = Berita::factory()->create([
            'id_petugas' => $petugas->petugas->id_petugas,
            'gambar' => $gambarPath,
        ]);

        Storage::disk('public')->assertExists($gambarPath);

        $response = $this->actingAs($petugas)
            ->delete(route('petugas.berita.destroy', $berita));

        $response->assertRedirect(route('petugas.berita.index'));
        $this->assertDatabaseMissing('berita', [
            'id_berita' => $berita->id_berita,
        ]);
        Storage::disk('public')->assertMissing($gambarPath);
    }

    public function test_anggota_tidak_dapat_mengakses_kelola_berita(): void
    {
        $role = Role::create(['nama_role' => 'Anggota']);
        $anggota = User::factory()->create(['id_role' => $role->id_role]);

        $this->actingAs($anggota)
            ->get(route('petugas.berita.index'))
            ->assertForbidden();

        $this->actingAs($anggota)
            ->get(route('petugas.berita.create'))
            ->assertForbidden();
    }

    public function test_validasi_saat_tambah_berita(): void
    {
        $petugas = $this->createPetugasUser();

        $response = $this->actingAs($petugas)
            ->post(route('petugas.berita.store'), [
                'judul' => '',
                'id_kategori_berita' => 9999, // Kategori tidak valid
                'status_berita' => 'invalid_status',
            ]);

        $response->assertSessionHasErrors(['judul', 'id_kategori_berita', 'status_berita']);
    }

    private function createPetugasUser(): User
    {
        $role = Role::create(['nama_role' => 'Petugas']);
        $user = User::factory()->create(['id_role' => $role->id_role]);
        Petugas::factory()->create(['id_user' => $user->id_user]);

        return $user->load('petugas');
    }

    private function fakeImage(string $name): UploadedFile
    {
        $png = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII=';

        return UploadedFile::fake()->createWithContent($name, base64_decode($png));
    }
}
