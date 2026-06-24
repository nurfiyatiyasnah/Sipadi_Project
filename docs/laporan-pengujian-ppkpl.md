# Laporan Pengujian PPKPL SIPADI

Tanggal pengujian: 18 Juni 2026  
Project: SIPADI - Sistem Informasi Perpustakaan Digital  
Framework: Laravel 12, PHP 8.2, PHPUnit 11

## 1. Ruang Lingkup

Pengujian dilakukan terhadap fitur yang benar-benar tersedia pada website berdasarkan `php artisan route:list --except-vendor`, controller, view, dan test yang ada di repository. Beberapa fitur pada README masih bersifat rencana/roadmap dan belum memiliki route fungsional, sehingga dicatat sebagai gap pada bagian Bug Report dan Rekomendasi.

Metode pengujian:

| Metode | Keterangan |
|---|---|
| Black Box Testing | Menguji input, output, status response, redirect, validasi, hak akses, dan file download tanpa membahas detail kode internal. |
| White Box Testing | Menguji fungsi `BeritaController::uniqueSlug()` dengan analisis statement coverage dan branch coverage. |
| Manual | Inspeksi route, controller, view, dan perilaku fitur dari sudut pandang aktor. |
| Otomatis | PHPUnit feature test menggunakan Laravel HTTP testing dan database assertion. |

## 2. Identifikasi Fitur Website

| ID | Fitur | Aktor | Route/Modul | Status |
|---|---|---|---|---|
| F01 | Landing page | Pengunjung, Anggota, Petugas | `GET /` | Tersedia |
| F02 | Registrasi data diri anggota | Pengunjung | `GET /register`, `POST /register` | Tersedia |
| F03 | Registrasi akun anggota | Pengunjung | `GET /register/akun`, `POST /register/akun` | Tersedia |
| F04 | Login | Pengunjung | `GET /login`, `POST /login` | Tersedia |
| F05 | Logout | Anggota, Petugas | `POST /logout` | Tersedia |
| F06 | Lupa/reset password | Pengunjung | `/forgot-password`, `/reset-password/{token}` | Tersedia |
| F07 | Verifikasi email | User login | `/verify-email`, `/email/verification-notification` | Tersedia |
| F08 | Konfirmasi dan update password | User login | `/confirm-password`, `PUT /password` | Tersedia |
| F09 | Redirect dashboard berdasarkan role | Anggota, Petugas | `GET /dashboard` | Tersedia |
| F10 | E-Kartu anggota | Anggota | `GET /e-kartu` | Tersedia |
| F11 | Download E-Kartu PNG | Anggota | `GET /e-kartu/download` | Tersedia |
| F12 | Profil user | Anggota, Petugas | `GET/PATCH/DELETE /profile` | Tersedia |
| F13 | Dashboard petugas | Petugas | `GET /petugas/dashboard` | Tersedia |
| F14 | Koleksi buku petugas | Petugas | `GET /petugas/koleksi` | Tersedia |
| F15 | Export koleksi CSV | Petugas | `GET /petugas/koleksi/export` | Tersedia |
| F16 | Kelola berita | Petugas | `GET/POST/PUT/PATCH/DELETE /petugas/berita` | Tersedia |
| F17 | Role based access control | Anggota, Petugas | Middleware `role` | Tersedia |
| F18 | Katalog publik, peminjaman, riwayat, aduan, agenda publik | Pengunjung, Anggota | Disebut di README/landing, tetapi route belum tersedia | Gap |

## 3. Test Scenario dan Test Case Black Box

| TC | Fitur | Skenario | Data Uji | Ekspektasi | Metode | Hasil |
|---|---|---|---|---|---|---|
| TC-01 | Landing page | Pengunjung membuka halaman utama | `GET /` | Status 200 dan link login/daftar tampil | Otomatis | PASS |
| TC-02 | Registrasi data diri | Pengunjung mengisi data diri valid | NIK 16 digit, nama, gender, tanggal lahir, alamat | Redirect ke `register/akun` dan session registrasi tersimpan | Otomatis | PASS |
| TC-03 | Registrasi data diri | NIK sudah terdaftar | NIK duplikat | Validasi error pada field `nik` | Otomatis | PASS |
| TC-04 | Registrasi data diri | Upload foto opsional | File PNG valid | Foto disimpan dan muncul pada E-Kartu | Otomatis | PASS |
| TC-05 | Registrasi akun | Data akun valid setelah data diri | Email unik, password confirmed, terms accepted | User, anggota, dan E-Kartu dibuat, user login, redirect E-Kartu | Otomatis | PASS |
| TC-06 | Registrasi akun | Email sudah dipakai | Email duplikat | Validasi error pada field `email` | Otomatis | PASS |
| TC-07 | Login | User aktif login dengan kredensial valid | Email dan password benar | User authenticated, `last_login_at` terisi, redirect sesuai role | Otomatis | PASS |
| TC-08 | Login | Password salah | Password tidak sesuai | User tetap guest dan muncul error login | Otomatis | PASS |
| TC-09 | Login | Akun nonaktif login | `status_akun = nonaktif` | Login ditolak dan session error pada email | Otomatis | PASS |
| TC-10 | Logout | User logout | `POST /logout` | Session invalid, user guest, redirect `/` | Otomatis | PASS |
| TC-11 | Reset password | User meminta link reset dan mengubah password | Email valid, token reset | Email reset terkirim dan password dapat diperbarui | Otomatis | PASS |
| TC-12 | Verifikasi email | User membuka dan mengirim ulang verifikasi | User belum verified | Halaman notice tampil dan notifikasi dapat dikirim | Otomatis | PASS |
| TC-13 | Update password | User mengubah password | Current password valid, password baru confirmed | Password berubah tanpa error | Otomatis | PASS |
| TC-14 | Profil | User membuka halaman profil | User login | Halaman profil status 200 | Otomatis | PASS |
| TC-15 | Profil | User update nama dan email | Nama/email baru | Data user dan relasi anggota/petugas diperbarui | Otomatis | PASS |
| TC-16 | Profil | User hapus akun dengan password benar | Password valid | User dan data relasi dihapus, user logout | Otomatis | PASS |
| TC-17 | E-Kartu | Anggota membuka E-Kartu | User role Anggota | Nama anggota dan nomor kartu tampil | Otomatis | PASS |
| TC-18 | E-Kartu download | Anggota mengunduh kartu | User role Anggota | Response `image/png`, file `e-kartu-{no_anggota}.png` | Otomatis | PASS |
| TC-19 | Hak akses E-Kartu | Petugas membuka E-Kartu anggota | User role Petugas | Response 403 Forbidden | Otomatis | PASS |
| TC-20 | Dashboard petugas | Petugas membuka dashboard | User role Petugas | Statistik anggota/koleksi/peminjaman/aduan tampil | Otomatis | PASS |
| TC-21 | Dashboard petugas | Anggota membuka dashboard petugas | User role Anggota | Response 403 Forbidden | Otomatis | PASS |
| TC-22 | Koleksi petugas | Petugas membuka daftar koleksi | Buku, kategori, eksemplar tersedia | Statistik dan tabel koleksi tampil | Otomatis | PASS |
| TC-23 | Filter koleksi | Petugas filter kategori/status | Query `kategori`, `status` | Data koleksi difilter sesuai input | Manual | PASS |
| TC-24 | Export koleksi | Petugas export koleksi | Data buku tersedia | Download `koleksi_buku.csv` berisi data buku dan kategori | Otomatis | PASS |
| TC-25 | Berita index | Petugas membuka daftar berita | Data berita terbit/draft | Daftar, kategori, dan status tampil | Otomatis | PASS |
| TC-26 | Berita filter/search | Petugas mencari dan memfilter berita | Search, kategori, status | Hanya berita yang sesuai filter tampil | Otomatis | PASS |
| TC-27 | Tambah berita | Petugas menambah berita draft | Judul, isi, kategori, gambar, status draft | Berita tersimpan sebagai draft dan gambar tersimpan | Otomatis | PASS |
| TC-28 | Tambah berita | Petugas menambah berita langsung terbit | Status `terbit` | Berita tersimpan dan `tanggal_terbit` terisi | Otomatis | PASS |
| TC-29 | Validasi berita | Petugas submit data tidak valid | Judul kosong, kategori invalid, status invalid | Session error pada field terkait | Otomatis | PASS |
| TC-30 | Update berita | Petugas update berita dan gambar | Judul/kategori/status/gambar baru | Data berubah, gambar lama dihapus, gambar baru tersimpan | Otomatis | PASS |
| TC-31 | Publish berita | Petugas publish draft | Berita draft | Status berubah menjadi `terbit` dan tanggal terbit terisi | Otomatis | PASS |
| TC-32 | Hapus berita | Petugas hapus berita bergambar | Berita dengan gambar | Record dan file gambar terhapus | Otomatis | PASS |
| TC-33 | Hak akses berita | Anggota membuka halaman kelola berita | User role Anggota | Response 403 Forbidden | Otomatis | PASS |
| TC-34 | Gap fitur publik | Pengunjung mencari route katalog/peminjaman/aduan/agenda publik yang disebut README/landing | Route list aplikasi | Route belum tersedia | Manual | FAIL |

## 4. Hasil Black Box Testing

Ringkasan hasil:

| Kategori | Jumlah Test Case | PASS | FAIL |
|---|---:|---:|---:|
| Fitur tersedia dan dapat diuji | 33 | 33 | 0 |
| Gap fitur yang disebut tetapi belum tersedia | 1 | 0 | 1 |
| Total | 34 | 33 | 1 |

Kesimpulan black box: seluruh fitur yang sudah memiliki route/controller/view utama berjalan sesuai ekspektasi pada pengujian ini. Satu hasil FAIL adalah gap implementasi, yaitu beberapa fitur yang tertulis pada README/landing belum tersedia sebagai route fungsional.

## 5. Bug Report

| ID | Judul | Severity | Status |
|---|---|---|---|
| BR-001 | Fitur publik pada README/landing belum memiliki route fungsional | Medium | Open |

Detail BR-001:

| Field | Isi |
|---|---|
| Modul | Landing page dan dokumentasi fitur |
| Langkah Reproduksi | 1. Buka README bagian fitur atau landing page. 2. Catat fitur katalog buku, peminjaman, riwayat peminjaman, aduan, agenda, layanan/fasilitas/profil publik. 3. Jalankan `php artisan route:list --except-vendor`. |
| Expected Result | Fitur yang ditampilkan sebagai fitur website memiliki route, controller, view, dan test, atau diberi label sebagai roadmap. |
| Actual Result | Route publik untuk beberapa fitur tersebut belum tersedia. Route yang tersedia saat ini hanya landing, auth, profile, E-Kartu anggota, dashboard/koleksi petugas, export koleksi, dan CRUD berita petugas. |
| Dampak | Pengguna atau penguji dapat mengira fitur sudah bisa digunakan, padahal belum tersedia di aplikasi. |
| Rekomendasi | Implementasikan route/controller/view/test untuk fitur tersebut atau ubah README/landing agar membedakan fitur tersedia dan fitur roadmap. |

## 6. White Box Testing

Modul yang dipilih: `BeritaController::uniqueSlug()`  
Lokasi: `app/Http/Controllers/BeritaController.php` baris 136-150  
Alasan pemilihan: fungsi ini penting untuk kelola berita karena mencegah konflik slug saat berita baru dibuat atau judul berita diperbarui.

Kode yang dianalisis:

```php
private function uniqueSlug(string $title, ?Berita $ignore = null): string
{
    $base = Str::slug($title) ?: Str::random(8);
    $slug = $base;
    $counter = 2;

    while (Berita::query()
        ->where('slug', $slug)
        ->when($ignore, fn ($query) => $query->whereKeyNot($ignore->getKey()))
        ->exists()) {
        $slug = $base.'-'.$counter;
        $counter++;
    }

    return $slug;
}
```

### 6.1 Flow dan Statement

| No | Statement | Keterangan |
|---|---|---|
| S1 | Buat `$base` dari slug judul atau random 8 karakter | Memiliki cabang slug normal/fallback |
| S2 | Set `$slug = $base` | Nilai awal slug |
| S3 | Set `$counter = 2` | Nomor suffix pertama |
| S4 | Cek apakah slug sudah ada di database | Kondisi loop |
| S5 | Set `$slug = $base.'-'.$counter` | Dibuat saat slug bentrok |
| S6 | Increment `$counter` | Persiapan suffix berikutnya |
| S7 | Return `$slug` | Output akhir |

### 6.2 Branch

| Branch | Kondisi | Jalur |
|---|---|---|
| B1 | `Str::slug($title)` menghasilkan slug | Slug normal |
| B2 | `Str::slug($title)` kosong | Fallback `Str::random(8)` |
| B3 | `$ignore` kosong | Query tidak mengabaikan record tertentu |
| B4 | `$ignore` berisi model | Query mengabaikan record yang sedang diedit |
| B5 | Slug sudah ada | Masuk loop dan tambah suffix |
| B6 | Slug belum ada | Keluar loop dan return slug |

### 6.3 Test Case White Box

| WB | Nama Test | Input/Kondisi | Statement Tercakup | Branch Tercakup | Hasil |
|---|---|---|---|---|---|
| WB-01 | Slug dibuat unik saat judul duplikat | Sudah ada slug `program-literasi-kota`, lalu tambah berita dengan judul sama | S1-S7 | B1, B3, B5, B6 | PASS |
| WB-02 | Fallback random saat judul tidak menghasilkan slug | Judul `!!!` | S1-S4, S7 | B2, B3, B6 | PASS |
| WB-03 | Update berita mengabaikan record yang sedang diedit | Update berita dari `judul-lama` ke judul yang slugnya sudah dipakai record lain | S1-S7 | B1, B4, B5, B6 | PASS |

Test otomatis dibuat pada `tests/Feature/BeritaSlugWhiteBoxTest.php`.

### 6.4 Coverage

Statement Coverage:

```text
Statement Coverage = jumlah statement yang dieksekusi / total statement x 100%
Statement Coverage = 7 / 7 x 100% = 100%
```

Branch Coverage:

```text
Branch Coverage = jumlah branch yang dieksekusi / total branch x 100%
Branch Coverage = 6 / 6 x 100% = 100%
```

Catatan: coverage dihitung manual berdasarkan control flow fungsi yang dipilih. Project belum dikonfigurasi untuk menghasilkan laporan coverage mesin menggunakan Xdebug/PCOV.

## 7. Automated Testing

Automated testing menggunakan PHPUnit feature test Laravel.

| File Test | Fitur yang Diuji |
|---|---|
| `tests/Feature/Auth/AuthenticationTest.php` | Login, logout, akun nonaktif |
| `tests/Feature/Auth/PasswordResetTest.php` | Lupa/reset password |
| `tests/Feature/Auth/PasswordUpdateTest.php` | Update password |
| `tests/Feature/Auth/PasswordConfirmationTest.php` | Konfirmasi password |
| `tests/Feature/Auth/EmailVerificationTest.php` | Verifikasi email |
| `tests/Feature/RegistrasiAnggotaTest.php` | Registrasi data diri, registrasi akun, foto profil, validasi NIK/email |
| `tests/Feature/EKartuTest.php` | Tampil dan download E-Kartu, pembatasan role |
| `tests/Feature/DashboardPetugasTest.php` | Dashboard petugas, koleksi, export CSV, pembatasan role |
| `tests/Feature/BeritaTest.php` | Daftar, search/filter, tambah, update, publish, hapus, validasi, pembatasan role berita |
| `tests/Feature/ProfileTest.php` | Tampil profil, update profil, hapus akun |
| `tests/Feature/BeritaSlugWhiteBoxTest.php` | White-box slug berita |

Hasil eksekusi:

| Perintah | Hasil |
|---|---|
| `vendor\bin\pint --dirty --format agent` | PASS |
| `php artisan test --compact tests\Feature\BeritaSlugWhiteBoxTest.php` | PASS, 3 tests, 9 assertions |
| `php artisan test --compact tests\Feature` | PASS, 52 tests, 211 assertions |

## 8. Analisis Hasil Pengujian

1. Fitur utama yang tersedia pada route saat ini stabil berdasarkan feature test: autentikasi, registrasi anggota, E-Kartu, profil, dashboard petugas, koleksi, export CSV, dan kelola berita.
2. Pembatasan role berjalan pada fitur anggota dan petugas. Anggota tidak dapat membuka route petugas, dan petugas tidak dapat membuka E-Kartu anggota.
3. Validasi input penting sudah diterapkan pada registrasi dan berita, termasuk NIK unik, email unik, kategori berita valid, status berita valid, serta upload gambar.
4. White-box testing pada fungsi slug berita menunjukkan seluruh statement dan branch yang dianalisis sudah tertutup oleh test.
5. Gap terbesar bukan pada fitur yang sudah berjalan, tetapi pada kesesuaian daftar fitur README/landing dengan implementasi aktual.

## 9. Rekomendasi Perbaikan

| Prioritas | Rekomendasi |
|---|---|
| Tinggi | Pisahkan fitur yang sudah tersedia dan fitur roadmap pada README/landing agar tidak membingungkan pengguna dan penguji. |
| Tinggi | Jika fitur katalog publik, peminjaman, riwayat, aduan, agenda, layanan, fasilitas, dan profil perpustakaan memang wajib, tambahkan route, controller, view, request validation, dan feature test untuk masing-masing fitur. |
| Sedang | Tambahkan automated test khusus untuk filter koleksi berdasarkan kategori dan status agar tidak hanya tercakup manual. |
| Sedang | Aktifkan coverage driver seperti PCOV atau Xdebug di environment testing agar statement/branch coverage dapat dihasilkan otomatis. |
| Sedang | Tambahkan GitHub Actions/CI yang menjalankan `vendor/bin/pint --dirty --format agent` dan `php artisan test --compact`. |
| Rendah | Tambahkan test akses untuk route `/dashboard` agar redirect role Anggota dan Petugas terdokumentasi otomatis. |

## 10. Referensi

- Laravel 12 HTTP Tests: https://laravel.com/docs/12.x/http-tests
- Laravel 12 Database Testing: https://laravel.com/docs/12.x/database-testing
