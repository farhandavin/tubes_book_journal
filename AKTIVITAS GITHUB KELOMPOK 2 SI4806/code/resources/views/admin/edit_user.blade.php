@extends('layout')

@section('content')
<div style="max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    <h2>Edit Data User</h2>
    <hr style="margin-bottom: 20px;">

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 15px;">
            <label style="font-weight: bold; display: block; margin-bottom: 5px;">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="font-weight: bold; display: block; margin-bottom: 5px;">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="font-weight: bold; display: block; margin-bottom: 5px;">Role</label>
            <select name="role" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User (Peminjam)</option>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
            </select>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button type="submit" style="background: #4e73df; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.users') }}" style="background: #858796; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection