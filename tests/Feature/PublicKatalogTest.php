<?php

namespace Tests\Feature;

use App\Models\Buku;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicKatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_katalog_search_by_title_is_case_insensitive(): void
    {
        // Create books with different cases in titles
        $book1 = Buku::factory()->create([
            'judul' => 'Arsitektur Klasik',
        ]);
        $book2 = Buku::factory()->create([
            'judul' => 'ARSITEKTUR MODERN',
        ]);
        $book3 = Buku::factory()->create([
            'judul' => 'Dasar Desain arsitektur',
        ]);
        $otherBook = Buku::factory()->create([
            'judul' => 'Pemrograman Web',
        ]);

        // Search with lowercase
        $response = $this->get(route('katalog', ['search' => 'arsitektur']));
        $response->assertStatus(200);
        $response->assertSee('Arsitektur Klasik');
        $response->assertSee('ARSITEKTUR MODERN');
        $response->assertSee('Dasar Desain arsitektur');
        $response->assertDontSee('Pemrograman Web');

        // Search with uppercase
        $response2 = $this->get(route('katalog', ['search' => 'ARSITEKTUR']));
        $response2->assertStatus(200);
        $response2->assertSee('Arsitektur Klasik');
        $response2->assertSee('ARSITEKTUR MODERN');
        $response2->assertSee('Dasar Desain arsitektur');
        $response2->assertDontSee('Pemrograman Web');
    }

    public function test_katalog_search_by_author_is_case_insensitive(): void
    {
        // Create books with different cases in authors
        $book1 = Buku::factory()->create([
            'penulis' => 'Buya Hamka',
        ]);
        $book2 = Buku::factory()->create([
            'penulis' => 'BUYA HAMKA',
        ]);
        $otherBook = Buku::factory()->create([
            'penulis' => 'Raditya Dika',
        ]);

        // Search with lowercase
        $response = $this->get(route('katalog', ['search' => 'buya hamka']));
        $response->assertStatus(200);
        $response->assertSee($book1->judul);
        $response->assertSee($book2->judul);
        $response->assertDontSee($otherBook->judul);

        // Search with uppercase
        $response2 = $this->get(route('katalog', ['search' => 'BUYA HAMKA']));
        $response2->assertStatus(200);
        $response2->assertSee($book1->judul);
        $response2->assertSee($book2->judul);
        $response2->assertDontSee($otherBook->judul);
    }
}
