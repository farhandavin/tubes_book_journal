@extends('layout')

@section('content')
<h1>Dashboard Admin</h1>
<p>Ringkasan data perpustakaan saat ini.</p>

<div style="display: flex; gap: 20px; margin-top: 20px; flex-wrap: wrap;">
    <div style="flex: 1; background: #4e73df; color: white; padding: 20px; border-radius: 8px; min-width: 200px;">
        <h3>Total Pengguna</h3>
        <p style="font-size: 2rem; font-weight: bold;">{{ $totalUsers }}</p>
        <a href="{{ route('admin.users') }}" style="color: #dddfeb; text-decoration: none; font-size: 0.9rem;">Lihat Detail &rarr;</a>
    </div>

    <div style="flex: 1; background: #1cc88a; color: white; padding: 20px; border-radius: 8px; min-width: 200px;">
        <h3>Total Buku</h3>
        <p style="font-size: 2rem; font-weight: bold;">{{ $totalBooks }}</p>
        <a href="{{ route('home') }}" style="color: #dddfeb; text-decoration: none; font-size: 0.9rem;">Lihat Katalog &rarr;</a>
    </div>

    <div style="flex: 1; background: #f6c23e; color: white; padding: 20px; border-radius: 8px; min-width: 200px;">
        <h3>Sedang Dipinjam</h3>
        <p style="font-size: 2rem; font-weight: bold;">{{ $activeLoans }}</p>
        <span style="font-size: 0.9rem; opacity: 0.8;">Buku sedang dibaca user</span>
    </div>
</div>

<div style="margin-top: 30px;">
    <h2>Menu Cepat</h2>
    <div style="display: flex; gap: 10px; margin-top: 10px;">
        <a href="{{ route('book.add') }}" class="btn" style="background: #36b9cc;">+ Tambah Buku Baru</a>
        <a href="{{ route('book.export') }}" class="btn" style="background: #858796;">Download Laporan CSV</a>
    </div>
</div>
@endsection