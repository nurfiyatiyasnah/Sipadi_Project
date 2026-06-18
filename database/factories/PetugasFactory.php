<?php

namespace Database\Factories;

use App\Models\Petugas;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Petugas>
 */
class PetugasFactory extends Factory
{
    protected $model = Petugas::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => User::factory(),
            'nama_petugas' => fake()->name(),
            'jabatan' => 'Petugas Perpustakaan',
            'no_hp' => fake()->phoneNumber(),
        ];
    }
}
