# SIPADI - Sistem Informasi Perpustakaan Digital

SIPADI adalah aplikasi berbasis web yang digunakan untuk membantu pengelolaan layanan perpustakaan secara digital. Sistem ini dirancang untuk mempermudah pengunjung, anggota, dan admin dalam mengakses informasi perpustakaan, mengelola data buku, melakukan peminjaman buku, mengelola berita, agenda, aduan, serta statistik pengunjung.

## Deskripsi Proyek

SIPADI dibuat untuk mendukung digitalisasi proses layanan perpustakaan agar lebih terstruktur, mudah diakses, dan efisien. Melalui sistem ini, pengunjung dapat melihat informasi layanan perpustakaan, fasilitas, profil perpustakaan, berita, katalog buku, agenda, serta mengajukan aduan atau masukan.

Anggota perpustakaan dapat melakukan login, melihat profil, mencari buku, mengajukan peminjaman, menerima pesan pengambilan buku, melihat riwayat peminjaman, mengembalikan buku, melihat notifikasi pengembalian, melihat pengumuman, melihat informasi keterlambatan, serta menginputkan aduan.

Admin dapat mengelola data utama sistem, seperti data user atau anggota, data buku, agenda dan event, berita terkini, aduan atau masukan, data peminjaman, profil perpustakaan, dan statistik pengunjung.

Masalah yang diselesaikan oleh sistem ini antara lain:

* Pengelolaan data buku yang masih dilakukan secara manual atau kurang terstruktur.
* Pengunjung membutuhkan akses cepat terhadap informasi layanan perpustakaan.
* Anggota membutuhkan sistem untuk mencari buku dan mengajukan peminjaman secara digital.
* Admin membutuhkan sistem untuk mengelola data anggota, buku, peminjaman, berita, agenda, dan aduan.
* Data statistik pengunjung perlu dicatat dan dikelola agar dapat digunakan sebagai bahan evaluasi layanan.

Target pengguna aplikasi ini adalah:

* Pengunjung
* Anggota Perpustakaan
* Admin

## Features

### Fitur Pengunjung

* Registrasi akun
* Mendownload e-kartu
* Melihat halaman beranda
* Melihat informasi layanan
* Melihat fasilitas perpustakaan
* Melihat profil perpustakaan
* Melihat berita
* Melihat katalog buku dan detail buku
* Melihat agenda dan event
* Menginputkan aduan atau masukan
* Melihat statistik pengunjung

### Fitur Anggota

* Login
* Logout
* Melihat profil
* Mencari buku
* Filter dan sorting buku
* Mengajukan peminjaman buku
* Menerima pesan pengambilan buku
* Melihat riwayat peminjaman
* Mengembalikan buku
* Melihat notifikasi pengembalian buku
* Melihat pengumuman
* Melihat informasi keterlambatan
* Menginputkan aduan atau masukan

### Fitur Admin

* Mengelola data user atau anggota
* Mengupdate data anggota
* Mengelola data buku
* Menambahkan buku
* Mengedit data atau detail buku
* Menambah stok buku
* Mengelola data agenda dan event
* Menambahkan agenda dan event
* Mengubah agenda dan event
* Menghapus agenda dan event
* Mengelola berita terkini
* Menambahkan berita terkini
* Mengubah isi berita
* Menghapus berita
* Mengelola aduan atau masukan
* Melihat aduan
* Merespon aduan
* Mengarsipkan aduan
* Mengelola data peminjaman
* Melihat detail peminjaman
* Mengatur jadwal pengambilan buku
* Mengirim notifikasi pengambilan buku
* Menginputkan pengembalian
* Mengelola profil perpustakaan
* Mengubah struktur organisasi
* Mengubah struktur kepegawaian
* Menambah prestasi
* Mengelola statistik pengunjung
* Menambah data kunjungan
* Mengedit data kunjungan
* Menghapus data kunjungan yang salah

## Tech Stack

Teknologi yang digunakan dalam pengembangan project ini:

* Laravel
* PHP
* PostgreSql
* HTML
* CSS
* JavaScript
* Bootstrap
* Composer
* Git
* GitHub

## Instalasi Singkat

Ikuti langkah-langkah berikut untuk menjalankan project pada komputer lokal.

### 1. Clone Repository

```bash
git clone https://github.com/username/nama-repository.git
```

### 2. Masuk ke Folder Project

```bash
cd nama-repository
```

### 3. Install Dependency Laravel

```bash
composer install
```

### 4. Copy File Environment

Untuk Windows CMD:

```bash
copy .env.example .env
```

Untuk Git Bash, Linux, atau macOS:

```bash
cp .env.example .env
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Konfigurasi Database

Buka file `.env`, lalu sesuaikan konfigurasi database:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sipadi
DB_USERNAME=root
DB_PASSWORD=
```

### 7. Buat Database

Buat database baru dengan nama:

```bash
sipadi_db
```

Database dapat dibuat melalui pgAdmin,dan DBeaver.

### 8. Jalankan Migration

```bash
php artisan migrate
```

### 9. Jalankan Seeder Jika Tersedia

```bash
php artisan db:seed
```

### 10. Jalankan Server Laravel

```bash
php artisan serve
```

### 11. Buka Aplikasi

Akses aplikasi melalui browser:

```bash
http://127.0.0.1:8000
```

## Struktur Menu Sistem

Struktur menu utama pada sistem terdiri dari:

* Beranda
* Informasi Layanan
* Fasilitas
* Profil Perpustakaan
* Berita
* Katalog Buku
* Detail Buku
* Agenda dan Event
* Registrasi
* Login
* Profil Anggota
* Peminjaman Buku
* Riwayat Peminjaman
* Informasi Keterlambatan
* Pengumuman
* Aduan atau Masukan
* Dashboard Admin
* Kelola Data User / Anggota
* Kelola Data Buku
* Kelola Agenda dan Event
* Kelola Berita Terkini
* Kelola Aduan / Masukan
* Kelola Data Peminjaman
* Kelola Profil Perpustakaan
* Kelola Statistik Pengunjung

## Aktor Sistem

| Aktor      | Deskripsi                                                                                                                                                                                       |
| ---------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Pengunjung | Pengguna yang belum login dan dapat mengakses informasi umum seperti beranda, layanan, fasilitas, profil perpustakaan, berita, katalog buku, agenda, registrasi akun, serta menginputkan aduan. |
| Anggota    | Pengguna yang sudah memiliki akun dan dapat login untuk mencari buku, mengajukan peminjaman, melihat riwayat peminjaman, mengembalikan buku, melihat notifikasi, dan menginputkan aduan.        |
| Admin      | Pengelola sistem yang memiliki akses untuk mengelola data anggota, buku, peminjaman, agenda, berita, aduan, profil perpustakaan, dan statistik pengunjung.                                      |

## Alur Singkat Sistem

1. Pengunjung membuka halaman beranda.
2. Pengunjung dapat melihat informasi layanan, fasilitas, profil perpustakaan, berita, katalog buku, serta agenda dan event.
3. Pengunjung melakukan registrasi akun untuk menjadi anggota.
4. Anggota melakukan login ke dalam sistem.
5. Anggota mencari buku menggunakan fitur pencarian, filter, dan sorting.
6. Anggota melihat detail buku.
7. Anggota mengajukan peminjaman buku.
8. Admin melihat detail peminjaman dan mengatur jadwal pengambilan buku.
9. Admin mengirim notifikasi pengambilan buku kepada anggota.
10. Anggota menerima pesan pengambilan buku.
11. Anggota mengembalikan buku.
12. Admin menginputkan data pengembalian.
13. Sistem menampilkan riwayat peminjaman dan informasi keterlambatan jika ada.
14. Pengunjung atau anggota dapat menginputkan aduan.
15. Admin melihat, merespon, dan mengarsipkan aduan.
16. Admin mengelola data buku, berita, agenda, profil perpustakaan, dan statistik pengunjung.

## Database

Nama database yang digunakan:

```bash
sipadi
```

Beberapa tabel utama yang dapat digunakan dalam sistem:

* users
* anggota
* buku
* kategori_buku
* peminjaman
* pengembalian
* notifikasi
* pengumuman
* berita
* agenda_event
* aduan
* profil_perpustakaan
* struktur_organisasi
* struktur_kepegawaian
* prestasi
* statistik_pengunjung

## Role dan Hak Akses

| Role       | Hak Akses                                                                                                                                                                                                                                                                            |
| ---------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| Pengunjung | Melihat beranda, informasi layanan, fasilitas, profil perpustakaan, berita, katalog buku, agenda, registrasi akun, download e-kartu, input aduan, dan melihat statistik pengunjung.                                                                                                  |
| Anggota    | Login, logout, melihat profil, mencari buku, filter dan sorting buku, mengajukan peminjaman, menerima pesan pengambilan buku, melihat riwayat peminjaman, mengembalikan buku, melihat notifikasi pengembalian, melihat pengumuman, melihat informasi keterlambatan, dan input aduan. |
| Admin      | Mengelola data user atau anggota, buku, agenda dan event, berita terkini, aduan atau masukan, data peminjaman, profil perpustakaan, dan statistik pengunjung.                                                                                                                        |

## Screenshot Proyek

Tambahkan screenshot tampilan aplikasi pada bagian berikut.

### Halaman Login

```markdown
![Halaman Login](public/images/screenshot/login.png)
```

### Halaman Beranda

```markdown
![Halaman Beranda](public/images/screenshot/beranda.png)
```

### Katalog Buku

```markdown
![Katalog Buku](public/images/screenshot/katalog-buku.png)
```

### Detail Buku

```markdown
![Detail Buku](public/images/screenshot/detail-buku.png)
```

### Pengajuan Peminjaman

```markdown
![Pengajuan Peminjaman](public/images/screenshot/pengajuan-peminjaman.png)
```

### Dashboard Admin

```markdown
![Dashboard Admin](public/images/screenshot/dashboard-admin.png)
```

### Kelola Data Buku

```markdown
![Kelola Data Buku](public/images/screenshot/kelola-data-buku.png)
```

### Kelola Data Peminjaman

```markdown
![Kelola Data Peminjaman](public/images/screenshot/kelola-peminjaman.png)
```

## Status Project

Project ini masih dalam tahap pengembangan. Beberapa fitur yang dapat dikembangkan lebih lanjut adalah:

* Notifikasi otomatis melalui email atau WhatsApp.
* Export laporan peminjaman dan pengembalian.
* Dashboard statistik peminjaman buku.
* Validasi keterlambatan pengembalian buku secara otomatis.
* Manajemen denda atau sanksi keterlambatan.
* Pencarian buku berdasarkan kategori, penulis, tahun terbit, dan status ketersediaan.
* Pengelolaan role dan permission yang lebih detail.

## Tim Pengembang

| No | Nama               | NIM           | Role               |
| -- | -----------------  | ------------- | ------------------ |
| 1  | Stenly Rizalevan   | 2411082035    | Project Manager    |
| 2  | Nikita Nurainun    | 2411081037    | System Analys      |
| 3  | Nurfiyati Yasnah   | 2411081043    | Programmer         |
| 4  | Hafizh Abdul Jabbar| 2411082042    | Programmer         |
| 5  | Aqaela Hawraael    | 2411083025    | QA / Teste         |

## Lisensi

Project ini dibuat untuk kebutuhan pembelajaran pada mata kuliah Konstruksi dan Evolusi Perangkat Lunak berbasis Project-Based Learning.