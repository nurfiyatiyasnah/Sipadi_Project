<?php

namespace Tests\Feature\Auth;

use App\Models\Anggota;
use App\Models\EKartuAnggota;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $this->seed();

        $response = $this->post('/register', [
            'nik' => '1375010101010001',
            'nama_lengkap' => 'Test User',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Bukittinggi',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'test@example.com')->firstOrFail();
        $anggota = Anggota::whereBelongsTo($user)->firstOrFail();

        $this->assertAuthenticated();
        $this->assertModelExists(EKartuAnggota::whereBelongsTo($anggota)->firstOrFail());
        $response->assertRedirect(route('landing', absolute: false));
    }
}
