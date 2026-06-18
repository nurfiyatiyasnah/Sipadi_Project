<?php

namespace Database\Factories;

use App\Models\Berita;
use App\Models\KategoriBerita;
use App\Models\Petugas;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Berita>
 */
class BeritaFactory extends Factory
{
    protected $model = Berita::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $judul = fake()->sentence(6);

        return [
            'id_kategori_berita' => KategoriBerita::factory(),
            'id_petugas' => Petugas::factory(),
            'judul' => $judul,
            'slug' => Str::slug($judul).'-'.Str::random(5),
            'isi' => fake()->paragraphs(3, true),
            'gambar' => null,
            'tanggal_terbit' => null,
            'status_berita' => 'draft',
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_berita' => 'terbit',
            'tanggal_terbit' => now(),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_berita' => 'draft',
            'tanggal_terbit' => null,
        ]);
    }
}
