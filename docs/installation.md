## Installation Documentation
Dokumen ini menjelaskan langkah instalasi proyek SIPADI - Sistem Informasi Perpustakaan Digital pada lingkungan lokal.

## Persyaratan Sistem
* PHP 8.2 atau lebih baru (mendukung Laravel 13)
* Composer
* Node.js dan NPM (opsional, jika menggunakan Vite/Tailwind untuk frontend)
* PostgreSQL (pgsql)
* Git
* Web browser modern
* Terminal atau PowerShell

## Clone Repository
bash
    git clone https://github.com/nurfiyatiyasnah/Sipadi_Project.git
    cd Sipadi_Project
## Install Dependency Backend
bash
    composer install

Perintah ini membaca file composer.json dan memasang seluruh dependency PHP yang dibutuhkan aplikasi.

## Setup Environment
Salin file environment contoh:
bash
    cp .env.example .env

Pada Windows PowerShell:
    powershell
    Copy-Item .env.example .env

Generate application key:
bash
    php artisan key:generate
    
## Setup Database
Buat database PostgreSQL melalui pgAdmin atau terminal psql.
Contoh nama database:
Code
    sipadi

Konfigurasi file .env:
    env
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=sipadi
    DB_USERNAME=postgres
    DB_PASSWORD=

Jalankan migration dan seeder:
bash
php artisan migrate --seed
## Menjalankan Aplikasi
bash
    php artisan serve

Aplikasi akan berjalan pada:
Code
    http://127.0.0.1:8000

## Menjalankan Test
bash
    php artisan test
Proyek menggunakan Pest PHP sebagai framework testing (berjalan di atas PHPUnit).

## Kredensial dan Akses Default
Sistem SIPADI menggunakan Role-Based Access Control.
Saat migrate --seed dijalankan, sistem membuat akun dummy untuk pengujian.
* Pengunjung → Beranda, katalog buku, berita, agenda, input aduan
* Anggota → Login, peminjaman, riwayat, pengembalian, notifikasi
* Admin → Kelola data anggota, buku, agenda, berita, aduan, profil, statistik
Catatan Developer: Periksa DatabaseSeeder.php untuk email & password default.

# Troubleshooting
* APP_KEY Belum Dibuat
Gejala:

    Code
        No application encryption key has been specified.

    Solusi:
    bash
        php artisan key:generate
* Database Belum Tersedia
    Gejala:
    Code
        SQLSTATE[08006] [7] FATAL: database "sipadi" does not exist

    Solusi:
    * Buat database sesuai nilai DB_DATABASE
    * Periksa DB_USERNAME dan DB_PASSWORD

    Jalankan ulang migration:
    bash
        php artisan migrate --seed
    Cache Konfigurasi Bermasalah

    bash
        php artisan optimize:clear
    Permission Storage Bermasalah (Linux/macOS)

    bash
        chmod -R 775 storage bootstrap/cache
    Pada Windows, pastikan folder storage dan bootstrap/cache memiliki izin tulis.

## Verifikasi Instalasi
Instalasi dianggap berhasil apabila:
* Halaman login SIPADI dapat diakses
* Hak akses Admin, Anggota, dan Pengunjung berfungsi
* Migration & Seeder berhasil dijalankan
* Seluruh test berjalan tanpa kegagalan

## Ringkasan Instalasi Cepat
bash
    git clone https://github.com/nurfiyatiyasnah/Sipadi_Project.git
    cd Sipadi_Project
    composer install
    cp .env.example .env
    php artisan key:generate
    php artisan migrate --seed
    php artisan serve