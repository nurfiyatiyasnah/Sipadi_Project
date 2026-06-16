<?php

use App\Http\Controllers\AgendaEventController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/agenda/create', [AgendaEventController::class, 'create'])->name('agenda.create');
    Route::post('/agenda', [AgendaEventController::class, 'store'])->name('agenda.store');
});

require __DIR__.'/auth.php';
