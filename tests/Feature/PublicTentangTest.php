<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicTentangTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_tentang_page_displays_library_profile(): void
    {
        $this->withoutVite();

        $response = $this->get(route('tentang'));

        $response->assertOk();
        $response->assertSee('Profil Perpustakaan');
        $response->assertSee('Sejarah');
        $response->assertSee('Visi');
        $response->assertSee('Misi');
        $response->assertSee('Struktur Kepegawaian');
        $response->assertSee('Bagan Kepegawaian Tahun 2026');
    }

    public function test_public_navbar_links_to_tentang_page(): void
    {
        $this->withoutVite();

        $response = $this->get(route('landing'));

        $response->assertOk();
        $response->assertSee('href="'.route('tentang').'"', false);
    }
}
