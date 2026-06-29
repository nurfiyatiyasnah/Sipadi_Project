<?php

namespace Database\Seeders;

use App\Models\AgendaEvent;
use App\Models\Berita;
use App\Models\KategoriBerita;
use App\Models\Petugas;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roleAnggota = Role::updateOrCreate(
            ['nama_role' => 'Anggota'],
            ['deskripsi' => 'Pengguna umum yang dapat mengakses layanan perpustakaan']
        );

        $rolePetugas = Role::updateOrCreate(
            ['nama_role' => 'Petugas'],
            ['deskripsi' => 'Pengelola sistem perpustakaan']
        );

        $petugasUser = User::updateOrCreate(
            ['email' => 'petugas@sipadi.test'],
            [
                'id_role' => $rolePetugas->id_role,
                'password' => 'password',
                'status_akun' => 'aktif',
            ]
        );

        $petugas = Petugas::updateOrCreate(
            ['id_user' => $petugasUser->id_user],
            [
                'nama_petugas' => 'Petugas SIPADI',
                'jabatan' => 'Petugas Perpustakaan',
                'no_hp' => null,
            ]
        );

        // Seed KategoriBerita
        $kategoriKegiatan = KategoriBerita::updateOrCreate(
            ['nama_kategori' => 'Kegiatan'],
            ['deskripsi' => 'Informasi tentang kegiatan di lingkungan perpustakaan']
        );

        $kategoriPengumuman = KategoriBerita::updateOrCreate(
            ['nama_kategori' => 'Pengumuman'],
            ['deskripsi' => 'Pengumuman resmi dari pengelola perpustakaan']
        );

        $kategoriArtikel = KategoriBerita::updateOrCreate(
            ['nama_kategori' => 'Artikel'],
            ['deskripsi' => 'Artikel bermanfaat seputar literasi dan perpustakaan']
        );

        // Seed sample Berita if empty
        if (Berita::count() === 0) {
            Berita::factory()->count(5)->published()->create([
                'id_petugas' => $petugas->id_petugas,
                'id_kategori_berita' => $kategoriKegiatan->id_kategori_berita,
            ]);

            Berita::factory()->count(3)->published()->create([
                'id_petugas' => $petugas->id_petugas,
                'id_kategori_berita' => $kategoriPengumuman->id_kategori_berita,
            ]);

            Berita::factory()->count(2)->draft()->create([
                'id_petugas' => $petugas->id_petugas,
                'id_kategori_berita' => $kategoriArtikel->id_kategori_berita,
            ]);
        }

        if (AgendaEvent::count() === 0) {
            AgendaEvent::create([
                'judul_event' => 'Webinar Literasi Digital 2024',
                'slug' => 'webinar-literasi-digital-2024',
                'deskripsi' => 'Seminar nasional yang mengupas tuntas tentang cara bijak ber-literasi digital di era kecerdasan buatan (AI) serta tips menghindari informasi palsu.',
                'lokasi' => 'Zoom Meeting (Online)',
                'tanggal_mulai' => '2024-11-15',
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '12:00:00',
                'status_event' => 'terbit',
                'kategori' => 'Seminar Nasional',
                'tampilkan_beranda' => true,
                'created_by' => $petugas->id_petugas,
            ]);

            AgendaEvent::create([
                'judul_event' => 'Bedah Buku: Sejarah Kearsipan',
                'slug' => 'bedah-buku-sejarah-kearsipan',
                'deskripsi' => 'Diskusi internal bedah buku sejarah kearsipan bersama pustakawan dan pengamat sejarah lokal.',
                'lokasi' => 'Ruang Serbaguna Lt. 2',
                'tanggal_mulai' => '2024-11-20',
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '15:00:00',
                'status_event' => 'draft',
                'kategori' => 'Diskusi Internal',
                'tampilkan_beranda' => false,
                'created_by' => $petugas->id_petugas,
            ]);

            AgendaEvent::create([
                'judul_event' => 'Pelatihan Manajemen Referensi (Mendeley)',
                'slug' => 'pelatihan-manajemen-referensi-mendeley',
                'deskripsi' => 'Workshop praktis penggunaan software Mendeley untuk mempermudah sitasi dan penyusunan daftar pustaka bagi mahasiswa.',
                'lokasi' => 'Lab Komputer Perpustakaan',
                'tanggal_mulai' => '2024-12-05',
                'jam_mulai' => '08:30:00',
                'jam_selesai' => '16:00:00',
                'status_event' => 'draft',
                'kategori' => 'Workshop Mahasiswa',
                'tampilkan_beranda' => false,
                'created_by' => $petugas->id_petugas,
            ]);
        }

        $this->call([
            KatalogSeeder::class,
        ]);
    }
}
