<?php

namespace Tests\Feature;

use App\Models\Role;
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
}
