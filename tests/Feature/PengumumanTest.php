<?php

namespace Tests\Feature;

use App\Models\Pengumuman;
use App\Models\Petugas;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PengumumanTest extends TestCase
{
    use RefreshDatabase;

    public function test_petugas_dapat_melihat_daftar_pengumuman(): void
    {
        $petugas = $this->createPetugasUser();

        $pengumuman1 = Pengumuman::create([
            'judul' => 'Pengumuman Penting 1',
            'isi' => 'Konten pengumuman penting 1',
            'id_petugas' => $petugas->petugas->id_petugas,
            'slug' => 'pengumuman-penting-1',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addDays(5)->toDateString(),
            'status_pengumuman' => 'terbit',
            'prioritas' => 'Penting',
            'target_pengguna' => 'Semua',
        ]);

        $response = $this->actingAs($petugas)
            ->get(route('petugas.pengumuman.index'));

        $response->assertOk()
            ->assertSee('Pengumuman Penting 1')
            ->assertSee('Aktif');
    }

    public function test_petugas_dapat_melihat_daftar_pengumuman_dengan_filter_dan_pencarian(): void
    {
        $petugas = $this->createPetugasUser();

        Pengumuman::create([
            'judul' => 'Ujian Tengah Semester',
            'isi' => 'Harap persiapkan diri untuk ujian',
            'id_petugas' => $petugas->petugas->id_petugas,
            'slug' => 'ujian-tengah-semester',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addDays(5)->toDateString(),
            'status_pengumuman' => 'terbit',
            'prioritas' => 'Normal',
            'target_pengguna' => 'Semua',
        ]);

        Pengumuman::create([
            'judul' => 'Pendaftaran Anggota Baru',
            'isi' => 'Pendaftaran dibuka senin depan',
            'id_petugas' => $petugas->petugas->id_petugas,
            'slug' => 'pendaftaran-anggota-baru',
            'tanggal_mulai' => now()->addDays(2)->toDateString(),
            'tanggal_selesai' => now()->addDays(7)->toDateString(),
            'status_pengumuman' => 'terbit',
            'prioritas' => 'Penting',
            'target_pengguna' => 'Siswa / Mahasiswa',
        ]);

        // Cari berdasarkan kata 'Ujian'
        $response = $this->actingAs($petugas)
            ->get(route('petugas.pengumuman.index', ['search' => 'Ujian']));
        $response->assertSee('Ujian Tengah Semester')
            ->assertDontSee('Pendaftaran Anggota Baru');

        // Filter berdasarkan status Mendatang
        $response = $this->actingAs($petugas)
            ->get(route('petugas.pengumuman.index', ['status' => 'Mendatang']));
        $response->assertSee('Pendaftaran Anggota Baru')
            ->assertDontSee('Ujian Tengah Semester');
    }

    public function test_petugas_dapat_menambah_pengumuman_terbit(): void
    {
        Storage::fake('public');
        $petugas = $this->createPetugasUser();
        $gambar = $this->fakeImage('banner.png');
        $lampiran = $this->fakeFile('dokumen.pdf');

        $response = $this->actingAs($petugas)
            ->post(route('petugas.pengumuman.store'), [
                'judul' => 'Pengumuman Terbit Baru',
                'isi' => 'Konten pengumuman terbit baru.',
                'tanggal_mulai' => now()->toDateString(),
                'tanggal_selesai' => now()->addDays(5)->toDateString(),
                'prioritas' => 'Normal',
                'target_pengguna' => 'Semua',
                'status_pengumuman' => 'terbit',
                'gambar' => $gambar,
                'file_lampiran' => [$lampiran],
            ]);

        $response->assertRedirect(route('petugas.pengumuman.index'));
        $this->assertDatabaseHas('pengumuman', [
            'judul' => 'Pengumuman Terbit Baru',
            'slug' => 'pengumuman-terbit-baru',
            'status_pengumuman' => 'terbit',
        ]);

        $pengumuman = Pengumuman::where('judul', 'Pengumuman Terbit Baru')->first();
        $this->assertNotNull($pengumuman->gambar);
        $this->assertNotNull($pengumuman->file_lampiran);
        Storage::disk('public')->assertExists($pengumuman->gambar);
        Storage::disk('public')->assertExists($pengumuman->file_lampiran[0]['path']);
    }

    public function test_petugas_dapat_menambah_pengumuman_draf(): void
    {
        $petugas = $this->createPetugasUser();

        $response = $this->actingAs($petugas)
            ->post(route('petugas.pengumuman.store'), [
                'judul' => 'Pengumuman Draf Baru',
                'isi' => 'Konten pengumuman draf baru.',
                'tanggal_mulai' => now()->toDateString(),
                'tanggal_selesai' => now()->addDays(5)->toDateString(),
                'prioritas' => 'Penting',
                'target_pengguna' => 'Dosen',
                'status_pengumuman' => 'draf',
            ]);

        $response->assertRedirect(route('petugas.pengumuman.index'));
        $this->assertDatabaseHas('pengumuman', [
            'judul' => 'Pengumuman Draf Baru',
            'status_pengumuman' => 'draf',
            'prioritas' => 'Penting',
            'target_pengguna' => 'Dosen',
        ]);
    }

    public function test_petugas_dapat_melihat_detail_pengumuman_dan_meningkatkan_views(): void
    {
        $petugas = $this->createPetugasUser();
        $pengumuman = Pengumuman::create([
            'judul' => 'Pengumuman Detail Test',
            'isi' => 'Konten isi pengumuman.',
            'id_petugas' => $petugas->petugas->id_petugas,
            'slug' => 'pengumuman-detail-test',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addDays(5)->toDateString(),
            'status_pengumuman' => 'terbit',
            'prioritas' => 'Normal',
            'target_pengguna' => 'Semua',
            'total_views' => 10,
        ]);

        $response = $this->actingAs($petugas)
            ->get(route('petugas.pengumuman.show', $pengumuman));

        $response->assertOk()
            ->assertSee('Pengumuman Detail Test');

        $pengumuman->refresh();
        $this->assertEquals(11, $pengumuman->total_views);
    }

    public function test_petugas_dapat_mengubah_pengumuman(): void
    {
        Storage::fake('public');
        $petugas = $this->createPetugasUser();
        $gambarLama = Storage::disk('public')->putFile('pengumuman', $this->fakeImage('old.png'));

        $pengumuman = Pengumuman::create([
            'judul' => 'Judul Pengumuman Lama',
            'isi' => 'Konten lama.',
            'id_petugas' => $petugas->petugas->id_petugas,
            'slug' => 'judul-pengumuman-lama',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addDays(5)->toDateString(),
            'status_pengumuman' => 'terbit',
            'prioritas' => 'Normal',
            'target_pengguna' => 'Semua',
            'gambar' => $gambarLama,
        ]);

        Storage::disk('public')->assertExists($gambarLama);

        $gambarBaru = $this->fakeImage('new.png');
        $response = $this->actingAs($petugas)
            ->put(route('petugas.pengumuman.update', $pengumuman), [
                'judul' => 'Judul Pengumuman Diperbarui',
                'isi' => 'Konten baru yang diperbarui.',
                'tanggal_mulai' => now()->toDateString(),
                'tanggal_selesai' => now()->addDays(10)->toDateString(),
                'prioritas' => 'Penting',
                'target_pengguna' => 'Siswa / Mahasiswa',
                'status_pengumuman' => 'terbit',
                'gambar' => $gambarBaru,
            ]);

        $response->assertRedirect(route('petugas.pengumuman.index'));
        $pengumuman->refresh();

        $this->assertEquals('Judul Pengumuman Diperbarui', $pengumuman->judul);
        $this->assertEquals('judul-pengumuman-diperbarui', $pengumuman->slug);
        $this->assertEquals('Penting', $pengumuman->prioritas);
        Storage::disk('public')->assertMissing($gambarLama);
        Storage::disk('public')->assertExists($pengumuman->gambar);
    }

    public function test_petugas_dapat_menghapus_pengumuman(): void
    {
        Storage::fake('public');
        $petugas = $this->createPetugasUser();
        $gambarPath = Storage::disk('public')->putFile('pengumuman', $this->fakeImage('banner.png'));

        $pengumuman = Pengumuman::create([
            'judul' => 'Pengumuman Hapus Test',
            'isi' => 'Konten.',
            'id_petugas' => $petugas->petugas->id_petugas,
            'slug' => 'pengumuman-hapus-test',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addDays(5)->toDateString(),
            'status_pengumuman' => 'terbit',
            'prioritas' => 'Normal',
            'target_pengguna' => 'Semua',
            'gambar' => $gambarPath,
        ]);

        Storage::disk('public')->assertExists($gambarPath);

        $response = $this->actingAs($petugas)
            ->delete(route('petugas.pengumuman.destroy', $pengumuman));

        $response->assertRedirect(route('petugas.pengumuman.index'));
        $this->assertDatabaseMissing('pengumuman', [
            'id_pengumuman' => $pengumuman->id_pengumuman,
        ]);
        Storage::disk('public')->assertMissing($gambarPath);
    }

    public function test_petugas_dapat_mengubah_pengumuman_tanpa_mengubah_gambar_dan_lampiran(): void
    {
        Storage::fake('public');
        $petugas = $this->createPetugasUser();
        $gambarLama = Storage::disk('public')->putFile('pengumuman', $this->fakeImage('old.png'));
        $lampiranLamaPath = Storage::disk('public')->putFile('pengumuman/lampiran', $this->fakeFile('old_doc.pdf'));
        $lampiranLama = [
            [
                'name' => 'old_doc.pdf',
                'path' => $lampiranLamaPath,
                'size' => '100 KB',
            ],
        ];

        $pengumuman = Pengumuman::create([
            'judul' => 'Judul Pengumuman Lama',
            'isi' => 'Konten lama.',
            'id_petugas' => $petugas->petugas->id_petugas,
            'slug' => 'judul-pengumuman-lama',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addDays(5)->toDateString(),
            'status_pengumuman' => 'terbit',
            'prioritas' => 'Normal',
            'target_pengguna' => 'Semua',
            'gambar' => $gambarLama,
            'file_lampiran' => $lampiranLama,
        ]);

        Storage::disk('public')->assertExists($gambarLama);
        Storage::disk('public')->assertExists($lampiranLamaPath);

        // Update without uploading new image or files
        $response = $this->actingAs($petugas)
            ->put(route('petugas.pengumuman.update', $pengumuman), [
                'judul' => 'Judul Pengumuman Diperbarui',
                'isi' => 'Konten baru yang diperbarui.',
                'tanggal_mulai' => now()->toDateString(),
                'tanggal_selesai' => now()->addDays(10)->toDateString(),
                'prioritas' => 'Penting',
                'target_pengguna' => 'Siswa / Mahasiswa',
                'status_pengumuman' => 'terbit',
            ]);

        $response->assertRedirect(route('petugas.pengumuman.index'));
        $pengumuman->refresh();

        $this->assertEquals('Judul Pengumuman Diperbarui', $pengumuman->judul);
        $this->assertEquals($gambarLama, $pengumuman->gambar);
        $this->assertEquals($lampiranLama, $pengumuman->file_lampiran);
        Storage::disk('public')->assertExists($gambarLama);
        Storage::disk('public')->assertExists($lampiranLamaPath);
    }

    public function test_anggota_tidak_dapat_mengakses_kelola_pengumuman(): void
    {
        $role = Role::create(['nama_role' => 'Anggota']);
        $anggota = User::factory()->create(['id_role' => $role->id_role]);

        $this->actingAs($anggota)
            ->get(route('petugas.pengumuman.index'))
            ->assertForbidden();
    }

    public function test_validasi_saat_tambah_pengumuman(): void
    {
        $petugas = $this->createPetugasUser();

        $response = $this->actingAs($petugas)
            ->post(route('petugas.pengumuman.store'), [
                'judul' => '',
                'isi' => '',
                'status_pengumuman' => 'invalid',
            ]);

        $response->assertSessionHasErrors(['judul', 'isi', 'status_pengumuman', 'tanggal_mulai', 'tanggal_selesai']);
    }

    private function createPetugasUser(): User
    {
        $role = Role::updateOrCreate(['nama_role' => 'Petugas']);
        $user = User::factory()->create(['id_role' => $role->id_role]);
        Petugas::factory()->create(['id_user' => $user->id_user]);

        return $user->load('petugas');
    }

    private function fakeImage(string $name): UploadedFile
    {
        $png = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII=';

        return UploadedFile::fake()->createWithContent($name, base64_decode($png));
    }

    private function fakeFile(string $name): UploadedFile
    {
        return UploadedFile::fake()->create($name, 100); // 100 KB fake file
    }
}
