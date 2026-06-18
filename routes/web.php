<?php

use App\Http\Controllers\BeritaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EKartuController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing.index');
})->name('landing');

Route::middleware(['auth'])->get('/dashboard', function () {
    /** @var User $user */
    $user = Auth::user();

    if ($user->isPetugas()) {
        return redirect()->route('petugas.dashboard');
    }

    return redirect()->route('landing');
})->name('dashboard');

Route::middleware(['auth', 'role:Anggota'])->group(function () {
    Route::get('/e-kartu', [EKartuController::class, 'show'])->name('anggota.e-kartu');
    Route::get('/e-kartu/download', [EKartuController::class, 'download'])->name('anggota.e-kartu.download');
});

Route::middleware(['auth', 'role:Petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

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
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
