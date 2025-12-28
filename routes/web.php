<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReviewController;

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

    // [UPDATED] Menggunakan PUT dan DELETE sesuai form edit/hapus
    Route::get('/edit/{id}', [BookController::class, 'editForm'])->name('book.edit');
    Route::put('/edit/{id}', [BookController::class, 'update'])->name('book.update');
    Route::delete('/delete/{id}', [BookController::class, 'destroy'])->name('book.delete');

    Route::get('/export-csv', [BookController::class, 'exportCsv'])->name('book.export');

    // 2. Route Analisis Sentimen & Rekomendasi (AI)
    Route::post('/book/{id}/analyze', [AIController::class, 'analyzeSentiment'])->name('book.analyze');
    Route::get('/ai-recommendation', [AIController::class, 'index'])->name('ai.index');
    Route::post('/ai-recommendation', [AIController::class, 'askAI'])->name('ai.ask');

    // 3. Route Peminjaman (Sisi User)
    Route::post('/book/{id}/borrow', [BorrowController::class, 'borrow'])->name('book.borrow');
    Route::post('/borrowing/{id}/return', [BorrowController::class, 'returnBook'])->name('borrow.return');
    Route::get('/my-books', [BorrowController::class, 'myBooks'])->name('my.books');

    // 4. Route Profile (Pengaturan Akun)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 5. Route Events (User View)
    Route::get('/events', [EventController::class, 'index'])->name('events.index');

    // 6. Route Reviews (Ulasan Buku)
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

// --- Group Middleware Admin (Akses Khusus Admin) ---
Route::middleware(['auth', 'verified', \App\Http\Middleware\IsAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // A. Dashboard Khusus Admin
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // B. Manajemen User (CRUD Admin)
        Route::get('/users', [AdminController::class, 'index'])->name('users');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');

        // Route Edit User
        Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');

        Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('users.destroy');
        Route::patch('/users/{user}/reset-password', [AdminController::class, 'resetPassword'])->name('users.reset-password');

        // C. Manajemen Peminjaman (Borrowing Management)
        Route::get('/borrowings', [AdminController::class, 'borrowings'])->name('borrowings.index');
        Route::patch('/borrowings/{id}/approve', [AdminController::class, 'approveBorrow'])->name('borrowings.approve');
        Route::patch('/borrowings/{id}/reject', [AdminController::class, 'rejectBorrow'])->name('borrowings.reject');

        // D. Manajemen Events (Admin CRUD)
        Route::post('/events', [EventController::class, 'store'])->name('events.store');

        // Route Edit Event
        Route::get('/events/{id}/edit', [EventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update');

        Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.delete');

        // E. Export Laporan Admin
        Route::get('/export/users', [AdminController::class, 'exportUsers'])->name('export.users');
        Route::get('/export/books', [AdminController::class, 'exportBooks'])->name('export.books');
    });

require __DIR__ . '/auth.php';