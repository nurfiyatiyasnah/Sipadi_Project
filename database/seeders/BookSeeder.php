<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        Book::create([
            'judul' => 'Sejarah Bukittinggi: Kota Pusaka',
            'isbn' => '978-602-1234-56-7',
            'penulis' => 'Dr. Ahmad Faisal',
            'kategori' => 'Sejarah Kota',
            'stok' => 12,
            'status' => 'Tersedia',
        ]);

        Book::create([
            'judul' => 'Arsitektur Tradisional Minang',
            'isbn' => '978-602-9876-54-3',
            'penulis' => 'Putri Indah, M.Arch',
            'kategori' => 'Budaya Minang',
            'stok' => 5,
            'status' => 'Dipinjam',
        ]);
    }
}
