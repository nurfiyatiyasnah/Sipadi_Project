<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [LoginController::class, 'showRegister'])->name('register');
Route::post('/register', [LoginController::class, 'register']);

Route::middleware('auth')->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::get('/koleksi', [DashboardController::class, 'koleksi'])->name('dashboard.koleksi');
        Route::resource('books', BookController::class);
        // Tambahkan ini untuk ekspor laporan
        Route::get('/koleksi/export', [DashboardController::class, 'export'])->name('dashboard.koleksi.export');
    });

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
