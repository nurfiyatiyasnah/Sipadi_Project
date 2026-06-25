<?php

use App\Http\Controllers\AgendaEventController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EKartuController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\ProfileController;
use App\Models\AgendaEvent;
use App\Http\Controllers\PublicAgendaController;
use App\Http\Controllers\PublicBeritaController;
use App\Http\Controllers\PublicKatalogController;
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
    Route::get('/e-kartu', [EKartuController::class, 'show'])->name('anggota.e-kartu');
    Route::get('/e-kartu/download', [EKartuController::class, 'download'])->name('anggota.e-kartu.download');
});

Route::middleware(['auth', 'role:Petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/anggota', [AnggotaController::class, 'index'])->name('anggota.index');
    Route::get('/anggota/{anggota}', [AnggotaController::class, 'show'])->name('anggota.show');
    Route::get('/anggota/{anggota}/edit', [AnggotaController::class, 'edit'])->name('anggota.edit');
    Route::put('/anggota/{anggota}', [AnggotaController::class, 'update'])->name('anggota.update');

    Route::get('/koleksi', [DashboardController::class, 'koleksi'])
        ->name('koleksi');

    Route::get('/koleksi/export', [DashboardController::class, 'export'])
        ->name('koleksi.export');

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
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
