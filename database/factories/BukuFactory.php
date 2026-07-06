<?php

namespace Database\Factories;

use App\Models\Buku;
use App\Models\KategoriBuku;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Buku>
 */
class BukuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_kategori' => KategoriBuku::factory(),
            'kode_buku' => 'BKU-'.Str::upper(fake()->unique()->bothify('??####')),
            'isbn' => fake()->unique()->isbn13(),
            'judul' => fake()->sentence(4),
            'penulis' => fake()->name(),
            'penerbit' => fake()->company(),
            'tahun_terbit' => fake()->numberBetween(1980, (int) now()->format('Y')),
            'deskripsi' => fake()->paragraph(),
            'status_katalog' => 'aktif',
        ];
    }
}
