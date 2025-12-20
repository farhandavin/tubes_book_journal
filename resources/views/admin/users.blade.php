@extends('layout')

@section('content')
<h1>Admin Dashboard: Manajemen User</h1>
<p>Daftar semua pengguna yang terdaftar di aplikasi.</p>

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
                    <span style="padding: 3px 8px; border-radius: 4px; background-color: {{ $user->role == 'admin' ? '#ffd700' : '#e2e3e5' }}">
                        {{ $user->role }}
                    </span>
                </td>
                <td style="padding: 10px;">{{ $user->created_at->format('d M Y') }}</td>
                <td style="padding: 10px;">
                    @if($user->id != auth()->id())
                        <form action="{{ route('admin.delete', $user->id) }}" method="POST" onsubmit="return confirm('Yakin hapus user ini? Semua data bukunya juga akan hilang.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: red; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 4px;">
                                Hapus
                            </button>
                        </form>
                    @else
                        <span style="color: grey;">(Akun Anda)</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection