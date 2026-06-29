<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\AturanPeminjaman;
use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\JadwalPengambilan;
use App\Models\KategoriBuku;
use App\Models\Notifikasi;
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
            'status_peminjaman' => 'menunggu',
            'tanggal_pengajuan' => now(),
        ]);

        DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_buku' => $buku->id_buku,
            'jumlah' => 1,
            'status_detail' => 'menunggu',
        ]);

        $response = $this->actingAs($petugas)
            ->get(route('petugas.peminjaman.index'));

        $response->assertOk()
            ->assertSee('PMJ-202310-045')
            ->assertSee('Ahmad Hidayat')
            ->assertSee('Sistem Informasi Manajemen');
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
            'status_peminjaman' => 'menunggu',
            'tanggal_pengajuan' => now(),
        ]);

        DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_buku' => $buku->id_buku,
            'jumlah' => 1,
            'status_detail' => 'menunggu',
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

        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-202310-089',
            'id_anggota' => $anggota->anggota->id_anggota,
            'status_peminjaman' => 'menunggu',
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
            'judul' => 'Pengajuan Peminjaman Ditolak',
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
            'status_peminjaman' => 'menunggu',
        ]);

        DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_buku' => $buku->id_buku,
            'jumlah' => 1,
            'status_detail' => 'menunggu',
        ]);

        $response = $this->actingAs($petugas)
            ->get(route('petugas.peminjaman.approve-form', $peminjaman->id_peminjaman));

        $response->assertOk()
            ->assertSee('Jadwal Pengambilan Buku')
            ->assertSee('Ahmad Rifai')
            ->assertSee('The Art of Computer Programming');
    }

    public function test_petugas_dapat_menyetujui_dan_mengatur_jadwal_pengambilan(): void
    {
        $petugas = $this->createPetugasUser();
        $anggota = $this->createAnggotaUser('Ahmad Rifai');

        $kategori = KategoriBuku::factory()->create();
        $buku = Buku::factory()->for($kategori, 'kategori')->create(['judul' => 'The Art of Computer Programming']);

        $aturan = AturanPeminjaman::create([
            'nama_aturan' => 'Aturan Default',
            'lama_pinjam_hari' => 14,
            'status_aktif' => true,
        ]);

        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-202310-092',
            'id_anggota' => $anggota->anggota->id_anggota,
            'id_aturan' => $aturan->id_aturan_peminjaman,
            'status_peminjaman' => 'menunggu',
        ]);

        DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_buku' => $buku->id_buku,
            'jumlah' => 1,
            'status_detail' => 'menunggu',
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
        $this->assertEquals('disetujui', $peminjaman->status_peminjaman);

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
            'judul' => 'Pengajuan Peminjaman Disetujui',
        ]);
    }

    public function test_petugas_dapat_mengekspor_daftar_peminjaman_ke_csv(): void
    {
        $petugas = $this->createPetugasUser();
        $anggota = $this->createAnggotaUser('Ahmad Hidayat');

        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-202310-045',
            'id_anggota' => $anggota->anggota->id_anggota,
            'status_peminjaman' => 'menunggu',
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
            'no_anggota' => 'ANG-' . rand(1000, 9999),
        ]);

        return $user->load('anggota');
    }
}
