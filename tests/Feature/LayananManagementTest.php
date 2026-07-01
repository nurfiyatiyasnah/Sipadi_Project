<?php

namespace Tests\Feature;

use App\Models\Layanan;
use App\Models\Petugas;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LayananManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_petugas_dapat_melihat_daftar_layanan(): void
    {
        $petugas = $this->createPetugasUser();
        Layanan::create([
            'nama_layanan' => 'Peminjaman Buku Fisik',
            'slug' => 'peminjaman-buku-fisik',
            'deskripsi' => 'Sirkulasi utama koleksi fisik.',
            'jam_layanan' => '08:00 - 16:00',
            'status_layanan' => 'aktif',
        ]);

        $response = $this->actingAs($petugas)->get(route('petugas.layanan.index'));

        $response
            ->assertOk()
            ->assertSee('Daftar Layanan')
            ->assertSee('Peminjaman Buku Fisik')
            ->assertSee('Layanan Baru')
            ->assertSee('Total Akses');
    }

    public function test_petugas_dapat_membuka_form_tambah_layanan(): void
    {
        $petugas = $this->createPetugasUser();

        $this->actingAs($petugas)
            ->get(route('petugas.layanan.create'))
            ->assertOk()
            ->assertSee('Tambah Layanan Baru')
            ->assertSee('Informasi Layanan')
            ->assertSee('Persyaratan & Prosedur', false);
    }

    public function test_petugas_dapat_menyimpan_layanan(): void
    {
        $petugas = $this->createPetugasUser();

        $response = $this->actingAs($petugas)->post(route('petugas.layanan.store'), [
            'nama_layanan' => 'Akses Jurnal Digital',
            'deskripsi' => 'Akses database e-library untuk anggota.',
            'persyaratan' => ['Anggota aktif'],
            'prosedur' => ['Login ke akun SIPADI'],
            'jam_layanan' => '24 Jam',
            'biaya' => 'Gratis',
            'kontak_layanan' => 'Rina Melati',
            'status_layanan' => 'aktif',
        ]);

        $response
            ->assertRedirect(route('petugas.layanan.index'))
            ->assertSessionHas('success', 'Layanan berhasil ditambahkan.');

        $this->assertDatabaseHas('layanan', [
            'nama_layanan' => 'Akses Jurnal Digital',
            'slug' => 'akses-jurnal-digital',
            'status_layanan' => 'aktif',
        ]);
    }

    public function test_petugas_dapat_melihat_dan_memperbarui_detail_layanan(): void
    {
        $petugas = $this->createPetugasUser();
        $layanan = Layanan::create([
            'nama_layanan' => 'Booking Ruang Diskusi',
            'slug' => 'booking-ruang-diskusi',
            'deskripsi' => 'Reservasi ruang diskusi.',
            'status_layanan' => 'review',
        ]);

        $this->actingAs($petugas)
            ->get(route('petugas.layanan.show', $layanan))
            ->assertOk()
            ->assertSee('Booking Ruang Diskusi')
            ->assertSee('Status Layanan');

        $response = $this->actingAs($petugas)->put(route('petugas.layanan.update', $layanan), [
            'nama_layanan' => 'Booking Ruang Diskusi Digital',
            'deskripsi' => 'Reservasi ruang diskusi dengan jadwal digital.',
            'persyaratan' => ['Anggota aktif'],
            'prosedur' => ['Pilih jadwal', 'Menunggu verifikasi'],
            'jam_layanan' => '09:00 - 17:00',
            'biaya' => 'Gratis',
            'kontak_layanan' => 'Budi Pratama',
            'status_layanan' => 'aktif',
        ]);

        $response
            ->assertRedirect(route('petugas.layanan.show', $layanan))
            ->assertSessionHas('success', 'Layanan berhasil diperbarui.');

        $this->assertDatabaseHas('layanan', [
            'id_layanan' => $layanan->id_layanan,
            'nama_layanan' => 'Booking Ruang Diskusi Digital',
            'slug' => 'booking-ruang-diskusi-digital',
            'status_layanan' => 'aktif',
        ]);
    }

    private function createPetugasUser(): User
    {
        $role = Role::create(['nama_role' => 'Petugas']);
        $user = User::factory()->create(['id_role' => $role->id_role]);

        Petugas::factory()->create(['id_user' => $user->id_user]);

        return $user;
    }
}
