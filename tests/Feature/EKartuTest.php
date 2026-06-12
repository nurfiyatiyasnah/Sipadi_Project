<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\EKartuAnggota;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EKartuTest extends TestCase
{
    use RefreshDatabase;

    public function test_anggota_dapat_melihat_e_kartu(): void
    {
        [$user, $anggota, $eKartu] = $this->createAnggotaWithCard();

        $response = $this->actingAs($user)->get(route('anggota.e-kartu'));

        $response
            ->assertOk()
            ->assertSee($anggota->nama_lengkap)
            ->assertSee($eKartu->no_anggota);
    }

    public function test_anggota_dapat_mengunduh_e_kartu_sebagai_pdf(): void
    {
        [$user, $anggota] = $this->createAnggotaWithCard();

        $response = $this->actingAs($user)->get(route('anggota.e-kartu.download'));

        $response
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf')
            ->assertDownload("e-kartu-{$anggota->no_anggota}.pdf");
    }

    public function test_petugas_tidak_dapat_mengakses_e_kartu_anggota(): void
    {
        $role = Role::create(['nama_role' => 'Petugas']);
        $petugas = User::factory()->create(['id_role' => $role->id_role]);

        $this->actingAs($petugas)
            ->get(route('anggota.e-kartu'))
            ->assertForbidden();
    }

    /**
     * @return array{User, Anggota, EKartuAnggota}
     */
    private function createAnggotaWithCard(): array
    {
        $role = Role::create(['nama_role' => 'Anggota']);
        $user = User::factory()->create(['id_role' => $role->id_role]);
        $anggota = Anggota::factory()->for($user, 'user')->create();
        $eKartu = EKartuAnggota::factory()->for($anggota, 'anggota')->create([
            'no_anggota' => $anggota->no_anggota,
        ]);

        return [$user, $anggota, $eKartu];
    }
}
