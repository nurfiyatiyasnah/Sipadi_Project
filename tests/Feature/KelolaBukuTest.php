<?php

namespace Tests\Feature;

use App\Livewire\BukuCreate;
use App\Livewire\BukuDetail;
use App\Livewire\BukuEdit;
use App\Livewire\KoleksiBukuIndex;
use App\Livewire\TambahStokBuku;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\EksemplarBuku;
use App\Models\KategoriBuku;
use App\Models\Peminjaman;
use App\Models\Petugas;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
            ->set('lokasi_rak', 'Lantai 2 - Rak A-12')
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
        $this->assertEquals(3, $book->eksemplar()->where('lokasi_rak', 'Lantai 2 - Rak A-12')->count());
        $this->assertFalse($book->eksemplar()->where('lokasi_rak', 'Rak A-1')->exists());
    }

    public function test_tambah_buku_tanpa_lokasi_rak_menyimpan_lokasi_null(): void
    {
        Storage::fake('public');
        $petugas = $this->createPetugasUser();
        $kategori = KategoriBuku::factory()->create();
        $cover = UploadedFile::fake()->create('cover.jpg', 100, 'image/jpeg');

        $this->actingAs($petugas);

        Livewire::test(BukuCreate::class)
            ->set('judul', 'Buku Tanpa Lokasi Rak')
            ->set('isbn', '978-602-7777-77-7')
            ->set('penulis', 'Penulis Test')
            ->set('penerbit', 'Penerbit Test')
            ->set('id_kategori', $kategori->id_kategori)
            ->set('tahun_terbit', '2023')
            ->set('cover_file', $cover)
            ->set('stok_awal', 1)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('petugas.koleksi'));

        $book = Buku::where('isbn', '978-602-7777-77-7')->firstOrFail();
        $copy = $book->eksemplar()->firstOrFail();

        $this->assertNull($copy->lokasi_rak);
        $this->assertFalse($book->eksemplar()->where('lokasi_rak', 'Rak A-1')->exists());
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
            ->set('lokasi_rak', 'Lantai 1 - Rak B-02')
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
        $this->assertEquals(3, $book->eksemplar()->whereIn('kode_eksemplar', [$expectedCode3, $expectedCode4, $expectedCode5])->where('lokasi_rak', 'Lantai 1 - Rak B-02')->count());
        $this->assertFalse($book->eksemplar()->whereIn('kode_eksemplar', [$expectedCode3, $expectedCode4, $expectedCode5])->where('lokasi_rak', 'Rak A-1')->exists());

        // Validate uniqueness
        $uniqueCodesCount = $book->eksemplar()->distinct()->count('kode_eksemplar');
        $this->assertEquals(5, $uniqueCodesCount);
    }

    public function test_tambah_stok_tanpa_lokasi_rak_menyimpan_lokasi_null(): void
    {
        $petugas = $this->createPetugasUser();
        $kategori = KategoriBuku::factory()->create();
        $book = Buku::factory()->create(['id_kategori' => $kategori->id_kategori]);

        $this->actingAs($petugas);

        Livewire::test(TambahStokBuku::class, ['id' => $book->id_buku])
            ->set('jumlah_stok_tambahan', 1)
            ->set('sumber_perolehan', 'Pembelian')
            ->set('tanggal_penerimaan', '2026-06-29')
            ->call('save')
            ->assertHasNoErrors();

        $copy = $book->eksemplar()->firstOrFail();

        $this->assertNull($copy->lokasi_rak);
        $this->assertFalse($book->eksemplar()->where('lokasi_rak', 'Rak A-1')->exists());
    }

    public function test_detail_buku_nonaktif_menampilkan_status_nonaktif_meski_eksemplar_tersedia(): void
    {
        $petugas = $this->createPetugasUser();
        $kategori = KategoriBuku::factory()->create();
        $book = Buku::factory()->create([
            'id_kategori' => $kategori->id_kategori,
            'status_katalog' => 'nonaktif',
        ]);
        EksemplarBuku::create([
            'id_buku' => $book->id_buku,
            'kode_eksemplar' => 'BK-4444-001',
            'status_eksemplar' => 'tersedia',
        ]);

        $this->actingAs($petugas);

        Livewire::test(BukuDetail::class, ['id' => $book->id_buku])
            ->assertSee('Nonaktif')
            ->assertSee('bg-slate-100 text-slate-600 border-slate-300', false);
    }

    public function test_buku_dengan_semua_eksemplar_bermasalah_tidak_ditandai_dipinjam_semua(): void
    {
        $petugas = $this->createPetugasUser();
        $kategori = KategoriBuku::factory()->create();
        $book = Buku::factory()->create([
            'id_kategori' => $kategori->id_kategori,
            'judul' => 'Buku Semua Eksemplar Bermasalah',
            'status_katalog' => 'aktif',
        ]);

        foreach (['rusak', 'hilang', 'nonaktif'] as $index => $status) {
            EksemplarBuku::create([
                'id_buku' => $book->id_buku,
                'kode_eksemplar' => sprintf('BK-3333-%03d', $index + 1),
                'status_eksemplar' => $status,
            ]);
        }

        $this->actingAs($petugas);

        Livewire::test(KoleksiBukuIndex::class)
            ->assertSee('Buku Semua Eksemplar Bermasalah')
            ->assertSee('Tidak Tersedia')
            ->assertDontSee('Dipinjam Semua');

        Livewire::test(BukuDetail::class, ['id' => $book->id_buku])
            ->assertSee('Tidak Tersedia')
            ->assertDontSee('Dipinjam Semua');
    }

    public function test_status_eksemplar_dinormalisasi_menjadi_lowercase(): void
    {
        $kategori = KategoriBuku::factory()->create();
        $book = Buku::factory()->create(['id_kategori' => $kategori->id_kategori]);

        $copy = EksemplarBuku::create([
            'id_buku' => $book->id_buku,
            'kode_eksemplar' => 'BK-3333-100',
            'status_eksemplar' => 'Tersedia',
        ]);

        $this->assertSame(EksemplarBuku::STATUS_TERSEDIA, $copy->refresh()->status_eksemplar);
    }

    public function test_detail_buku_tidak_menampilkan_metadata_hardcoded_yang_tidak_ada_di_tabel_buku(): void
    {
        $petugas = $this->createPetugasUser();
        $kategori = KategoriBuku::factory()->create(['nama_kategori' => 'Kategori Test']);
        $book = Buku::factory()->create([
            'id_kategori' => $kategori->id_kategori,
            'judul' => 'Buku Tanpa Metadata Palsu',
            'penulis' => 'Penulis Test',
            'penerbit' => 'Penerbit Test',
            'tahun_terbit' => 2024,
            'isbn' => '978-602-0000-00-1',
            'deskripsi' => 'Deskripsi netral untuk pengujian detail buku.',
        ]);

        $this->actingAs($petugas);

        Livewire::test(BukuDetail::class, ['id' => $book->id_buku])
            ->assertDontSee('Bahasa')
            ->assertDontSee('Indonesia')
            ->assertDontSee('Edisi / Keterangan')
            ->assertDontSee('Edisi Standar');
    }

    public function test_detail_buku_menampilkan_lokasi_rak_terisi_dari_semua_eksemplar(): void
    {
        $petugas = $this->createPetugasUser();
        $kategori = KategoriBuku::factory()->create();
        $book = Buku::factory()->create(['id_kategori' => $kategori->id_kategori]);
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

        $this->actingAs($petugas);

        Livewire::test(BukuDetail::class, ['id' => $book->id_buku])
            ->assertSee('Lokasi Rak Eksemplar')
            ->assertSee('Lantai 1 - Rak B-02')
            ->assertSee('Lantai 2 - Rak C-01')
            ->assertDontSee('Lokasi Rak Utama')
            ->assertDontSee('Belum diatur');
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
            ->withKetersediaanCounts()
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

    public function test_admin_tidak_dapat_menghapus_eksemplar_dipesan(): void
    {
        $petugas = $this->createPetugasUser();
        $kategori = KategoriBuku::factory()->create();
        $book = Buku::factory()->create(['id_kategori' => $kategori->id_kategori]);
        $copy = EksemplarBuku::create([
            'id_buku' => $book->id_buku,
            'kode_eksemplar' => 'BK-9999-003',
            'status_eksemplar' => 'dipesan',
        ]);

        $this->actingAs($petugas);

        Livewire::test(BukuDetail::class, ['id' => $book->id_buku])
            ->call('deleteCopy', $copy->id_eksemplar_buku)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('eksemplar_buku', [
            'id_eksemplar_buku' => $copy->id_eksemplar_buku,
        ]);
    }

    public function test_admin_tidak_dapat_menghapus_eksemplar_dengan_peminjaman_aktif_meski_status_tersedia(): void
    {
        $petugas = $this->createPetugasUser();
        $anggota = Anggota::factory()->create();
        $kategori = KategoriBuku::factory()->create();
        $book = Buku::factory()->create(['id_kategori' => $kategori->id_kategori]);
        $copy = EksemplarBuku::create([
            'id_buku' => $book->id_buku,
            'kode_eksemplar' => 'BK-9999-004',
            'status_eksemplar' => 'tersedia',
        ]);
        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-202607-999',
            'id_anggota' => $anggota->id_anggota,
            'status_peminjaman' => 'siap_diambil',
        ]);
        DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_buku' => $book->id_buku,
            'id_eksemplar_buku' => $copy->id_eksemplar_buku,
            'jumlah' => 1,
            'status_detail' => 'dipesan',
        ]);

        $this->actingAs($petugas);

        Livewire::test(BukuDetail::class, ['id' => $book->id_buku])
            ->call('deleteCopy', $copy->id_eksemplar_buku)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('eksemplar_buku', [
            'id_eksemplar_buku' => $copy->id_eksemplar_buku,
        ]);
    }

    public function test_admin_tidak_dapat_menghapus_eksemplar_dari_buku_lain_melalui_detail_buku_ini(): void
    {
        $petugas = $this->createPetugasUser();
        $kategori = KategoriBuku::factory()->create();
        $currentBook = Buku::factory()->create(['id_kategori' => $kategori->id_kategori]);
        $otherBook = Buku::factory()->create(['id_kategori' => $kategori->id_kategori]);
        $otherCopy = EksemplarBuku::create([
            'id_buku' => $otherBook->id_buku,
            'kode_eksemplar' => 'BK-9999-005',
            'status_eksemplar' => 'tersedia',
        ]);

        $this->actingAs($petugas);

        try {
            Livewire::test(BukuDetail::class, ['id' => $currentBook->id_buku])
                ->call('deleteCopy', $otherCopy->id_eksemplar_buku);

            $this->fail('Eksemplar dari buku lain tidak boleh bisa dihapus melalui detail buku ini.');
        } catch (ModelNotFoundException $exception) {
            $this->assertSame(EksemplarBuku::class, $exception->getModel());
        }

        $this->assertDatabaseHas('eksemplar_buku', [
            'id_eksemplar_buku' => $otherCopy->id_eksemplar_buku,
        ]);
    }

    public function test_admin_dapat_mengubah_eksemplar_dipinjam_tanpa_peminjaman_aktif_menjadi_tersedia(): void
    {
        $petugas = $this->createPetugasUser();
        $kategori = KategoriBuku::factory()->create();
        $book = Buku::factory()->create(['id_kategori' => $kategori->id_kategori]);
        $copy = EksemplarBuku::create([
            'id_buku' => $book->id_buku,
            'kode_eksemplar' => 'BK-8888-001',
            'status_eksemplar' => 'dipinjam',
        ]);

        $this->actingAs($petugas);

        Livewire::test(BukuDetail::class, ['id' => $book->id_buku])
            ->call('updateCopyStatus', $copy->id_eksemplar_buku, 'tersedia')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('eksemplar_buku', [
            'id_eksemplar_buku' => $copy->id_eksemplar_buku,
            'status_eksemplar' => 'tersedia',
        ]);
    }

    public function test_admin_tidak_dapat_mengubah_eksemplar_yang_masih_memiliki_peminjaman_aktif(): void
    {
        $petugas = $this->createPetugasUser();
        $anggota = Anggota::factory()->create();
        $kategori = KategoriBuku::factory()->create();
        $book = Buku::factory()->create(['id_kategori' => $kategori->id_kategori]);
        $copy = EksemplarBuku::create([
            'id_buku' => $book->id_buku,
            'kode_eksemplar' => 'BK-7777-001',
            'status_eksemplar' => 'dipinjam',
        ]);
        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-202607-777',
            'id_anggota' => $anggota->id_anggota,
            'status_peminjaman' => 'aktif',
        ]);
        DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_buku' => $book->id_buku,
            'id_eksemplar_buku' => $copy->id_eksemplar_buku,
            'jumlah' => 1,
            'status_detail' => 'dipinjam',
        ]);

        $this->actingAs($petugas);

        Livewire::test(BukuDetail::class, ['id' => $book->id_buku])
            ->call('updateCopyStatus', $copy->id_eksemplar_buku, 'tersedia')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('eksemplar_buku', [
            'id_eksemplar_buku' => $copy->id_eksemplar_buku,
            'status_eksemplar' => 'dipinjam',
        ]);
    }

    public function test_admin_tidak_dapat_menonaktifkan_buku_dengan_eksemplar_dipesan(): void
    {
        $petugas = $this->createPetugasUser();
        $kategori = KategoriBuku::factory()->create();
        $book = Buku::factory()->create([
            'id_kategori' => $kategori->id_kategori,
            'status_katalog' => 'aktif',
        ]);

        EksemplarBuku::create([
            'id_buku' => $book->id_buku,
            'kode_eksemplar' => 'BK-6666-001',
            'status_eksemplar' => 'dipesan',
        ]);

        $this->actingAs($petugas);

        Livewire::test(KoleksiBukuIndex::class)
            ->call('deleteBook', $book->id_buku)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('buku', [
            'id_buku' => $book->id_buku,
            'status_katalog' => 'aktif',
        ]);
    }

    public function test_admin_tidak_dapat_menonaktifkan_buku_dengan_peminjaman_siap_diambil(): void
    {
        $petugas = $this->createPetugasUser();
        $anggota = Anggota::factory()->create();
        $kategori = KategoriBuku::factory()->create();
        $book = Buku::factory()->create([
            'id_kategori' => $kategori->id_kategori,
            'status_katalog' => 'aktif',
        ]);
        $copy = EksemplarBuku::create([
            'id_buku' => $book->id_buku,
            'kode_eksemplar' => 'BK-5555-001',
            'status_eksemplar' => 'tersedia',
        ]);
        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-202607-555',
            'id_anggota' => $anggota->id_anggota,
            'status_peminjaman' => 'siap_diambil',
        ]);
        DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_buku' => $book->id_buku,
            'id_eksemplar_buku' => $copy->id_eksemplar_buku,
            'jumlah' => 1,
            'status_detail' => 'dipesan',
        ]);

        $this->actingAs($petugas);

        Livewire::test(KoleksiBukuIndex::class)
            ->call('deleteBook', $book->id_buku)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('buku', [
            'id_buku' => $book->id_buku,
            'status_katalog' => 'aktif',
        ]);
    }

    public function test_admin_tidak_dapat_menonaktifkan_buku_dengan_pengajuan_diajukan(): void
    {
        $petugas = $this->createPetugasUser();
        $anggota = Anggota::factory()->create();
        $kategori = KategoriBuku::factory()->create();
        $book = Buku::factory()->create([
            'id_kategori' => $kategori->id_kategori,
            'status_katalog' => 'aktif',
        ]);
        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-202607-444',
            'id_anggota' => $anggota->id_anggota,
            'status_peminjaman' => 'diajukan',
        ]);
        DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_buku' => $book->id_buku,
            'jumlah' => 1,
            'status_detail' => 'diajukan',
        ]);

        $this->actingAs($petugas);

        Livewire::test(KoleksiBukuIndex::class)
            ->call('deleteBook', $book->id_buku)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('buku', [
            'id_buku' => $book->id_buku,
            'status_katalog' => 'aktif',
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
