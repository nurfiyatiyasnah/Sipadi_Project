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

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/koleksi', [DashboardController::class, 'koleksi'])->name('admin.dashboard.koleksi');
    Route::get('/books/export', [DashboardController::class, 'export'])->name('admin.dashboard.koleksi.export');

    Route::resource('books', BookController::class);

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
