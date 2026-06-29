<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\AturanPeminjaman;
use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\DetailPengembalian;
use App\Models\EksemplarBuku;
use App\Models\KategoriBuku;
use App\Models\Keterlambatan;
use App\Models\Notifikasi;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Petugas;
use App\Models\Role;
use App\Models\SanksiAnggota;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PetugasPengembalianTest extends TestCase
{
    use RefreshDatabase;

    public function test_petugas_dapat_melihat_daftar_peminjaman_aktif(): void
    {
        $petugas = $this->createPetugasUser();
        $anggota = $this->createAnggotaUser('Ahmad Hidayat');

        $kategori = KategoriBuku::factory()->create();
        $buku = Buku::factory()->for($kategori, 'kategori')->create(['judul' => 'Sistem Informasi Manajemen']);

        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-202310-045',
            'id_anggota' => $anggota->anggota->id_anggota,
            'status_peminjaman' => 'aktif',
            'tanggal_pengajuan' => now(),
            'tanggal_diambil' => now()->subDays(2),
            'tanggal_jatuh_tempo' => now()->addDays(5),
        ]);

        DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_buku' => $buku->id_buku,
            'jumlah' => 1,
            'status_detail' => 'disetujui',
        ]);

        $response = $this->actingAs($petugas)
            ->get(route('petugas.pengembalian.index'));

        $response->assertOk()
            ->assertSee('PMJ-202310-045')
            ->assertSee('Ahmad Hidayat')
            ->assertSee('Sistem Informasi Manajemen');
    }

    public function test_petugas_dapat_melihat_detail_peminjaman_aktif(): void
    {
        $petugas = $this->createPetugasUser();
        $anggota = $this->createAnggotaUser('Budi Santoso');

        $kategori = KategoriBuku::factory()->create();
        $buku = Buku::factory()->for($kategori, 'kategori')->create(['judul' => 'Algoritma Pemrograman']);

        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-202310-089',
            'id_anggota' => $anggota->anggota->id_anggota,
            'status_peminjaman' => 'aktif',
            'tanggal_pengajuan' => now(),
            'tanggal_diambil' => now()->subDays(2),
            'tanggal_jatuh_tempo' => now()->addDays(5),
        ]);

        DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_buku' => $buku->id_buku,
            'jumlah' => 1,
            'status_detail' => 'disetujui',
        ]);

        $response = $this->actingAs($petugas)
            ->get(route('petugas.pengembalian.show', $peminjaman->id_peminjaman));

        $response->assertOk()
            ->assertSee('PMJ-202310-089')
            ->assertSee('Budi Santoso')
            ->assertSee('Algoritma Pemrograman')
            ->assertSee('Proses Pengembalian');
    }

    public function test_petugas_dapat_melihat_form_proses_pengembalian(): void
    {
        $petugas = $this->createPetugasUser();
        $anggota = $this->createAnggotaUser('Budi Santoso');

        $kategori = KategoriBuku::factory()->create();
        $buku = Buku::factory()->for($kategori, 'kategori')->create(['judul' => 'Algoritma Pemrograman']);

        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-202310-089',
            'id_anggota' => $anggota->anggota->id_anggota,
            'status_peminjaman' => 'aktif',
            'tanggal_pengajuan' => now(),
            'tanggal_diambil' => now()->subDays(2),
            'tanggal_jatuh_tempo' => now()->addDays(5),
        ]);

        DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_buku' => $buku->id_buku,
            'jumlah' => 1,
            'status_detail' => 'disetujui',
        ]);

        $response = $this->actingAs($petugas)
            ->get(route('petugas.pengembalian.proses-form', $peminjaman->id_peminjaman));

        $response->assertOk()
            ->assertSee('Algoritma Pemrograman')
            ->assertSee('Keadaan Buku');
    }

    public function test_petugas_dapat_memproses_sanksi_dan_melihat_preview(): void
    {
        Storage::fake('public');

        $petugas = $this->createPetugasUser();
        $anggota = $this->createAnggotaUser('Budi Santoso');

        $kategori = KategoriBuku::factory()->create();
        $buku = Buku::factory()->for($kategori, 'kategori')->create(['judul' => 'Algoritma Pemrograman']);

        $aturan = AturanPeminjaman::create([
            'nama_aturan' => 'Aturan Mahasiswa',
            'lama_pinjam_hari' => 10,
            'masa_suspend_per_hari_terlambat' => 2, // 2 days suspend per late day!
            'status_aktif' => true,
        ]);

        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-202310-089',
            'id_anggota' => $anggota->anggota->id_anggota,
            'id_aturan' => $aturan->id_aturan_peminjaman,
            'status_peminjaman' => 'aktif',
            'tanggal_pengajuan' => now(),
            'tanggal_diambil' => now()->subDays(15),
            'tanggal_jatuh_tempo' => now()->subDays(5), // Overdue by 5 days
        ]);

        DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_buku' => $buku->id_buku,
            'jumlah' => 1,
            'status_detail' => 'disetujui',
        ]);

        $file = UploadedFile::fake()->create('buku_rusak.jpg', 100);

        $response = $this->actingAs($petugas)
            ->post(route('petugas.pengembalian.proses-sanksi', $peminjaman->id_peminjaman), [
                'tanggal_pengembalian' => now()->toDateString(),
                'keadaan_buku' => 'Rusak Ringan',
                'catatan_kondisi' => 'Cover sobek sedikit',
                'foto_kondisi' => $file,
            ]);

        $response->assertOk()
            ->assertSee('Detail Pengembalian & Sanksi')
            ->assertSee('Rusak Ringan')
            ->assertSee('10 Hari') // Late penalty days multiplied by 2!
            ->assertSee('Konfirmasi Pengembalian');
    }

    public function test_petugas_dapat_memproses_sanksi_dengan_aturan_default(): void
    {
        Storage::fake('public');

        $petugas = $this->createPetugasUser();
        $anggota = $this->createAnggotaUser('Budi Santoso');

        $kategori = KategoriBuku::factory()->create();
        $buku = Buku::factory()->for($kategori, 'kategori')->create(['judul' => 'Algoritma Pemrograman']);

        // Create default active rule with multiplier = 3
        AturanPeminjaman::create([
            'nama_aturan' => 'Aturan Default',
            'lama_pinjam_hari' => 14,
            'masa_suspend_per_hari_terlambat' => 3,
            'status_aktif' => true,
        ]);

        // id_aturan is left null
        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-202310-091',
            'id_anggota' => $anggota->anggota->id_anggota,
            'id_aturan' => null,
            'status_peminjaman' => 'aktif',
            'tanggal_pengajuan' => now(),
            'tanggal_diambil' => now()->subDays(15),
            'tanggal_jatuh_tempo' => now()->subDays(5), // Overdue by 5 days
        ]);

        DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_buku' => $buku->id_buku,
            'jumlah' => 1,
            'status_detail' => 'disetujui',
        ]);

        $file = UploadedFile::fake()->create('buku_rusak.jpg', 100);

        $response = $this->actingAs($petugas)
            ->post(route('petugas.pengembalian.proses-sanksi', $peminjaman->id_peminjaman), [
                'tanggal_pengembalian' => now()->toDateString(),
                'keadaan_buku' => 'Rusak Ringan',
                'catatan_kondisi' => 'Cover sobek sedikit',
                'foto_kondisi' => $file,
            ]);

        $response->assertOk()
            ->assertSee('Detail Pengembalian & Sanksi')
            ->assertSee('Rusak Ringan')
            ->assertSee('15 Hari') // 5 days late * 3 multiplier = 15
            ->assertSee('Konfirmasi Pengembalian');
    }

    public function test_petugas_dapat_menyimpan_transaksi_pengembalian_dan_sanksi(): void
    {
        Storage::fake('public');

        $petugas = $this->createPetugasUser();
        $anggota = $this->createAnggotaUser('Budi Santoso');

        $kategori = KategoriBuku::factory()->create();
        $buku = Buku::factory()->for($kategori, 'kategori')->create(['judul' => 'Algoritma Pemrograman']);
        
        $eksemplar = EksemplarBuku::factory()->create([
            'id_buku' => $buku->id_buku,
            'status_eksemplar' => 'dipinjam',
            'kondisi_eksemplar' => 'Baik',
        ]);

        $aturan = AturanPeminjaman::create([
            'nama_aturan' => 'Aturan Mahasiswa',
            'lama_pinjam_hari' => 10,
            'masa_suspend_per_hari_terlambat' => 2, // 2 days suspend per late day!
            'status_aktif' => true,
        ]);

        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-202310-089',
            'id_anggota' => $anggota->anggota->id_anggota,
            'id_aturan' => $aturan->id_aturan_peminjaman,
            'status_peminjaman' => 'aktif',
            'tanggal_pengajuan' => now(),
            'tanggal_diambil' => now()->subDays(15),
            'tanggal_jatuh_tempo' => now()->subDays(5),
        ]);

        DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_buku' => $buku->id_buku,
            'jumlah' => 1,
            'status_detail' => 'disetujui',
        ]);

        // Mock temporary photo path
        $tempPath = 'pengembalian_temp/buku_rusak_temp.jpg';
        Storage::disk('public')->put($tempPath, 'fake photo content');

        $response = $this->actingAs($petugas)
            ->post(route('petugas.pengembalian.store', $peminjaman->id_peminjaman), [
                'tanggal_pengembalian' => now()->toDateString(),
                'hari_terlambat' => 5,
                'keadaan_buku' => 'Rusak Ringan',
                'catatan_kondisi' => 'Cover sobek sedikit',
                'photo_path' => $tempPath,
            ]);

        $response->assertRedirect(route('petugas.pengembalian.riwayat'));

        $peminjaman->refresh();
        $this->assertEquals('selesai', $peminjaman->status_peminjaman);

        // Verify permanent photo path
        $finalPhotoPath = 'pengembalian/buku_rusak_temp.jpg';
        Storage::disk('public')->assertExists($finalPhotoPath);

        // Verify database records
        $this->assertDatabaseHas('pengembalian', [
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'total_hari_terlambat' => 5,
            'status_pengembalian' => 'Terlambat',
        ]);

        $this->assertDatabaseHas('detail_pengembalian', [
            'kondisi_buku' => 'Rusak Ringan',
            'catatan' => 'Cover sobek sedikit',
        ]);

        $this->assertDatabaseHas('keterlambatan', [
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'hari_terlambat' => 5,
        ]);

        $this->assertDatabaseHas('sanksi_anggota', [
            'id_anggota' => $anggota->anggota->id_anggota,
            'jenis_sanksi' => 'Skorsing 10 Hari', // 5 days late * 2 multiplier
            'status_sanksi' => 'aktif',
        ]);

        $eksemplar->refresh();
        $this->assertEquals('rusak', $eksemplar->status_eksemplar);
        $this->assertEquals('Rusak', $eksemplar->kondisi_eksemplar);

        // Verify notification
        $this->assertDatabaseHas('notifikasi', [
            'id_user' => $anggota->id_user,
            'judul' => 'Pengembalian Buku Berhasil',
        ]);
    }

    public function test_petugas_dapat_melihat_riwayat_pengembalian(): void
    {
        $petugas = $this->createPetugasUser();
        $anggota = $this->createAnggotaUser('Ahmad Hidayat');

        $kategori = KategoriBuku::factory()->create();
        $buku = Buku::factory()->for($kategori, 'kategori')->create(['judul' => 'Sistem Informasi Manajemen']);

        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-202310-045',
            'id_anggota' => $anggota->anggota->id_anggota,
            'status_peminjaman' => 'selesai',
            'tanggal_pengajuan' => now(),
            'tanggal_diambil' => now()->subDays(2),
            'tanggal_jatuh_tempo' => now()->addDays(5),
        ]);

        $pengembalian = Pengembalian::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_petugas' => $petugas->petugas->id_petugas,
            'tanggal_pengembalian' => now(),
            'total_hari_terlambat' => 0,
            'status_pengembalian' => 'Tepat Waktu',
        ]);

        $response = $this->actingAs($petugas)
            ->get(route('petugas.pengembalian.riwayat'));

        $response->assertOk()
            ->assertSee('PMJ-202310-045')
            ->assertSee('Ahmad Hidayat')
            ->assertSee('Tepat Waktu');
    }

    public function test_petugas_dapat_mengekspor_riwayat_pengembalian_ke_csv(): void
    {
        $petugas = $this->createPetugasUser();
        $anggota = $this->createAnggotaUser('Ahmad Hidayat');

        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-202310-045',
            'id_anggota' => $anggota->anggota->id_anggota,
            'status_peminjaman' => 'selesai',
            'tanggal_pengajuan' => now(),
            'tanggal_diambil' => now()->subDays(2),
            'tanggal_jatuh_tempo' => now()->addDays(5),
        ]);

        Pengembalian::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_petugas' => $petugas->petugas->id_petugas,
            'tanggal_pengembalian' => now(),
            'total_hari_terlambat' => 0,
            'status_pengembalian' => 'Tepat Waktu',
        ]);

        $response = $this->actingAs($petugas)
            ->get(route('petugas.pengembalian.export-csv'));

        $response->assertOk()
            ->assertDownload('laporan_pengembalian_buku.csv');

        $content = $response->streamedContent();
        $this->assertStringContainsString('PMJ-202310-045', $content);
        $this->assertStringContainsString('Ahmad Hidayat', $content);
    }

    public function test_petugas_dapat_melihat_daftar_peminjaman_yang_disetujui(): void
    {
        $petugas = $this->createPetugasUser();
        $anggota = $this->createAnggotaUser('Ahmad Hidayat');

        $kategori = KategoriBuku::factory()->create();
        $buku = Buku::factory()->for($kategori, 'kategori')->create(['judul' => 'Sistem Informasi Manajemen']);

        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-202310-090',
            'id_anggota' => $anggota->anggota->id_anggota,
            'status_peminjaman' => 'disetujui',
            'tanggal_pengajuan' => now(),
            'tanggal_diambil' => now()->subDays(2),
            'tanggal_jatuh_tempo' => now()->addDays(5),
        ]);

        DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_buku' => $buku->id_buku,
            'jumlah' => 1,
            'status_detail' => 'disetujui',
        ]);

        $response = $this->actingAs($petugas)
            ->get(route('petugas.pengembalian.index'));

        $response->assertOk()
            ->assertSee('PMJ-202310-090')
            ->assertSee('Ahmad Hidayat')
            ->assertSee('Sistem Informasi Manajemen');
    }

    public function test_anggota_tidak_dapat_mengakses_fitur_pengembalian_petugas(): void
    {
        $anggota = $this->createAnggotaUser('Hafizh');

        $this->actingAs($anggota)
            ->get(route('petugas.pengembalian.index'))
            ->assertForbidden();
    }

    private function createPetugasUser(): User
    {
        $role = Role::updateOrCreate(['nama_role' => 'Petugas']);
        $user = User::factory()->create(['id_role' => $role->id_role]);
        Petugas::factory()->create(['id_user' => $user->id_user]);

        return $user->load('petugas');
    }

    private function createAnggotaUser(string $nama): User
    {
        $role = Role::updateOrCreate(['nama_role' => 'Anggota']);
        $user = User::factory()->create(['id_role' => $role->id_role]);
        Anggota::factory()->create([
            'id_user' => $user->id_user,
            'nama_lengkap' => $nama,
            'no_anggota' => 'ANG-' . rand(1000, 9999),
        ]);

        return $user->load('anggota');
    }
}
