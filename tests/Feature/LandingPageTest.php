<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Role;
use App\Models\SanksiAnggota;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that guest visitors can render the landing page.
     */
    public function test_landing_page_renders_for_guest(): void
    {
        $response = $this->get(route('landing'));

        $response->assertStatus(200);
        $response->assertSee('Selamat Datang di SIPADI');
        $response->assertSee('Katalog');
        $response->assertSee('Masuk');
        $response->assertSee('Daftar');
    }

    /**
     * Test that logged-in Anggota is redirect-linked to E-Kartu.
     */
    public function test_landing_page_renders_for_authenticated_anggota(): void
    {
        $role = Role::firstOrCreate(
            ['nama_role' => 'Anggota'],
            ['deskripsi' => 'Pengguna umum']
        );
        $user = User::factory()->create(['id_role' => $role->id_role]);

        $response = $this->actingAs($user)->get(route('landing'));

        $response->assertStatus(200);
        $response->assertSee('E-Kartu');
        $response->assertSee('Keluar');
        $response->assertDontSee('Masuk');
    }

    /**
     * Test that logged-in Petugas is redirect-linked to Dashboard Petugas.
     */
    public function test_landing_page_renders_for_authenticated_petugas(): void
    {
        $role = Role::firstOrCreate(
            ['nama_role' => 'Petugas'],
            ['deskripsi' => 'Petugas perpustakaan']
        );
        $user = User::factory()->create(['id_role' => $role->id_role]);

        $response = $this->actingAs($user)->get(route('landing'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
        $response->assertSee('Keluar');
        $response->assertDontSee('Masuk');
    }

    public function test_public_navbar_shows_active_member_status_badge_across_public_pages(): void
    {
        $anggota = $this->createAnggota();

        foreach ([route('landing'), route('katalog')] as $url) {
            $response = $this->actingAs($anggota->user)->get($url);

            $response->assertOk()
                ->assertSee('data-member-status-badge="Aktif"', false);
        }
    }

    public function test_public_navbar_prioritizes_blocked_member_status_badge(): void
    {
        $anggota = $this->createAnggota();

        $this->createActiveSanksi($anggota, 'Nonaktif Peminjaman 3 Hari', now()->addDays(3));
        $this->createActiveSanksi($anggota, 'Diblokir');

        $response = $this->actingAs($anggota->user)->get(route('landing'));

        $response->assertOk()
            ->assertSee('data-member-status-badge="Diblokir"', false)
            ->assertDontSee('data-member-status-badge="Sedang Sanksi"', false);
    }

    public function test_public_navbar_shows_sanctioned_member_status_badge(): void
    {
        $anggota = $this->createAnggota();

        $this->createActiveSanksi($anggota, 'Nonaktif Peminjaman 3 Hari', now()->addDays(3));

        $response = $this->actingAs($anggota->user)->get(route('landing'));

        $response->assertOk()
            ->assertSee('data-member-status-badge="Sedang Sanksi"', false)
            ->assertDontSee('data-member-status-badge="Aktif"', false);
    }

    public function test_public_navbar_prioritizes_nonactive_member_status_badge(): void
    {
        $anggota = $this->createAnggota(['status_anggota' => 'nonaktif']);

        $this->createActiveSanksi($anggota, 'Diblokir');

        $response = $this->actingAs($anggota->user)->get(route('landing'));

        $response->assertOk()
            ->assertSee('data-member-status-badge="Nonaktif"', false)
            ->assertDontSee('data-member-status-badge="Diblokir"', false);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function createAnggota(array $attributes = []): Anggota
    {
        $role = Role::firstOrCreate(
            ['nama_role' => 'Anggota'],
            ['deskripsi' => 'Pengguna umum']
        );
        $user = User::factory()->create(['id_role' => $role->id_role]);

        return Anggota::factory()->for($user, 'user')->create($attributes);
    }

    private function createActiveSanksi(Anggota $anggota, string $jenisSanksi, \DateTimeInterface|string|null $tanggalSelesai = null): SanksiAnggota
    {
        return SanksiAnggota::create([
            'id_anggota' => $anggota->id_anggota,
            'id_peminjaman' => null,
            'jenis_sanksi' => $jenisSanksi,
            'alasan' => 'Status navbar public.',
            'tanggal_mulai' => now(),
            'tanggal_selesai' => $tanggalSelesai,
            'status_sanksi' => 'aktif',
        ]);
    }
}
