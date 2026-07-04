# Test Case Pengujian Blackbox SIPADI

Dokumen ini berisi rancangan test case blackbox berdasarkan fitur yang terlihat dari route, controller, request validation, dan tampilan aplikasi SIPADI.

## Ringkasan Modul

- Landing page publik.
- Registrasi anggota dua tahap: data diri dan akun.
- Login, logout, lupa password, reset password, dan verifikasi email.
- E-kartu anggota dan download PNG.
- Profil pengguna.
- Dashboard petugas.
- Koleksi buku petugas, termasuk filter dan ekspor CSV.
- Manajemen berita petugas: daftar, filter, tambah, edit, terbitkan, dan hapus.
- Kontrol akses berbasis role Anggota dan Petugas.

## Asumsi Data Uji

| Kode | Data | Keterangan |
| --- | --- | --- |
| DU-01 | Akun anggota aktif | User memiliki role Anggota dan relasi data anggota. |
| DU-02 | Akun petugas aktif | User memiliki role Petugas dan relasi data petugas. |
| DU-03 | Akun nonaktif | User ada di database dengan `status_akun` selain `aktif`. |
| DU-04 | Kategori berita tersedia | Minimal satu data kategori berita aktif untuk form berita. |
| DU-05 | Koleksi buku tersedia | Minimal satu data buku, kategori, dan eksemplar. |
| DU-06 | Berita draft tersedia | Minimal satu berita berstatus `draft`. |
| DU-07 | Berita terbit tersedia | Minimal satu berita berstatus `terbit`. |

## Test Case

### Landing Page

| ID | Skenario | Precondition | Data Uji | Langkah Pengujian | Hasil yang Diharapkan | Prioritas |
| --- | --- | --- | --- | --- | --- | --- |
| BB-001 | Membuka halaman utama | Tidak login | - | Akses `/` | Halaman landing SIPADI tampil tanpa perlu login. | High |
| BB-002 | Akses dashboard tanpa login | Tidak login | - | Akses `/dashboard` | Sistem mengarahkan pengguna ke halaman login. | High |

### Registrasi Anggota

| ID | Skenario | Precondition | Data Uji | Langkah Pengujian | Hasil yang Diharapkan | Prioritas |
| --- | --- | --- | --- | --- | --- | --- |
| BB-003 | Membuka form data diri | Tidak login | - | Akses `/register` | Form langkah 1 registrasi tampil dengan field nama lengkap, NIK, jenis kelamin, tanggal lahir, alamat, dan foto. | High |
| BB-004 | Melanjutkan registrasi dengan data diri valid | Tidak login | NIK 16 digit unik, nama valid, jenis kelamin valid, tanggal lahir sebelum hari ini, alamat valid | Isi form `/register`, klik `Lanjut Buat Akun` | Sistem menyimpan data ke sesi dan mengarahkan ke `/register/akun`. | High |
| BB-005 | Registrasi ditolak jika NIK kosong | Tidak login | NIK kosong | Submit form data diri | Pesan validasi `NIK wajib diisi.` tampil dan tetap di form data diri. | High |
| BB-006 | Registrasi ditolak jika NIK bukan 16 digit | Tidak login | NIK kurang/lebih dari 16 digit | Submit form data diri | Pesan validasi `NIK harus terdiri dari 16 digit angka.` tampil. | High |
| BB-007 | Registrasi ditolak jika NIK sudah terdaftar | Tidak login | NIK dari DU-01 | Submit form data diri | Pesan validasi NIK sudah terdaftar tampil. | High |
| BB-008 | Registrasi menerima NIK dengan pemisah non-angka | Tidak login | NIK berisi spasi/tanda hubung tetapi total angka 16 digit | Submit form data diri | Sistem membersihkan karakter non-angka dan lanjut ke `/register/akun`. | Medium |
| BB-009 | Registrasi ditolak jika tanggal lahir hari ini atau masa depan | Tidak login | Tanggal hari ini atau besok | Submit form data diri | Pesan validasi tanggal lahir harus sebelum hari ini tampil. | High |
| BB-010 | Upload foto valid | Tidak login | File JPG/PNG kurang dari 2 MB | Submit form data diri dengan foto | Sistem lanjut ke `/register/akun` dan preview/ringkasan foto tersedia. | Medium |
| BB-011 | Upload foto ditolak jika bukan gambar | Tidak login | File PDF/TXT | Submit form data diri dengan file tersebut | Pesan `Foto profil harus berupa gambar.` atau format tidak valid tampil. | High |
| BB-012 | Upload foto ditolak jika lebih dari 2 MB | Tidak login | JPG/PNG lebih dari 2 MB | Submit form data diri | Pesan ukuran maksimal 2 MB tampil. | Medium |
| BB-013 | Akses langkah akun tanpa data diri | Tidak login, sesi registrasi kosong | - | Akses `/register/akun` langsung | Sistem mengarahkan kembali ke `/register`. | High |
| BB-014 | Membuat akun dengan data valid | Sudah selesai langkah data diri | Email unik valid, password valid dan sama, terms dicentang | Submit form `/register/akun` | User, anggota, dan e-kartu dibuat; pengguna login otomatis dan diarahkan ke `/e-kartu` dengan status registrasi berhasil. | High |
| BB-015 | Membuat akun ditolak jika email kosong | Sudah selesai langkah data diri | Email kosong | Submit form akun | Pesan `Email wajib diisi.` tampil. | High |
| BB-016 | Membuat akun ditolak jika format email salah | Sudah selesai langkah data diri | `user-salah` | Submit form akun | Pesan `Format email belum valid.` tampil. | High |
| BB-017 | Membuat akun ditolak jika email sudah digunakan | Sudah selesai langkah data diri | Email milik DU-01 | Submit form akun | Pesan email sudah digunakan tampil. | High |
| BB-018 | Membuat akun ditolak jika konfirmasi password berbeda | Sudah selesai langkah data diri | Password dan konfirmasi berbeda | Submit form akun | Pesan `Konfirmasi password belum sama.` tampil. | High |
| BB-019 | Membuat akun ditolak jika terms tidak dicentang | Sudah selesai langkah data diri | Terms kosong | Submit form akun | Pesan persetujuan syarat dan ketentuan tampil. | High |

### Login dan Logout

| ID | Skenario | Precondition | Data Uji | Langkah Pengujian | Hasil yang Diharapkan | Prioritas |
| --- | --- | --- | --- | --- | --- | --- |
| BB-020 | Login anggota aktif | DU-01 tersedia | Email dan password anggota valid | Akses `/login`, submit kredensial | Login berhasil dan diarahkan ke landing page. | High |
| BB-021 | Login petugas aktif | DU-02 tersedia | Email dan password petugas valid | Akses `/login`, submit kredensial | Login berhasil dan diarahkan ke `/petugas/dashboard`. | High |
| BB-022 | Login ditolak jika password salah | User aktif tersedia | Email valid, password salah | Submit login | Pesan kredensial tidak cocok tampil dan user tidak login. | High |
| BB-023 | Login ditolak jika akun nonaktif | DU-03 tersedia | Email/password akun nonaktif | Submit login | Pesan akun belum aktif atau dinonaktifkan tampil. | High |
| BB-024 | Rate limit login gagal | Tidak login | Email valid/salah, password salah | Submit login gagal lebih dari 5 kali | Sistem menolak percobaan berikutnya dengan pesan throttle. | Medium |
| BB-025 | Logout berhasil | User login | - | Klik logout | Sesi berakhir dan pengguna diarahkan ke `/`. | High |

### Lupa Password dan Reset Password

| ID | Skenario | Precondition | Data Uji | Langkah Pengujian | Hasil yang Diharapkan | Prioritas |
| --- | --- | --- | --- | --- | --- | --- |
| BB-026 | Membuka form lupa password | Tidak login | - | Akses `/forgot-password` | Form permintaan link reset password tampil. | Medium |
| BB-027 | Meminta link reset dengan email terdaftar | User aktif tersedia | Email valid terdaftar | Submit form lupa password | Sistem menampilkan status bahwa link reset dikirim. | Medium |
| BB-028 | Meminta link reset dengan email tidak valid | Tidak login | Format email salah | Submit form lupa password | Pesan validasi email tampil. | Medium |
| BB-029 | Membuka halaman reset password dengan token valid | Token reset valid tersedia | Token valid | Akses `/reset-password/{token}` | Form reset password tampil. | Medium |
| BB-030 | Reset password berhasil | Token reset valid tersedia | Email valid, password baru valid dan terkonfirmasi | Submit form reset password | Password berubah dan user diarahkan sesuai alur autentikasi. | Medium |

### E-Kartu Anggota

| ID | Skenario | Precondition | Data Uji | Langkah Pengujian | Hasil yang Diharapkan | Prioritas |
| --- | --- | --- | --- | --- | --- | --- |
| BB-031 | Anggota membuka e-kartu | Login sebagai DU-01 | - | Akses `/e-kartu` | Halaman e-kartu tampil berisi nama anggota, nomor kartu/NIK, kalangan, masa berlaku, kode kartu, dan status aktif. | High |
| BB-032 | Anggota download e-kartu PNG | Login sebagai DU-01 | - | Klik `Download PNG` atau akses `/e-kartu/download` | File PNG terunduh dengan nama `e-kartu-{no_anggota}.png`. | High |
| BB-033 | Petugas tidak boleh membuka e-kartu anggota | Login sebagai DU-02 | - | Akses `/e-kartu` | Sistem menolak akses atau mengarahkan sesuai middleware role. | High |
| BB-034 | User tanpa data anggota membuka e-kartu | Login user tanpa relasi anggota | - | Akses `/e-kartu` | Sistem menampilkan 404 atau halaman error yang sesuai, bukan data pengguna lain. | High |

### Profil Pengguna

| ID | Skenario | Precondition | Data Uji | Langkah Pengujian | Hasil yang Diharapkan | Prioritas |
| --- | --- | --- | --- | --- | --- | --- |
| BB-035 | Membuka halaman profil | User login | - | Akses `/profile` | Form profil tampil dengan data user login. | Medium |
| BB-036 | Update profil berhasil | User login | Nama valid, email unik valid | Submit update profil | Email user berubah, nama anggota/petugas ikut berubah, dan status `profile-updated` tampil. | High |
| BB-037 | Update profil ditolak jika nama kosong | User login | Nama kosong | Submit update profil | Pesan validasi nama wajib tampil. | High |
| BB-038 | Update profil ditolak jika email sudah digunakan user lain | User login, email user lain tersedia | Email duplikat | Submit update profil | Pesan validasi email unik tampil. | High |
| BB-039 | Hapus akun ditolak jika password kosong | User login | Password kosong | Submit hapus akun | Pesan validasi password tampil dan akun tidak terhapus. | High |
| BB-040 | Hapus akun ditolak jika password salah | User login | Password salah | Submit hapus akun | Pesan current password tampil dan akun tidak terhapus. | High |
| BB-041 | Hapus akun berhasil | User login | Password benar | Submit hapus akun | User logout, akun dan data relasi terkait terhapus, lalu diarahkan ke `/`. | High |

### Dashboard Petugas

| ID | Skenario | Precondition | Data Uji | Langkah Pengujian | Hasil yang Diharapkan | Prioritas |
| --- | --- | --- | --- | --- | --- | --- |
| BB-042 | Petugas membuka dashboard | Login sebagai DU-02 | - | Akses `/petugas/dashboard` | Dashboard menampilkan statistik anggota, koleksi buku, peminjaman aktif, aduan baru, aktivitas terkini, aksi cepat, status layanan, dan prioritas hari ini. | High |
| BB-043 | Anggota tidak boleh membuka dashboard petugas | Login sebagai DU-01 | - | Akses `/petugas/dashboard` | Sistem menolak akses atau mengarahkan sesuai middleware role. | High |
| BB-044 | Dashboard tetap tampil saat data kosong | Login sebagai DU-02, database minim/kosong | - | Akses `/petugas/dashboard` | Dashboard tampil dengan nilai 0 dan placeholder aktivitas kosong. | Medium |

### Koleksi Buku Petugas

| ID | Skenario | Precondition | Data Uji | Langkah Pengujian | Hasil yang Diharapkan | Prioritas |
| --- | --- | --- | --- | --- | --- | --- |
| BB-045 | Petugas membuka halaman koleksi | Login sebagai DU-02, DU-05 tersedia | - | Akses `/petugas/koleksi` | Halaman menampilkan statistik total judul, total eksemplar, sedang dipinjam, tersedia, tabel koleksi, dan persentase ketersediaan. | High |
| BB-046 | Filter koleksi berdasarkan kategori | Login sebagai DU-02, DU-05 tersedia | Kategori tertentu | Pilih kategori, klik `Terapkan Filter` | Tabel hanya menampilkan buku pada kategori terpilih dan query filter tetap terlihat. | Medium |
| BB-047 | Filter koleksi berdasarkan status aktif | Login sebagai DU-02, DU-05 tersedia | Status `aktif` | Pilih status aktif, klik `Terapkan Filter` | Tabel hanya menampilkan buku berstatus aktif. | Medium |
| BB-048 | Filter koleksi tanpa hasil | Login sebagai DU-02 | Kombinasi filter tanpa data | Terapkan filter | Pesan `Belum ada koleksi yang sesuai dengan filter.` tampil. | Medium |
| BB-049 | Ekspor koleksi CSV | Login sebagai DU-02, DU-05 tersedia | - | Klik `Ekspor CSV` | File `koleksi_buku.csv` terunduh dengan header Kode, Judul, ISBN, Penulis, Kategori, Total Eksemplar, Tersedia, Status Katalog. | High |
| BB-050 | Anggota tidak boleh ekspor koleksi | Login sebagai DU-01 | - | Akses `/petugas/koleksi/export` | Sistem menolak akses atau mengarahkan sesuai middleware role. | High |

### Manajemen Berita Petugas

| ID | Skenario | Precondition | Data Uji | Langkah Pengujian | Hasil yang Diharapkan | Prioritas |
| --- | --- | --- | --- | --- | --- | --- |
| BB-051 | Petugas membuka daftar berita | Login sebagai DU-02 | DU-06/DU-07 tersedia | Akses `/petugas/berita` | Halaman menampilkan statistik total berita, terbit, draft, filter, daftar berita, dan tombol tambah berita. | High |
| BB-052 | Daftar berita kosong | Login sebagai DU-02, berita kosong | - | Akses `/petugas/berita` | Pesan `Belum Ada Berita` dan tombol tambah berita tampil. | Medium |
| BB-053 | Filter berita berdasarkan kata kunci | Login sebagai DU-02 | Judul berita tertentu | Isi `Cari Berita`, klik `Filter` | Daftar hanya menampilkan berita yang sesuai kata kunci. | Medium |
| BB-054 | Filter berita berdasarkan kategori | Login sebagai DU-02, DU-04 tersedia | Kategori tertentu | Pilih kategori, klik `Filter` | Daftar hanya menampilkan berita pada kategori tersebut. | Medium |
| BB-055 | Filter berita berdasarkan status draft | Login sebagai DU-02, DU-06 tersedia | Status `draft` | Pilih status draft, klik `Filter` | Daftar hanya menampilkan berita draft. | Medium |
| BB-056 | Reset filter berita | Login sebagai DU-02, filter aktif | - | Klik `Reset` | Filter hilang dan daftar kembali ke kondisi awal. | Low |
| BB-057 | Membuka form tambah berita | Login sebagai DU-02, DU-04 tersedia | - | Akses `/petugas/berita/tambah` | Form tambah berita tampil dengan field judul, isi, kategori, gambar, dan status. | High |
| BB-058 | Menyimpan berita draft valid | Login sebagai DU-02, DU-04 tersedia | Judul valid, kategori valid, isi opsional, status draft | Submit form tambah berita | Berita tersimpan sebagai draft dan pesan `Berita berhasil disimpan sebagai draft.` tampil. | High |
| BB-059 | Menerbitkan berita baru valid | Login sebagai DU-02, DU-04 tersedia | Judul valid, kategori valid, status terbit | Submit form tambah berita | Berita tersimpan dengan status terbit, tanggal terbit terisi, dan pesan berhasil diterbitkan tampil. | High |
| BB-060 | Tambah berita ditolak jika judul kosong | Login sebagai DU-02 | Judul kosong | Submit form tambah berita | Pesan `Judul berita wajib diisi.` tampil. | High |
| BB-061 | Tambah berita ditolak jika judul lebih dari 150 karakter | Login sebagai DU-02 | Judul 151 karakter | Submit form tambah berita | Pesan judul maksimal 150 karakter tampil. | Medium |
| BB-062 | Tambah berita ditolak jika kategori kosong | Login sebagai DU-02 | Kategori kosong | Submit form tambah berita | Pesan `Kategori berita wajib dipilih.` tampil. | High |
| BB-063 | Tambah berita ditolak jika status tidak valid | Login sebagai DU-02 | Status selain draft/terbit | Submit form tambah berita | Pesan status berita tidak valid tampil. | High |
| BB-064 | Upload gambar berita valid | Login sebagai DU-02 | JPG/JPEG/PNG/WebP kurang dari 5 MB | Submit form tambah berita dengan gambar | Berita tersimpan dan gambar tampil pada kartu berita. | Medium |
| BB-065 | Upload gambar berita ditolak jika format tidak valid | Login sebagai DU-02 | PDF/TXT | Submit form tambah berita | Pesan format gambar harus JPG, JPEG, PNG, atau WebP tampil. | Medium |
| BB-066 | Upload gambar berita ditolak jika lebih dari 5 MB | Login sebagai DU-02 | Gambar lebih dari 5 MB | Submit form tambah berita | Pesan ukuran maksimal 5 MB tampil. | Medium |
| BB-067 | Membuka form edit berita | Login sebagai DU-02, berita tersedia | Berita tertentu | Klik `Edit` pada daftar berita | Form edit tampil dengan data berita terpilih. | High |
| BB-068 | Update berita berhasil | Login sebagai DU-02, berita tersedia | Judul/kategori/status valid | Submit form edit | Berita diperbarui dan pesan `Berita berhasil diperbarui.` tampil. | High |
| BB-069 | Update judul membuat slug tetap unik | Login sebagai DU-02, dua berita tersedia | Judul sama dengan berita lain | Update judul | Berita berhasil diperbarui tanpa konflik URL/slug. | Medium |
| BB-070 | Terbitkan berita draft | Login sebagai DU-02, DU-06 tersedia | - | Klik `Terbitkan` pada berita draft | Status berubah menjadi terbit, tanggal terbit terisi, dan pesan berhasil diterbitkan tampil. | High |
| BB-071 | Hapus berita dibatalkan | Login sebagai DU-02, berita tersedia | - | Klik `Hapus`, lalu klik `Batal` di modal | Modal tertutup dan berita tetap ada. | Medium |
| BB-072 | Hapus berita berhasil | Login sebagai DU-02, berita tersedia | - | Klik `Hapus`, konfirmasi `Hapus Berita` | Berita terhapus, gambar terkait ikut dihapus jika ada, dan pesan berhasil dihapus tampil. | High |
| BB-073 | Anggota tidak boleh membuka manajemen berita | Login sebagai DU-01 | - | Akses `/petugas/berita` | Sistem menolak akses atau mengarahkan sesuai middleware role. | High |

### Kontrol Akses dan Keamanan Dasar

| ID | Skenario | Precondition | Data Uji | Langkah Pengujian | Hasil yang Diharapkan | Prioritas |
| --- | --- | --- | --- | --- | --- | --- |
| BB-074 | Guest tidak boleh membuka halaman anggota | Tidak login | - | Akses `/e-kartu` | Sistem mengarahkan ke login. | High |
| BB-075 | Guest tidak boleh membuka halaman petugas | Tidak login | - | Akses `/petugas/dashboard` | Sistem mengarahkan ke login. | High |
| BB-076 | CSRF melindungi form POST | Tidak login atau login sesuai fitur | Request POST tanpa token CSRF | Kirim request ke endpoint form seperti `/login` atau `/petugas/berita` tanpa CSRF | Sistem menolak request. | High |
| BB-077 | Data user lain tidak tampil pada e-kartu | Login sebagai anggota A dan anggota B tersedia | - | Anggota A akses `/e-kartu` | E-kartu hanya berisi data anggota A, bukan anggota B. | High |
| BB-078 | Route dashboard umum mengarahkan sesuai role | User login | DU-01 dan DU-02 | Akses `/dashboard` sebagai anggota lalu petugas | Anggota diarahkan ke landing; petugas diarahkan ke dashboard petugas. | High |

## Catatan Eksekusi

- Jalankan test case secara manual dari browser untuk memvalidasi perilaku blackbox.
- Untuk skenario file upload, siapkan file uji valid dan invalid dengan ukuran sesuai batas masing-masing modul.
- Untuk skenario role, gunakan akun berbeda agar validasi akses tidak tercampur oleh sesi browser.
- Untuk skenario penghapusan akun dan berita, gunakan data uji khusus agar tidak menghapus data penting.
