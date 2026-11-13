<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

// Katalog buku publik (jadikan juga halaman utama)
Route::get('/', [BookController::class, 'index']);
Route::get('/katalog', [BookController::class, 'index'])->name('katalog');

// Detail buku
Route::get('/buku/{book}', [BookController::class, 'show'])->name('books.show');