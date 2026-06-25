<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Berita;
use App\Models\KategoriBerita;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageNewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_only_shows_published_news_and_hides_draft_news(): void
    {
        // 1. Setup categories
        $kategoriKegiatan = KategoriBerita::factory()->create(['nama_kategori' => 'Kegiatan']);
        $kategoriPengumuman = KategoriBerita::factory()->create(['nama_kategori' => 'Pengumuman Draft']);

        // 2. Setup news (1 published, 1 draft)
        $beritaTerbit = Berita::factory()->published()->create([
            'judul' => 'Info Workshop Literasi Digital Terbit',
            'id_kategori_berita' => $kategoriKegiatan->id_kategori_berita,
        ]);

        $beritaDraft = Berita::factory()->draft()->create([
            'judul' => 'Info Pemeliharaan Gedung Draft',
            'id_kategori_berita' => $kategoriPengumuman->id_kategori_berita,
        ]);

        // 3. Request landing page
        $response = $this->get(route('landing'));

        // 4. Assertions
        $response->assertStatus(200);

        // Published news must be visible
        $response->assertSee('Info Workshop Literasi Digital Terbit');
        $response->assertSee('Kegiatan');

        // Draft news must NOT be visible
        $response->assertDontSee('Info Pemeliharaan Gedung Draft');
        $response->assertDontSee('Pengumuman Draft');
    }

    public function test_landing_page_shows_empty_message_when_no_published_news(): void
    {
        // Setup only a draft news
        $kategori = KategoriBerita::factory()->create(['nama_kategori' => 'Artikel']);
        Berita::factory()->draft()->create([
            'judul' => 'Artikel Draft Baru',
            'id_kategori_berita' => $kategori->id_kategori_berita,
        ]);

        $response = $this->get(route('landing'));

        $response->assertStatus(200);
        $response->assertSee('Belum ada berita terbaru.');
        $response->assertDontSee('Artikel Draft Baru');
    }

    public function test_landing_page_limits_news_to_three_latest_items(): void
    {
        $kategori = KategoriBerita::factory()->create(['nama_kategori' => 'Kegiatan']);

        // Create 4 published news items
        // The one with the lowest ID (first created) should not appear on the homepage
        $news1 = Berita::factory()->published()->create([
            'judul' => 'Berita Terlama Ke-4',
            'id_kategori_berita' => $kategori->id_kategori_berita,
        ]);
        $news2 = Berita::factory()->published()->create([
            'judul' => 'Berita Terbaru Ke-3',
            'id_kategori_berita' => $kategori->id_kategori_berita,
        ]);
        $news3 = Berita::factory()->published()->create([
            'judul' => 'Berita Terbaru Ke-2',
            'id_kategori_berita' => $kategori->id_kategori_berita,
        ]);
        $news4 = Berita::factory()->published()->create([
            'judul' => 'Berita Paling Baru Ke-1',
            'id_kategori_berita' => $kategori->id_kategori_berita,
        ]);

        $response = $this->get(route('landing'));

        $response->assertStatus(200);

        // The latest 3 must be visible
        $response->assertSee('Berita Paling Baru Ke-1');
        $response->assertSee('Berita Terbaru Ke-2');
        $response->assertSee('Berita Terbaru Ke-3');

        // The oldest one (4th) must NOT be visible
        $response->assertDontSee('Berita Terlama Ke-4');
    }

    public function test_authenticated_user_sees_profile_and_no_login_register_buttons(): void
    {
        $role = Role::create(['nama_role' => 'Anggota']);
        $user = User::factory()->create([
            'id_role' => $role->id_role,
        ]);
        $anggota = Anggota::factory()->create([
            'id_user' => $user->id_user,
            'nama_lengkap' => 'Budi Santoso',
        ]);

        // Request as guest
        $responseGuest = $this->get(route('landing'));
        $responseGuest->assertStatus(200);
        $responseGuest->assertSee('Masuk');
        $responseGuest->assertSee('Daftar');
        $responseGuest->assertDontSee('Budi Santoso');

        // Request as logged-in user
        $responseAuth = $this->actingAs($user)->get(route('landing'));
        $responseAuth->assertStatus(200);
        // We assert HTML structure changes. Let's make sure we assert it correctly
        // Wait, "Masuk" and "Daftar" might be inside route links. Let's assert we don't see buttons or the specific text.
        // On our navbar, guest has: Masuk and Daftar links.
        // Auth has: Keluar button and profile.
        $responseAuth->assertDontSee('href="'.route('login').'"', false);
        $responseAuth->assertDontSee('href="'.route('register').'"', false);
        $responseAuth->assertSee('Budi Santoso');

        // Also check that going to dashboard redirects to landing page
        $responseRedirect = $this->actingAs($user)->get(route('anggota.dashboard'));
        $responseRedirect->assertRedirect(route('landing'));
    }
}
