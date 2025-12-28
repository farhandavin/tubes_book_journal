<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // ==========================================
    // 1. MANAJEMEN USER
    // ==========================================

    // Tampilkan daftar user
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->paginate(10);
        return view('admin.users', compact('users'));
    }

    // Form Tambah User
    public function createUser()
    {
        return view('admin.create_user');
    }

    // Simpan User Baru
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:user,admin',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users')->with('success', 'User berhasil ditambahkan.');
    }

    // Hapus user
    public function destroy(User $user)
    {
        if ($user->id == auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri saat sedang login.');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    // Reset Password User
    public function resetPassword(User $user)
    {
        $user->update([
            'password' => Hash::make('password123') // Password default
        ]);

        return back()->with('success', 'Password user ' . $user->name . ' telah direset menjadi: password123');
    }

    // ==========================================
    // 2. MANAJEMEN PEMINJAMAN (BORROWING)
    // ==========================================

    public function borrowings()
    {
        // Eager loading 'user' dan 'book' untuk menghemat query (N+1 Problem)
        $borrowings = Borrowing::with(['user', 'book'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
                        
        return view('admin.borrowings', compact('borrowings'));
    }

    // UPDATE: Logika Approve Peminjaman (Versi Baru)
    public function approveBorrow($id) 
    {
        $loan = Borrowing::findOrFail($id);
        $book = $loan->book;

        if ($book->stock > 0) {
            $book->decrement('stock');
            $loan->update(['status' => 'dipinjam']);
            
            return back()->with('success', 'Peminjaman disetujui!');
        }
        
        return back()->with('error', 'Stok buku habis!');
    }

    // UPDATE: Logika Reject Peminjaman (Versi Baru)
    public function rejectBorrow($id) 
    {
        Borrowing::findOrFail($id)->update(['status' => 'ditolak']);
        
        return back()->with('success', 'Peminjaman ditolak.');
    }

    // ==========================================
    // 3. DASHBOARD & LAPORAN
    // ==========================================

    public function dashboard()
    {
        $totalUsers = User::count();
        $totalBooks = Book::count();
        $activeLoans = Borrowing::where('status', 'dipinjam')->count();
        
        return view('admin.dashboard', compact('totalUsers', 'totalBooks', 'activeLoans'));
    }

    public function exportUsers()
    {
        $users = User::all();
        $filename = "laporan_user_" . date('Y-m-d') . ".csv";
        
        $handle = fopen('php://output', 'w');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        fputcsv($handle, ['ID', 'Nama', 'Email', 'Role', 'Tanggal Bergabung']); 
        
        foreach ($users as $user) {
            fputcsv($handle, [$user->id, $user->name, $user->email, $user->role, $user->created_at]);
        }
        
        fclose($handle);
        exit;
    }

    public function exportBooks()
    {
        $books = Book::all();
        $filename = "laporan_buku_" . date('Y-m-d') . ".csv";

        $handle = fopen('php://output', 'w');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        fputcsv($handle, ['ID', 'Judul', 'Penulis', 'Kategori', 'Tahun', 'Stok']);

        foreach ($books as $book) {
            fputcsv($handle, [
                $book->id,
                $book->title,
                $book->author,
                $book->category ?? '-',
                $book->year,
                $book->stock ?? '0'
            ]);
        }
        fclose($handle);
        exit;
    }
}