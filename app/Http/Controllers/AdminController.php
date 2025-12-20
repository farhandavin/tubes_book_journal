<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    // Tampilkan daftar semua user
    public function index()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    // Hapus user
    public function destroy($id)
    {
        // Mencegah admin menghapus dirinya sendiri
        if ($id == auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri saat sedang login.');
        }

        User::destroy($id);
        return back()->with('success', 'User berhasil dihapus.');
    }

    public function dashboard()
    {
        $totalUsers = User::count();
        $totalBooks = \App\Models\Book::count();
        // Hitung berapa buku yang sedang dipinjam (status 'dipinjam')
        $activeLoans = \App\Models\Borrowing::where('status', 'dipinjam')->count();
        
        return view('admin.dashboard', compact('totalUsers', 'totalBooks', 'activeLoans'));
    }
}