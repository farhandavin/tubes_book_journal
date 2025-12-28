<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BorrowController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Halaman Utama (Daftar Buku) ---
Route::get('/', [BookController::class, 'index'])->name('home');

// --- Route Dashboard Bawaan Breeze ---
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// --- Group Middleware Auth (Fitur untuk User Login) ---
Route::middleware(['auth'])->group(function () {

    // 1. Route Fitur Buku (CRUD)
    Route::get('/add', [BookController::class, 'addForm'])->name('book.add');
    Route::post('/add', [BookController::class, 'store'])->name('book.store');
    Route::post('/search', [BookController::class, 'searchApi'])->name('book.search');

    Route::get('/edit/{id}', [BookController::class, 'editForm'])->name('book.edit');
    Route::post('/edit/{id}', [BookController::class, 'update'])->name('book.update');
    Route::post('/delete/{id}', [BookController::class, 'destroy'])->name('book.delete');

    Route::get('/export-csv', [BookController::class, 'exportCsv'])->name('book.export');

    // 2. Route Analisis Sentimen & Rekomendasi (AI)
    Route::post('/book/{id}/analyze', [AIController::class, 'analyzeSentiment'])->name('book.analyze');
    Route::get('/ai-recommendation', [AIController::class, 'index'])->name('ai.index');
    Route::post('/ai-recommendation', [AIController::class, 'askAI'])->name('ai.ask');

    // 3. Route Peminjaman (Borrow)
    Route::post('/book/{id}/borrow', [BorrowController::class, 'borrow'])->name('book.borrow');
    Route::post('/borrowing/{id}/return', [BorrowController::class, 'returnBook'])->name('borrow.return');
    Route::get('/my-books', [BorrowController::class, 'myBooks'])->name('my.books');

    // 4. Route Profile (Pengaturan Akun)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- Group Middleware Admin (Akses Khusus Admin) ---
Route::middleware(['auth', 'verified', \App\Http\Middleware\IsAdmin::class])
    ->prefix('admin') 
    ->name('admin.')  
    ->group(function () {

        // Dashboard Khusus Admin
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Manajemen User (CRUD Admin)
        Route::get('/users', [AdminController::class, 'index'])->name('users');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        
        // Menggunakan users.destroy agar lebih deskriptif
        Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('users.destroy');

        // Export Laporan Admin
        Route::get('/export/users', [AdminController::class, 'exportUsers'])->name('export.users');
        Route::get('/export/books', [AdminController::class, 'exportBooks'])->name('export.books');
    });

require __DIR__ . '/auth.php';