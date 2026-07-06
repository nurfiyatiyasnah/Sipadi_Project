# Test Case Pengujian Whitebox SIPADI

Dokumen ini berisi rancangan test case whitebox berdasarkan struktur internal aplikasi: route, controller, middleware, form request, model scope, relasi Eloquent, transaksi database, dan percabangan logika.

## Fokus Pengujian

- Branch dan decision coverage pada autentikasi, role, validasi, dan redirect.
- Path coverage pada proses registrasi dua tahap sampai pembuatan e-kartu.
- Condition coverage pada filter koleksi dan berita.
- Data flow coverage untuk data yang berpindah dari request, session, database, storage, sampai response.
- Exception/error path seperti data relasi tidak tersedia, akun nonaktif, dan akses role salah.

## Referensi Unit Kode

| Kode | Unit Kode | Fokus Internal |
| --- | --- | --- |
| UK-01 | `routes/web.php` | Routing publik, auth, role Anggota, role Petugas. |
| UK-02 | `routes/auth.php` | Routing guest/auth untuk autentikasi. |
| UK-03 | `App\Http\Middleware\RoleMiddleware` | Branch user kosong, role sesuai, role tidak sesuai. |
| UK-04 | `App\Models\User` | Helper role, accessor nama/name, relasi anggota/petugas. |
| UK-05 | `RegisterDataDiriRequest` | Normalisasi NIK dan validasi data diri. |
| UK-06 | `RegisterAkunRequest` | Normalisasi email dan validasi akun. |
| UK-07 | `RegisteredUserController` | Session registration, upload temporary file, transaksi user/anggota/e-kartu. |
| UK-08 | `LoginRequest` | Auth attempt, status akun, rate limiter, update last login. |
| UK-09 | `AuthenticatedSessionController` | Redirect berdasarkan role dan logout. |
| UK-10 | `ProfileController` | Update email/nama, reset email verification, delete cascading relation. |
| UK-11 | `EKartuController` | Ambil anggota user login, firstOrCreate e-kartu, response PNG. |
| UK-12 | `DashboardController` | Statistik, aktivitas terkini, filter koleksi, export CSV. |
| UK-13 | `BeritaController` | CRUD berita, publish, upload/delete image, slug unik. |
| UK-14 | `Berita` model | Scope published, draft, filter search/kategori/status. |

## Test Case Whitebox

### Middleware dan Routing

| ID | Unit | Tujuan Coverage | Jalur/Branch yang Diuji | Data Uji | Ekspektasi Internal |
| --- | --- | --- | --- | --- | --- |
| WB-001 | UK-03 | Branch user null | `! $user` bernilai true | Request tanpa user ke route role | Middleware menjalankan `abort(403)`; controller tidak dieksekusi. |
| WB-002 | UK-03 | Branch role salah | User ada, `nama_role !== $role` | User Anggota akses route Petugas | Middleware menjalankan `abort(403)`; response 403. |
| WB-003 | UK-03 | Branch role benar | User ada, `nama_role === $role` | User Petugas akses route Petugas | Middleware memanggil `$next($request)`. |
| WB-004 | UK-01 | Route dashboard branch Petugas | `$user->isPetugas()` true | Login sebagai Petugas, akses `/dashboard` | Route closure mengembalikan redirect ke `petugas.dashboard`. |
| WB-005 | UK-01 | Route dashboard branch non-Petugas | `$user->isPetugas()` false | Login sebagai Anggota, akses `/dashboard` | Route closure mengembalikan redirect ke `landing`. |
| WB-006 | UK-01/UK-02 | Middleware auth pada protected route | Request guest ke route auth | Guest akses `/profile`, `/e-kartu`, atau `/petugas/dashboard` | Middleware auth mencegah controller berjalan dan redirect ke login. |

### Model User

| ID | Unit | Tujuan Coverage | Jalur/Branch yang Diuji | Data Uji | Ekspektasi Internal |
| --- | --- | --- | --- | --- | --- |
| WB-007 | UK-04 | Helper `isPetugas` true | Role bernama `Petugas` | User dengan relasi role Petugas | Method mengembalikan `true`. |
| WB-008 | UK-04 | Helper `isPetugas` false | Role bukan Petugas/null | User Anggota atau tanpa role | Method mengembalikan `false`. |
| WB-009 | UK-04 | Helper `isAnggota` true | Role bernama `Anggota` | User dengan relasi role Anggota | Method mengembalikan `true`. |
| WB-010 | UK-04 | Accessor nama prioritas anggota | `anggota?->nama_lengkap` tersedia | User dengan relasi anggota dan petugas kosong | `$user->nama` bernilai nama anggota. |
| WB-011 | UK-04 | Accessor nama prioritas petugas | Anggota null, petugas tersedia | User dengan relasi petugas | `$user->nama` bernilai nama petugas. |
| WB-012 | UK-04 | Accessor fallback email | Anggota dan petugas null | User tanpa relasi profil | `$user->nama` bernilai email. |
| WB-013 | UK-04 | Cast password hashed | Fill password plain text | Buat user dengan password baru | Nilai tersimpan tidak sama dengan plain text dan bisa diverifikasi hash. |

### Validasi Registrasi Data Diri

| ID | Unit | Tujuan Coverage | Jalur/Branch yang Diuji | Data Uji | Ekspektasi Internal |
| --- | --- | --- | --- | --- | --- |
| WB-014 | UK-05 | `prepareForValidation` NIK | Regex `/\D/` membersihkan non-digit | NIK `1371-1234 5678 9012` | Validator menerima nilai akhir 16 digit. |
| WB-015 | UK-05 | Rule required NIK | NIK kosong | Payload tanpa NIK | Validator gagal pada rule `required`. |
| WB-016 | UK-05 | Rule digits NIK | NIK kurang/lebih 16 digit | NIK 15 atau 17 digit | Validator gagal pada rule `digits:16`. |
| WB-017 | UK-05 | Rule unique NIK | NIK sudah ada di tabel anggota | Payload NIK duplikat | Validator gagal pada rule `unique:anggota,nik`. |
| WB-018 | UK-05 | Rule enum jenis kelamin | Nilai bukan daftar allowed | `jenis_kelamin=Pria` | Validator gagal pada `Rule::in`. |
| WB-019 | UK-05 | Rule tanggal lahir | Tanggal tidak sebelum hari ini | Tanggal hari ini/masa depan | Validator gagal pada rule `before:today`. |
| WB-020 | UK-05 | Rule foto valid | File valid | JPG/PNG <= 2 MB | Validator lulus rule `image`, `mimes`, dan `max`. |
| WB-021 | UK-05 | Rule foto invalid mime | File bukan JPG/PNG | PDF/TXT | Validator gagal pada `image` atau `mimes`. |

### Validasi Registrasi Akun

| ID | Unit | Tujuan Coverage | Jalur/Branch yang Diuji | Data Uji | Ekspektasi Internal |
| --- | --- | --- | --- | --- | --- |
| WB-022 | UK-06 | `prepareForValidation` email | Email dibuat lowercase | `USER@MAIL.COM` | Nilai tervalidasi menjadi `user@mail.com`. |
| WB-023 | UK-06 | Rule unique email | Email sudah ada di users | Email duplikat | Validator gagal pada `Rule::unique(User::class)`. |
| WB-024 | UK-06 | Rule confirmed password | Konfirmasi berbeda | Password dan confirmation beda | Validator gagal pada rule `confirmed`. |
| WB-025 | UK-06 | Rule accepted terms | Terms tidak dikirim | Payload tanpa `terms` | Validator gagal pada rule `accepted`. |
| WB-026 | UK-06 | Semua rule valid | Payload valid lengkap | Email unik, password valid, terms 1 | Validator lulus dan controller boleh lanjut. |

### Controller Registrasi

| ID | Unit | Tujuan Coverage | Jalur/Branch yang Diuji | Data Uji | Ekspektasi Internal |
| --- | --- | --- | --- | --- | --- |
| WB-027 | UK-07 | `storeDataDiri` tanpa foto | `hasFile('foto')` false | Payload data diri valid tanpa foto | Session `registration.data_diri` terisi; `foto_path` null; redirect ke `register.akun`. |
| WB-028 | UK-07 | `storeDataDiri` dengan foto baru | `hasFile('foto')` true dan foto lama null | Payload valid dengan foto | File tersimpan di `registrasi/temp`; session menyimpan path foto. |
| WB-029 | UK-07 | Ganti foto temporary | `fotoPath` lama ada dan foto baru diupload | Sesi punya foto lama | Storage menghapus foto lama lalu menyimpan foto baru. |
| WB-030 | UK-07 | `createAkun` tanpa session | `session()->has('registration.data_diri')` false | Guest akses `register/akun` langsung | Redirect ke route `register`. |
| WB-031 | UK-07 | `createAkun` dengan session | Session data diri tersedia | Session lengkap | View `auth.register-akun` menerima `dataDiri` dan `fotoPath`. |
| WB-032 | UK-07 | `store` tanpa session | Guard awal gagal | Submit akun tanpa session data diri | Redirect ke route `register`; transaksi tidak berjalan. |
| WB-033 | UK-07 | Transaksi registrasi sukses | Semua data valid | Data diri + akun valid | Role Anggota dibuat/diambil, User dibuat, Anggota dibuat, EKartuAnggota dibuat, user login, session registration dihapus. |
| WB-034 | UK-07 | Copy foto ke folder final | `fotoTempPath` ada | Registrasi dengan foto | File dicopy ke `anggota/foto`; path final disimpan ke anggota; file temp dihapus setelah sukses. |
| WB-035 | UK-07 | Rollback saat exception | Exception terjadi dalam transaksi | Simulasikan kegagalan create anggota/e-kartu | Data user/anggota/e-kartu tidak parsial; foto final dihapus jika sempat dibuat. |

### Login dan Session

| ID | Unit | Tujuan Coverage | Jalur/Branch yang Diuji | Data Uji | Ekspektasi Internal |
| --- | --- | --- | --- | --- | --- | --- |
| WB-036 | UK-08 | Auth berhasil | `Auth::attempt` true | Email/password valid | Rate limiter clear, `last_login_at` terisi. |
| WB-037 | UK-08 | Auth gagal | `Auth::attempt` false | Password salah | Rate limiter hit, ValidationException pada field email. |
| WB-038 | UK-08 | Akun nonaktif | Auth true, `status_akun !== aktif` | User nonaktif | Auth logout, limiter hit, ValidationException pesan akun nonaktif. |
| WB-039 | UK-08 | Rate limit belum tercapai | Attempts <= 5 | Gagal login beberapa kali | `ensureIsNotRateLimited` return tanpa exception. |
| WB-040 | UK-08 | Rate limit tercapai | Attempts > 5 | Gagal login berulang dari throttle key sama | Event Lockout dikirim dan ValidationException throttle muncul. |
| WB-041 | UK-09 | Redirect login Petugas | `$user->isPetugas()` true | Petugas login | Controller redirect intended ke `petugas.dashboard`. |
| WB-042 | UK-09 | Redirect login Anggota | `$user->isPetugas()` false | Anggota login | Controller redirect intended ke `landing`. |
| WB-043 | UK-09 | Logout | Session aktif | User login lalu logout | Guard logout, session invalidate, token regenerate, redirect `/`. |

### Profil

| ID | Unit | Tujuan Coverage | Jalur/Branch yang Diuji | Data Uji | Ekspektasi Internal |
| --- | --- | --- | --- | --- | --- | --- |
| WB-044 | UK-10 | Edit load relasi | `load(['anggota', 'petugas'])` | User login | View menerima user dengan relasi anggota/petugas sudah dimuat. |
| WB-045 | UK-10 | Update email sama | `$user->isDirty('email')` false | Submit email lama | `email_verified_at` tidak diubah. |
| WB-046 | UK-10 | Update email berubah | `$user->isDirty('email')` true | Submit email baru | `email_verified_at` di-set null. |
| WB-047 | UK-10 | Update nama anggota | `$user->anggota?->update` jalan | User Anggota | `anggota.nama_lengkap` berubah sesuai input `name`. |
| WB-048 | UK-10 | Update nama petugas | `$user->petugas?->update` jalan | User Petugas | `petugas.nama_petugas` berubah sesuai input `name`. |
| WB-049 | UK-10 | Delete password salah | Validasi `current_password` gagal | Password salah | Transaksi hapus tidak berjalan; user tetap ada. |
| WB-050 | UK-10 | Delete akun anggota | Password benar | User dengan anggota dan e-kartu | e-kartu dihapus, anggota dihapus, user dihapus, session invalidated. |
| WB-051 | UK-10 | Delete akun petugas | Password benar | User dengan petugas | Petugas dihapus, user dihapus, session invalidated. |

### E-Kartu

| ID | Unit | Tujuan Coverage | Jalur/Branch yang Diuji | Data Uji | Ekspektasi Internal |
| --- | --- | --- | --- | --- | --- | --- |
| WB-052 | UK-11 | Ambil anggota berhasil | `firstOrFail` menemukan relasi anggota | User Anggota valid | Method private `anggota` mengembalikan model Anggota dengan relasi eKartuAnggota. |
| WB-053 | UK-11 | Ambil anggota gagal | `firstOrFail` tidak menemukan data | User tanpa anggota | Controller menghasilkan 404. |
| WB-054 | UK-11 | E-kartu sudah ada | `firstOrCreate` menemukan row | Anggota punya e-kartu | Tidak membuat e-kartu baru; data lama digunakan. |
| WB-055 | UK-11 | E-kartu belum ada | `firstOrCreate` membuat row | Anggota tanpa e-kartu | Row e-kartu dibuat dengan no_anggota dari NIK, kalangan config, barcode UUID, masa berlaku config. |
| WB-056 | UK-11 | Download PNG | Renderer dipanggil | User Anggota valid | Response status 200, header `Content-Type: image/png`, disposition attachment, cache private no-store. |

### Dashboard dan Koleksi

| ID | Unit | Tujuan Coverage | Jalur/Branch yang Diuji | Data Uji | Ekspektasi Internal |
| --- | --- | --- | --- | --- | --- | --- |
| WB-057 | UK-12 | Statistik dashboard | Count setiap model | Data anggota/buku/peminjaman/aduan | Array `stats` berisi count sesuai query. |
| WB-058 | UK-12 | Aktivitas pengembalian ada | Branch `$pengembalian` true | Ada data pengembalian dengan relasi | Aktivitas pengembalian masuk ke array dengan judul/deskripsi/status/waktu. |
| WB-059 | UK-12 | Aktivitas anggota baru ada | Branch `$anggotaBaru` true | Ada anggota terbaru | Aktivitas registrasi anggota baru masuk ke array. |
| WB-060 | UK-12 | Aktivitas buku baru ada | Branch `$bukuBaru` true | Ada buku terbaru | Aktivitas pembaruan stok masuk ke array. |
| WB-061 | UK-12 | Aktivitas kosong | Semua data sumber kosong | Database kosong/minim | Method memakai `emptyActivities` dan mengembalikan 3 placeholder. |
| WB-062 | UK-12 | Status layanan aduan baru | `aduan_baru > 0` true | Ada aduan baru | Status layanan aduan bernilai `Dipantau` dengan tone merah. |
| WB-063 | UK-12 | Status layanan aduan aman | `aduan_baru > 0` false | Tidak ada aduan baru | Status layanan aduan bernilai `Aman` dengan tone emerald. |
| WB-064 | UK-12 | Filter koleksi kategori | `$request->filled('kategori')` true | Query kategori tertentu | Query buku menambahkan `where('id_kategori', kategori)`. |
| WB-065 | UK-12 | Filter koleksi status | `$request->filled('status')` true | Query status tertentu | Query buku menambahkan `where('status_katalog', status)`. |
| WB-066 | UK-12 | Persentase ketersediaan nol eksemplar | `eksemplar = 0` | Tidak ada eksemplar | Pembagi memakai `max($stats['eksemplar'], 1)`; tidak terjadi division by zero. |
| WB-067 | UK-12 | Export CSV | Iterasi semua buku | Data buku dengan kategori/eksemplar | Stream menulis header dan row sesuai urutan kolom. |

### Model dan Controller Berita

| ID | Unit | Tujuan Coverage | Jalur/Branch yang Diuji | Data Uji | Ekspektasi Internal |
| --- | --- | --- | --- | --- | --- | --- |
| WB-068 | UK-14 | Scope published | `where status_berita = terbit` | Berita draft dan terbit | Query hanya mengembalikan berita terbit. |
| WB-069 | UK-14 | Scope draft | `where status_berita = draft` | Berita draft dan terbit | Query hanya mengembalikan berita draft. |
| WB-070 | UK-14 | Scope filter search | `when($search)` true | Search cocok judul atau isi | Query menambahkan nested where judul/isi LIKE. |
| WB-071 | UK-14 | Scope filter kategori | `when($kategori)` true | Kategori tertentu | Query menambahkan where `id_kategori_berita`. |
| WB-072 | UK-14 | Scope filter status | `when($status)` true | Status draft/terbit | Query menambahkan where `status_berita`. |
| WB-073 | UK-13 | Index berita | Query eager load dan stats | Data kategori dan berita | View menerima `berita`, `kategoriList`, dan stats total/terbit/draft. |
| WB-074 | UK-13 | Store draft | `status_berita === draft` | Payload berita draft valid | `tanggal_terbit` null, slug unik dibuat, pesan draft dikirim. |
| WB-075 | UK-13 | Store terbit | `status_berita === terbit` | Payload berita terbit valid | `tanggal_terbit` now, slug unik dibuat, pesan terbit dikirim. |
| WB-076 | UK-13 | Store dengan gambar | `hasFile('gambar')` true | File gambar valid | File disimpan ke disk public folder `berita`; path masuk payload. |
| WB-077 | UK-13 | Update judul sama | `$berita->judul !== $data['judul']` false | Update isi/status saja | Slug tidak dihitung ulang. |
| WB-078 | UK-13 | Update judul berubah | Branch judul berubah true | Judul baru valid | Slug dihitung ulang dengan `uniqueSlug`. |
| WB-079 | UK-13 | Update gambar baru | `array_key_exists('gambar', $data)` true dan oldImage berbeda | Upload gambar baru | Gambar lama dihapus dari disk public setelah update. |
| WB-080 | UK-13 | Publish draft tanpa tanggal | `tanggal_terbit ?? now()` memakai now | Berita draft tanggal null | Status jadi terbit dan tanggal terbit terisi now. |
| WB-081 | UK-13 | Publish berita sudah punya tanggal | `tanggal_terbit ?? now()` memakai tanggal lama | Berita punya tanggal terbit | Tanggal lama dipertahankan. |
| WB-082 | UK-13 | Destroy dengan gambar | `if ($berita->gambar)` true | Berita punya gambar | File gambar dihapus lalu row berita dihapus. |
| WB-083 | UK-13 | Destroy tanpa gambar | `if ($berita->gambar)` false | Berita tanpa gambar | Tidak ada delete storage; row berita dihapus. |
| WB-084 | UK-13 | Unique slug tanpa konflik | While condition false pertama | Judul unik | Slug sama dengan `Str::slug($title)`. |
| WB-085 | UK-13 | Unique slug dengan konflik | While condition true minimal sekali | Judul sama dengan berita lain | Slug menjadi `base-2`, `base-3`, dan seterusnya sampai unik. |
| WB-086 | UK-13 | Unique slug title kosong/simbol | `Str::slug($title) ?: Str::random(8)` memakai random | Judul berisi simbol tanpa huruf/angka | Slug random 8 karakter dipakai. |

## Prioritas Automasi PHPUnit

| Prioritas | Test Case |
| --- | --- |
| Sangat tinggi | WB-001 sampai WB-006, WB-014 sampai WB-035, WB-036 sampai WB-043, WB-052 sampai WB-056, WB-074 sampai WB-085 |
| Tinggi | WB-044 sampai WB-051, WB-057 sampai WB-067, WB-068 sampai WB-073 |
| Menengah | WB-007 sampai WB-013, WB-020 sampai WB-026, WB-086 |

## Catatan Implementasi Test

- Gunakan feature test untuk route, middleware, redirect, validasi request, upload file, dan response download.
- Gunakan unit test atau feature test ringan untuk model helper dan scope Eloquent.
- Gunakan factory yang sudah tersedia untuk User, Anggota, Petugas, Berita, KategoriBerita, Buku, KategoriBuku, EksemplarBuku, dan EKartuAnggota.
- Gunakan fake storage saat menguji upload/delete gambar atau foto.
- Gunakan fake event/mail/notification jika menguji flow yang memicu event registrasi atau reset password.
- Jalankan test minimal per file atau per filter ketika test dibuat, misalnya `php artisan test --compact --filter=namaTest`.
