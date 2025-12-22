<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // --- MANAJEMEN USER ---

    // Tampilkan daftar semua user
    public function index()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    // Form Tambah User (Baru)
    public function createUser()
    {
        return view('admin.create_user');
    }

    // Simpan User Baru (Baru)
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
    public function destroy($id)
    {
        if ($id == auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri saat sedang login.');
        }

        User::destroy($id);
        return back()->with('success', 'User berhasil dihapus.');
    }

    // --- DASHBOARD & LAPORAN ---

    public function dashboard()
    {
        $totalUsers = User::count();
        $totalBooks = Book::count();
        $activeLoans = Borrowing::where('status', 'dipinjam')->count();
        
        return view('admin.dashboard', compact('totalUsers', 'totalBooks', 'activeLoans'));
    }

    // 1. Export Data User ke CSV
    public function exportUsers()
    {
        $users = User::all();
        $filename = "laporan_user_" . date('Y-m-d') . ".csv";
        
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        fputcsv($handle, ['ID', 'Nama', 'Email', 'Role', 'Tanggal Bergabung']); // Header CSV
        
        foreach ($users as $user) {
            fputcsv($handle, [
                $user->id, 
                $user->name, 
                $user->email, 
                $user->role, 
                $user->created_at
            ]);
        }
        
        fclose($handle);
        exit;
    }

    // 2. Export Data Buku ke CSV
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
                $book->category ?? '-', // Asumsi kolom category ada
                $book->year,
                $book->stock ?? '0'
            ]);
        }
        fclose($handle);
        exit;
    }
}