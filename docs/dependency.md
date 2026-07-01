# Analisis Dependency SIPADI

Dokumen ini merangkum dependency, file/folder hasil instalasi, konfigurasi runtime, dan bagian aplikasi yang ditemukan pada proyek SIPADI per 30 Juni 2026.

## Sumber Analisis

Analisis dilakukan dari file dan command berikut:

- `composer.json` dan `composer.lock`
- `package.json` dan `package-lock.json`
- `php artisan about`
- `php artisan migrate:status`
- `php artisan route:list --except-vendor`
- `vite.config.js`, `tailwind.config.js`, `postcss.config.js`
- Struktur folder `app`, `database`, `resources`, `routes`, `tests`, `vendor`, `node_modules`, `public`, dan `storage`

File `.env` tidak dirinci di dokumen ini karena dapat berisi kredensial lokal.

## Ringkasan Stack

| Area | Teknologi | Versi/Status |
| --- | --- | --- |
| Runtime backend | PHP | 8.2.12 |
| Framework backend | Laravel | 12.57.0 |
| UI reaktif server-side | Livewire | 4.3.1 |
| Auth scaffolding | Laravel Breeze | 2.4.2 |
| Frontend bundler | Vite | 7.3.5 |
| Styling | Tailwind CSS | 3.4.19 |
| JavaScript interactivity | Alpine.js | 3.15.12 |
| HTTP JavaScript client | Axios | 1.17.0 |
| Database aplikasi lokal | PostgreSQL | `database.default = pgsql` |
| Queue | Laravel database queue | `queue.default = database` |
| Session | Database session | `session.driver = database` |
| Cache | File cache | `cache.default = file` |
| Mail | Log mailer | `mail.default = log` |
| Testing | PHPUnit | 11.5.55 |
| Formatter PHP | Laravel Pint | 1.29.1 |
| Package manager PHP | Composer | 2.9.5 |
| Runtime frontend | Node.js | 24.14.0 |
| Package manager frontend | NPM | 11.9.0 |

## Dependency Composer Langsung

Dependency backend dipasang melalui `composer install` dan dikunci di `composer.lock`.

### Production

| Package | Constraint | Versi Terpasang | Fungsi di Proyek |
| --- | --- | --- | --- |
| `php` | `^8.2` | 8.2.12 CLI lokal | Runtime Laravel. |
| `laravel/framework` | `^12.0` | 12.57.0 | Framework utama aplikasi. |
| `livewire/livewire` | `^4.3` | 4.3.1 | Komponen interaktif untuk modul buku/koleksi/stok. |
| `laravel/tinker` | `^2.10.1` | 2.11.1 | REPL/debugging aplikasi Laravel. |
| `barryvdh/laravel-dompdf` | `^3.1` | 3.1.2 | Wrapper Dompdf untuk PDF. Terpasang, tetapi pemakaian langsung belum ditemukan pada kode saat ini. |

### Development

| Package | Constraint | Versi Terpasang | Fungsi di Proyek |
| --- | --- | --- | --- |
| `fakerphp/faker` | `^1.23` | 1.24.1 | Data palsu untuk factory dan test. |
| `laravel/boost` | `^2.2` | 2.4.5 | Bantuan AI/MCP dan guideline Laravel. |
| `laravel/breeze` | `*` | 2.4.2 | Scaffolding autentikasi Blade/Tailwind. |
| `laravel/pail` | `^1.2.2` | 1.2.6 | Pembacaan log dari CLI. |
| `laravel/pint` | `^1.24` | 1.29.1 | Formatter kode PHP. |
| `laravel/sail` | `^1.41` | 1.57.0 | Opsi environment Docker Laravel. |
| `mockery/mockery` | `^1.6` | 1.6.12 | Mock object untuk test. |
| `nunomaduro/collision` | `^8.6` | 8.9.4 | Tampilan error CLI saat development/test. |
| `phpunit/phpunit` | `^11.5.50` | 11.5.55 | Framework test PHPUnit. |

### Ringkasan Composer Lock

`composer.lock` mencatat 123 package total:

- 84 production/transitive package.
- 39 development/transitive package.
- Package penting yang ikut terpasang secara transitif: `dompdf/dompdf`, `guzzlehttp/guzzle`, `monolog/monolog`, `nesbot/carbon`, `ramsey/uuid`, `symfony/*`, `league/flysystem`, `vlucas/phpdotenv`, `laravel/prompts`, dan `psy/psysh`.

Lockfile adalah sumber daftar lengkap package transitif. Jangan mengubah `vendor/` secara manual; ubah dependency melalui `composer.json` lalu jalankan `composer update` atau `composer install` sesuai kebutuhan.

## Dependency NPM Langsung

Dependency frontend dipasang melalui `npm install` dan dikunci di `package-lock.json`.

| Package | Constraint | Versi Terpasang | Fungsi di Proyek |
| --- | --- | --- | --- |
| `vite` | `^7.0.7` | 7.3.5 | Build tool frontend. |
| `laravel-vite-plugin` | `^2.0.0` | 2.1.0 | Integrasi Vite dengan Laravel. |
| `tailwindcss` | `^3.1.0` | 3.4.19 | Utility CSS utama. |
| `@tailwindcss/forms` | `^0.5.2` | 0.5.11 | Styling form Tailwind. |
| `postcss` | `^8.4.31` | 8.5.15 | Pipeline CSS untuk Tailwind. |
| `autoprefixer` | `^10.4.2` | 10.5.0 | Prefix CSS otomatis. |
| `alpinejs` | `^3.4.2` | 3.15.12 | Interaksi ringan di Blade. |
| `axios` | `^1.11.0` | 1.17.0 | HTTP client JavaScript. |
| `concurrently` | `^9.0.1` | 9.2.1 | Menjalankan server, queue listener, dan Vite bersamaan via Composer script. |
| `@tailwindcss/vite` | `^4.0.0` | 4.3.0 | Plugin Tailwind Vite v4. Terpasang, tetapi belum dipakai di `vite.config.js`. |

### Ringkasan NPM Lock

`package-lock.json` memakai `lockfileVersion` 3 dan mencatat 231 entry package. Semua dependency NPM proyek ini berada di `devDependencies` karena aplikasi menggunakan Laravel Blade sebagai entry utama, bukan aplikasi frontend terpisah.

Catatan penting:

- Tailwind aktif melalui `postcss.config.js` dengan `tailwindcss` dan `autoprefixer`.
- `vite.config.js` hanya memuat `laravel-vite-plugin`.
- `@tailwindcss/vite` v4 terpasang, tetapi tidak aktif pada konfigurasi Vite saat ini.
- Karena `@tailwindcss/vite` membawa paket Tailwind v4 transitif, lockfile memuat Tailwind v3 dan v4 sekaligus. Yang dipakai oleh konfigurasi proyek saat ini adalah Tailwind v3.4.19.

## File dan Folder Hasil Instalasi

Folder berikut adalah hasil instalasi, build, cache, atau runtime generated files.

| Path | Isi | Status |
| --- | --- | --- |
| `vendor/` | 1.772 folder, 10.096 file | Hasil `composer install`. Jangan diedit manual. |
| `node_modules/` | 612 folder, 5.840 file | Hasil `npm install`. Jangan diedit manual. |
| `public/build/` | 1 folder, 3 file | Hasil `npm run build` Vite. |
| `public/storage` | 7 folder, 15 file | Link publik ke `storage/app/public`; status `LINKED`. |
| `storage/app/` | 10 folder, 21 file | File upload/aplikasi lokal. |
| `storage/framework/` | 33 folder, 154 file | Cache Laravel seperti view/session/framework files. |
| `storage/logs/` | 3 file | Log aplikasi Laravel. |

Folder yang biasanya dikontrol source code:

| Root | Folder | File | Keterangan |
| --- | ---: | ---: | --- |
| `app` | 11 | 78 | Kode aplikasi utama. |
| `bootstrap` | 1 | 5 | Bootstrap Laravel 12 dan cache bootstrap. |
| `config` | 0 | 11 | Konfigurasi aplikasi. |
| `database` | 3 | 63 | Migration, factory, dan seeder. |
| `docs` | 0 | 6 | Dokumentasi proyek. |
| `public` | 4 | 9 | Entry publik dan asset build/link. |
| `resources` | 24 | 91 | Blade view, CSS, JS. |
| `routes` | 0 | 3 | Definisi route web, auth, console. |
| `tests` | 3 | 26 | Test PHPUnit. |

## Konfigurasi Build Frontend

Entry Vite:

- `resources/css/app.css`
- `resources/js/app.js`

Konfigurasi terkait:

- `vite.config.js` memakai `laravel-vite-plugin` dengan refresh otomatis.
- `resources/css/app.css` berisi direktif `@tailwind base`, `@tailwind components`, dan `@tailwind utilities`.
- `tailwind.config.js` memindai Blade di `resources/views/**/*.blade.php`, view pagination Laravel, dan compiled views di `storage/framework/views/*.php`.
- Font yang diperluas: `Figtree` untuk sans dan `Playfair Display` untuk serif.
- `resources/js/app.js` memulai Alpine hanya jika `window.Alpine` belum ada.
- `resources/js/bootstrap.js` memasang Axios ke `window.axios` dan header `X-Requested-With`.

## Struktur Aplikasi Laravel

Ringkasan file di folder `app`:

| Area | Jumlah File | Catatan |
| --- | ---: | --- |
| Controllers | 26 | Modul public, anggota, petugas, auth, buku, berita, agenda, aduan, pengumuman, peminjaman, pengembalian. |
| Form Requests | 10 | Validasi auth, profile, berita, agenda, pengumuman. |
| Livewire Components | 5 | `BukuCreate`, `BukuEdit`, `BukuDetail`, `KoleksiBukuIndex`, `TambahStokBuku`. |
| Middleware | 1 | `RoleMiddleware`, alias route `role`. |
| Models | 32 | Entitas utama SIPADI. |
| Providers | 1 | `AppServiceProvider`. |
| View Components | 2 | Layout app dan guest. |
| Other App Classes | 1 | `EKartuPngRenderer`. |

## Route dan Modul Fitur

`php artisan route:list --except-vendor` menemukan 93 route aplikasi.

Modul public:

- Landing page.
- Berita public.
- Katalog public.
- Agenda public.
- Pelacakan aduan.

Modul anggota:

- Dashboard anggota.
- Peminjaman saya.
- E-kartu.
- Pembuatan aduan.
- Pengajuan peminjaman.
- Notifikasi anggota.

Modul petugas:

- Dashboard petugas.
- Manajemen aduan dan arsip.
- Manajemen anggota.
- Manajemen koleksi dan buku.
- Manajemen berita.
- Manajemen agenda.
- Manajemen pengumuman.
- Manajemen peminjaman.
- Manajemen pengembalian.

Autentikasi menggunakan route Breeze dengan login, register bertahap, reset password, verifikasi email, konfirmasi password, update password, dan logout.

## Database dan Migration

Status migrasi lokal: 50 migration sudah `Ran`.

Koneksi aktif dari konfigurasi lokal:

- Database: `pgsql`
- Queue: `database`
- Session: `database`

Kelompok tabel dari migration:

| Kelompok | Tabel/Fungsi |
| --- | --- |
| Auth dan role | `users`, `roles`, kolom role user, support auth tambahan. |
| Keanggotaan | `anggota`, `petugas`, `e_kartu_anggota`. |
| Katalog buku | `kategori_buku`, `buku`, `eksemplar_buku`, `mutasi_stok_buku`, `log_pencarian_buku`. |
| Peminjaman | `aturan_peminjaman`, `peminjaman`, `detail_peminjaman`, `jadwal_pengambilan`. |
| Pengembalian dan sanksi | `pengembalian`, `detail_pengembalian`, `keterlambatan`, `sanksi_anggota`. |
| Konten | `kategori_berita`, `berita`, `pengumuman`, `konten_beranda`, `prestasi`, `layanan`, `fasilitas`, `agenda_event`. |
| Aduan | `aduan`, `tanggapan_aduan`, `arsip_aduan`. |
| Notifikasi | `notifikasi`, `log_pengiriman_notifikasi`. |
| Queue | `jobs`, `failed_jobs`, `job_batches`. |
| Statistik | `kunjungan`. |

Factory tersedia untuk beberapa entitas utama seperti `User`, `Anggota`, `Petugas`, `Buku`, `EksemplarBuku`, `KategoriBuku`, `Berita`, `KategoriBerita`, dan `EKartuAnggota`.

Seeder yang ditemukan:

- `DatabaseSeeder`
- `KatalogSeeder`

## Storage dan File Upload

Konfigurasi filesystem menggunakan disk:

- `local`: `storage/app/private`
- `public`: `storage/app/public`, diekspos melalui `public/storage`
- `s3`: tersedia di konfigurasi, tetapi bergantung pada environment AWS

Pemakaian storage yang ditemukan:

- Upload gambar berita.
- Upload gambar pengumuman dan lampiran pengumuman.
- Upload gambar agenda.
- Upload foto anggota.
- Upload lampiran aduan.
- Upload foto/berkas terkait pengembalian.
- Cover buku melalui Livewire.

## PDF dan E-Kartu

Dependency PDF `barryvdh/laravel-dompdf` dan `dompdf/dompdf` sudah terpasang.

Kondisi pemakaian saat ini:

- Belum ditemukan pemanggilan langsung `Dompdf`, `Pdf`, atau facade PDF pada kode aplikasi.
- `EKartuController::download()` saat ini menghasilkan file `image/png` dari `App\EKartuPngRenderer`, bukan PDF.
- Beberapa view masih menampilkan teks terkait PDF, sehingga dependency Dompdf kemungkinan disiapkan untuk kebutuhan export PDF berikutnya.

## Laravel Boost dan MCP

Proyek memiliki konfigurasi Laravel Boost:

- `.mcp.json` mendaftarkan server MCP `laravel-boost` dengan command `php artisan boost:mcp`.
- `boost.json` mengaktifkan guideline, MCP, dan skill:
  - `laravel-best-practices`
  - `livewire-development`
  - `tailwindcss-development`

Package terkait Boost yang terpasang:

- `laravel/boost`
- `laravel/mcp`
- `laravel/roster`

## Testing

Testing menggunakan PHPUnit.

Ringkasan:

- 26 file test.
- 50 migration tersedia dan seluruhnya sudah berjalan di database lokal.
- `phpunit.xml` memakai SQLite in-memory untuk environment testing.
- Environment testing memakai:
  - `DB_CONNECTION=sqlite`
  - `DB_DATABASE=:memory:`
  - `CACHE_STORE=array`
  - `QUEUE_CONNECTION=sync`
  - `SESSION_DRIVER=array`
  - `MAIL_MAILER=array`

Command yang relevan:

```bash
php artisan test --compact
vendor/bin/pint --dirty --format agent
```

## Composer Scripts

Script penting dari `composer.json`:

| Script | Fungsi |
| --- | --- |
| `composer run setup` | Install Composer, buat `.env`, generate key, migrate, install NPM, build frontend. |
| `composer run dev` | Menjalankan `php artisan serve`, `queue:listen`, dan `npm run dev` secara paralel via `concurrently`. |
| `composer test` | Clear config lalu menjalankan `php artisan test`. |
| `post-autoload-dump` | Laravel package discovery. |
| `post-update-cmd` | Publish Laravel assets dan menjalankan `php artisan boost:update`. |

## NPM Scripts

Script dari `package.json`:

| Script | Fungsi |
| --- | --- |
| `npm run dev` | Menjalankan Vite development server. |
| `npm run build` | Build asset production ke `public/build`. |

## Catatan Pemeliharaan

- `composer.lock` dan `package-lock.json` wajib ikut commit agar versi dependency konsisten.
- `vendor/`, `node_modules/`, `storage/framework/`, dan `public/build/` adalah hasil install/build/cache; jangan diedit manual.
- Jika UI tidak berubah setelah edit Blade/CSS/JS, jalankan `npm run dev` saat development atau `npm run build` untuk output production.
- Jika menambah package PHP, gunakan Composer dan cek dampaknya pada `composer.lock`.
- Jika menambah package frontend, gunakan NPM dan cek dampaknya pada `package-lock.json`.
- Setelah mengubah kode PHP, jalankan `vendor/bin/pint --dirty --format agent`.
- Setelah mengubah fitur, jalankan test terkait dengan `php artisan test --compact`.
