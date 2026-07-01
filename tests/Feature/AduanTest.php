<?php

namespace Tests\Feature;

use App\Models\Aduan;
use App\Models\Anggota;
use App\Models\Petugas;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AduanTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_create_complaint_page(): void
    {
        $response = $this->get('/aduan/tambah');

        $response->assertRedirect('/login');
    }

    public function test_non_anggota_user_gets_forbidden_when_accessing_create_complaint_page(): void
    {
        $this->seed();
        $user = User::factory()->create([
            'id_role' => Role::where('nama_role', 'Petugas')->first()->id_role,
        ]);

        $response = $this->actingAs($user)->get('/aduan/tambah');

        $response->assertStatus(403);
    }

    public function test_anggota_can_access_create_complaint_page_with_prefilled_data(): void
    {
        $this->seed();
        $user = User::factory()->create([
            'id_role' => Role::where('nama_role', 'Anggota')->first()->id_role,
        ]);
        $anggota = Anggota::factory()->for($user, 'user')->create([
            'nama_lengkap' => 'Azzura Test',
            'no_telepon' => '0812345678',
        ]);

        $response = $this->actingAs($user)->get('/aduan/tambah');

        $response->assertOk();
        $response->assertSee('Azzura Test');
        $response->assertSee('0812345678');
    }

    public function test_anggota_can_submit_complaint(): void
    {
        $this->seed();
        Storage::fake('public');

        $user = User::factory()->create([
            'id_role' => Role::where('nama_role', 'Anggota')->first()->id_role,
        ]);
        $anggota = Anggota::factory()->for($user, 'user')->create();

        $file = UploadedFile::fake()->create('attachment.pdf', 100);

        $response = $this->actingAs($user)->post('/aduan/tambah', [
            'kategori_aduan' => 'Fasilitas Ruang Baca',
            'isi_aduan' => 'AC di ruang baca kurang dingin.',
            'lampiran' => $file,
        ]);

        $this->assertDatabaseHas('aduan', [
            'id_anggota' => $anggota->id_anggota,
            'kategori_aduan' => 'Fasilitas Ruang Baca',
            'isi_aduan' => 'AC di ruang baca kurang dingin.',
        ]);

        $aduan = Aduan::latest('id_aduan')->first();
        $this->assertNotNull($aduan->lampiran);
        Storage::disk('public')->assertExists($aduan->lampiran);

        $response->assertRedirect(route('aduan.track', ['ticket' => $aduan->kode_aduan]));
    }

    public function test_anyone_can_track_complaint_by_ticket_code(): void
    {
        $this->seed();
        $user = User::factory()->create([
            'id_role' => Role::where('nama_role', 'Anggota')->first()->id_role,
        ]);
        $anggota = Anggota::factory()->for($user, 'user')->create();

        $aduan = Aduan::create([
            'kode_aduan' => 'AD-2026-06-999',
            'id_anggota' => $anggota->id_anggota,
            'subjek' => 'Aduan Test',
            'isi_aduan' => 'Test content',
            'kategori_aduan' => 'Fasilitas Ruang Baca',
            'status_aduan' => 'terkirim',
        ]);

        $response = $this->get('/aduan/lacak?ticket=AD-2026-06-999');

        $response->assertOk();
        $response->assertSee('AD-2026-06-999');
        $response->assertSee('Test content');
    }

    // ==========================================
    // PETUGAS / ADMIN FEATURES TESTS
    // ==========================================

    public function test_petugas_can_view_active_complaints_list(): void
    {
        $this->seed();
        $petugasUser = User::where('email', 'petugas@sipadi.test')->firstOrFail();

        $response = $this->actingAs($petugasUser)->get('/petugas/aduan');

        $response->assertOk();
        $response->assertSee('Daftar Aduan');
    }

    public function test_petugas_can_view_complaint_details(): void
    {
        $this->seed();
        $petugasUser = User::where('email', 'petugas@sipadi.test')->firstOrFail();

        $memberUser = User::factory()->create([
            'id_role' => Role::where('nama_role', 'Anggota')->first()->id_role,
        ]);
        $anggota = Anggota::factory()->for($memberUser, 'user')->create([
            'nama_lengkap' => 'Budi Santoso',
        ]);

        $aduan = Aduan::create([
            'kode_aduan' => 'AD-2026-06-111',
            'id_anggota' => $anggota->id_anggota,
            'subjek' => 'Fasilitas AC di Ruang Baca Utama Mati',
            'isi_aduan' => 'Selamat siang admin, AC mati.',
            'kategori_aduan' => 'Fasilitas & Gedung',
            'status_aduan' => 'terkirim',
        ]);

        $response = $this->actingAs($petugasUser)->get('/petugas/aduan/'.$aduan->id_aduan);

        $response->assertOk();
        $response->assertSee('AD-2026-06-111');
        $response->assertSee('Budi Santoso');
    }

    public function test_petugas_can_submit_tanggapan(): void
    {
        $this->seed();
        $petugasUser = User::where('email', 'petugas@sipadi.test')->firstOrFail();
        $petugas = Petugas::where('id_user', $petugasUser->id_user)->firstOrFail();

        $memberUser = User::factory()->create([
            'id_role' => Role::where('nama_role', 'Anggota')->first()->id_role,
        ]);
        $anggota = Anggota::factory()->for($memberUser, 'user')->create();

        $aduan = Aduan::create([
            'kode_aduan' => 'AD-2026-06-222',
            'id_anggota' => $anggota->id_anggota,
            'subjek' => 'Masalah AC',
            'isi_aduan' => 'AC mati.',
            'kategori_aduan' => 'Fasilitas & Gedung',
            'status_aduan' => 'terkirim',
        ]);

        $response = $this->actingAs($petugasUser)->post('/petugas/aduan/'.$aduan->id_aduan.'/tanggapi', [
            'status_aduan' => 'diproses',
            'isi_tanggapan' => 'Terima kasih atas laporannya, sedang kami cek.',
        ]);

        $this->assertDatabaseHas('tanggapan_aduan', [
            'id_aduan' => $aduan->id_aduan,
            'id_petugas' => $petugas->id_petugas,
            'isi_tanggapan' => 'Terima kasih atas laporannya, sedang kami cek.',
            'status_setelah_respon' => 'diproses',
        ]);

        $aduan->refresh();
        $this->assertSame('diproses', $aduan->status_aduan);
        $response->assertRedirect(route('petugas.aduan.show', $aduan));
    }

    public function test_petugas_can_toggle_archive_complaint(): void
    {
        $this->seed();
        $petugasUser = User::where('email', 'petugas@sipadi.test')->firstOrFail();
        $petugas = Petugas::where('id_user', $petugasUser->id_user)->firstOrFail();

        $memberUser = User::factory()->create([
            'id_role' => Role::where('nama_role', 'Anggota')->first()->id_role,
        ]);
        $anggota = Anggota::factory()->for($memberUser, 'user')->create();

        $aduan = Aduan::create([
            'kode_aduan' => 'AD-2026-06-333',
            'id_anggota' => $anggota->id_anggota,
            'subjek' => 'Masalah AC',
            'isi_aduan' => 'AC mati.',
            'kategori_aduan' => 'Fasilitas & Gedung',
            'status_aduan' => 'terkirim',
        ]);

        // Archive
        $response = $this->actingAs($petugasUser)->post('/petugas/aduan/'.$aduan->id_aduan.'/arsip');
        $response->assertRedirect();

        $this->assertDatabaseHas('arsip_aduan', [
            'id_aduan' => $aduan->id_aduan,
            'diarsipkan_oleh' => $petugas->id_petugas,
        ]);

        // Unarchive
        $response = $this->actingAs($petugasUser)->post('/petugas/aduan/'.$aduan->id_aduan.'/arsip');
        $this->assertDatabaseMissing('arsip_aduan', [
            'id_aduan' => $aduan->id_aduan,
        ]);
    }
}
