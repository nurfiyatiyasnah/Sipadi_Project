<?php

namespace Tests\Feature;

use App\Models\Pengumuman;
use App\Models\Petugas;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPengumumanTest extends TestCase
{
    use RefreshDatabase;

    private Petugas $petugas;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create([
            'nama_role' => 'Petugas',
            'deskripsi' => 'Petugas',
        ]);
        $user = User::factory()->create([
            'id_role' => $role->id_role,
        ]);
        $this->petugas = Petugas::create([
            'id_user' => $user->id_user,
            'nama_petugas' => 'Petugas Test',
            'jabatan' => 'Pustakawan',
        ]);
    }

    public function test_can_view_public_announcements_list(): void
    {
        // 1. Create active published announcements
        Pengumuman::create([
            'judul' => 'Pengumuman Aktif A',
            'slug' => 'pengumuman-aktif-a',
            'isi' => 'Konten pengumuman aktif A.',
            'id_petugas' => $this->petugas->id_petugas,
            'tanggal_mulai' => now()->subDays(2)->toDateString(),
            'tanggal_selesai' => now()->addDays(5)->toDateString(),
            'status_pengumuman' => 'terbit',
            'prioritas' => 'Normal',
            'target_pengguna' => 'Semua',
        ]);

        Pengumuman::create([
            'judul' => 'Pengumuman Penting B',
            'slug' => 'pengumuman-penting-b',
            'isi' => 'Konten pengumuman penting B.',
            'id_petugas' => $this->petugas->id_petugas,
            'tanggal_mulai' => now()->subDays(1)->toDateString(),
            'tanggal_selesai' => now()->addDays(10)->toDateString(),
            'status_pengumuman' => 'terbit',
            'prioritas' => 'Penting',
            'target_pengguna' => 'Siswa / Mahasiswa',
        ]);

        // 2. Request index page
        $response = $this->get(route('pengumuman.public.index'));

        // 3. Assertions
        $response->assertStatus(200);
        $response->assertSee('Pengumuman');
        $response->assertSee('Pengumuman Aktif A');
        $response->assertSee('Pengumuman Penting B');
        $response->assertSee('default-pengumuman.png');
    }

    public function test_can_search_public_announcements(): void
    {
        Pengumuman::create([
            'judul' => 'Pengumuman Cari X',
            'slug' => 'pengumuman-cari-x',
            'isi' => 'Konten unik cari.',
            'id_petugas' => $this->petugas->id_petugas,
            'tanggal_mulai' => now()->subDays(1)->toDateString(),
            'tanggal_selesai' => now()->addDays(5)->toDateString(),
            'status_pengumuman' => 'terbit',
            'prioritas' => 'Normal',
            'target_pengguna' => 'Semua',
        ]);

        Pengumuman::create([
            'judul' => 'Pengumuman Sembunyikan Y',
            'slug' => 'pengumuman-sembunyikan-y',
            'isi' => 'Konten biasa saja.',
            'id_petugas' => $this->petugas->id_petugas,
            'tanggal_mulai' => now()->subDays(1)->toDateString(),
            'tanggal_selesai' => now()->addDays(5)->toDateString(),
            'status_pengumuman' => 'terbit',
            'prioritas' => 'Normal',
            'target_pengguna' => 'Semua',
        ]);

        $response = $this->get(route('pengumuman.public.index', ['search' => 'Cari X']));
        $response->assertStatus(200);
        $response->assertSee('Pengumuman Cari X');
        $response->assertDontSee('Pengumuman Sembunyikan Y');
    }

    public function test_can_view_published_announcement_details(): void
    {
        $pengumuman = Pengumuman::create([
            'judul' => 'Detail Pengumuman Z',
            'slug' => 'detail-pengumuman-z',
            'isi' => 'Isi detail pengumuman Z.',
            'id_petugas' => $this->petugas->id_petugas,
            'tanggal_mulai' => now()->subDays(1)->toDateString(),
            'tanggal_selesai' => now()->addDays(5)->toDateString(),
            'status_pengumuman' => 'terbit',
            'prioritas' => 'Normal',
            'target_pengguna' => 'Semua',
            'total_views' => 10,
        ]);

        $response = $this->get(route('pengumuman.public.show', $pengumuman->slug));

        $response->assertStatus(200);
        $response->assertSee('Detail Pengumuman Z');
        $response->assertSee('Isi detail pengumuman Z.');
        $response->assertSee('default-pengumuman.png');

        $pengumuman->refresh();
        $this->assertEquals(11, $pengumuman->total_views);
    }

    public function test_cannot_view_draft_announcement_details(): void
    {
        $pengumuman = Pengumuman::create([
            'judul' => 'Draft Pengumuman',
            'slug' => 'draft-pengumuman',
            'isi' => 'Isi draft.',
            'id_petugas' => $this->petugas->id_petugas,
            'tanggal_mulai' => now()->subDays(1)->toDateString(),
            'tanggal_selesai' => now()->addDays(5)->toDateString(),
            'status_pengumuman' => 'draf',
            'prioritas' => 'Normal',
            'target_pengguna' => 'Semua',
        ]);

        $response = $this->get(route('pengumuman.public.show', $pengumuman->slug));
        $response->assertStatus(404);
    }

    public function test_cannot_view_expired_announcement_details(): void
    {
        $pengumuman = Pengumuman::create([
            'judul' => 'Expired Pengumuman',
            'slug' => 'expired-pengumuman',
            'isi' => 'Isi expired.',
            'id_petugas' => $this->petugas->id_petugas,
            'tanggal_mulai' => now()->subDays(5)->toDateString(),
            'tanggal_selesai' => now()->subDays(1)->toDateString(),
            'status_pengumuman' => 'terbit',
            'prioritas' => 'Normal',
            'target_pengguna' => 'Semua',
        ]);

        $response = $this->get(route('pengumuman.public.show', $pengumuman->slug));
        $response->assertStatus(404);
    }
}
