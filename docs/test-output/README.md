# Bukti Eksekusi Automated Testing

Jalankan perintah berikut dari root project:

```bash
php artisan test --compact
```

Untuk test fitur utama:

```bash
php artisan test --compact tests/Feature/RegistrasiAnggotaTest.php
php artisan test --compact tests/Feature/EKartuTest.php
php artisan test --compact tests/Feature/DashboardPetugasTest.php
php artisan test --compact tests/Feature/BeritaTest.php
```

Simpan output terminal ke file ini:

```bash
php artisan test --compact > docs/test-output/phpunit-output.txt
```

Ambil screenshot hasil eksekusi terminal dan simpan ke:

```text
docs/screenshots/phpunit-result.png
```

Catatan: Pada sesi Codex ini, command Laravel diblokir policy sandbox sehingga bukti eksekusi terminal harus dibuat langsung dari terminal lokal.
