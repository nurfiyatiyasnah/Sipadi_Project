<?php

namespace Database\Factories;

use App\Models\Buku;
use App\Models\EksemplarBuku;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<EksemplarBuku>
 */
class EksemplarBukuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_buku' => Buku::factory(),
            'kode_eksemplar' => 'EKS-'.Str::upper(fake()->unique()->bothify('??####')),
            'status_eksemplar' => EksemplarBuku::STATUS_TERSEDIA,
            'kondisi_eksemplar' => 'baik',
            'lokasi_rak' => 'Rak '.fake()->randomLetter(),
            'tanggal_masuk' => fake()->dateTimeBetween('-5 years', 'now'),
        ];
    }
}
