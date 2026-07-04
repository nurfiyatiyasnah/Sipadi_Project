# Pengujian White Box Statement Coverage

**Proyek:** SIPADI - Sistem Informasi Perpustakaan Digital  
**Jenis Pengujian:** White Box Testing  
**Teknik:** Statement Coverage

## Kode Program

| No | Kode Program |
| --- | --- |
| 1 | `return view('auth.register');` |
| 2 | `session(['registration.data_diri' => $data, 'registration.foto_path' => $fotoPath]);` |
| 3 | `return redirect()->route('register.akun');` |
| 4 | `$user = User::create([...]);` |
| 5 | `$anggota = Anggota::create([...]);` |
| 6 | `EKartuAnggota::create([...]);` |
| 7 | `$request->authenticate();` |
| 8 | `$request->session()->regenerate();` |
| 9 | `return redirect()->intended(route('petugas.dashboard'));` |
| 10 | `return response($renderer->render($anggota, $eKartu), 200, [...]);` |

## Tabel Statement

| No | Statement | Kode |
| --- | --- | --- |
| 1 | Menampilkan halaman registrasi data diri anggota. | S1 |
| 2 | Menyimpan data diri dan path foto sementara ke session registrasi. | S2 |
| 3 | Mengarahkan pengguna dari langkah data diri ke langkah pembuatan akun. | S3 |
| 4 | Membuat data user baru berdasarkan email, password, role, dan status akun. | S4 |
| 5 | Membuat data anggota yang terhubung dengan user baru. | S5 |
| 6 | Membuat data e-kartu anggota setelah registrasi akun berhasil. | S6 |
| 7 | Memvalidasi kredensial login pengguna melalui request autentikasi. | S7 |
| 8 | Meregenerasi session setelah login berhasil. | S8 |
| 9 | Mengarahkan petugas yang berhasil login ke dashboard petugas. | S9 |
| 10 | Menghasilkan response download e-kartu dalam format PNG. | S10 |

## Tabel Skenario Pengujian

| ID Test | Tujuan Pengujian | Input | Output Diharapkan |
| --- | --- | --- | --- |
| TC-01 | Mengeksekusi statement S1: register screen render. | Akses halaman `/register`. | Halaman registrasi data diri tampil dengan status 200. |
| TC-02 | Mengeksekusi statement S2: data diri disimpan ke session. | Data diri valid: NIK 16 digit unik, nama, jenis kelamin, tanggal lahir, alamat, dan foto opsional. | Session `registration.data_diri` dan `registration.foto_path` tersimpan. |
| TC-03 | Mengeksekusi statement S3: redirect ke langkah akun. | Submit form data diri valid. | Sistem mengarahkan pengguna ke route `register.akun`. |
| TC-04 | Mengeksekusi statement S4: registrasi akun membuat user. | Email unik, password valid, konfirmasi password sama, terms disetujui. | Data user baru tersimpan di tabel `users`. |
| TC-05 | Mengeksekusi statement S5: registrasi akun membuat anggota. | Data diri valid di session dan data akun valid. | Data anggota baru tersimpan dan terhubung dengan user. |
| TC-06 | Mengeksekusi statement S6: registrasi akun membuat e-kartu. | Registrasi akun berhasil. | Data e-kartu anggota tersimpan dengan nomor anggota, kalangan, barcode, dan masa berlaku. |
| TC-07 | Mengeksekusi statement S7: login memvalidasi kredensial. | Email dan password user aktif valid. | Kredensial diterima dan user berhasil terautentikasi. |
| TC-08 | Mengeksekusi statement S8: login meregenerasi session. | Login valid. | Session login diregenerasi untuk mencegah session fixation. |
| TC-09 | Mengeksekusi statement S9: petugas diarahkan ke dashboard. | Login menggunakan akun role Petugas. | User diarahkan ke `/petugas/dashboard`. |
| TC-10 | Mengeksekusi statement S10: download e-kartu PNG. | Login sebagai Anggota lalu akses `/e-kartu/download`. | Sistem mengembalikan file PNG e-kartu dengan status 200. |

## Tabel Hasil Pengujian

| ID Test | Input | Output Diharapkan | Output Aktual |
| --- | --- | --- | --- |
| TC-01 | Akses halaman `/register`. | Halaman registrasi data diri tampil dengan status 200. | Halaman registrasi data diri tampil dengan status 200. |
| TC-02 | Data diri valid. | Session `registration.data_diri` dan `registration.foto_path` tersimpan. | Session registrasi tersimpan sesuai hasil pengujian. |
| TC-03 | Submit form data diri valid. | Sistem mengarahkan pengguna ke route `register.akun`. | Sistem mengarahkan pengguna ke halaman pembuatan akun. |
| TC-04 | Email unik, password valid, dan terms disetujui. | Data user baru tersimpan di tabel `users`. | Data user baru tersimpan sesuai hasil pengujian. |
| TC-05 | Data diri di session dan data akun valid. | Data anggota baru tersimpan dan terhubung dengan user. | Data anggota baru berhasil dibuat dan terhubung dengan user. |
| TC-06 | Registrasi akun berhasil. | Data e-kartu anggota tersimpan. | Data e-kartu anggota berhasil dibuat. |
| TC-07 | Email dan password user aktif valid. | Kredensial diterima dan user berhasil terautentikasi. | User berhasil login. |
| TC-08 | Login valid. | Session login diregenerasi. | Session berhasil diregenerasi setelah login. |
| TC-09 | Login menggunakan akun role Petugas. | User diarahkan ke `/petugas/dashboard`. | User diarahkan ke `/petugas/dashboard`. |
| TC-10 | Anggota akses `/e-kartu/download`. | Sistem mengembalikan file PNG e-kartu dengan status 200. | File PNG e-kartu berhasil diunduh dengan status 200. |

## Perhitungan Statement Coverage

| Jumlah Statement | Statement Tereksekusi | Statement Belum Tereksekusi | Statement Coverage |
| --- | --- | --- | --- |
| 10 | 10 | 0 | 100% |

**Statement Coverage = (10 / 10) x 100% = 100%**

## Kesimpulan

Berdasarkan pengujian statement coverage terhadap 10 statement utama pada fitur registrasi, login, redirect role petugas, dan download e-kartu, seluruh statement berhasil dieksekusi. Nilai statement coverage yang diperoleh adalah **100%**, sehingga jalur utama pada modul yang diuji telah tercakup oleh skenario pengujian.
