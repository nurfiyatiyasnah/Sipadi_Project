<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed default roles
        $adminRole = Role::create(['nama_role' => 'Admin', 'deskripsi' => 'Administrator sistem']);
        Role::create(['nama_role' => 'Anggota', 'deskripsi' => 'Anggota perpustakaan']);
        Role::create(['nama_role' => 'Petugas', 'deskripsi' => 'Petugas perpustakaan']);

        // Seed admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@sipadi.test',
            'password' => Hash::make('password'),
            'id_role' => $adminRole->id,
        ]);
    }
}
