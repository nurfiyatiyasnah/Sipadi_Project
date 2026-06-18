<?php

namespace Database\Seeders;

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
    }
}
