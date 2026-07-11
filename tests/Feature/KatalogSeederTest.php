<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\EksemplarBuku;
use App\Models\KategoriBuku;
use Database\Seeders\KatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KatalogSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_katalog_seeder_can_seed_data_when_empty(): void
    {
        $this->assertEquals(0, KategoriBuku::count());
        $this->assertEquals(0, Buku::count());
        $this->assertEquals(0, EksemplarBuku::count());

        $this->seed(KatalogSeeder::class);

        $this->assertGreaterThan(0, KategoriBuku::count());
        $this->assertGreaterThan(0, Buku::count());
        $this->assertGreaterThan(0, EksemplarBuku::count());

        // Assert specific books are seeded
        $this->assertDatabaseHas('buku', [
            'judul' => 'Tenggelamnya Kapal Van der Wijck',
            'penulis' => 'Buya Hamka',
        ]);

        $this->assertDatabaseHas('kategori_buku', [
            'nama_kategori' => 'Sejarah Minangkabau',
        ]);
    }

    public function test_katalog_seeder_does_not_duplicate_data_if_already_seeded(): void
    {
        $this->seed(KatalogSeeder::class);

        $categoriesCount = KategoriBuku::count();
        $booksCount = Buku::count();
        $copiesCount = EksemplarBuku::count();

        // Run the seeder again
        $this->seed(KatalogSeeder::class);

        $this->assertEquals($categoriesCount, KategoriBuku::count());
        $this->assertEquals($booksCount, Buku::count());
        $this->assertEquals($copiesCount, EksemplarBuku::count());
    }

    public function test_katalog_seeder_tidak_membuat_buku_terlihat_dipinjam_tanpa_transaksi(): void
    {
        $this->seed(KatalogSeeder::class);

        $this->assertSame(0, EksemplarBuku::query()
            ->whereIn('status_eksemplar', EksemplarBuku::BORROWED_COPY_STATUSES)
            ->count());

        $this->get(route('katalog'))
            ->assertOk()
            ->assertDontSee('DIPINJAM SEMUA');
    }
}
