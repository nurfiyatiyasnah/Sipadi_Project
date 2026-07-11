<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\EKartuAnggota;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RegistrasiAnggotaTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_registrasi_anggota_dapat_ditampilkan(): void
    {
        $response = $this->get(route('register'));

        $response
            ->assertOk()
            ->assertSee('Isi Data Diri')
            ->assertSee('Nama Lengkap')
            ->assertSee('NIK')
            ->assertSee('Lanjut Buat Akun');
    }

    public function test_pengunjung_dapat_mendaftar_sebagai_anggota(): void
    {
        $this->post(route('register.data.store'), [
            'nik' => '1375010101010001',
            'nama_lengkap' => 'Budi Santoso',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Bukittinggi',
            'no_telepon' => '081234567890',
        ])->assertRedirect(route('register.akun'));

        $response = $this->post(route('register.akun.store'), [
            'email' => 'budi@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => '1',
        ]);

        $user = User::where('email', 'budi@example.com')->firstOrFail();
        $anggota = Anggota::whereBelongsTo($user)->firstOrFail();
        $eKartu = EKartuAnggota::whereBelongsTo($anggota)->firstOrFail();

        $this->assertAuthenticatedAs($user);
        $this->assertSame('Anggota', $user->role->nama_role);
        $this->assertSame('1375010101010001', $anggota->no_anggota);
        $this->assertSame($anggota->nik, $eKartu->no_anggota);
        $response
            ->assertSessionHas('status', 'registration-success')
            ->assertRedirect(route('register.e-kartu'));

        $this->get(route('register.e-kartu'))
            ->assertOk()
            ->assertSee('E-Kartu Anggota')
            ->assertSee('E-Kartu Anggota Anda Sudah Aktif');
    }

    public function test_foto_profil_opsional_disimpan_dan_muncul_di_e_kartu(): void
    {
        Storage::fake('public');

        $this->post(route('register.data.store'), [
            'nik' => '1375010101010002',
            'nama_lengkap' => 'Siti Aminah',
            'jenis_kelamin' => 'Perempuan',
            'tanggal_lahir' => '2001-02-02',
            'alamat' => 'Bukittinggi',
            'no_telepon' => '081234567890',
            'foto' => UploadedFile::fake()->createWithContent(
                'siti.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII=')
            ),
        ])->assertRedirect(route('register.akun'));

        $response = $this->post(route('register.akun.store'), [
            'email' => 'siti@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => '1',
        ]);

        $anggota = Anggota::where('nik', '1375010101010002')->firstOrFail();

        $this->assertNotNull($anggota->foto);
        Storage::disk('public')->assertExists($anggota->foto);
        $response->assertRedirect(route('register.e-kartu'));

        $this->get(route('register.e-kartu'))
            ->assertOk()
            ->assertSee('storage/'.$anggota->foto, false);
    }

    public function test_nik_anggota_harus_unik(): void
    {
        $this->seed();

        $existingUser = User::factory()->create(['email' => 'budi@example.com']);
        Anggota::factory()->for($existingUser, 'user')->create(['nik' => '1375010101010001']);

        $response = $this->from(route('register'))->post(route('register.data.store'), [
            'nik' => '1375010101010001',
            'nama_lengkap' => 'Budi Santoso',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Bukittinggi',
            'no_telepon' => '081234567890',
        ]);

        $response
            ->assertSessionHasErrors(['nik'])
            ->assertRedirect(route('register'));
    }

    public function test_error_data_diri_ditampilkan_dengan_jelas(): void
    {
        $existingUser = User::factory()->create(['email' => 'sudahada@example.com']);
        Anggota::factory()->for($existingUser, 'user')->create(['nik' => '1375010101010001']);

        $response = $this
            ->followingRedirects()
            ->from(route('register'))
            ->post(route('register.data.store'), [
                'nik' => '1375010101010001',
                'nama_lengkap' => 'Budi Santoso',
                'jenis_kelamin' => 'Laki-laki',
                'tanggal_lahir' => '2000-01-01',
                'alamat' => 'Bukittinggi',
                'no_telepon' => '081234567890',
            ]);

        $response
            ->assertOk()
            ->assertSee('Data diri belum bisa dilanjutkan.')
            ->assertSee('NIK ini sudah terdaftar.');
    }

    public function test_email_akun_harus_unik(): void
    {
        User::factory()->create(['email' => 'budi@example.com']);

        $this->post(route('register.data.store'), [
            'nik' => '1375010101010003',
            'nama_lengkap' => 'Budi Santoso',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Bukittinggi',
            'no_telepon' => '081234567890',
        ])->assertRedirect(route('register.akun'));

        $response = $this->from(route('register.akun'))->post(route('register.akun.store'), [
            'email' => 'budi@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => '1',
        ]);

        $response
            ->assertSessionHasErrors(['email'])
            ->assertRedirect(route('register.akun'));
    }

    public function test_registrasi_dapat_dilanjutkan_setelah_kegagalan_transaksi_atau_validasi_akun(): void
    {
        Storage::fake('public');

        // Step 1: Upload foto profil
        $this->post(route('register.data.store'), [
            'nik' => '1375010101010004',
            'nama_lengkap' => 'Test Gagal',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Bukittinggi',
            'no_telepon' => '081234567890',
            'foto' => UploadedFile::fake()->create('profil.png', 100),
        ])->assertRedirect(route('register.akun'));

        $fotoTempPath = session('registration.foto_path');
        $this->assertNotNull($fotoTempPath);
        Storage::disk('public')->assertExists($fotoTempPath);

        // Buat email yang bertabrakan untuk memicu kegagalan validasi / keunikan email di Step 2
        User::factory()->create(['email' => 'tabrakan@example.com']);

        // Step 2: Coba buat akun dengan email yang sudah ada (akan gagal)
        $response = $this->from(route('register.akun'))->post(route('register.akun.store'), [
            'email' => 'tabrakan@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => '1',
        ]);

        $response->assertSessionHasErrors(['email']);
        $response->assertRedirect(route('register.akun'));

        // Pastikan file temporer MASIH ADA setelah kegagalan
        Storage::disk('public')->assertExists($fotoTempPath);

        // Step 2: Coba lagi dengan email yang valid dan unik (harus berhasil)
        $response = $this->post(route('register.akun.store'), [
            'email' => 'sukses@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('register.e-kartu'));

        // Cek database
        $anggota = Anggota::where('nik', '1375010101010004')->firstOrFail();
        $this->assertNotNull($anggota->foto);

        // Pastikan file di folder permanen ADA, dan file temporer TELAH DIHAPUS
        Storage::disk('public')->assertExists($anggota->foto);
        Storage::disk('public')->assertMissing($fotoTempPath);
    }
}
