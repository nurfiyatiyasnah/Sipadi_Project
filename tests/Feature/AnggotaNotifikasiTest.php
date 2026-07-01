<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\EksemplarBuku;
use App\Models\JadwalPengambilan;
use App\Models\KategoriBuku;
use App\Models\Notifikasi;
use App\Models\Peminjaman;
use App\Models\Petugas;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnggotaNotifikasiTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_admin_approve_peminjaman_membuat_notifikasi_disetujui(): void
    {
        $petugas = $this->createPetugasUser();
        $anggota = $this->createAnggotaUser('Ahmad Hidayat');

        $kategori = KategoriBuku::factory()->create();
        $buku = Buku::factory()->for($kategori, 'kategori')->create();
        EksemplarBuku::factory()->create([
            'id_buku' => $buku->id_buku,
            'status_eksemplar' => 'tersedia',
        ]);

        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-1111',
            'id_anggota' => $anggota->anggota->id_anggota,
            'status_peminjaman' => 'diajukan',
        ]);

        DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_buku' => $buku->id_buku,
            'jumlah' => 1,
            'status_detail' => 'diajukan',
        ]);

        $response = $this->actingAs($petugas)->post(route('petugas.peminjaman.approve', $peminjaman->id_peminjaman), [
            'tanggal_pengambilan' => now()->addDays(2)->format('Y-m-d'),
            'jam_pengambilan' => '09:00',
            'lokasi_pengambilan' => 'Lobby Utama',
            'pesan' => 'Harap tepat waktu',
        ]);

        $response->assertRedirect(route('petugas.peminjaman.index'));

        $this->assertDatabaseHas('notifikasi', [
            'id_user' => $anggota->id_user,
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'judul' => 'Peminjaman Disetujui',
            'jenis_notifikasi' => 'peminjaman_disetujui',
            'status_baca' => 'belum_dibaca',
        ]);
    }

    public function test_badge_lonceng_menghitung_notifikasi_belum_dibaca(): void
    {
        $anggota = $this->createAnggotaUser('Budi Santoso');

        Notifikasi::create([
            'id_user' => $anggota->id_user,
            'judul' => 'Notif 1',
            'status_baca' => 'belum_dibaca',
        ]);

        Notifikasi::create([
            'id_user' => $anggota->id_user,
            'judul' => 'Notif 2',
            'status_baca' => 'dibaca',
        ]);

        $response = $this->actingAs($anggota)->get(route('landing'));

        $response->assertSee('1 Baru');
    }

    public function test_anggota_bisa_klik_notifikasi_dan_diarahkan_ke_tiket(): void
    {
        $anggota = $this->createAnggotaUser('Candra');
        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-2222',
            'id_anggota' => $anggota->anggota->id_anggota,
            'status_peminjaman' => 'siap_diambil',
        ]);

        $jadwal = JadwalPengambilan::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'tanggal_pengambilan' => now(),
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '10:00:00',
            'lokasi_pengambilan' => 'Lobby',
            'status_jadwal' => 'disetujui',
        ]);

        $notif = Notifikasi::create([
            'id_user' => $anggota->id_user,
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_jadwal_pengambilan' => $jadwal->id_jadwal_pengambilan,
            'judul' => 'Peminjaman Disetujui',
            'jenis_notifikasi' => 'peminjaman_disetujui',
            'status_baca' => 'belum_dibaca',
        ]);

        $response = $this->actingAs($anggota)->get(route('anggota.notifikasi.read', $notif->id_notifikasi));

        $response->assertRedirect(route('anggota.peminjaman-saya', ['ticket' => $peminjaman->kode_peminjaman]));

        $notif->refresh();
        $this->assertEquals('dibaca', $notif->status_baca);
        $this->assertNotNull($notif->dibaca_pada);
    }

    public function test_anggota_tidak_bisa_membuka_notifikasi_milik_user_lain(): void
    {
        $user1 = $this->createAnggotaUser('User Satu');
        $user2 = $this->createAnggotaUser('User Dua');

        $notif = Notifikasi::create([
            'id_user' => $user1->id_user,
            'judul' => 'Rahasia',
            'status_baca' => 'belum_dibaca',
        ]);

        $response = $this->actingAs($user2)->get(route('anggota.notifikasi.read', $notif->id_notifikasi));

        $response->assertForbidden();
    }

    public function test_anggota_tidak_bisa_membuka_tiket_anggota_lain(): void
    {
        $user1 = $this->createAnggotaUser('User Satu');
        $user2 = $this->createAnggotaUser('User Dua');

        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-3333',
            'id_anggota' => $user1->anggota->id_anggota,
            'status_peminjaman' => 'siap_diambil',
        ]);

        $response = $this->actingAs($user2)->get(route('anggota.peminjaman-saya', ['ticket' => $peminjaman->kode_peminjaman]));

        $response->assertOk();
        $response->assertViewHas('autoOpenTicket', null);
    }

    public function test_notifikasi_ditolak_diarahkan_ke_peminjaman_saya(): void
    {
        $anggota = $this->createAnggotaUser('Deni');
        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-4444',
            'id_anggota' => $anggota->anggota->id_anggota,
            'status_peminjaman' => 'ditolak',
        ]);

        $notif = Notifikasi::create([
            'id_user' => $anggota->id_user,
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'judul' => 'Peminjaman Ditolak',
            'jenis_notifikasi' => 'peminjaman_ditolak',
            'status_baca' => 'belum_dibaca',
        ]);

        $response = $this->actingAs($anggota)->get(route('anggota.notifikasi.read', $notif->id_notifikasi));

        $response->assertRedirect(route('anggota.peminjaman-saya'));
    }

    public function test_halaman_daftar_notifikasi_menampilkan_riwayat(): void
    {
        $anggota = $this->createAnggotaUser('Eka');

        Notifikasi::create([
            'id_user' => $anggota->id_user,
            'judul' => 'Pengumuman Penting',
            'isi' => 'Harap kembalikan buku tepat waktu.',
            'status_baca' => 'belum_dibaca',
        ]);

        $response = $this->actingAs($anggota)->get(route('anggota.notifikasi.index'));

        $response->assertOk()
            ->assertSee('Pengumuman Penting')
            ->assertSee('Harap kembalikan buku tepat waktu.');
    }
}
