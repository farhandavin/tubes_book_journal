<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\ProfileController; // 👈 PENTING: Tambahkan ini

// --- Halaman Utama (Daftar Buku) ---
Route::get('/', [BookController::class, 'index'])->name('home');

// --- Group Middleware Auth ---
Route::middleware(['auth'])->group(function () {
    
    // 1. Route Fitur Buku (CRUD)
    Route::get('/add', [BookController::class, 'addForm'])->name('book.add');
    Route::post('/search', [BookController::class, 'searchApi'])->name('book.search');
    Route::post('/add', [BookController::class, 'store'])->name('book.store');
    
    Route::get('/edit/{id}', [BookController::class, 'editForm'])->name('book.edit');
    Route::post('/edit/{id}', [BookController::class, 'update'])->name('book.update');
    Route::post('/delete/{id}', [BookController::class, 'destroy'])->name('book.delete');
    
    Route::get('/export-csv', [BookController::class, 'exportCsv'])->name('book.export');
    Route::get('/ai-recommendation', [AIController::class, 'index'])->name('ai.index');
    Route::post('/ai-recommendation', [AIController::class, 'askAI'])->name('ai.ask');

    // 👇 2. Route Profile (TAMBAHKAN BAGIAN INI AGAR ERROR HILANG) 👇
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- Route Dashboard Bawaan Breeze ---
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';