<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Borrowing;
use Carbon\Carbon;

class BorrowController extends Controller
{
    // Aksi Meminjam Buku
    public function borrow($bookId)
    {
        $book = Book::findOrFail($bookId);

        // Cek apakah buku sedang dipinjam orang lain
        if ($book->isBorrowed()) {
            return back()->with('error', 'Buku ini sedang dipinjam orang lain.');
        }

        Borrowing::create([
            'user_id' => auth()->id(),
            'book_id' => $bookId,
            'borrowed_at' => Carbon::now(),
            'status' => 'dipinjam'
        ]);

        return back()->with('success', 'Buku berhasil dipinjam! Selamat membaca.');
    }

    // Aksi Mengembalikan Buku
    public function returnBook($id)
    {
        // Cari data peminjaman milik user yang login
        $borrowing = Borrowing::where('user_id', auth()->id())
                              ->where('id', $id)
                              ->where('status', 'dipinjam')
                              ->firstOrFail();

        $borrowing->update([
            'status' => 'dikembalikan',
            'returned_at' => Carbon::now()
        ]);

        return back()->with('success', 'Buku berhasil dikembalikan.');
    }

    // Halaman Daftar Buku yang Sedang Saya Pinjam
    public function myBooks()
    {
        $borrowings = Borrowing::with('book')
                               ->where('user_id', auth()->id())
                               ->orderBy('created_at', 'desc')
                               ->get();

        return view('borrowings.index', compact('borrowings'));
    }
}