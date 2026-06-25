<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\EksemplarBuku;
use App\Models\KategoriBuku;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (KategoriBuku::count() === 0) {
            $kats = [
                'Sejarah Minangkabau' => 'Buku-buku tentang sejarah Minangkabau dan Sumatera Barat.',
                'Sastra & Budaya' => 'Karya Sastra, Seni, dan Kebudayaan Minangkabau.',
                'Pendidikan' => 'Buku pelajaran, modul ajar, dan referensi akademis.',
                'Agama' => 'Buku-buku keagamaan dan teologi.',
                'Fiksi' => 'Novel, cerpen, dan karya sastra fiksi modern.',
            ];

            foreach ($kats as $name => $desc) {
                KategoriBuku::create([
                    'nama_kategori' => $name,
                    'deskripsi' => $desc,
                ]);
            }
        }

        if (Buku::count() === 0) {
            $sastra = KategoriBuku::where('nama_kategori', 'Sastra & Budaya')->first();
            $sejarah = KategoriBuku::where('nama_kategori', 'Sejarah Minangkabau')->first();
            $fiksi = KategoriBuku::where('nama_kategori', 'Fiksi')->first();

            $booksData = [
                [
                    'judul' => 'Tenggelamnya Kapal Van der Wijck',
                    'penulis' => 'Buya Hamka',
                    'penerbit' => 'Balai Pustaka',
                    'tahun_terbit' => 1938,
                    'isbn' => '978-602-250-137-4',
                    'deskripsi' => 'Tenggelamnya Kapal Van der Wijck mengisahkan kisah cinta yang memilukan antara Zainuddin, seorang pemuda berdarah campuran Minang-Makassar, dan Hayati, seorang gadis bangsawan Minang yang jelita. Cinta mereka terbentur oleh adat Minangkabau yang kokoh dan menolak orang luar.',
                    'gambar_cover' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=600',
                    'kategori' => $sastra,
                    'copies' => [
                        ['status' => 'Tersedia', 'rak' => 'Lantai 2 - Rak S-01'],
                        ['status' => 'Tersedia', 'rak' => 'Lantai 2 - Rak S-01'],
                    ],
                ],
                [
                    'judul' => 'Arsitektur Tradisional Minangkabau',
                    'penulis' => 'Dr. Syamsul Asri',
                    'penerbit' => 'Erlangga',
                    'tahun_terbit' => 2015,
                    'isbn' => '978-602-432-111-0',
                    'deskripsi' => 'Buku ini mengulas secara mendalam arsitektur tradisional Minangkabau, mulai dari falsafah Rumah Gadang, konstruksi unik tahan gempa, pembagian ruang dalam adat, hingga ragam hias ukiran kayu yang memiliki arti filosofis mendalam.',
                    'gambar_cover' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=600',
                    'kategori' => $sastra,
                    'copies' => [
                        ['status' => 'Dipinjam', 'rak' => 'Lantai 2 - Rak A-05'],
                    ],
                ],
                [
                    'judul' => 'Sejarah Bukittinggi: Kota Perjuangan',
                    'penulis' => 'Mestika Zed',
                    'penerbit' => 'UNP Press',
                    'tahun_terbit' => 2008,
                    'isbn' => '978-979-3786-25-4',
                    'deskripsi' => 'Buku sejarah yang komprehensif menguraikan perkembangan Kota Bukittinggi sejak masa kolonial Belanda, pendudukan Jepang, masa revolusi kemerdekaan, hingga perannya yang vital sebagai Ibu Kota Negara Darurat RI (PDRI).',
                    'gambar_cover' => 'https://images.unsplash.com/photo-1505664194779-8bebcb3f9e2e?q=80&w=600',
                    'kategori' => $sejarah,
                    'copies' => [
                        ['status' => 'Tersedia', 'rak' => 'Lantai 1 - Rak B-12'],
                        ['status' => 'Tersedia', 'rak' => 'Lantai 1 - Rak B-12'],
                    ],
                ],
                [
                    'judul' => 'Kuliner Khas Minangkabau',
                    'penulis' => 'Reno Andam Suri',
                    'penerbit' => 'Gramedia Pustaka Utama',
                    'tahun_terbit' => 2012,
                    'isbn' => '978-979-22-8504-8',
                    'deskripsi' => 'Sebuah catatan kuliner yang mendokumentasikan kekayaan bumbu, bahan pangan tradisional, sejarah masakan, resep rahasia randang, gulai, pangek, dan cerita budaya yang melatarbelakangi kelezatan masakan Minangkabau.',
                    'gambar_cover' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=600',
                    'kategori' => $sastra,
                    'copies' => [
                        ['status' => 'Tersedia', 'rak' => 'Lantai 1 - Rak K-02'],
                    ],
                ],
                [
                    'judul' => 'Laskar Pelangi',
                    'penulis' => 'Andrea Hirata',
                    'penerbit' => 'Bentang Pustaka',
                    'tahun_terbit' => 2005,
                    'isbn' => '978-979-3062-79-2',
                    'deskripsi' => 'Laskar Pelangi menceritakan kisah inspiratif perjuangan sepuluh anak di Pulau Belitung (Laskar Pelangi) untuk mendapatkan pendidikan di sekolah Muhammadiyah yang serba kekurangan. Penuh dengan keindahan persahabatan, ketulusan guru, dan impian masa kecil.',
                    'gambar_cover' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?q=80&w=600',
                    'kategori' => $fiksi,
                    'copies' => [
                        ['status' => 'Tersedia', 'rak' => 'Lantai 2 - Rak A-12'],
                    ],
                ],
                [
                    'judul' => 'Sang Pemimpi',
                    'penulis' => 'Andrea Hirata',
                    'penerbit' => 'Bentang Pustaka',
                    'tahun_terbit' => 2006,
                    'isbn' => '978-979-3062-92-1',
                    'deskripsi' => 'Buku kedua dari tetralogi Laskar Pelangi. Mengisahkan Ikal, Arai, dan Jimbron dalam mengarungi masa remaja di SMA, bekerja keras menjadi kuli pelabuhan demi mewujudkan mimpi kuliah di Sorbonne, Paris, Prancis.',
                    'gambar_cover' => 'https://images.unsplash.com/photo-1532012197267-da84d127e765?q=80&w=600',
                    'kategori' => $fiksi,
                    'copies' => [
                        ['status' => 'Tersedia', 'rak' => 'Lantai 2 - Rak A-12'],
                    ],
                ],
                [
                    'judul' => 'Bumi Manusia',
                    'penulis' => 'Pramoedya Ananta Toer',
                    'penerbit' => 'Hasta Mitra',
                    'tahun_terbit' => 1980,
                    'isbn' => '978-979-97312-3-4',
                    'deskripsi' => 'Sebuah adikarya sastra Indonesia yang mengambil latar akhir masa kolonial Belanda. Mengisahkan roman percintaan Minke, seorang pemuda pribumi terpelajar, dan Annelies Mellema, seorang gadis indo, di tengah kungkungan hukum kolonial.',
                    'gambar_cover' => 'https://images.unsplash.com/photo-1476275466078-4007374efbbe?q=80&w=600',
                    'kategori' => $fiksi,
                    'copies' => [
                        ['status' => 'Dipinjam', 'rak' => 'Lantai 2 - Rak F-08'],
                    ],
                ],
                [
                    'judul' => 'Hujan Bulan Juni',
                    'penulis' => 'Sapardi Djoko Damono',
                    'penerbit' => 'Gramedia Pustaka Utama',
                    'tahun_terbit' => 1994,
                    'isbn' => '978-979-22-9850-5',
                    'deskripsi' => 'Kumpulan puisi cinta legendaris karya Sapardi Djoko Damono yang sangat menyentuh hati, menggambarkan kesabaran, kerinduan, ketabahan cinta yang tak terungkapkan.',
                    'gambar_cover' => 'https://images.unsplash.com/photo-1518495973542-4542c06a5843?q=80&w=600',
                    'kategori' => $fiksi,
                    'copies' => [
                        ['status' => 'Tersedia', 'rak' => 'Lantai 2 - Rak F-10'],
                    ],
                ],
                [
                    'judul' => 'Negeri 5 Menara',
                    'penulis' => 'A. Fuadi',
                    'penerbit' => 'Gramedia Pustaka Utama',
                    'tahun_terbit' => 2009,
                    'isbn' => '978-979-22-4845-6',
                    'deskripsi' => 'Kisah petualangan Alif dan lima sahabatnya di Pondok Madani, sebuah pesantren di Jawa Timur. Mereka dipersatukan oleh mimpi di bawah menara masjid, meyakini mantra sakti "Man Jadda Wajada" yang akan mengantarkan mereka keliling dunia.',
                    'gambar_cover' => 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=600',
                    'kategori' => $fiksi,
                    'copies' => [
                        ['status' => 'Tersedia', 'rak' => 'Lantai 2 - Rak F-15'],
                    ],
                ],
            ];

            foreach ($booksData as $b) {
                if (! $b['kategori']) {
                    continue;
                }
                $buku = Buku::create([
                    'id_kategori' => $b['kategori']->id_kategori,
                    'kode_buku' => 'BKU-'.Str::upper(Str::random(6)),
                    'isbn' => $b['isbn'],
                    'judul' => $b['judul'],
                    'penulis' => $b['penulis'],
                    'penerbit' => $b['penerbit'],
                    'tahun_terbit' => $b['tahun_terbit'],
                    'deskripsi' => $b['deskripsi'],
                    'gambar_cover' => $b['gambar_cover'],
                    'status_katalog' => 'Aktif',
                ]);

                foreach ($b['copies'] as $index => $c) {
                    EksemplarBuku::create([
                        'id_buku' => $buku->id_buku,
                        'kode_eksemplar' => 'EKS-'.Str::upper(Str::random(6)).'-'.($index + 1),
                        'status_eksemplar' => $c['status'],
                        'kondisi_eksemplar' => 'Baik',
                        'lokasi_rak' => $c['rak'],
                        'tanggal_masuk' => now(),
                    ]);
                }
            }
        }
    }
}
