<?php

namespace Tests\Feature;

use App\Livewire\BukuCreate;
use App\Livewire\BukuDetail;
use App\Livewire\BukuEdit;
use App\Livewire\KoleksiBukuIndex;
use App\Livewire\TambahStokBuku;
use App\Models\Buku;
use App\Models\EksemplarBuku;
use App\Models\KategoriBuku;
use App\Models\Petugas;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class KelolaBukuTest extends TestCase
{
    use RefreshDatabase;

    private function createPetugasUser(): User
    {
        $role = Role::firstOrCreate(['nama_role' => 'Petugas']);
        $user = User::factory()->create(['id_role' => $role->id_role]);
        Petugas::factory()->create(['id_user' => $user->id_user]);

        return $user;
    }

    private function createAnggotaUser(): User
    {
        $role = Role::firstOrCreate(['nama_role' => 'Anggota']);

        return User::factory()->create(['id_role' => $role->id_role]);
    }

    /**
     * Test admin can view book list.
     */
    public function test_admin_dapat_melihat_daftar_buku(): void
    {
        $petugas = $this->createPetugasUser();
        $kategori = KategoriBuku::factory()->create(['nama_kategori' => 'Fiksi']);
        $buku = Buku::factory()->create([
            'id_kategori' => $kategori->id_kategori,
            'judul' => 'Laskar Pelangi',
            'penulis' => 'Andrea Hirata',
        ]);
        EksemplarBuku::factory()->create([
            'id_buku' => $buku->id_buku,
            'status_eksemplar' => 'tersedia',
        ]);

        $response = $this->actingAs($petugas)->get(route('petugas.koleksi'));

        $response->assertOk();

        Livewire::test(KoleksiBukuIndex::class)
            ->assertSee('Laskar Pelangi')
            ->assertSee('Andrea Hirata');
    }

    /**
     * Test admin can create a book with cover upload and initial stock.
     */
    public function test_admin_dapat_membuat_buku(): void
    {
        Storage::fake('public');
        $petugas = $this->createPetugasUser();
        $kategori = KategoriBuku::factory()->create();
        $cover = UploadedFile::fake()->create('cover.jpg', 100, 'image/jpeg');

        $this->actingAs($petugas);

        Livewire::test(BukuCreate::class)
            ->set('judul', 'Buku Test Baru')
            ->set('isbn', '978-602-1234-56-7')
            ->set('penulis', 'Penulis Test')
            ->set('penerbit', 'Penerbit Test')
            ->set('id_kategori', $kategori->id_kategori)
            ->set('tahun_terbit', '2023')
            ->set('deskripsi', 'Deskripsi buku baru')
            ->set('cover_file', $cover)
            ->set('stok_awal', 3)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('petugas.koleksi'));

        $book = Buku::where('isbn', '978-602-1234-56-7')->first();
        $this->assertNotNull($book);
        $this->assertEquals('Buku Test Baru', $book->judul);
        $this->assertNotNull($book->gambar_cover);

        // Check if initial copies are created automatically
        $this->assertEquals(3, $book->eksemplar()->count());

        // Assert sequence codes are generated correctly BK-{book_id padded 4}-{seq padded 3}
        $expectedCode1 = sprintf('BK-%04d-001', $book->id_buku);
        $expectedCode2 = sprintf('BK-%04d-002', $book->id_buku);
        $expectedCode3 = sprintf('BK-%04d-003', $book->id_buku);

        $this->assertTrue($book->eksemplar()->where('kode_eksemplar', $expectedCode1)->exists());
        $this->assertTrue($book->eksemplar()->where('kode_eksemplar', $expectedCode2)->exists());
        $this->assertTrue($book->eksemplar()->where('kode_eksemplar', $expectedCode3)->exists());
    }

    /**
     * Test admin can edit book.
     */
    public function test_admin_dapat_mengedit_buku(): void
    {
        Storage::fake('public');
        $petugas = $this->createPetugasUser();
        $kategori = KategoriBuku::factory()->create();
        $book = Buku::factory()->create([
            'id_kategori' => $kategori->id_kategori,
            'judul' => 'Judul Awal',
            'isbn' => '978-602-1111-11-1',
        ]);
        $newCover = UploadedFile::fake()->create('new_cover.jpg', 100, 'image/jpeg');

        $this->actingAs($petugas);

        Livewire::test(BukuEdit::class, ['id' => $book->id_buku])
            ->set('judul', 'Judul Terupdate')
            ->set('cover_file', $newCover)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('petugas.koleksi'));

        $book->refresh();
        $this->assertEquals('Judul Terupdate', $book->judul);
        $this->assertNotNull($book->gambar_cover);
    }

    /**
     * Test admin can add stock and system generates copies sequentially and uniquely.
     */
    public function test_admin_dapat_menambah_stok_dan_sistem_membuat_eksemplar_otomatis(): void
    {
        $petugas = $this->createPetugasUser();
        $kategori = KategoriBuku::factory()->create();
        $book = Buku::factory()->create(['id_kategori' => $kategori->id_kategori]);

        // Pre-create 2 copies
        EksemplarBuku::create([
            'id_buku' => $book->id_buku,
            'kode_eksemplar' => sprintf('BK-%04d-001', $book->id_buku),
            'status_eksemplar' => 'tersedia',
        ]);
        EksemplarBuku::create([
            'id_buku' => $book->id_buku,
            'kode_eksemplar' => sprintf('BK-%04d-002', $book->id_buku),
            'status_eksemplar' => 'dipinjam',
        ]);

        $this->actingAs($petugas);

        // Add 3 copies
        Livewire::test(TambahStokBuku::class, ['id' => $book->id_buku])
            ->set('jumlah_stok_tambahan', 3)
            ->set('sumber_perolehan', 'Pembelian Dinas')
            ->set('tanggal_penerimaan', '2026-06-29')
            ->set('catatan', 'Catatan penambahan')
            ->call('save')
            ->assertHasNoErrors();

        // Total copies should be 5
        $this->assertEquals(5, $book->eksemplar()->count());

        // Sequential numbers should continue from max current sequence (002 + 1 = 003)
        $expectedCode3 = sprintf('BK-%04d-003', $book->id_buku);
        $expectedCode4 = sprintf('BK-%04d-004', $book->id_buku);
        $expectedCode5 = sprintf('BK-%04d-005', $book->id_buku);

        $this->assertTrue($book->eksemplar()->where('kode_eksemplar', $expectedCode3)->exists());
        $this->assertTrue($book->eksemplar()->where('kode_eksemplar', $expectedCode4)->exists());
        $this->assertTrue($book->eksemplar()->where('kode_eksemplar', $expectedCode5)->exists());

        // Validate uniqueness
        $uniqueCodesCount = $book->eksemplar()->distinct()->count('kode_eksemplar');
        $this->assertEquals(5, $uniqueCodesCount);
    }

    /**
     * Test stock count is calculated from copies.
     */
    public function test_stok_buku_dihitung_dari_jumlah_eksemplar(): void
    {
        $petugas = $this->createPetugasUser();
        $kategori = KategoriBuku::factory()->create();
        $book = Buku::factory()->create(['id_kategori' => $kategori->id_kategori]);

        EksemplarBuku::factory()->create([
            'id_buku' => $book->id_buku,
            'status_eksemplar' => 'tersedia',
        ]);
        EksemplarBuku::factory()->create([
            'id_buku' => $book->id_buku,
            'status_eksemplar' => 'tersedia',
        ]);
        EksemplarBuku::factory()->create([
            'id_buku' => $book->id_buku,
            'status_eksemplar' => 'dipinjam',
        ]);

        $this->actingAs($petugas);

        $bookWithCounts = Buku::query()
            ->withCount('eksemplar')
            ->withCount([
                'eksemplar as eksemplar_tersedia_count' => fn ($q) => $q
                    ->whereIn('status_eksemplar', ['tersedia', 'Tersedia']),
            ])
            ->findOrFail($book->id_buku);

        $this->assertEquals(3, $bookWithCounts->eksemplar_count);
        $this->assertEquals(2, $bookWithCounts->eksemplar_tersedia_count);
    }

    /**
     * Test non-admin user cannot access admin book management routes.
     */
    public function test_user_non_admin_tidak_dapat_mengakses_fitur_admin(): void
    {
        $anggota = $this->createAnggotaUser();
        $kategori = KategoriBuku::factory()->create();
        $book = Buku::factory()->create(['id_kategori' => $kategori->id_kategori]);

        $this->actingAs($anggota);

        $this->get(route('petugas.koleksi'))->assertForbidden();
        $this->get(route('petugas.buku.create'))->assertForbidden();
        $this->get(route('petugas.buku.show', $book->id_buku))->assertForbidden();
        $this->get(route('petugas.buku.edit', $book->id_buku))->assertForbidden();
        $this->get(route('petugas.buku.tambah-stok', $book->id_buku))->assertForbidden();
    }

    /**
     * Test admin can delete a book copy (eksemplar).
     */
    public function test_admin_dapat_menghapus_eksemplar(): void
    {
        $petugas = $this->createPetugasUser();
        $kategori = KategoriBuku::factory()->create();
        $book = Buku::factory()->create(['id_kategori' => $kategori->id_kategori]);

        $copyTersedia = EksemplarBuku::create([
            'id_buku' => $book->id_buku,
            'kode_eksemplar' => 'BK-9999-001',
            'status_eksemplar' => 'tersedia',
        ]);

        $copyDipinjam = EksemplarBuku::create([
            'id_buku' => $book->id_buku,
            'kode_eksemplar' => 'BK-9999-002',
            'status_eksemplar' => 'dipinjam',
        ]);

        $this->actingAs($petugas);

        // Try deleting available copy
        Livewire::test(BukuDetail::class, ['id' => $book->id_buku])
            ->call('deleteCopy', $copyTersedia->id_eksemplar_buku)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('eksemplar_buku', [
            'id_eksemplar_buku' => $copyTersedia->id_eksemplar_buku,
        ]);

        // Try deleting borrowed copy (should fail validation and keep record)
        Livewire::test(BukuDetail::class, ['id' => $book->id_buku])
            ->call('deleteCopy', $copyDipinjam->id_eksemplar_buku)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('eksemplar_buku', [
            'id_eksemplar_buku' => $copyDipinjam->id_eksemplar_buku,
        ]);
    }

    /**
     * Test admin can delete book with stock mutations but no copies.
     */
    public function test_admin_dapat_menghapus_buku_yang_memiliki_mutasi_stok_tapi_tidak_ada_eksemplar(): void
    {
        $petugas = $this->createPetugasUser();
        $kategori = KategoriBuku::factory()->create();
        $book = Buku::factory()->create(['id_kategori' => $kategori->id_kategori]);

        // Add mutation
        $book->mutasiStok()->create([
            'id_petugas' => $petugas->petugas?->id_petugas,
            'jenis_mutasi' => 'tambah',
            'jumlah' => 1,
            'stok_total_sebelum' => 0,
            'stok_total_sesudah' => 1,
            'stok_tersedia_sebelum' => 0,
            'stok_tersedia_sesudah' => 1,
        ]);

        $this->actingAs($petugas);

        Livewire::test(KoleksiBukuIndex::class)
            ->call('deleteBook', $book->id_buku)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('buku', [
            'id_buku' => $book->id_buku,
        ]);
        $this->assertDatabaseMissing('mutasi_stok_buku', [
            'id_buku' => $book->id_buku,
        ]);
    }
}
