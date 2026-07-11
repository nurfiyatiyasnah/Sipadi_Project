<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\EKartuAnggota;
use App\Models\Keterlambatan;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Role;
use App\Models\SanksiAnggota;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AnggotaManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $petugasUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles
        $petugasRole = Role::create(['nama_role' => 'Petugas']);
        Role::create(['nama_role' => 'Anggota']);

        // Create petugas user
        $this->petugasUser = User::factory()->create(['id_role' => $petugasRole->id_role]);
    }

    public function test_non_petugas_cannot_access_anggota_management(): void
    {
        $response = $this->get(route('petugas.anggota.index'));
        $response->assertRedirect(route('login'));

        $anggotaRole = Role::where('nama_role', 'Anggota')->first();
        $anggotaUser = User::factory()->create(['id_role' => $anggotaRole->id_role]);

        $response = $this->actingAs($anggotaUser)->get(route('petugas.anggota.index'));
        $response->assertForbidden();
    }

    public function test_petugas_can_view_anggota_list(): void
    {
        $anggota = Anggota::factory()->create(['nama_lengkap' => 'Alex Jones']);

        $response = $this->actingAs($this->petugasUser)->get(route('petugas.anggota.index'));

        $response->assertStatus(200);
        $response->assertSee('Alex Jones');
        $response->assertSee($anggota->nik);
        $response->assertDontSee('Tambah Anggota');
        $response->assertSee(route('petugas.anggota.show', $anggota->id_anggota), false);
        $response->assertSee('role="link"', false);
        $response->assertDontSee('>Aksi<', false);
        $response->assertDontSee('Lihat Detail');
        $response->assertDontSee('Edit Anggota');
        $response->assertDontSee('Blokir Anggota');
        $response->assertDontSee('Buka Blokir');
        $response->assertDontSee('name="status_anggota"', false);
        $response->assertDontSee('name="status_sanksi"', false);
    }

    public function test_petugas_can_search_anggota_by_name_or_nik(): void
    {
        Anggota::factory()->create(['nama_lengkap' => 'Budi Santoso', 'nik' => '3271012345678901']);
        Anggota::factory()->create(['nama_lengkap' => 'Siti Aminah', 'nik' => '3271098765432102']);

        // Search by name
        $response = $this->actingAs($this->petugasUser)->get(route('petugas.anggota.index', ['search' => 'Budi']));
        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertDontSee('Siti Aminah');

        // Search by NIK
        $response = $this->actingAs($this->petugasUser)->get(route('petugas.anggota.index', ['search' => '3271098765432102']));
        $response->assertStatus(200);
        $response->assertSee('Siti Aminah');
        $response->assertDontSee('Budi Santoso');
    }

    public function test_petugas_can_filter_anggota_by_status(): void
    {
        Anggota::factory()->create(['nama_lengkap' => 'Active Member', 'status_anggota' => 'aktif']);
        Anggota::factory()->create(['nama_lengkap' => 'Inactive Member', 'status_anggota' => 'nonaktif']);

        $response = $this->actingAs($this->petugasUser)->get(route('petugas.anggota.index', ['status' => 'aktif']));
        $response->assertSee('Active Member');
        $response->assertDontSee('Inactive Member');

        $response = $this->actingAs($this->petugasUser)->get(route('petugas.anggota.index', ['status' => 'nonaktif']));
        $response->assertSee('Inactive Member');
        $response->assertDontSee('Active Member');
    }

    public function test_petugas_can_filter_anggota_by_status_sanksi(): void
    {
        Anggota::factory()->create(['nama_lengkap' => 'Member Clean']);
        $memberLate = Anggota::factory()->create(['nama_lengkap' => 'Member Late']);
        $memberBlocked = Anggota::factory()->create(['nama_lengkap' => 'Member Blocked']);
        $memberExpired = Anggota::factory()->create(['nama_lengkap' => 'Member Expired']);

        SanksiAnggota::create([
            'id_anggota' => $memberLate->id_anggota,
            'id_peminjaman' => null,
            'jenis_sanksi' => 'Nonaktif Peminjaman 3 Hari',
            'alasan' => 'Terlambat mengembalikan buku.',
            'tanggal_mulai' => now()->subDay(),
            'tanggal_selesai' => now()->addDays(2),
            'status_sanksi' => 'aktif',
        ]);

        SanksiAnggota::create([
            'id_anggota' => $memberBlocked->id_anggota,
            'id_peminjaman' => null,
            'jenis_sanksi' => 'Diblokir',
            'alasan' => 'Review administratif.',
            'tanggal_mulai' => now()->subDay(),
            'tanggal_selesai' => null,
            'status_sanksi' => 'aktif',
        ]);

        SanksiAnggota::create([
            'id_anggota' => $memberBlocked->id_anggota,
            'id_peminjaman' => null,
            'jenis_sanksi' => 'Nonaktif Peminjaman 3 Hari',
            'alasan' => 'Terlambat mengembalikan buku.',
            'tanggal_mulai' => now(),
            'tanggal_selesai' => now()->addDays(3),
            'status_sanksi' => 'aktif',
        ]);

        SanksiAnggota::create([
            'id_anggota' => $memberExpired->id_anggota,
            'id_peminjaman' => null,
            'jenis_sanksi' => 'Nonaktif Peminjaman 3 Hari',
            'alasan' => 'Sanksi lampau.',
            'tanggal_mulai' => now()->subDays(5),
            'tanggal_selesai' => now()->subDay(),
            'status_sanksi' => 'aktif',
        ]);

        $response = $this->actingAs($this->petugasUser)->get(route('petugas.anggota.index', ['sanksi' => 'Bebas']));

        $response->assertOk()
            ->assertSee('Member Clean')
            ->assertSee('Member Expired')
            ->assertDontSee('Member Late')
            ->assertDontSee('Member Blocked');

        $response = $this->actingAs($this->petugasUser)->get(route('petugas.anggota.index', ['sanksi' => 'Sanksi']));

        $response->assertOk()
            ->assertSee('Member Late')
            ->assertDontSee('Member Clean')
            ->assertDontSee('Member Expired')
            ->assertDontSee('Member Blocked');

        $response = $this->actingAs($this->petugasUser)->get(route('petugas.anggota.index', ['sanksi' => 'Diblokir']));

        $response->assertOk()
            ->assertSee('Member Blocked')
            ->assertSeeInOrder(['Member Blocked', 'Diblokir'])
            ->assertDontSee('Member Clean')
            ->assertDontSee('Member Expired')
            ->assertDontSee('Member Late');
    }

    public function test_petugas_can_view_anggota_details_and_loan_history(): void
    {
        $anggota = Anggota::factory()->create(['nama_lengkap' => 'Ahmad Ridwan']);
        EKartuAnggota::factory()->create([
            'id_anggota' => $anggota->id_anggota,
            'no_anggota' => $anggota->nik,
            'masa_berlaku' => now()->addYears(3),
        ]);

        $response = $this->actingAs($this->petugasUser)->get(route('petugas.anggota.show', $anggota->id_anggota));

        $response->assertStatus(200);
        $response->assertSee('Ahmad Ridwan');
        $response->assertSee($anggota->user->email);
        $response->assertSee('Bebas Sanksi');
        $response->assertSee(route('petugas.peminjaman.index', ['id_anggota' => $anggota->id_anggota]), false);
    }

    public function test_detail_anggota_menampilkan_statistik_keterlambatan_dan_status_risiko_perlu_review(): void
    {
        $anggota = Anggota::factory()->create(['nama_lengkap' => 'Rani Pratama']);

        foreach ([2, 3, 4] as $index => $hariTerlambat) {
            $peminjaman = Peminjaman::create([
                'kode_peminjaman' => sprintf('PMJ-STAT-%03d', $index + 1),
                'id_anggota' => $anggota->id_anggota,
                'status_peminjaman' => 'selesai',
                'tanggal_pengajuan' => now()->subDays(14 - $index),
                'tanggal_diambil' => now()->subDays(13 - $index),
                'tanggal_jatuh_tempo' => now()->subDays(10 - $index),
            ]);

            Keterlambatan::create([
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'id_anggota' => $anggota->id_anggota,
                'tanggal_jatuh_tempo' => $peminjaman->tanggal_jatuh_tempo,
                'tanggal_dihitung' => now()->subDays(3 - $index),
                'hari_terlambat' => $hariTerlambat,
                'status_perhitungan' => 'Selesai',
            ]);
        }

        $response = $this->actingAs($this->petugasUser)->get(route('petugas.anggota.show', $anggota->id_anggota));

        $response->assertOk()
            ->assertSee('Total Hari Telat')
            ->assertSee('Status Risiko')
            ->assertSeeInOrder(['Keterlambatan', '3', 'Total Hari Telat', '9', 'Status Risiko', 'Perlu Review'])
            ->assertSee('Update Data')
            ->assertSee('Lihat Semua Riwayat');
    }

    public function test_detail_anggota_menampilkan_statistik_default_tanpa_keterlambatan(): void
    {
        $anggota = Anggota::factory()->create(['nama_lengkap' => 'Dimas Saputra']);

        $response = $this->actingAs($this->petugasUser)->get(route('petugas.anggota.show', $anggota->id_anggota));

        $response->assertOk()
            ->assertSee('Total Hari Telat')
            ->assertSee('Status Risiko')
            ->assertSeeInOrder(['Total Hari Telat', '0', 'Status Risiko', 'Aman', 'Sanksi Aktif', '0', 'Terakhir Telat', '-'])
            ->assertSee('Update Data')
            ->assertSee('Lihat Semua Riwayat');
    }

    public function test_detail_anggota_menampilkan_peminjaman_selesai_sebagai_dikembalikan(): void
    {
        $anggota = Anggota::factory()->create(['nama_lengkap' => 'Stenly Rizalevan']);
        $buku = Buku::factory()->create([
            'judul' => 'Kuliner Khas Minangkabau',
            'isbn' => '978-979-22-8504-8',
        ]);

        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PMJ-202607-001',
            'id_anggota' => $anggota->id_anggota,
            'status_peminjaman' => 'selesai',
            'tanggal_pengajuan' => now()->subDays(5),
            'tanggal_diambil' => now()->subDays(4),
            'tanggal_jatuh_tempo' => now()->addDays(10),
        ]);

        DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_buku' => $buku->id_buku,
            'jumlah' => 1,
            'status_detail' => 'dikembalikan',
        ]);

        Pengembalian::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'tanggal_pengembalian' => now(),
            'total_hari_terlambat' => 0,
            'status_pengembalian' => 'Tepat Waktu',
        ]);

        $response = $this->actingAs($this->petugasUser)->get(route('petugas.anggota.show', $anggota->id_anggota));

        $response->assertOk()
            ->assertSee('Kuliner Khas Minangkabau')
            ->assertSee('Dikembalikan')
            ->assertDontSee('Sedang Dipinjam')
            ->assertDontSee('>Aksi<', false)
            ->assertDontSee('fa-ellipsis-vertical', false);
    }

    public function test_petugas_can_view_edit_form(): void
    {
        $anggota = Anggota::factory()->create();

        $response = $this->actingAs($this->petugasUser)->get(route('petugas.anggota.edit', $anggota->id_anggota));

        $response->assertStatus(200);
        $response->assertSee('Update Data Anggota');
        $response->assertSee($anggota->nama_lengkap);
        $response->assertSee('Bebas Sanksi');
        $response->assertSee('Aksi Administratif');
        $response->assertSee('Nonaktifkan Anggota');
        $response->assertSee('Blokir Peminjaman');
        $response->assertSee('Alasan Nonaktif');
        $response->assertSee('Alasan Blokir');
        $response->assertSee('Berlaku Sampai');
        $response->assertDontSee('name="status_anggota"', false);
        $response->assertDontSee('name="status_sanksi"', false);
    }

    public function test_petugas_can_nonaktifkan_anggota_from_administrative_action(): void
    {
        $anggota = Anggota::factory()->create([
            'nama_lengkap' => 'Rizki Maulana',
            'status_anggota' => 'aktif',
        ]);

        $response = $this->actingAs($this->petugasUser)->post(route('petugas.anggota.nonaktifkan', $anggota->id_anggota), [
            'administrative_action' => 'deactivate',
            'alasan_nonaktif' => 'Permintaan administratif dari petugas.',
        ]);

        $response->assertRedirect(route('petugas.anggota.edit', $anggota->id_anggota));
        $response->assertSessionHas('success', 'Anggota berhasil dinonaktifkan.');

        $anggota->refresh();
        $anggota->user->refresh();

        $this->assertEquals('nonaktif', $anggota->status_anggota);
        $this->assertEquals('nonaktif', $anggota->user->status_akun);
        $this->assertEquals('Permintaan administratif dari petugas.', $anggota->alasan_nonaktif);
    }

    public function test_petugas_can_aktifkan_anggota_from_administrative_action(): void
    {
        $anggota = Anggota::factory()->create([
            'nama_lengkap' => 'Rizki Maulana',
            'status_anggota' => 'nonaktif',
            'alasan_nonaktif' => 'Dokumen perlu dilengkapi.',
        ]);
        $anggota->user->update(['status_akun' => 'nonaktif']);

        $response = $this->actingAs($this->petugasUser)->post(route('petugas.anggota.aktifkan', $anggota->id_anggota), [
            'administrative_action' => 'activate',
        ]);

        $response->assertRedirect(route('petugas.anggota.edit', $anggota->id_anggota));
        $response->assertSessionHas('success', 'Anggota berhasil diaktifkan kembali.');

        $anggota->refresh();
        $anggota->user->refresh();

        $this->assertEquals('aktif', $anggota->status_anggota);
        $this->assertEquals('aktif', $anggota->user->status_akun);
        $this->assertNull($anggota->alasan_nonaktif);
    }

    public function test_petugas_can_blokir_peminjaman_from_administrative_action(): void
    {
        $anggota = Anggota::factory()->create([
            'nama_lengkap' => 'Rizki Maulana',
            'status_anggota' => 'aktif',
        ]);
        $tanggalSelesai = now()->addDays(7)->toDateString();

        $response = $this->actingAs($this->petugasUser)->post(route('petugas.anggota.blokir-peminjaman', $anggota->id_anggota), [
            'administrative_action' => 'blockBorrowing',
            'alasan_blokir' => 'Perlu review peminjaman anggota.',
            'tanggal_selesai' => $tanggalSelesai,
        ]);

        $response->assertRedirect(route('petugas.anggota.edit', $anggota->id_anggota));
        $response->assertSessionHas('success', 'Peminjaman anggota berhasil diblokir.');

        $activeSanksi = $anggota->sanksi()
            ->where('status_sanksi', 'aktif')
            ->where('jenis_sanksi', 'Diblokir')
            ->first();

        $this->assertNotNull($activeSanksi);
        $this->assertNull($activeSanksi->id_peminjaman);
        $this->assertEquals('Perlu review peminjaman anggota.', $activeSanksi->alasan);
        $this->assertEquals($tanggalSelesai, $activeSanksi->tanggal_selesai->toDateString());
    }

    public function test_petugas_can_buka_blokir_peminjaman_tanpa_mengubah_sanksi_lain(): void
    {
        $anggota = Anggota::factory()->create([
            'nama_lengkap' => 'Rizki Maulana',
            'status_anggota' => 'aktif',
        ]);

        $blokir = SanksiAnggota::create([
            'id_anggota' => $anggota->id_anggota,
            'id_peminjaman' => null,
            'jenis_sanksi' => 'Diblokir',
            'alasan' => 'Perlu review administratif.',
            'tanggal_mulai' => now(),
            'tanggal_selesai' => now()->addDays(7),
            'status_sanksi' => 'aktif',
        ]);

        $sanksiTerlambat = SanksiAnggota::create([
            'id_anggota' => $anggota->id_anggota,
            'id_peminjaman' => null,
            'jenis_sanksi' => 'Nonaktif Peminjaman 3 Hari',
            'alasan' => 'Terlambat mengembalikan buku.',
            'tanggal_mulai' => now(),
            'tanggal_selesai' => now()->addDays(3),
            'status_sanksi' => 'aktif',
        ]);

        $response = $this->actingAs($this->petugasUser)->post(route('petugas.anggota.buka-blokir-peminjaman', $anggota->id_anggota), [
            'administrative_action' => 'unblockBorrowing',
            'catatan_buka_blokir' => 'Review administratif selesai.',
        ]);

        $response->assertRedirect(route('petugas.anggota.edit', $anggota->id_anggota));
        $response->assertSessionHas('success', 'Blokir peminjaman berhasil dibuka.');

        $blokir->refresh();
        $sanksiTerlambat->refresh();

        $this->assertEquals('selesai', $blokir->status_sanksi);
        $this->assertEquals(today()->toDateString(), $blokir->tanggal_selesai->toDateString());
        $this->assertStringContainsString('Catatan buka blokir: Review administratif selesai.', $blokir->alasan);
        $this->assertEquals('aktif', $sanksiTerlambat->status_sanksi);
        $this->assertEquals('Nonaktif Peminjaman 3 Hari', $sanksiTerlambat->jenis_sanksi);
    }

    public function test_detail_anggota_memprioritaskan_badge_diblokir_saat_ada_sanksi_lain_aktif(): void
    {
        $anggota = Anggota::factory()->create(['nama_lengkap' => 'Rizki Maulana']);

        SanksiAnggota::create([
            'id_anggota' => $anggota->id_anggota,
            'id_peminjaman' => null,
            'jenis_sanksi' => 'Nonaktif Peminjaman 3 Hari',
            'alasan' => 'Terlambat mengembalikan buku.',
            'tanggal_mulai' => now(),
            'tanggal_selesai' => now()->addDays(3),
            'status_sanksi' => 'aktif',
        ]);

        SanksiAnggota::create([
            'id_anggota' => $anggota->id_anggota,
            'id_peminjaman' => null,
            'jenis_sanksi' => 'Diblokir',
            'alasan' => 'Perlu review administratif.',
            'tanggal_mulai' => now(),
            'tanggal_selesai' => null,
            'status_sanksi' => 'aktif',
        ]);

        $response = $this->actingAs($this->petugasUser)->get(route('petugas.anggota.show', $anggota->id_anggota));

        $response->assertOk()
            ->assertSee('Diblokir')
            ->assertDontSee('Nonaktif Peminjaman 3 Hari');
    }

    public function test_edit_anggota_memprioritaskan_status_diblokir_walau_sanksi_lain_lebih_baru(): void
    {
        $anggota = Anggota::factory()->create(['nama_lengkap' => 'Rizki Maulana']);

        SanksiAnggota::create([
            'id_anggota' => $anggota->id_anggota,
            'id_peminjaman' => null,
            'jenis_sanksi' => 'Diblokir',
            'alasan' => 'Perlu review administratif.',
            'tanggal_mulai' => now(),
            'tanggal_selesai' => null,
            'status_sanksi' => 'aktif',
        ]);

        SanksiAnggota::create([
            'id_anggota' => $anggota->id_anggota,
            'id_peminjaman' => null,
            'jenis_sanksi' => 'Nonaktif Peminjaman 3 Hari',
            'alasan' => 'Terlambat mengembalikan buku.',
            'tanggal_mulai' => now(),
            'tanggal_selesai' => now()->addDays(3),
            'status_sanksi' => 'aktif',
        ]);

        $response = $this->actingAs($this->petugasUser)->get(route('petugas.anggota.edit', $anggota->id_anggota));

        $response->assertOk()
            ->assertSee('Diblokir')
            ->assertSee('Anggota sedang diblokir dari layanan peminjaman.')
            ->assertSee('Menyelesaikan blokir peminjaman aktif untuk anggota.')
            ->assertDontSee('Membatasi anggota agar tidak dapat melakukan peminjaman.')
            ->assertDontSee('Sedang Sanksi');
    }

    public function test_edit_anggota_menampilkan_status_sanksi_otomatis(): void
    {
        $anggota = Anggota::factory()->create();

        SanksiAnggota::create([
            'id_anggota' => $anggota->id_anggota,
            'id_peminjaman' => null,
            'jenis_sanksi' => 'Nonaktif Peminjaman 3 Hari',
            'alasan' => 'Terlambat mengembalikan buku.',
            'tanggal_mulai' => now(),
            'tanggal_selesai' => now()->addDays(3),
            'status_sanksi' => 'aktif',
        ]);

        $response = $this->actingAs($this->petugasUser)->get(route('petugas.anggota.edit', $anggota->id_anggota));

        $response->assertOk()
            ->assertSee('Sedang Sanksi')
            ->assertSee('Nonaktif Peminjaman 3 Hari')
            ->assertDontSee('name="status_sanksi"', false);
    }

    public function test_petugas_can_update_anggota_profile_without_status_fields(): void
    {
        Storage::fake('public');

        $anggota = Anggota::factory()->create([
            'nama_lengkap' => 'Old Name',
            'no_telepon' => '0812345678',
            'alamat' => 'Old Address',
            'status_anggota' => 'aktif',
        ]);

        $dummyPhoto = UploadedFile::fake()->createWithContent(
            'avatar.jpg',
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII=')
        );

        $response = $this->actingAs($this->petugasUser)->put(route('petugas.anggota.update', $anggota->id_anggota), [
            'nama_lengkap' => 'New Name',
            'email' => 'newemail@example.com',
            'no_telepon' => '0899999999',
            'alamat' => 'New Address',
            'foto' => $dummyPhoto,
        ]);

        $response->assertRedirect(route('petugas.anggota.show', $anggota->id_anggota));
        $response->assertSessionHas('success', 'Data berhasil diperbarui');

        $anggota->refresh();

        $this->assertEquals('New Name', $anggota->nama_lengkap);
        $this->assertEquals('0899999999', $anggota->no_telepon);
        $this->assertEquals('New Address', $anggota->alamat);
        $this->assertEquals('aktif', $anggota->status_anggota);
        $this->assertEquals('aktif', $anggota->user->status_akun);
        $this->assertEquals('newemail@example.com', $anggota->user->email);

        $this->assertNotNull($anggota->foto);
        Storage::disk('public')->assertExists($anggota->foto);

        $this->assertFalse($anggota->sanksi()->where('status_sanksi', 'aktif')->exists());
    }

    public function test_update_anggota_mengabaikan_status_fields_dan_mempertahankan_alamat(): void
    {
        $anggota = Anggota::factory()->create([
            'nama_lengkap' => 'Budi Santoso',
            'no_telepon' => '0812345678',
            'alamat' => 'Soreang, Bandung',
            'status_anggota' => 'aktif',
        ]);

        $response = $this->actingAs($this->petugasUser)->put(route('petugas.anggota.update', $anggota->id_anggota), [
            'nama_lengkap' => 'Budi Santoso',
            'email' => $anggota->user->email,
            'no_telepon' => '0812345678',
            'status_anggota' => 'nonaktif',
            'status_sanksi' => 'Diblokir',
        ]);

        $response->assertRedirect(route('petugas.anggota.show', $anggota->id_anggota));
        $anggota->refresh();

        $this->assertEquals('aktif', $anggota->status_anggota);
        $this->assertEquals('Soreang, Bandung', $anggota->alamat); // Alamat is preserved!
        $this->assertFalse($anggota->sanksi()->where('status_sanksi', 'aktif')->exists());
    }

    public function test_petugas_can_update_anggota_and_redirect_to_index_without_status_fields(): void
    {
        $anggota = Anggota::factory()->create([
            'nama_lengkap' => 'Budi Santoso',
            'no_telepon' => '0812345678',
            'alamat' => 'Soreang, Bandung',
            'status_anggota' => 'aktif',
        ]);

        $response = $this->actingAs($this->petugasUser)->put(route('petugas.anggota.update', $anggota->id_anggota), [
            'nama_lengkap' => 'Budi Santoso',
            'email' => $anggota->user->email,
            'no_telepon' => '0812345678',
            'redirect_to' => 'index',
        ]);

        $response->assertRedirect(route('petugas.anggota.index'));
        $response->assertSessionHas('success', 'Data anggota berhasil diperbarui');
        $anggota->refresh();

        $this->assertEquals('aktif', $anggota->status_anggota);
        $this->assertEquals('aktif', $anggota->user->status_akun);
    }

    public function test_update_anggota_tidak_menyelesaikan_sanksi_aktif_secara_manual(): void
    {
        $anggota = Anggota::factory()->create([
            'nama_lengkap' => 'Budi Santoso',
            'no_telepon' => '0812345678',
            'alamat' => 'Soreang, Bandung',
            'status_anggota' => 'nonaktif',
        ]);
        $anggota->user->update(['status_akun' => 'nonaktif']);

        // Create an active block sanksi
        SanksiAnggota::create([
            'id_anggota' => $anggota->id_anggota,
            'jenis_sanksi' => 'Diblokir',
            'alasan' => 'Test',
            'tanggal_mulai' => now(),
            'status_sanksi' => 'aktif',
            'id_peminjaman' => null,
        ]);

        $response = $this->actingAs($this->petugasUser)->put(route('petugas.anggota.update', $anggota->id_anggota), [
            'nama_lengkap' => 'Budi Santoso',
            'email' => $anggota->user->email,
            'no_telepon' => '0812345678',
            'status_anggota' => 'aktif',
            'status_sanksi' => 'Bersih',
            'redirect_to' => 'index',
        ]);

        $response->assertRedirect(route('petugas.anggota.index'));
        $anggota->refresh();

        $this->assertEquals('nonaktif', $anggota->status_anggota);
        $this->assertEquals('nonaktif', $anggota->user->status_akun);

        $activeSanksi = $anggota->sanksi()->where('status_sanksi', 'aktif')->first();
        $this->assertNotNull($activeSanksi);
        $this->assertEquals('Diblokir', $activeSanksi->jenis_sanksi);
    }
}
