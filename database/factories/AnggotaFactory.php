<?php

namespace Database\Factories;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Anggota>
 */
class AnggotaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nik = fake()->unique()->numerify('################');

        return [
            'id_user' => User::factory(),
            'no_anggota' => $nik,
            'nik' => $nik,
            'nama_lengkap' => fake()->name(),
            'jenis_kelamin' => fake()->randomElement(['Laki-laki', 'Perempuan']),
            'tanggal_lahir' => fake()->dateTimeBetween('-60 years', '-15 years'),
            'alamat' => fake()->address(),
            'no_telepon' => fake()->phoneNumber(),
            'tanggal_daftar' => now()->toDateString(),
            'status_anggota' => 'aktif',
        ];
    }
}
