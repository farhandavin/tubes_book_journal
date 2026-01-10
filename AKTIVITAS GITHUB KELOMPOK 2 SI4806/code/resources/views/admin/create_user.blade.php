@extends('layout')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <h1>Tambah User Baru</h1>
    <p>Silakan isi data pengguna baru di bawah ini.</p>
    
    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #ddd;">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Nama Lengkap</label>
                <input type="text" name="name" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Email</label>
                <input type="email" name="email" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Password</label>
                <input type="password" name="password" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Role (Peran)</label>
                <select name="role" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="user">User (Peminjam)</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" style="background: #4e73df; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">
                    Simpan User
                </button>
                <a href="{{ route('admin.users') }}" style="background: #858796; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection