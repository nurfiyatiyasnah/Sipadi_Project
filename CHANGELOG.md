# Changelog

Semua perubahan penting pada proyek SIPADI dicatat di file ini.

Format changelog mengikuti pengelompokan perubahan berdasarkan tanggal dan jenis perubahan.

## [Unreleased]

### Ditambahkan

- Menambahkan `ApprovePeminjamanRequest` untuk memusatkan validasi approval peminjaman petugas.
- Menambahkan test validasi agar petugas tidak dapat menyetujui peminjaman dengan tanggal pengambilan lampau atau data jadwal yang belum lengkap.
- Menambahkan catatan refactoring tahap 1 pada `docs/refactoring.md`.

### Diubah

- Memindahkan validasi inline approval peminjaman dari `PetugasPeminjamanController` ke Form Request.
- Menggunakan data hasil `$request->validated()` saat membuat jadwal pengambilan, menghitung jatuh tempo, dan mengirim notifikasi approval peminjaman.

### Pengujian

- `vendor/bin/pint --dirty --format agent`
- `php artisan test --compact tests\Feature\PetugasPeminjamanTest.php`
- `git diff --check`

## [2026-07-01]

### Ditambahkan

- Menambahkan fitur pengumuman pada landing page dari commit `293d27d`.
- Menambahkan halaman daftar dan detail pengumuman publik dari commit `293d27d`.
- Menambahkan gambar default dan test fitur pengumuman publik dari commit `293d27d`.

### Diperbaiki

- Memperbaiki tampilan halaman Tentang Kami pada landing page dari commit `293d27d`.
- Memperbaiki tampilan halaman Layanan pada landing page dari commit `293d27d`.
- Menyesuaikan navbar publik untuk akses halaman pengumuman dari commit `293d27d`.

### Dokumentasi

- Merapikan isi dokumentasi pada folder `docs` dari commit `0332750`.

## [2026-06-30]

### Ditambahkan

- Menambahkan halaman layanan untuk admin dan user dari commit `c984b77`.
- Menambahkan halaman Tentang Kami untuk user dari commit `c984b77`.
- Menambahkan halaman Peminjaman Saya dari commit `66a99e2`.
- Menambahkan notifikasi pesan pada alur peminjaman dari commit `66a99e2`.

### Diperbaiki

- Memperbaiki alur dan logika peminjaman dari commit `66a99e2`.
- Memperbaiki beberapa bagian pada beranda anggota dari commit `63f8448`.
- Memperbaiki tampilan navbar user dari commit `4e5f00d`.

### Dokumentasi

- Mengisi dokumentasi fitur pada `docs/features.md` dari commit `26b2176`.
- Mengisi dokumentasi instalasi pada `docs/installation.md` dari commit `3ddc254`.
- Mengisi dokumentasi dependency pada `docs/dependency.md` dari commit `7e07c8b`.

## [2026-06-29]

### Ditambahkan

- Menambahkan fitur peminjaman dan pengembalian pada admin dari commit `6e95677`.
- Menambahkan fitur kelola buku di dashboard admin dari commit `2b50791`.

### Diperbaiki

- Memperbaiki UI/UX detail agenda pada beranda user dari commit `810a5c5`.
- Memperbaiki dashboard admin dari commit `3d3df14`.
- Memperbaiki dashboard controller dari commit `6774dbe`.
- Memperbaiki daftar agenda pada halaman kelola agenda dari commit `e2417c9`.
- Memperbaiki kategori buku di dashboard admin dari commit `3a5b609`.

## [2026-06-28]

### Ditambahkan

- Menambahkan halaman pengajuan peminjaman dari commit `a1f2a0c`.
- Menambahkan fitur profil anggota dari commit `6a783f4`.

## [2026-06-27]

### Ditambahkan

- Menambahkan fitur aduan pada dashboard admin dan beranda user dari commit `b7137c6`.

### Diperbaiki

- Memperbaiki error pada `resources/views/landing/index.blade.php` dari commit `69b77ba`.

## [2026-06-25]

### Ditambahkan

- Menambahkan fitur pengumuman pada dashboard admin dan beranda user dari commit `aede01f`.
- Menambahkan fitur kelola pengumuman di dashboard admin dari commit `55ea00f`.
- Menambahkan fitur agenda untuk user dan admin dari commit `751970b`.

### Diperbaiki

- Merapikan halaman beranda user dari commit `b4fdb13`.
- Memperbaiki data pada halaman beranda dari commit `ca2e3ef`.
- Merapikan `PublicKatalogController` dan memisahkan seeder katalog buku dari commit `be2d929`.
- Memperbaiki hasil merge dari branch pengembangan dari commit `047aeb7`.
- Memperbaiki UI beranda dari commit `434ce76`.

## [2026-06-24]

### Ditambahkan

- Menambahkan dashboard katalog buku dari commit `662d392`.
- Menambahkan dashboard dan detail berita dari commit `ef7251d`.
- Menambahkan landing page untuk anggota yang sudah login dari commit `681d711`.

### Diperbaiki

- Memperbaiki UI beranda dari commit `a97246c`.
- Memperbaiki UI download e-kartu dan menambahkan beranda dari commit `292f60e`.
- Memperbaiki login dari commit `47951a2`.

## [2026-06-22]

### Ditambahkan

- Menambahkan fitur kelola anggota di dashboard admin dari commit `c4bbcbe`.

## [2026-06-18]

### Ditambahkan

- Menambahkan fitur berita dengan status berita dari commit `1b6ea84`.

## [2026-06-16]

### Ditambahkan

- Menambahkan fitur login dari commit `654c23c`.
- Membuat UI/UX registrasi dan menambahkan dashboard admin dari commit `03ce9f4`.

## [2026-06-15]

### Diperbaiki

- Memperbaiki konfigurasi Vite dari commit `1efb87f`.
- Memperbaiki database dari commit `6214b5c`.

## [2026-06-12]

### Ditambahkan

- Menambahkan fitur login, register, download e-kartu, landing page user, dan dashboard admin dari commit `1b8ef89`.

### Diperbaiki

- Memperbaiki e-kartu dari commit `3fe3fa3`.

## [2026-06-10]

### Ditambahkan

- Menambahkan halaman create buku dari commit `bb4ce49`.

### Diubah

- Merapikan struktur folder admin dari commit `bb4ce49`.

## [2026-06-09]

### Ditambahkan

- Menambahkan migration dan Laravel Breeze dari commit `b95994a`.
- Menambahkan tampilan koleksi buku pada dashboard admin dari commit `4e0c81d`.

## [2026-06-03]

### Ditambahkan

- Menambahkan tampilan dashboard admin dari commit `4fab026`.
- Menambahkan dashboard login dari commit `33fec0d`.

### Diperbaiki

- Memperbaiki view login dan register dari commit `ca7d165`.

### Dokumentasi

- Menambahkan isi file instalasi dari commit `bc13397`.
- Menambahkan `README.md` dari commit `cbb22f1`.

## [2026-06-02]

### Dokumentasi

- Menambahkan dokumentasi awal proyek dari commit `20eef22`.
- Menambahkan commit dokumentasi awal dari commit `3228a98`.
