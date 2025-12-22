@extends('layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center;">
    <h1>Manajemen User</h1>
    {{-- Tombol Tambah User --}}
    <a href="{{ route('admin.users.create') }}" 
       style="background: #4e73df; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-weight: bold;">
       + Tambah User
    </a>
</div>

<p>Daftar semua pengguna yang terdaftar di aplikasi.</p>

@if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
@endif

<div style="margin-top: 20px; overflow-x: auto;">
    <table border="1" style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead style="background-color: #f2f2f2;">
            <tr>
                <th style="padding: 10px;">ID</th>
                <th style="padding: 10px;">Nama</th>
                <th style="padding: 10px;">Email</th>
                <th style="padding: 10px;">Role</th>
                <th style="padding: 10px;">Tanggal Daftar</th>
                <th style="padding: 10px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td style="padding: 10px;">{{ $user->id }}</td>
                <td style="padding: 10px;">{{ $user->name }}</td>
                <td style="padding: 10px;">{{ $user->email }}</td>
                <td style="padding: 10px;">
                    <span style="padding: 3px 8px; border-radius: 4px; font-size: 0.9em; font-weight: bold;
                        background-color: {{ $user->role == 'admin' ? '#f6c23e' : '#e2e3e5' }};
                        color: {{ $user->role == 'admin' ? '#fff' : '#333' }};">
                        {{ strtoupper($user->role) }}
                    </span>
                </td>
                <td style="padding: 10px;">{{ $user->created_at->format('d M Y') }}</td>
                <td style="padding: 10px;">
                    @if($user->id != auth()->id())
                        <form action="{{ route('admin.delete', $user->id) }}" method="POST" onsubmit="return confirm('Yakin hapus user ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: #e74a3b; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 4px;">
                                Hapus
                            </button>
                        </form>
                    @else
                        <span style="color: grey; font-style: italic;">(Anda)</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection