<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\EksemplarBuku;
use App\Models\KategoriBuku;
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

    public function test_detail_katalog_menampilkan_lokasi_rak_dari_eksemplar(): void
    {
        $book = Buku::factory()->create();
        EksemplarBuku::factory()->create([
            'id_buku' => $book->id_buku,
            'lokasi_rak' => 'Lantai 1 - Rak B-02',
        ]);

        $response = $this->get(route('katalog.show', $book->id_buku));

        $response->assertOk();
        $response->assertSee('Lantai 1 - Rak B-02');
        $response->assertDontSee('Lantai 2 - Rak A-12');
    }

    public function test_detail_katalog_tanpa_lokasi_rak_tidak_menampilkan_lokasi_palsu(): void
    {
        $book = Buku::factory()->create();
        EksemplarBuku::factory()->create([
            'id_buku' => $book->id_buku,
            'lokasi_rak' => null,
        ]);

        $response = $this->get(route('katalog.show', $book->id_buku));

        $response->assertOk();
        $response->assertSee('Belum diatur');
        $response->assertDontSee('Lantai 2 - Rak A-12');
    }

    public function test_detail_katalog_menampilkan_lokasi_rak_terisi_dari_semua_eksemplar(): void
    {
        $book = Buku::factory()->create();
        EksemplarBuku::factory()->create([
            'id_buku' => $book->id_buku,
            'lokasi_rak' => null,
        ]);
        EksemplarBuku::factory()->create([
            'id_buku' => $book->id_buku,
            'lokasi_rak' => 'Lantai 1 - Rak B-02',
        ]);
        EksemplarBuku::factory()->create([
            'id_buku' => $book->id_buku,
            'lokasi_rak' => 'Lantai 2 - Rak C-01',
        ]);

        $response = $this->get(route('katalog.show', $book->id_buku));

        $response->assertOk();
        $response->assertSee('Lantai 1 - Rak B-02');
        $response->assertSee('Lantai 2 - Rak C-01');
        $response->assertDontSee('Belum diatur');
    }

    public function test_katalog_tidak_menandai_buku_bermasalah_sebagai_dipinjam(): void
    {
        $book = Buku::factory()->create([
            'judul' => 'Buku Eksemplar Rusak',
            'status_katalog' => 'aktif',
        ]);

        foreach (['rusak', 'hilang', 'nonaktif'] as $index => $status) {
            EksemplarBuku::create([
                'id_buku' => $book->id_buku,
                'kode_eksemplar' => sprintf('BK-2222-%03d', $index + 1),
                'status_eksemplar' => $status,
            ]);
        }

        $this->get(route('katalog'))
            ->assertOk()
            ->assertSee('Buku Eksemplar Rusak')
            ->assertSee('TIDAK TERSEDIA')
            ->assertDontSee('DIPINJAM');

        $this->get(route('katalog.show', $book->id_buku))
            ->assertOk()
            ->assertSee('Tidak Tersedia')
            ->assertDontSee('Dipinjam Semua');
    }

    public function test_detail_katalog_hanya_menampilkan_tombol_pengajuan_jika_buku_tersedia(): void
    {
        $availableBook = Buku::factory()->create([
            'judul' => 'Buku Bisa Dipinjam',
            'status_katalog' => 'aktif',
        ]);
        EksemplarBuku::factory()->create([
            'id_buku' => $availableBook->id_buku,
            'status_eksemplar' => EksemplarBuku::STATUS_TERSEDIA,
        ]);

        $unavailableBook = Buku::factory()->create([
            'judul' => 'Buku Tidak Bisa Dipinjam',
            'status_katalog' => 'aktif',
        ]);
        EksemplarBuku::factory()->create([
            'id_buku' => $unavailableBook->id_buku,
            'status_eksemplar' => EksemplarBuku::STATUS_RUSAK,
        ]);

        $this->get(route('katalog.show', $availableBook->id_buku))
            ->assertOk()
            ->assertSee('Ajukan Peminjaman')
            ->assertDontSee('Tidak Bisa Dipinjam');

        $this->get(route('katalog.show', $unavailableBook->id_buku))
            ->assertOk()
            ->assertSee('Tidak Bisa Dipinjam')
            ->assertDontSee('Ajukan Peminjaman');
    }

    public function test_detail_katalog_tidak_menampilkan_tombol_simpan_koleksi_palsu(): void
    {
        $book = Buku::factory()->create([
            'judul' => 'Buku Tanpa Fitur Koleksi',
            'status_katalog' => 'aktif',
        ]);
        EksemplarBuku::factory()->create([
            'id_buku' => $book->id_buku,
            'status_eksemplar' => EksemplarBuku::STATUS_TERSEDIA,
        ]);

        $this->get(route('katalog.show', $book->id_buku))
            ->assertOk()
            ->assertDontSee('Simpan ke Koleksi')
            ->assertDontSee('Tersimpan di Koleksi')
            ->assertDontSee('Buku berhasil disimpan ke koleksi Anda')
            ->assertDontSee('Buku dihapus dari koleksi Anda')
            ->assertDontSee('toggleFavorite', false);
    }

    public function test_detail_katalog_hanya_menampilkan_badge_kategori_asli(): void
    {
        $category = KategoriBuku::factory()->create([
            'nama_kategori' => 'Sejarah Minangkabau',
        ]);
        $book = Buku::factory()->for($category, 'kategori')->create([
            'judul' => 'Buku Kategori Asli',
            'status_katalog' => 'aktif',
        ]);

        $this->get(route('katalog.show', $book->id_buku))
            ->assertOk()
            ->assertSee('Sejarah Minangkabau')
            ->assertDontSee('Referensi Lokal')
            ->assertDontSee('Koleksi Umum');
    }

    public function test_detail_katalog_tidak_menampilkan_metadata_hardcoded_yang_tidak_ada_di_tabel_buku(): void
    {
        $book = Buku::factory()->create([
            'judul' => 'Laskar Pelangi',
            'penulis' => 'Andrea Hirata',
            'penerbit' => 'Bentang Pustaka',
            'tahun_terbit' => 2005,
            'isbn' => '978-602-291-662-8',
        ]);

        $response = $this->get(route('katalog.show', $book->id_buku));

        $response->assertOk();
        $response->assertDontSee('Ulasan');
        $response->assertDontSee('Dilihat');
        $response->assertDontSee('Halaman');
        $response->assertDontSee('Bahasa');
        $response->assertDontSee('Indonesia');
        $response->assertDontSee('Nomor Panggil');
        $response->assertDontSee('4.8');
        $response->assertDontSee('529');
    }

    public function test_katalog_publik_tidak_menampilkan_buku_nonaktif(): void
    {
        Buku::factory()->create([
            'judul' => 'Buku Aktif Publik',
            'status_katalog' => 'aktif',
        ]);
        Buku::factory()->create([
            'judul' => 'Buku Nonaktif Publik',
            'status_katalog' => 'nonaktif',
        ]);

        $response = $this->get(route('katalog'));

        $response->assertOk();
        $response->assertSee('Buku Aktif Publik');
        $response->assertDontSee('Buku Nonaktif Publik');
    }

    public function test_detail_katalog_buku_nonaktif_tidak_bisa_diakses(): void
    {
        $book = Buku::factory()->create([
            'status_katalog' => 'nonaktif',
        ]);

        $this->get(route('katalog.show', $book->id_buku))
            ->assertNotFound();
    }

    public function test_rekomendasi_katalog_tidak_menampilkan_buku_nonaktif(): void
    {
        $activeBook = Buku::factory()->create([
            'judul' => 'Buku Aktif Utama',
            'status_katalog' => 'aktif',
        ]);
        $categoryId = $activeBook->id_kategori;
        Buku::factory()->create([
            'id_kategori' => $categoryId,
            'judul' => 'Rekomendasi Aktif',
            'status_katalog' => 'aktif',
        ]);
        Buku::factory()->create([
            'id_kategori' => $categoryId,
            'judul' => 'Rekomendasi Nonaktif',
            'status_katalog' => 'nonaktif',
        ]);

        $response = $this->get(route('katalog.show', $activeBook->id_buku));

        $response->assertOk();
        $response->assertSee('Rekomendasi Aktif');
        $response->assertDontSee('Rekomendasi Nonaktif');
    }
}
