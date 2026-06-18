<?php

namespace Database\Factories;

use App\Models\KategoriBerita;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KategoriBerita>
 */
class KategoriBeritaFactory extends Factory
{
    protected $model = KategoriBerita::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_kategori' => fake()->unique()->word(),
            'deskripsi' => fake()->sentence(),
        ];
    }
}
