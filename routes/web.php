<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Katalog buku publik (halaman utama)
Route::get('/', [BookController::class, 'index']);
Route::get('/katalog', [BookController::class, 'index'])->name('katalog');

// Detail buku
Route::get('/buku/{book}', [BookController::class, 'show'])->name('books.show');

// Login via Google (Socialite)
Route::get('/auth/google', [\App\Http\Controllers\Auth\GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [\App\Http\Controllers\Auth\GoogleController::class, 'callback']);

// Setelah login diarahkan ke katalog (bukan dashboard)
Route::get('/dashboard', fn () => redirect()->route('katalog'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Reader & progress
    Route::get('/baca/{book}', [\App\Http\Controllers\ReaderController::class, 'show'])->name('books.read');
    Route::get('/baca/{book}/file', [\App\Http\Controllers\ReaderController::class, 'file'])->name('books.file');
    Route::post('/baca/{book}/progress', [\App\Http\Controllers\ReadingProgressController::class, 'update'])->name('books.progress');
});

require __DIR__.'/auth.php';