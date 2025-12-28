<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowController extends Controller
{
    // User Request Peminjaman
    public function borrow(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        // 1. Cek Stok (Minimal ada 1)
        if ($book->stock < 1) {
            return back()->with('error', 'Stok buku habis!');
        }

        // 2. Cek apakah user sedang meminjam buku yang SAMA (Pending/Dipinjam)
        $existing = Borrowing::where('user_id', Auth::id())
                    ->where('book_id', $id)
                    ->whereIn('status', ['pending', 'dipinjam'])
                    ->exists();

        if ($existing) {
            return back()->with('error', 'Anda sudah meminjam atau me-request buku ini.');
        }

        // 3. Buat Data Peminjaman (Status Pending)
        Borrowing::create([
            'user_id' => Auth::id(),
            'book_id' => $id,
            'borrow_date' => now(),
            'status' => 'pending', // <-- Status awal pending
        ]);

        return back()->with('success', 'Permintaan peminjaman berhasil dikirim. Tunggu persetujuan Admin.');
    }

    // User Mengembalikan Buku
    public function returnBook($id)
    {
        $borrowing = Borrowing::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

        if ($borrowing->status !== 'dipinjam') {
            return back()->with('error', 'Buku ini belum disetujui atau sudah dikembalikan.');
        }

        // Update status
        $borrowing->update(['status' => 'dikembalikan', 'return_date' => now()]);

        // Kembalikan Stok Buku
        $book = Book::find($borrowing->book_id);
        $book->increment('stock');

        return back()->with('success', 'Buku berhasil dikembalikan.');
    }

    // Halaman Buku Saya
    public function myBooks()
    {
        $borrowings = Borrowing::where('user_id', Auth::id())
                        ->with('book')
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('borrowings.index', compact('borrowings'));
    }
}