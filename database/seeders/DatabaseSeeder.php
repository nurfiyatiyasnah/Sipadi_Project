<?php

namespace Database\Seeders;

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

        Petugas::updateOrCreate(
            ['id_user' => $petugasUser->id_user],
            [
                'nama_petugas' => 'Petugas SIPADI',
                'jabatan' => 'Petugas Perpustakaan',
                'no_hp' => null,
            ]
        );
    }
}
