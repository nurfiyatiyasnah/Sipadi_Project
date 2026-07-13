# GitHub Actions

Dokumen ini menjelaskan workflow GitHub Actions yang digunakan pada project Sipadi.

File dokumentasi ini hanya berfungsi sebagai panduan. File yang benar-benar dijalankan oleh GitHub Actions berada di:

```text
.github/workflows/ci.yml
```

## Tujuan Workflow

Workflow `Laravel CI` digunakan untuk mengecek apakah aplikasi Laravel masih bisa di-install, di-build, dimigrasikan, dan dites secara otomatis setiap ada perubahan kode.

Workflow ini akan berjalan saat:

- Ada `push` ke branch `main` atau `master`.
- Ada `pull_request` menuju branch `main` atau `master`.

## Database CI

Project ini menggunakan PostgreSQL. Karena itu workflow menyediakan service database PostgreSQL 16 dengan konfigurasi testing berikut:

```text
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sipadi_test
DB_USERNAME=postgres
DB_PASSWORD=password
```

Service PostgreSQL akan dicek kesehatannya dengan `pg_isready` sebelum langkah aplikasi berjalan.

## Langkah Yang Dijalankan

Urutan proses pada workflow:

1. Mengambil source code repository dengan `actions/checkout`.
2. Menyiapkan PHP 8.2 menggunakan `shivammathur/setup-php`.
3. Mengaktifkan extension PHP yang dibutuhkan, termasuk `pdo_pgsql` dan `pgsql`.
4. Menyiapkan Node.js 22 menggunakan `actions/setup-node`.
5. Menginstall dependency PHP dengan `composer install`.
6. Menginstall dependency JavaScript dengan `npm ci`.
7. Membuild asset frontend dengan `npm run build`.
8. Membuat file `.env` dari `.env.example`.
9. Membuat `APP_KEY` dengan `php artisan key:generate`.
10. Menjalankan migration ke PostgreSQL dengan `php artisan migrate --force`.
11. Menjalankan test aplikasi dengan `composer test`.

## Catatan Testing

Walaupun aplikasi memakai PostgreSQL, konfigurasi test saat ini masih mengarah ke SQLite in-memory melalui `phpunit.xml` dan `tests/TestCase.php`.

Karena itu workflow melakukan dua pengecekan:

- Migration divalidasi langsung ke PostgreSQL.
- Test otomatis dijalankan sesuai konfigurasi testing project saat ini.

Jika nantinya semua test ingin benar-benar berjalan menggunakan PostgreSQL, maka konfigurasi `phpunit.xml` dan guard database di `tests/TestCase.php` perlu disesuaikan.

## Jika Workflow Gagal

Beberapa penyebab umum workflow gagal:

- `npm run build` gagal: cek konfigurasi Vite, Tailwind, atau asset frontend.
- `php artisan migrate --force` gagal: cek migration dan kompatibilitas PostgreSQL.
- `composer test` gagal: cek pesan error PHPUnit pada tab Actions.
- Error Blade seperti `expecting "endif"`: perbaiki struktur directive Blade, misalnya `@if`, `@else`, dan `@endif`.

## Cara Melihat Hasil

Setelah file `.github/workflows/ci.yml` masuk ke GitHub:

1. Buka repository di GitHub.
2. Masuk ke tab `Actions`.
3. Pilih workflow `Laravel CI`.
4. Buka run terbaru untuk melihat hasil setiap langkah.

Jika semua langkah sukses, maka workflow akan berstatus hijau.
