<?php

use App\Http\Controllers\AduanController;
use App\Http\Controllers\AgendaEventController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AnggotaDashboardController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EKartuController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\PengajuanPeminjamanController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\PetugasPeminjamanController;
use App\Http\Controllers\PetugasPengembalianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicAgendaController;
use App\Http\Controllers\PublicBeritaController;
use App\Http\Controllers\PublicKatalogController;
use App\Http\Controllers\PublicLayananController;
use App\Models\AgendaEvent;
use App\Models\Anggota;
use App\Models\Berita;
use App\Models\Buku;
use App\Models\EksemplarBuku;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $beritaList = Berita::query()
        ->published()
        ->with('kategoriBerita')
        ->latest('id_berita')
        ->limit(3)
        ->get();

    $agendaList = AgendaEvent::query()
        ->where('status_event', 'terbit')
        ->latest('id_event')
        ->limit(3)
        ->get();

    // Statistik beranda dari database
    $koleksiBuku = Buku::count();
    $jumlahBuku = EksemplarBuku::count();
    $anggotaAktif = Anggota::where('status_anggota', 'aktif')->count();

    // Pilihan buku dari database (3 buku terbaru)
    $pilihanBuku = Buku::query()
        ->with('kategori')
        ->withCount('eksemplar')
        ->withCount([
            'eksemplar as eksemplar_tersedia_count' => fn ($q) => $q
                ->whereIn('status_eksemplar', ['tersedia', 'Tersedia']),
        ])
        ->latest('id_buku')
        ->limit(3)
        ->get();

    return view('landing.index', compact(
        'beritaList',
        'agendaList',
        'koleksiBuku',
        'jumlahBuku',
        'anggotaAktif',
        'pilihanBuku'
    ));
})->name('landing');

Route::get('/berita', [PublicBeritaController::class, 'index'])->name('berita.public.index');
Route::get('/berita/{slug}', [PublicBeritaController::class, 'show'])->name('berita.public.show');

Route::get('/katalog', [PublicKatalogController::class, 'index'])->name('katalog');
Route::get('/katalog/{buku}', [PublicKatalogController::class, 'show'])->name('katalog.show');
Route::get('/agenda', [PublicAgendaController::class, 'index'])->name('agenda.index');
Route::get('/agenda/{slug}', [PublicAgendaController::class, 'show'])->name('agenda.show');
Route::get('/layanan', [PublicLayananController::class, 'index'])->name('layanan.index');
Route::get('/layanan/{layanan:slug}', [PublicLayananController::class, 'show'])->name('layanan.show');
Route::view('/tentang-kami', 'landing.tentang')->name('tentang');
Route::get('/aduan/lacak', [AduanController::class, 'track'])->name('aduan.track');

Route::middleware(['auth'])->get('/dashboard', function () {
    /** @var User $user */
    $user = Auth::user();

    if ($user->isPetugas()) {
        return redirect()->route('petugas.dashboard');
    }

    return redirect()->route('anggota.dashboard');
})->name('dashboard');

Route::middleware(['auth', 'role:Anggota'])->group(function () {
    Route::get('/beranda', function () {
        return redirect()->route('landing');
    })->name('anggota.dashboard');
    Route::get('/peminjaman-saya', [AnggotaDashboardController::class, 'peminjamanSaya'])->name('anggota.peminjaman-saya');
    Route::get('/e-kartu', [EKartuController::class, 'show'])->name('anggota.e-kartu');
    Route::get('/e-kartu/download', [EKartuController::class, 'download'])->name('anggota.e-kartu.download');
    Route::get('/aduan/tambah', [AduanController::class, 'create'])->name('aduan.create');
    Route::post('/aduan/tambah', [AduanController::class, 'store'])->name('aduan.store');

    // Pengajuan Peminjaman
    Route::get('/peminjaman/{buku}/ajukan', [PengajuanPeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman/{buku}/ajukan', [PengajuanPeminjamanController::class, 'store'])->name('peminjaman.store');

    // Notifikasi & Tiket Peminjaman
    Route::get('/anggota/notifikasi', [AnggotaDashboardController::class, 'indexNotifikasi'])->name('anggota.notifikasi.index');
    Route::get('/anggota/notifikasi/{notifikasi}', [AnggotaDashboardController::class, 'readNotifikasi'])->name('anggota.notifikasi.read');
});

Route::middleware(['auth', 'role:Petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Aduan (Complaints) Routes
    Route::get('/aduan', [AduanController::class, 'indexPetugas'])->name('aduan.index');
    Route::get('/aduan/arsip', [AduanController::class, 'arsipPetugas'])->name('aduan.arsip');
    Route::get('/aduan/{aduan}', [AduanController::class, 'showPetugas'])->name('aduan.show');
    Route::get('/aduan/{aduan}/tanggapi', [AduanController::class, 'createTanggapan'])->name('aduan.tanggapi');
    Route::post('/aduan/{aduan}/tanggapi', [AduanController::class, 'storeTanggapan'])->name('aduan.store-tanggapi');
    Route::post('/aduan/{aduan}/arsip', [AduanController::class, 'toggleArsip'])->name('aduan.toggle-arsip');

    Route::get('/anggota', [AnggotaController::class, 'index'])->name('anggota.index');
    Route::get('/anggota/{anggota}', [AnggotaController::class, 'show'])->name('anggota.show');
    Route::get('/anggota/{anggota}/edit', [AnggotaController::class, 'edit'])->name('anggota.edit');
    Route::put('/anggota/{anggota}', [AnggotaController::class, 'update'])->name('anggota.update');

    Route::get('/koleksi', [DashboardController::class, 'koleksi'])
        ->name('koleksi');

    Route::get('/koleksi/export', [DashboardController::class, 'export'])
        ->name('koleksi.export');

    Route::get('/buku/tambah', [BukuController::class, 'create'])->name('buku.create');
    Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');
    Route::get('/buku/{id}/edit', [BukuController::class, 'edit'])->name('buku.edit');
    Route::get('/buku/{id}/tambah-stok', [BukuController::class, 'tambahStok'])->name('buku.tambah-stok');

    Route::get('/berita', [BeritaController::class, 'index'])
        ->name('berita.index');
    Route::get('/berita/tambah', [BeritaController::class, 'create'])
        ->name('berita.create');
    Route::post('/berita', [BeritaController::class, 'store'])
        ->name('berita.store');
    Route::get('/berita/{berita}/edit', [BeritaController::class, 'edit'])
        ->name('berita.edit');
    Route::put('/berita/{berita}', [BeritaController::class, 'update'])
        ->name('berita.update');
    Route::patch('/berita/{berita}/publish', [BeritaController::class, 'publish'])
        ->name('berita.publish');
    Route::delete('/berita/{berita}', [BeritaController::class, 'destroy'])
        ->name('berita.destroy');

    Route::get('/agenda', [AgendaEventController::class, 'index'])->name('agenda.index');
    Route::get('/agenda/tambah', [AgendaEventController::class, 'create'])->name('agenda.create');
    Route::post('/agenda', [AgendaEventController::class, 'store'])->name('agenda.store');
    Route::get('/agenda/{agenda}', [AgendaEventController::class, 'show'])->name('agenda.show');
    Route::get('/agenda/{agenda}/edit', [AgendaEventController::class, 'edit'])->name('agenda.edit');
    Route::put('/agenda/{agenda}', [AgendaEventController::class, 'update'])->name('agenda.update');
    Route::delete('/agenda/{agenda}', [AgendaEventController::class, 'destroy'])->name('agenda.destroy');

    // Pengumuman routes
    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::get('/pengumuman/tambah', [PengumumanController::class, 'create'])->name('pengumuman.create');
    Route::post('/pengumuman', [PengumumanController::class, 'store'])->name('pengumuman.store');
    Route::get('/pengumuman/{pengumuman}', [PengumumanController::class, 'show'])->name('pengumuman.show');
    Route::get('/pengumuman/{pengumuman}/edit', [PengumumanController::class, 'edit'])->name('pengumuman.edit');
    Route::put('/pengumuman/{pengumuman}', [PengumumanController::class, 'update'])->name('pengumuman.update');
    Route::delete('/pengumuman/{pengumuman}', [PengumumanController::class, 'destroy'])->name('pengumuman.destroy');

    // Peminjaman routes for Petugas
    Route::get('/peminjaman', [PetugasPeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/export', [PetugasPeminjamanController::class, 'export'])->name('peminjaman.export');
    Route::get('/peminjaman/{peminjaman}', [PetugasPeminjamanController::class, 'show'])->name('peminjaman.show');
    Route::post('/peminjaman/{peminjaman}/tolak', [PetugasPeminjamanController::class, 'reject'])->name('peminjaman.tolak');
    Route::get('/peminjaman/{peminjaman}/setujui', [PetugasPeminjamanController::class, 'approveForm'])->name('peminjaman.approve-form');
    Route::post('/peminjaman/{peminjaman}/setujui', [PetugasPeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('/peminjaman/{peminjaman}/ambil', [PetugasPeminjamanController::class, 'markAsPickedUp'])->name('peminjaman.ambil');

    // Pengembalian routes for Petugas
    Route::get('/pengembalian', [PetugasPengembalianController::class, 'index'])->name('pengembalian.index');
    Route::get('/pengembalian/riwayat', [PetugasPengembalianController::class, 'riwayat'])->name('pengembalian.riwayat');
    Route::get('/pengembalian/export-csv', [PetugasPengembalianController::class, 'exportCsv'])->name('pengembalian.export-csv');
    Route::get('/pengembalian/{peminjaman}', [PetugasPengembalianController::class, 'show'])->name('pengembalian.show');
    Route::get('/pengembalian/{peminjaman}/proses', [PetugasPengembalianController::class, 'prosesForm'])->name('pengembalian.proses-form');
    Route::post('/pengembalian/{peminjaman}/sanksi', [PetugasPengembalianController::class, 'prosesSanksi'])->name('pengembalian.proses-sanksi');
    Route::post('/pengembalian/{peminjaman}/simpan', [PetugasPengembalianController::class, 'store'])->name('pengembalian.store');

    Route::get('/layanan', [LayananController::class, 'index'])->name('layanan.index');
    Route::get('/layanan/tambah', [LayananController::class, 'create'])->name('layanan.create');
    Route::post('/layanan', [LayananController::class, 'store'])->name('layanan.store');
    Route::get('/layanan/{layanan}', [LayananController::class, 'show'])->name('layanan.show');
    Route::get('/layanan/{layanan}/edit', [LayananController::class, 'edit'])->name('layanan.edit');
    Route::put('/layanan/{layanan}', [LayananController::class, 'update'])->name('layanan.update');
    Route::delete('/layanan/{layanan}', [LayananController::class, 'destroy'])->name('layanan.destroy');
});

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/koleksi', [DashboardController::class, 'koleksi'])->name('admin.dashboard.koleksi');
    Route::get('/dashboard/koleksi/export', [DashboardController::class, 'export'])->name('admin.dashboard.koleksi.export');

    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/{buku}', [BookController::class, 'show'])->name('books.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';
