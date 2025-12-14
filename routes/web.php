<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AIController; // Jika fitur AI sudah dibuat

// --- Halaman Utama (Daftar Buku) ---
// Kita ganti default '/' agar langsung memanggil controller buku
Route::get('/', [BookController::class, 'index'])->name('home');

// --- Fitur CRUD Buku ---
Route::middleware(['auth'])->group(function () {
    // Hanya user login yang bisa tambah/edit/hapus
    Route::get('/add', [BookController::class, 'addForm'])->name('book.add');
    Route::post('/search', [BookController::class, 'searchApi'])->name('book.search');
    Route::post('/add', [BookController::class, 'store'])->name('book.store');
    
    Route::get('/edit/{id}', [BookController::class, 'editForm'])->name('book.edit');
    Route::post('/edit/{id}', [BookController::class, 'update'])->name('book.update');
    Route::post('/delete/{id}', [BookController::class, 'destroy'])->name('book.delete');
    
    // Export & AI
    Route::get('/export-csv', [BookController::class, 'exportCsv'])->name('book.export');
    Route::get('/ai-recommendation', [AIController::class, 'index'])->name('ai.index');
    Route::post('/ai-recommendation', [AIController::class, 'askAI'])->name('ai.ask');
});

// --- Route Bawaan Breeze (Jangan Dihapus) ---
// Route ini mengatur halaman dashboard & profil user
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php'; // Ini file rahasia Breeze untuk login/register