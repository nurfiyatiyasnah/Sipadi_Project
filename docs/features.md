# Daftar Fitur SIPADI
SIPADI adalah aplikasi Sistem Informasi Perpustakaan Digital untuk pengunjung, anggota, dan petugas perpustakaan. Fitur utama aplikasi mencakup publikasi informasi perpustakaan, katalog buku, registrasi anggota, e-kartu, peminjaman, pengembalian, aduan, notifikasi, dan pengelolaan konten.

## Ringkasan Aktor
| Aktor | Deskripsi | Akses Utama |
| --- | --- | --- |
| Pengunjung | Pengguna belum login yang mengakses informasi public. | Landing page, katalog, berita, agenda, tracking aduan. |
| Anggota | Pengguna terdaftar dengan role `Anggota`. | Dashboard anggota, e-kartu, pengajuan peminjaman, riwayat peminjaman, notifikasi, aduan. |
| Petugas | Pengelola sistem dengan role `Petugas`. | Dashboard petugas, anggota, koleksi buku, berita, agenda, pengumuman, aduan, peminjaman, pengembalian, export laporan. |

Role aplikasi memakai middleware `role`:
- `role:Anggota` untuk area anggota.
- `role:Petugas` untuk area petugas/admin.

## Ringkasan Route
Route aplikasi aktif berjumlah 93 route non-vendor.

| Kelompok Route | Prefix/Contoh | Modul |
| --- | --- | --- |
| Public | `/`, `/katalog`, `/berita`, `/agenda`, `/aduan/lacak` | Informasi umum, katalog, berita, agenda, pelacakan aduan. |
| Auth | `/login`, `/register`, `/forgot-password`, `/reset-password`, `/verify-email` | Login, registrasi, reset password, verifikasi email. |
| Anggota | `/beranda`, `/peminjaman-saya`, `/e-kartu`, `/aduan/tambah`, `/anggota/notifikasi` | Area layanan anggota. |
| Petugas | `/petugas/*` | Dashboard dan pengelolaan operasional perpustakaan. |
| Profile | `/profile` | Edit profil, update password, hapus akun. |

## Fitur Public

### Landing Page
Halaman utama menampilkan informasi ringkas aplikasi dan data yang diambil dari database.

Fitur:
- Menampilkan berita terbaru yang sudah terbit.
- Menampilkan agenda/event terbaru dengan status terbit.
- Menampilkan statistik koleksi buku, jumlah eksemplar, dan anggota aktif.
- Menampilkan pilihan buku terbaru beserta kategori dan ketersediaan eksemplar.
- Menjadi pintu masuk ke katalog, berita, agenda, login, dan registrasi.

### Katalog Public
Katalog public digunakan pengunjung dan anggota untuk mencari buku.

Fitur:
- Menampilkan daftar buku dengan pagination 12 item per halaman.
- Pencarian berdasarkan judul, penulis, dan ISBN.
- Filter kategori.
- Filter status ketersediaan:
  - tersedia
  - dipinjam
- Filter tahun terbit dari dan sampai.
- Sorting:
  - terbaru
  - terlama
  - A-Z
  - Z-A
- Menampilkan detail buku.
- Menampilkan status ketersediaan berdasarkan eksemplar.
- Menampilkan rekomendasi buku dari kategori yang sama.
- Menampilkan lokasi rak dari eksemplar pertama jika tersedia.

### Berita Public
Fitur:
- Menampilkan daftar berita yang sudah diterbitkan.
- Menampilkan detail berita berdasarkan slug.
- Menampilkan kategori berita.
- Mendukung tampilan berita unggulan dan berita terbaru melalui view public.

### Agenda Public
Fitur:
- Menampilkan daftar agenda/event public.
- Menampilkan detail agenda berdasarkan slug.
- Menampilkan agenda berstatus terbit.
- View agenda public memiliki logika tampilan kalender/list berbasis Alpine.js.

### Pelacakan Aduan
Fitur:
- Pengguna dapat melacak aduan berdasarkan kode tiket.
- Menampilkan data aduan dan tanggapan petugas jika tiket ditemukan.
- Kode tiket memakai format `AD-YYYY-MM-XXX`.

## Fitur Autentikasi dan Registrasi
Autentikasi menggunakan scaffolding Laravel Breeze yang telah disesuaikan untuk kebutuhan anggota perpustakaan.

### Login dan Logout
Fitur:
- Login menggunakan email dan password.
- Update waktu login terakhir.
- Menolak login akun nonaktif.
- Logout dengan invalidasi session.

### Registrasi Anggota Bertahap

Registrasi anggota dilakukan dalam dua tahap.

Tahap 1, data diri:
- NIK.
- Nama lengkap.
- Jenis kelamin.
- Tanggal lahir.
- Alamat.
- Nomor telepon.
- Foto anggota opsional.

Tahap 2, data akun:
- Email.
- Password.

Tahap 3, E-kartu:
- Mendownload E-kartu

Fitur:
- Data tahap pertama disimpan sementara di session.
- Foto sementara disimpan di `registrasi/temp`.
- Setelah akun dibuat, foto dipindahkan ke `anggota/foto`.
- Role `Anggota` dibuat otomatis jika belum ada.
- Data `User`, `Anggota`, dan `EKartuAnggota` dibuat dalam transaksi database.
- User langsung login setelah registrasi berhasil.
- User diarahkan ke halaman e-kartu setelah registrasi.

### Reset Password dan Verifikasi Email
Fitur:
- Permintaan reset password.
- Reset password menggunakan token.
- Verifikasi email dengan signed URL.
- Kirim ulang email verifikasi dengan throttle.
- Konfirmasi password.
- Update password.

## Fitur Anggota

### Peminjaman Saya
Fitur:
- Menampilkan riwayat peminjaman anggota.
- Menampilkan status setiap peminjaman.
- Menampilkan detail buku pada setiap transaksi.
- Menampilkan jadwal pengambilan jika peminjaman sudah disetujui.
- Mendukung auto-open tiket pengambilan melalui query `ticket`.

### Pengajuan Peminjaman Buku
Fitur:
- Anggota dapat mengajukan peminjaman dari detail katalog.
- Sistem memvalidasi:
  - user harus memiliki data anggota
  - status anggota harus aktif
  - anggota tidak sedang terkena sanksi aktif
  - tidak ada pengajuan/peminjaman aktif untuk buku yang sama
  - aturan peminjaman aktif harus tersedia
  - jumlah peminjaman aktif belum melewati batas
  - eksemplar buku tersedia
  - anggota menyetujui syarat peminjaman
- Sistem membuat kode peminjaman dengan format `PJM-YYYYMMDD-XXXX`.
- Sistem membuat record `peminjaman` dan `detail_peminjaman`.
- Status awal pengajuan adalah `diajukan`.

### E-Kartu Anggota
Fitur:
- Menampilkan e-kartu anggota.
- Membuat e-kartu otomatis jika belum ada.
- Nomor anggota berasal dari NIK/no anggota.
- Barcode memakai UUID.
- Masa berlaku default diambil dari `config/sipadi.php`.
- Download e-kartu sebagai file PNG.

### Aduan Anggota
Fitur:
- Anggota login dapat mengirim aduan.
- Validasi kategori aduan dan isi aduan.
- Lampiran opsional dengan format:
  - PNG
  - JPG
  - JPEG
  - PDF
- Ukuran lampiran maksimal 5 MB.
- Sistem membuat kode tiket `AD-YYYY-MM-XXX`.
- Status awal aduan adalah `terkirim`.
- Prioritas awal aduan adalah `sedang`.
- Setelah submit, anggota diarahkan ke halaman lacak aduan.

### Notifikasi Anggota
Fitur:
- Menampilkan daftar notifikasi anggota.
- Menandai notifikasi sebagai dibaca.
- Notifikasi peminjaman disetujui dapat mengarahkan anggota ke tiket pengambilan.
- Akses notifikasi dibatasi ke pemilik notifikasi.

### Profil Anggota
Fitur:
- Edit informasi profil.
- Update password.
- Hapus akun.
- View profil anggota juga mendukung tampilan QR/e-kartu melalui modal berbasis Alpine.js.

## Fitur Petugas

### Dashboard Petugas
Fitur:
- Menampilkan statistik:
  - total anggota
  - koleksi buku
  - peminjaman aktif
  - pengajuan peminjaman
  - buku terlambat
  - aduan baru
- Menampilkan aktivitas terbaru:
  - pengembalian buku
  - anggota baru
  - pembaruan koleksi
- Menampilkan aksi cepat.
- Menampilkan status layanan.
- Menampilkan prioritas hari ini.
- Menampilkan peminjaman terbaru.
- Menampilkan agenda terdekat.

### Manajemen Anggota
Fitur:
- Menampilkan daftar anggota dengan pagination.
- Pencarian berdasarkan nama atau NIK.
- Filter status anggota.
- Filter sanksi:
  - bebas
  - sanksi
  - diblokir
- Melihat detail anggota.
- Melihat e-kartu anggota.
- Melihat statistik total peminjaman dan keterlambatan anggota.
- Melihat riwayat peminjaman anggota.
- Mengedit data anggota:
  - nama lengkap
  - email
  - nomor telepon
  - alamat
  - status anggota
  - status sanksi
  - foto
- Mengubah sanksi anggota:
  - bersih
  - sanksi 15 hari
  - diblokir
- Status `Diblokir` membuat status anggota menjadi nonaktif.

### Manajemen Koleksi Buku
Fitur daftar koleksi:
- Menampilkan daftar buku dengan Livewire.
- Pencarian berdasarkan:
  - judul
  - ISBN
  - penulis
  - penerbit
  - kode buku
- Filter kategori.
- Filter status katalog.
- Statistik:
  - jumlah judul
  - jumlah eksemplar
  - jumlah dipinjam
  - jumlah tersedia
  - persentase ketersediaan
- Export data koleksi ke CSV.

Fitur tambah buku:
- Input judul, ISBN, penulis, penerbit, kategori, tahun terbit, deskripsi, cover, dan stok awal.
- Validasi ISBN unik.
- Validasi cover image.
- Membuat kode buku otomatis `BKU-XXXXXX`.
- Membuat eksemplar awal berdasarkan stok awal.
- Membuat mutasi stok awal.

Fitur edit buku:
- Edit data bibliografi.
- Ganti cover buku.
- Menghapus cover lama jika cover lokal diganti.

Fitur detail buku:
- Menampilkan detail buku.
- Menampilkan eksemplar.
- Menampilkan riwayat peminjaman terkait buku.

Fitur tambah stok:
- Menambah eksemplar buku.
- Input jumlah stok tambahan.
- Input sumber perolehan.
- Input tanggal penerimaan.
- Input catatan.
- Membuat kode eksemplar berurutan.
- Mencatat mutasi stok.
- Menggunakan transaksi dan lock pada buku untuk mencegah konflik penambahan stok.

Fitur hapus/nonaktifkan buku:
- Jika buku sedang dipinjam, buku tidak dapat dihapus atau dinonaktifkan.
- Jika buku memiliki eksemplar atau riwayat peminjaman, status katalog diubah menjadi nonaktif.
- Jika tidak memiliki eksemplar/riwayat, buku dapat dihapus permanen.

### Manajemen Berita
Fitur:
- Menampilkan daftar berita dengan pagination.
- Pencarian berita.
- Filter kategori.
- Filter status berita.
- Statistik total, terbit, dan draft.
- Tambah berita.
- Edit berita.
- Upload gambar berita.
- Generate slug unik dari judul.
- Simpan sebagai draft atau langsung terbit.
- Publish berita draft.
- Hapus berita dan gambar terkait.

### Manajemen Agenda/Event
Fitur:
- Menampilkan daftar agenda/event.
- Pencarian berdasarkan judul.
- Filter status.
- Statistik total, terbit, dan draft.
- Tambah agenda.
- Edit agenda.
- Upload gambar agenda.
- Generate slug unik.
- Simpan sebagai draft atau terbit.
- Menandai agenda agar tampil di beranda.
- Melihat detail agenda dan agenda terkait.
- Hapus agenda dan gambar terkait.

### Manajemen Pengumuman
Fitur:
- Menampilkan daftar pengumuman.
- Pencarian berdasarkan judul atau isi.
- Filter status:
  - aktif
  - mendatang
  - selesai
  - draf
- Statistik total, aktif, dan mendatang.
- Tambah pengumuman.
- Edit pengumuman.
- Upload gambar pengumuman.
- Upload beberapa lampiran.
- Menyimpan metadata lampiran:
  - nama file
  - path
  - ukuran file
- Generate slug unik.
- Menampilkan detail pengumuman.
- Menghitung total view saat detail dibuka.
- Menampilkan pengumuman terbaru lain.
- Hapus pengumuman, gambar, dan lampiran terkait.

### Manajemen Aduan
Fitur:
- Menampilkan daftar aduan anggota.
- Filter status:
  - menunggu
  - ditanggapi
  - diarsipkan
- Pencarian berdasarkan kode aduan atau nama anggota.
- Melihat detail aduan.
- Melihat riwayat jumlah aduan anggota yang sama.
- Melihat lampiran aduan.
- Memberikan tanggapan aduan.
- Mengubah status aduan menjadi:
  - diproses
  - selesai
- Arsipkan aduan.
- Mengembalikan aduan dari arsip.
- Menampilkan daftar arsip aduan.

### Manajemen Peminjaman
Fitur:
- Menampilkan daftar pengajuan peminjaman.
- Filter status:
  - menunggu
  - disetujui
  - ditolak
  - semua
- Pencarian berdasarkan:
  - kode peminjaman
  - nama anggota
  - nomor anggota
  - judul buku
- Statistik:
  - menunggu
  - disetujui hari ini
  - ditolak hari ini
  - total sirkulasi aktif
- Melihat detail pengajuan.
- Mengecek jumlah pinjaman aktif anggota.
- Mengecek sanksi aktif anggota.
- Menolak pengajuan dan mengirim notifikasi.
- Menyetujui pengajuan.
- Memilih eksemplar tersedia dengan `lockForUpdate`.
- Mengubah status eksemplar menjadi `dipesan`.
- Membuat jadwal pengambilan.
- Menghitung tanggal jatuh tempo berdasarkan aturan peminjaman.
- Mengirim notifikasi peminjaman disetujui.
- Menandai buku sudah diambil.
- Mengubah status peminjaman menjadi `aktif`.
- Mengubah status eksemplar menjadi `dipinjam`.
- Export daftar peminjaman ke CSV.

### Manajemen Pengembalian
Fitur:
- Menampilkan daftar peminjaman aktif/terlambat.
- Filter status:
  - semua
  - terlambat
  - sedang dipinjam
- Pencarian berdasarkan:
  - kode peminjaman
  - nama anggota
  - nomor anggota
  - judul buku
- Statistik:
  - total aktif
  - total terlambat
  - buku beredar
- Melihat detail peminjaman yang akan dikembalikan.
- Menghitung hari terlambat dari tanggal jatuh tempo.
- Form proses pengembalian.
- Input tanggal pengembalian.
- Input kondisi buku:
  - Baik
  - Rusak Ringan
  - Rusak Berat
  - Hilang
- Upload foto kondisi buku maksimal 5 MB.
- Preview sanksi sebelum konfirmasi.
- Mencatat pengembalian.
- Mencatat detail pengembalian.
- Mengubah status detail menjadi `dikembalikan`.
- Mengubah status eksemplar:
  - `tersedia` jika kondisi baik
  - `rusak` jika rusak ringan/berat
  - `hilang` jika hilang
- Membuat catatan keterlambatan jika terlambat.
- Membuat sanksi nonaktif peminjaman sesuai jumlah hari terlambat.
- Mengubah status peminjaman menjadi `selesai`.
- Mengirim notifikasi pengembalian/sanksi ke anggota.
- Menampilkan riwayat pengembalian.
- Filter riwayat berdasarkan status peminjaman, status pengembalian, sanksi, kondisi buku, dan rentang tanggal.
- Export riwayat pengembalian ke CSV.

## Laporan dan Export
| Fitur | Format | Route | Isi Utama |
| --- | --- | --- | --- |
| Export koleksi buku | CSV | `/petugas/koleksi/export` | Kode, judul, ISBN, penulis, kategori, total eksemplar, tersedia, status katalog. |
| Export peminjaman | CSV | `/petugas/peminjaman/export` | Kode peminjaman, anggota, buku, ISBN, tanggal pengajuan, status peminjaman. |
| Export pengembalian | CSV | `/petugas/pengembalian/export-csv` | Kode transaksi, anggota, buku, tanggal pinjam, tanggal kembali, keterlambatan, kondisi buku, sanksi. |

## Notifikasi Sistem
Notifikasi disimpan di tabel `notifikasi`.
Jenis notifikasi yang tampak di kode:
- `peminjaman_ditolak`
- `peminjaman_disetujui`
- `pengembalian_berhasil`
- `sanksi_aktif`

Fitur notifikasi:
- Status baca:
  - belum dibaca
  - dibaca
- Waktu dikirim.
- Waktu dibaca.
- Relasi ke peminjaman.
- Relasi ke jadwal pengambilan.
- Redirect ke tiket pengambilan untuk peminjaman yang disetujui.

## Aturan Bisnis Utama

### Keanggotaan
- Anggota baru otomatis berstatus `aktif`.
- Nomor anggota menggunakan NIK.
- E-kartu otomatis dibuat saat registrasi.
- Masa berlaku e-kartu default: 5 tahun berdasarkan `config/sipadi.php`.
- Anggota diblokir akan diperlakukan sebagai nonaktif.

### Peminjaman
- Hanya anggota aktif yang dapat mengajukan peminjaman.
- Anggota dengan sanksi aktif tidak dapat mengajukan peminjaman.
- Anggota tidak dapat mengajukan buku yang sama jika masih memiliki pengajuan/peminjaman aktif.
- Aturan peminjaman aktif wajib tersedia.
- Jumlah peminjaman aktif dibatasi oleh `maksimal_peminjaman_aktif`.
- Buku harus memiliki eksemplar tersedia.
- Saat pengajuan disetujui, satu eksemplar dikunci dan berubah status menjadi `dipesan`.
- Saat buku diambil, peminjaman menjadi `aktif` dan eksemplar menjadi `dipinjam`.

### Pengembalian
- Pengembalian hanya diproses untuk status `aktif` atau `terlambat`.
- Jika terlambat, sistem menghitung total hari terlambat.
- Sanksi keterlambatan memakai pola 1 hari terlambat = 1 hari nonaktif peminjaman.
- Kondisi buku menentukan status eksemplar setelah kembali.

### Aduan
- Hanya anggota terdaftar yang dapat membuat aduan.
- Aduan dapat dilacak dengan kode tiket.
- Petugas dapat menanggapi, menyelesaikan, mengarsipkan, dan mengembalikan aduan dari arsip.

## Data Awal dari Seeder
Seeder utama membuat data awal berikut:
- Aturan peminjaman default:
  - lama pinjam 14 hari
  - maksimal 5 buku per peminjaman
  - maksimal 3 peminjaman aktif
  - masa suspend 1 hari per hari terlambat
- Role:
  - Anggota
  - Petugas
- Akun petugas:
  - email: `petugas@sipadi.test`
  - password: `password`
- Kategori berita:
  - Kegiatan
  - Pengumuman
  - Artikel
- Contoh berita.
- Contoh agenda.
- Kategori buku.
- Contoh katalog buku dan eksemplar.

## Test Coverage yang Tersedia
File test fitur yang ditemukan:
- `AduanTest`
- `AnggotaManagementTest`
- `AnggotaNotifikasiTest`
- `AuthenticationTest`
- `EmailVerificationTest`
- `PasswordConfirmationTest`
- `PasswordResetTest`
- `PasswordUpdateTest`
- `RegistrationTest`
- `BeritaSlugWhiteBoxTest`
- `BeritaTest`
- `DashboardPetugasTest`
- `EKartuTest`
- `KatalogSeederTest`
- `KelolaBukuTest`
- `LandingPageNewsTest`
- `PengumumanTest`
- `PetugasPeminjamanTest`
- `PetugasPengembalianTest`
- `ProfileTest`
- `PublicAgendaTest`
- `PublicKatalogTest`
- `RegistrasiAnggotaTest`

Command test:

```bash
php artisan test --compact
```

## Catatan Status Fitur
Fitur yang sudah tampak jelas dari route/controller/view:
- Public landing page.
- Public katalog, berita, dan agenda.
- Registrasi anggota.
- Login/logout dan reset password.
- E-kartu anggota.
- Pengajuan peminjaman.
- Notifikasi anggota.
- Aduan dan tracking aduan.
- Dashboard petugas.
- Manajemen anggota.
- Manajemen buku/koleksi.
- Manajemen berita.
- Manajemen agenda.
- Manajemen pengumuman.
- Manajemen peminjaman.
- Manajemen pengembalian.
- Export CSV koleksi, peminjaman, dan pengembalian.

Fitur yang datanya/modelnya ada, tetapi route CRUD khusus belum tampak pada route aktif:
- Manajemen fasilitas.
- Manajemen layanan.
- Manajemen prestasi.
- Manajemen konten beranda.
- Manajemen kunjungan/statistik pengunjung.
- Manajemen aturan peminjaman dari UI.
- Manajemen role/permission dinamis dari UI.

Fitur yang disebut di README tetapi belum terlihat sebagai modul route lengkap:
- Profil perpustakaan.
- Struktur organisasi.
- Struktur kepegawaian.
- Statistik pengunjung dengan tambah/edit/hapus kunjungan.
- Notifikasi otomatis melalui email atau WhatsApp.
- Export laporan PDF.

## Matriks Akses Ringkas
| Fitur | Pengunjung | Anggota | Petugas |
| --- | --- | --- | --- |
| Landing page | Ya | Ya | Ya |
| Katalog dan detail buku | Ya | Ya | Ya |
| Berita public | Ya | Ya | Ya |
| Agenda public | Ya | Ya | Ya |
| Registrasi | Ya | Tidak perlu | Tidak perlu |
| Login/logout | Ya | Ya | Ya |
| E-kartu | Tidak | Ya | Lihat melalui data anggota |
| Pengajuan peminjaman | Tidak | Ya | Tidak |
| Riwayat peminjaman sendiri | Tidak | Ya | Tidak |
| Notifikasi sendiri | Tidak | Ya | Tidak |
| Aduan | Tracking saja | Buat dan tracking | Kelola dan tanggapi |
| Dashboard petugas | Tidak | Tidak | Ya |
| Kelola anggota | Tidak | Tidak | Ya |
| Kelola koleksi buku | Tidak | Tidak | Ya |
| Kelola berita | Tidak | Tidak | Ya |
| Kelola agenda | Tidak | Tidak | Ya |
| Kelola pengumuman | Tidak | Tidak | Ya |
| Kelola peminjaman | Tidak | Tidak | Ya |
| Kelola pengembalian | Tidak | Tidak | Ya |
| Export CSV | Tidak | Tidak | Ya |

## Alur Utama Sistem

### Alur Registrasi Anggota
1. Pengunjung membuka halaman registrasi.
2. Pengunjung mengisi data diri.
3. Sistem menyimpan data diri sementara di session.
4. Pengunjung membuat akun dengan email dan password.
5. Sistem membuat user, anggota, dan e-kartu.
6. Sistem login otomatis sebagai anggota.
7. Anggota diarahkan ke halaman e-kartu.

### Alur Peminjaman Buku
1. Anggota membuka katalog.
2. Anggota mencari dan memilih buku.
3. Anggota membuka form pengajuan peminjaman.
4. Sistem memvalidasi status anggota, sanksi, batas peminjaman, dan ketersediaan eksemplar.
5. Anggota mengirim pengajuan.
6. Petugas meninjau pengajuan.
7. Petugas menyetujui atau menolak pengajuan.
8. Jika disetujui, sistem membuat jadwal pengambilan dan notifikasi.
9. Anggota mengambil buku.
10. Petugas menandai buku sudah diambil.
11. Status peminjaman menjadi aktif.

### Alur Pengembalian Buku
1. Petugas membuka daftar pengembalian.
2. Petugas memilih peminjaman aktif/terlambat.
3. Petugas mengisi tanggal pengembalian dan kondisi buku.
4. Sistem menghitung keterlambatan dan preview sanksi.
5. Petugas mengonfirmasi pengembalian.
6. Sistem membuat record pengembalian.
7. Sistem mengubah status eksemplar.
8. Sistem membuat sanksi jika terlambat.
9. Sistem mengirim notifikasi ke anggota.

### Alur Aduan
1. Anggota membuka form aduan.
2. Anggota memilih kategori dan menulis isi aduan.
3. Anggota menambahkan lampiran opsional.
4. Sistem membuat kode tiket.
5. Anggota dapat melacak aduan dengan kode tiket.
6. Petugas melihat daftar aduan.
7. Petugas memberi tanggapan dan mengubah status.
8. Petugas dapat mengarsipkan aduan yang selesai.
