<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\AturanPeminjaman;
use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\EksemplarBuku;
use App\Models\KategoriBuku;
use App\Models\Peminjaman;
use App\Models\Petugas;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetugasPeminjamanTest extends TestCase
{
    use RefreshDatabase;

    public function test_petugas_dapat_melihat_daftar_pengajuan_peminjaman(): void
    {
        $petugas = $this->createPetugasUser();
        $anggota = $this->createAnggotaUser('Ahmad Hidayat');

        $kategori = KategoriBuku::factory()->create();
        $buku = Buku::factory()->for($kategori, 'kategori')->create(['judul' => 'Sistem Informasi Manajemen']);

        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-202310-045',
            'id_anggota' => $anggota->anggota->id_anggota,
            'status_peminjaman' => 'diajukan',
            'tanggal_pengajuan' => now(),
        ]);

        DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_buku' => $buku->id_buku,
            'jumlah' => 1,
            'status_detail' => 'diajukan',
        ]);

        $response = $this->actingAs($petugas)
            ->get(route('petugas.peminjaman.index'));

        $response->assertOk()
            ->assertSee('PMJ-202310-045')
            ->assertSee('Ahmad Hidayat')
            ->assertSee('Sistem Informasi Manajemen')
            ->assertSee(route('petugas.peminjaman.show', $peminjaman->id_peminjaman), false)
            ->assertSee('>Aksi<', false)
            ->assertSee('Lihat Detail')
            ->assertSee('Setujui & Atur Jadwal', false)
            ->assertSee('Tolak Pengajuan');
    }

    public function test_petugas_dapat_memfilter_riwayat_peminjaman_berdasarkan_anggota(): void
    {
        $petugas = $this->createPetugasUser();
        $anggotaPertama = $this->createAnggotaUser('Dina Pratiwi');
        $anggotaKedua = $this->createAnggotaUser('Raka Wijaya');

        $kategori = KategoriBuku::factory()->create();
        $bukuPertama = Buku::factory()->for($kategori, 'kategori')->create(['judul' => 'Basis Data Terapan']);
        $bukuKedua = Buku::factory()->for($kategori, 'kategori')->create(['judul' => 'Jaringan Komputer']);

        $peminjamanPertama = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-202607-101',
            'id_anggota' => $anggotaPertama->anggota->id_anggota,
            'status_peminjaman' => 'aktif',
            'tanggal_pengajuan' => now(),
        ]);

        DetailPeminjaman::create([
            'id_peminjaman' => $peminjamanPertama->id_peminjaman,
            'id_buku' => $bukuPertama->id_buku,
            'jumlah' => 1,
            'status_detail' => 'dipinjam',
        ]);

        $peminjamanKedua = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-202607-202',
            'id_anggota' => $anggotaKedua->anggota->id_anggota,
            'status_peminjaman' => 'selesai',
            'tanggal_pengajuan' => now(),
        ]);

        DetailPeminjaman::create([
            'id_peminjaman' => $peminjamanKedua->id_peminjaman,
            'id_buku' => $bukuKedua->id_buku,
            'jumlah' => 1,
            'status_detail' => 'dikembalikan',
        ]);

        $response = $this->actingAs($petugas)
            ->get(route('petugas.peminjaman.index', ['id_anggota' => $anggotaPertama->anggota->id_anggota]));

        $response->assertOk()
            ->assertSee('Menampilkan riwayat peminjaman untuk: Dina Pratiwi')
            ->assertSee('PMJ-202607-101')
            ->assertSee('Basis Data Terapan')
            ->assertSee('Tampilkan Semua Data')
            ->assertDontSee('PMJ-202607-202')
            ->assertDontSee('Raka Wijaya')
            ->assertDontSee('Jaringan Komputer')
            ->assertSee('>Aksi<', false);
    }

    public function test_petugas_dapat_melihat_detail_pengajuan_peminjaman(): void
    {
        $petugas = $this->createPetugasUser();
        $anggota = $this->createAnggotaUser('Budi Santoso');

        $kategori = KategoriBuku::factory()->create();
        $buku = Buku::factory()->for($kategori, 'kategori')->create(['judul' => 'Algoritma Pemrograman']);

        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-202310-089',
            'id_anggota' => $anggota->anggota->id_anggota,
            'status_peminjaman' => 'diajukan',
            'tanggal_pengajuan' => now(),
        ]);

        DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_buku' => $buku->id_buku,
            'jumlah' => 1,
            'status_detail' => 'diajukan',
        ]);

        $response = $this->actingAs($petugas)
            ->get(route('petugas.peminjaman.show', $peminjaman->id_peminjaman));

        $response->assertOk()
            ->assertSee('PMJ-202310-089')
            ->assertSee('Budi Santoso')
            ->assertSee('Algoritma Pemrograman')
            ->assertSee('Tolak Pengajuan')
            ->assertSee('Setujui & Atur Jadwal', false);
    }

    public function test_petugas_dapat_menolak_pengajuan_peminjaman(): void
    {
        $petugas = $this->createPetugasUser();
        $anggota = $this->createAnggotaUser('Budi Santoso');

        $kategori = KategoriBuku::factory()->create();
        $buku = Buku::factory()->for($kategori, 'kategori')->create();

        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-202310-089',
            'id_anggota' => $anggota->anggota->id_anggota,
            'status_peminjaman' => 'diajukan',
        ]);

        DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_buku' => $buku->id_buku,
            'jumlah' => 1,
            'status_detail' => 'diajukan',
        ]);

        $response = $this->actingAs($petugas)
            ->post(route('petugas.peminjaman.tolak', $peminjaman->id_peminjaman));

        $response->assertRedirect(route('petugas.peminjaman.index'));
        $peminjaman->refresh();
        $this->assertEquals('ditolak', $peminjaman->status_peminjaman);

        // Verify notification is created
        $this->assertDatabaseHas('notifikasi', [
            'id_user' => $anggota->id_user,
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'judul' => 'Peminjaman Ditolak',
        ]);
    }

    public function test_petugas_dapat_melihat_form_setujui_pengajuan_peminjaman(): void
    {
        $petugas = $this->createPetugasUser();
        $anggota = $this->createAnggotaUser('Ahmad Rifai');

        $kategori = KategoriBuku::factory()->create();
        $buku = Buku::factory()->for($kategori, 'kategori')->create(['judul' => 'The Art of Computer Programming']);

        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-202310-092',
            'id_anggota' => $anggota->anggota->id_anggota,
            'status_peminjaman' => 'diajukan',
        ]);

        DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_buku' => $buku->id_buku,
            'jumlah' => 1,
            'status_detail' => 'diajukan',
        ]);

        $response = $this->actingAs($petugas)
            ->get(route('petugas.peminjaman.approve-form', $peminjaman->id_peminjaman));

        $response->assertOk()
            ->assertSee('Setujui & Atur Jadwal', false)
            ->assertSee('Ahmad Rifai')
            ->assertSee('The Art of Computer Programming');
    }

    public function test_petugas_dapat_menyetujui_dan_mengatur_jadwal_pengambilan(): void
    {
        $petugas = $this->createPetugasUser();
        $anggota = $this->createAnggotaUser('Ahmad Rifai');

        $kategori = KategoriBuku::factory()->create();
        $buku = Buku::factory()->for($kategori, 'kategori')->create(['judul' => 'The Art of Computer Programming']);

        $eksemplar = EksemplarBuku::factory()->create([
            'id_buku' => $buku->id_buku,
            'status_eksemplar' => 'tersedia',
        ]);

        $aturan = AturanPeminjaman::create([
            'nama_aturan' => 'Aturan Default',
            'lama_pinjam_hari' => 14,
            'status_aktif' => true,
        ]);

        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-202310-092',
            'id_anggota' => $anggota->anggota->id_anggota,
            'id_aturan' => $aturan->id_aturan_peminjaman,
            'status_peminjaman' => 'diajukan',
        ]);

        DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_buku' => $buku->id_buku,
            'jumlah' => 1,
            'status_detail' => 'diajukan',
        ]);

        $response = $this->actingAs($petugas)
            ->post(route('petugas.peminjaman.approve', $peminjaman->id_peminjaman), [
                'tanggal_pengambilan' => now()->addDay()->toDateString(),
                'jam_pengambilan' => '09:00',
                'lokasi_pengambilan' => 'Meja Sirkulasi Lantai 1',
                'pesan' => 'Harap bawa e-kartu.',
            ]);

        $response->assertRedirect(route('petugas.peminjaman.index'));

        $peminjaman->refresh();
        $this->assertEquals('siap_diambil', $peminjaman->status_peminjaman);

        // Verify exemplar status is set to dipesan
        $eksemplar->refresh();
        $this->assertEquals('dipesan', $eksemplar->status_eksemplar);

        // Verify detail_peminjaman has exemplar and status_detail is dipesan
        $detail = $peminjaman->detailPeminjaman->first();
        $this->assertEquals($eksemplar->id_eksemplar_buku, $detail->id_eksemplar_buku);
        $this->assertEquals('dipesan', $detail->status_detail);

        // Verify schedule is created
        $this->assertDatabaseHas('jadwal_pengambilan', [
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'lokasi_pengambilan' => 'Meja Sirkulasi Lantai 1',
            'pesan' => 'Harap bawa e-kartu.',
        ]);

        // Verify notification is created
        $this->assertDatabaseHas('notifikasi', [
            'id_user' => $anggota->id_user,
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'judul' => 'Peminjaman Disetujui',
        ]);
    }

    public function test_petugas_tidak_dapat_menyetujui_peminjaman_dengan_jadwal_tidak_valid(): void
    {
        $petugas = $this->createPetugasUser();
        $anggota = $this->createAnggotaUser('Ahmad Rifai');

        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-202310-094',
            'id_anggota' => $anggota->anggota->id_anggota,
            'status_peminjaman' => 'diajukan',
        ]);

        $response = $this->actingAs($petugas)
            ->from(route('petugas.peminjaman.approve-form', $peminjaman->id_peminjaman))
            ->post(route('petugas.peminjaman.approve', $peminjaman->id_peminjaman), [
                'tanggal_pengambilan' => now()->subDay()->toDateString(),
            ]);

        $response
            ->assertRedirect(route('petugas.peminjaman.approve-form', $peminjaman->id_peminjaman))
            ->assertSessionHasErrors([
                'tanggal_pengambilan',
                'jam_pengambilan',
                'lokasi_pengambilan',
            ]);

        $peminjaman->refresh();
        $this->assertEquals('diajukan', $peminjaman->status_peminjaman);
    }

    public function test_petugas_dapat_menandai_buku_diambil(): void
    {
        $petugas = $this->createPetugasUser();
        $anggota = $this->createAnggotaUser('Ahmad Rifai');

        $kategori = KategoriBuku::factory()->create();
        $buku = Buku::factory()->for($kategori, 'kategori')->create(['judul' => 'The Art of Computer Programming']);

        $eksemplar = EksemplarBuku::factory()->create([
            'id_buku' => $buku->id_buku,
            'status_eksemplar' => 'dipesan',
        ]);

        $aturan = AturanPeminjaman::create([
            'nama_aturan' => 'Aturan Default',
            'lama_pinjam_hari' => 14,
            'status_aktif' => true,
        ]);

        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-202310-093',
            'id_anggota' => $anggota->anggota->id_anggota,
            'id_aturan' => $aturan->id_aturan_peminjaman,
            'status_peminjaman' => 'siap_diambil',
        ]);

        DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_buku' => $buku->id_buku,
            'id_eksemplar_buku' => $eksemplar->id_eksemplar_buku,
            'jumlah' => 1,
            'status_detail' => 'dipesan',
        ]);

        $response = $this->actingAs($petugas)
            ->post(route('petugas.peminjaman.ambil', $peminjaman->id_peminjaman));

        $response->assertRedirect(route('petugas.peminjaman.show', $peminjaman->id_peminjaman));

        $peminjaman->refresh();
        $this->assertEquals('aktif', $peminjaman->status_peminjaman);

        $detail = $peminjaman->detailPeminjaman->first();
        $this->assertEquals('dipinjam', $detail->status_detail);

        $eksemplar->refresh();
        $this->assertEquals('dipinjam', $eksemplar->status_eksemplar);
    }

    public function test_petugas_dapat_mengekspor_daftar_peminjaman_ke_csv(): void
    {
        $petugas = $this->createPetugasUser();
        $anggota = $this->createAnggotaUser('Ahmad Hidayat');

        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-202310-045',
            'id_anggota' => $anggota->anggota->id_anggota,
            'status_peminjaman' => 'diajukan',
        ]);

        $response = $this->actingAs($petugas)
            ->get(route('petugas.peminjaman.export'));

        $response->assertOk()
            ->assertDownload('laporan_peminjaman.csv');

        $content = $response->streamedContent();
        $this->assertStringContainsString('PMJ-202310-045', $content);
        $this->assertStringContainsString('Ahmad Hidayat', $content);
    }

    public function test_anggota_tidak_dapat_mengakses_fitur_peminjaman_petugas(): void
    {
        $anggota = $this->createAnggotaUser('Hafizh');

        $this->actingAs($anggota)
            ->get(route('petugas.peminjaman.index'))
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
            'no_anggota' => 'ANG-'.rand(1000, 9999),
        ]);

        return $user->load('anggota');
    }
}
