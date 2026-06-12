<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\EKartuAnggota;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrasiAnggotaTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_registrasi_anggota_dapat_ditampilkan(): void
    {
        $response = $this->get(route('register'));

        $response
            ->assertOk()
            ->assertSee('Nama lengkap')
            ->assertSee('NIK');
    }

    public function test_pengunjung_dapat_mendaftar_sebagai_anggota(): void
    {
        $this->seed();

        $response = $this->post(route('register'), [
            'nik' => '1375010101010001',
            'nama_lengkap' => 'Budi Santoso',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Bukittinggi',
            'email' => 'budi@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'budi@example.com')->firstOrFail();
        $anggota = Anggota::whereBelongsTo($user)->firstOrFail();
        $eKartu = EKartuAnggota::whereBelongsTo($anggota)->firstOrFail();

        $this->assertAuthenticatedAs($user);
        $this->assertSame('Anggota', $user->role->nama_role);
        $this->assertSame('1375010101010001', $anggota->no_anggota);
        $this->assertSame($anggota->nik, $eKartu->no_anggota);
        $response->assertRedirect(route('landing'));
    }

    public function test_nik_dan_email_anggota_harus_unik(): void
    {
        $this->seed();

        $existingUser = User::factory()->create(['email' => 'budi@example.com']);
        Anggota::factory()->for($existingUser, 'user')->create(['nik' => '1375010101010001']);

        $response = $this->from(route('register'))->post(route('register'), [
            'nik' => '1375010101010001',
            'nama_lengkap' => 'Budi Santoso',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Bukittinggi',
            'email' => 'budi@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response
            ->assertSessionHasErrors(['nik', 'email'])
            ->assertRedirect(route('register'));
    }
}
