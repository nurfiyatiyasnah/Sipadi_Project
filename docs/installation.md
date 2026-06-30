# Panduan Instalasi SIPADI

Dokumen ini menjelaskan cara menyiapkan SIPADI (Sistem Informasi Perpustakaan Digital) di komputer lokal dari awal sampai aplikasi bisa dibuka di browser.

## Prasyarat

Pastikan perangkat sudah memiliki software berikut:

| Kebutuhan | Versi/Status yang Disarankan |
| --- | --- |
| PHP | 8.2 atau lebih baru |
| Composer | 2.x |
| Node.js | 20 atau lebih baru |
| NPM | 10 atau lebih baru |
| PostgreSQL | 14 atau lebih baru |
| Git | Versi stabil terbaru |

Extension PHP yang perlu tersedia:

- `pdo_pgsql`
- `pgsql`
- `pdo_sqlite`
- `fileinfo`
- `mbstring`
- `openssl`
- `xml`
- `xmlwriter`
- `zip`
- `curl`

Untuk mengecek requirement PHP berdasarkan package yang sudah terpasang:

```bash
composer check-platform-reqs
```

## Clone Repository

Clone repository dari GitHub, lalu masuk ke folder proyek.

```bash
git clone <url-repository-sipadi>
cd Sipadi_Project
```

Jika path folder memiliki spasi, gunakan tanda kutip saat menjalankan command dari luar folder proyek.

Contoh PowerShell:

```powershell
cd "C:\Kuliah\Pembelajaran Semester 4\Sipadi_Project"
```

## Instalasi Manual

Cara manual lebih aman untuk setup pertama karena konfigurasi database bisa dicek sebelum migration dijalankan.

### 1. Install Dependency PHP

```bash
composer install
```

Command ini akan membuat folder `vendor/` dan menjalankan package discovery Laravel.

### 2. Install Dependency Frontend

```bash
npm install
```

Command ini akan membuat folder `node_modules/`.

### 3. Buat File Environment

Windows CMD:

```bat
copy .env.example .env
```

PowerShell:

```powershell
Copy-Item .env.example .env
```

Git Bash, Linux, atau macOS:

```bash
cp .env.example .env
```

File `.env` bersifat lokal dan tidak boleh di-commit.

### 4. Generate Application Key

```bash
php artisan key:generate
```

Pastikan nilai `APP_KEY` di `.env` sudah terisi setelah command ini selesai.

### 5. Buat Database PostgreSQL

Buat database baru untuk SIPADI. Nama default yang digunakan proyek ini adalah:

```text
sipadi
```

Contoh memakai `psql`:

```bash
psql -U postgres -c "CREATE DATABASE sipadi;"
```

Database juga bisa dibuat melalui pgAdmin, DBeaver, TablePlus, atau tool PostgreSQL lain.

### 6. Konfigurasi Database di `.env`

Sesuaikan bagian database dengan kredensial PostgreSQL lokal.

Contoh:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sipadi
DB_USERNAME=postgres
DB_PASSWORD=password_database_lokal
```

Jika PostgreSQL lokal memakai user `root` tanpa password seperti `.env.example`, bagian ini boleh disesuaikan kembali:

```env
DB_USERNAME=root
DB_PASSWORD=
```

### 7. Konfigurasi Driver Lokal

Untuk setup lokal yang stabil, gunakan konfigurasi berikut:

```env
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=file
MAIL_MAILER=log
FILESYSTEM_DISK=local
```

Catatan: `.env.example` saat ini memakai `CACHE_STORE=database`, tetapi migration `cache` dan `cache_locks` belum tersedia di proyek. Gunakan `CACHE_STORE=file` untuk instalasi lokal kecuali tabel cache database memang sudah ditambahkan.

### 8. Jalankan Migration dan Seeder

Jalankan migration untuk membuat tabel aplikasi.

```bash
php artisan migrate
```

Isi data awal dengan seeder.

```bash
php artisan db:seed
```

Atau jalankan sekaligus:

```bash
php artisan migrate --seed
```

Seeder akan membuat role dasar, aturan peminjaman default, akun petugas, kategori berita, contoh berita, contoh agenda, kategori buku, dan contoh katalog.

Akun petugas default dari seeder:

```text
Email: petugas@sipadi.test
Password: password
```

### 9. Buat Storage Link

```bash
php artisan storage:link
```

Command ini membuat link `public/storage` ke `storage/app/public`, sehingga file upload dapat diakses dari browser.

### 10. Build Asset Frontend

Untuk development dengan hot reload:

```bash
npm run dev
```

Untuk build asset production:

```bash
npm run build
```

## Menjalankan Aplikasi

Ada dua cara menjalankan aplikasi saat development.

### Opsi A: Jalankan Semua Proses Sekaligus

```bash
composer run dev
```

Script ini menjalankan tiga proses:

- `php artisan serve`
- `php artisan queue:listen --tries=1`
- `npm run dev`

Gunakan opsi ini saat mengembangkan fitur yang membutuhkan server Laravel, queue listener, dan Vite.

### Opsi B: Jalankan Manual di Terminal Terpisah

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
php artisan queue:listen --tries=1
```

Terminal 3:

```bash
npm run dev
```

Buka aplikasi di browser:

```text
http://127.0.0.1:8000
```

## Shortcut Setup Composer

Proyek menyediakan script:

```bash
composer run setup
```

Script ini menjalankan:

- `composer install`
- membuat `.env` dari `.env.example` jika belum ada
- `php artisan key:generate`
- `php artisan migrate --force`
- `npm install`
- `npm run build`

Gunakan shortcut ini hanya jika database PostgreSQL dan `.env.example` sudah sesuai dengan environment lokal. Untuk setup pertama, cara manual tetap lebih aman karena migration membutuhkan database yang sudah dibuat dan kredensial yang benar.

## Menjalankan Test

Testing memakai PHPUnit. Konfigurasi test di `phpunit.xml` memakai SQLite in-memory, cache array, queue sync, session array, dan mail array.

Jalankan seluruh test:

```bash
php artisan test --compact
```

Jalankan satu file test:

```bash
php artisan test --compact tests/Feature/ExampleTest.php
```

Jalankan test berdasarkan filter:

```bash
php artisan test --compact --filter=namaTest
```

## Formatting Kode PHP

Jika mengubah kode PHP, jalankan Laravel Pint:

```bash
vendor/bin/pint --dirty --format agent
```

Untuk perubahan dokumentasi Markdown saja, Pint dan PHPUnit tidak wajib dijalankan.

## Command Verifikasi Setelah Instalasi

Gunakan command berikut untuk mengecek kondisi aplikasi:

```bash
php artisan about
```

```bash
php artisan migrate:status
```

```bash
php artisan route:list --except-vendor
```

Hasil yang diharapkan:

- Laravel terbaca sebagai versi 12.x.
- Database menggunakan `pgsql`.
- Queue menggunakan `database`.
- Session menggunakan `database`.
- `public/storage` berstatus linked setelah `storage:link`.
- Migration utama berstatus `Ran`.

## Troubleshooting

### `could not find driver`

Penyebab umum: extension PostgreSQL PHP belum aktif.

Solusi:

- Aktifkan `pdo_pgsql` dan `pgsql` di konfigurasi PHP.
- Pastikan PHP CLI yang dipakai terminal sama dengan PHP yang sudah dikonfigurasi.
- Jalankan `php -m` untuk mengecek daftar extension aktif.

### `SQLSTATE[08006]` atau gagal koneksi PostgreSQL

Penyebab umum:

- PostgreSQL belum berjalan.
- Database `sipadi` belum dibuat.
- `DB_USERNAME` atau `DB_PASSWORD` salah.
- Port PostgreSQL bukan `5432`.

Solusi:

- Cek service PostgreSQL.
- Cek ulang konfigurasi `DB_*` di `.env`.
- Coba login ke database dengan `psql`, pgAdmin, atau DBeaver.

### `Base table or view not found: cache`

Penyebab umum: `CACHE_STORE=database`, tetapi tabel cache belum tersedia.

Solusi lokal paling cepat:

```env
CACHE_STORE=file
```

Lalu bersihkan cache konfigurasi:

```bash
php artisan config:clear
```

### `Vite manifest not found` atau asset tidak muncul

Solusi development:

```bash
npm run dev
```

Solusi production/local build:

```bash
npm run build
```

### Gambar upload tidak tampil

Pastikan storage link sudah dibuat.

```bash
php artisan storage:link
```

Jika link sudah ada tetapi bermasalah, hapus link lama secara hati-hati lalu jalankan ulang command tersebut.

### Perubahan `.env` belum terbaca

Bersihkan cache konfigurasi:

```bash
php artisan config:clear
```

Jika menjalankan server, hentikan lalu jalankan ulang server.

## File dan Folder yang Dihasilkan

Setelah instalasi, folder/file berikut biasanya muncul atau berubah:

| Path | Sumber | Keterangan |
| --- | --- | --- |
| `.env` | Salinan `.env.example` | Konfigurasi lokal, tidak di-commit. |
| `vendor/` | `composer install` | Dependency PHP. |
| `node_modules/` | `npm install` | Dependency frontend. |
| `public/build/` | `npm run build` | Asset hasil build Vite. |
| `public/storage` | `php artisan storage:link` | Link publik ke storage upload. |
| `storage/logs/` | Runtime Laravel | Log aplikasi. |
| `storage/framework/` | Runtime Laravel | Cache, session, dan file framework. |

Folder `vendor/`, `node_modules/`, `public/build/`, `public/storage`, dan file `.env` sudah masuk `.gitignore`.

## Ringkasan Cepat

Untuk setup manual dari awal:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```

Jika memakai Windows CMD, ganti command `cp .env.example .env` dengan:

```bat
copy .env.example .env
```

Jika memakai PowerShell, gunakan:

```powershell
Copy-Item .env.example .env
```
