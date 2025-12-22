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
    // Route Analisis Sentimen
    Route::post('/book/{id}/analyze', [AIController::class, 'analyzeSentiment'])->name('book.analyze');
    Route::get('/ai-recommendation', [AIController::class, 'index'])->name('ai.index');
    Route::post('/ai-recommendation', [AIController::class, 'askAI'])->name('ai.ask');

    // 👇 2. Route Profile (TAMBAHKAN BAGIAN INI AGAR ERROR HILANG) 👇
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::post('/book/{id}/borrow', [App\Http\Controllers\BorrowController::class, 'borrow'])->name('book.borrow');
    Route::post('/borrowing/{id}/return', [App\Http\Controllers\BorrowController::class, 'returnBook'])->name('borrow.return');
    Route::get('/my-books', [App\Http\Controllers\BorrowController::class, 'myBooks'])->name('my.books');
});

// --- Route Dashboard Bawaan Breeze ---
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware([App\Http\Middleware\IsAdmin::class])->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Manajemen User (CRUD)
    Route::get('/admin/users', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.users');
    Route::get('/admin/users/create', [App\Http\Controllers\AdminController::class, 'createUser'])->name('admin.users.create'); // Baru
    Route::post('/admin/users', [App\Http\Controllers\AdminController::class, 'storeUser'])->name('admin.users.store'); // Baru
    Route::delete('/admin/users/{id}', [App\Http\Controllers\AdminController::class, 'destroy'])->name('admin.delete');

    // Export Laporan (Baru)
    Route::get('/admin/export/users', [App\Http\Controllers\AdminController::class, 'exportUsers'])->name('admin.export.users');
    Route::get('/admin/export/books', [App\Http\Controllers\AdminController::class, 'exportBooks'])->name('admin.export.books');
});

require __DIR__ . '/auth.php';