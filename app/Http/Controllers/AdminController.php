<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // --- MANAJEMEN USER ---

    // Tampilkan daftar user (dengan Pagination & Filter Self)
    public function index()
    {
        // Ambil semua user, KECUALI admin yang sedang login
        // Menggunakan pagination 10 per halaman
        $users = User::where('id', '!=', auth()->id())->paginate(10);

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

    // Hapus user (Menggabungkan keamanan & kebersihan kode)
    public function destroy(User $user)
    {
        // Cek keamanan: Admin tidak boleh menghapus dirinya sendiri
        if ($user->id == auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri saat sedang login.');
        }

        // Proses hapus
        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }

    // --- DASHBOARD & LAPORAN ---

    public function dashboard()
    {
        $totalUsers = User::count();
        $totalBooks = Book::count();
        // Menghitung peminjaman yang statusnya 'dipinjam'
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
        
        // Header Kolom CSV
        fputcsv($handle, ['ID', 'Nama', 'Email', 'Role', 'Tanggal Bergabung']); 
        
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
                $book->category ?? '-', // Menggunakan '??' untuk mencegah error jika kolom kosong
                $book->year,
                $book->stock ?? '0'
            ]);
        }
        fclose($handle);
        exit;
    }
}