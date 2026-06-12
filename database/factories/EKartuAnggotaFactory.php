<?php

namespace Database\Factories;

use App\Models\Anggota;
use App\Models\EKartuAnggota;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<EKartuAnggota>
 */
class EKartuAnggotaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_anggota' => Anggota::factory(),
            'no_anggota' => 'AGT-'.now()->format('Ymd').'-'.Str::upper(fake()->unique()->bothify('??####')),
            'kalangan' => config('sipadi.keanggotaan.kalangan_default'),
            'barcode' => (string) Str::uuid(),
            'masa_berlaku' => now()->addYears((int) config('sipadi.keanggotaan.masa_berlaku_tahun')),
        ];
    }
}
