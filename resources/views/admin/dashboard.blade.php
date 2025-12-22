@extends('layout')

@section('content')
<h1>Dashboard Admin</h1>
<p>Ringkasan data perpustakaan saat ini.</p>

{{-- Kartu Statistik --}}
<div style="display: flex; gap: 20px; margin-top: 20px; flex-wrap: wrap;">
    <div style="flex: 1; background: #4e73df; color: white; padding: 20px; border-radius: 8px; min-width: 200px;">
        <h3>Total Pengguna</h3>
        <p style="font-size: 2rem; font-weight: bold;">{{ $totalUsers }}</p>
        <a href="{{ route('admin.users') }}" style="color: #dddfeb; text-decoration: none; font-size: 0.9rem;">Kelola User &rarr;</a>
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

<hr style="margin: 40px 0; border: 0; border-top: 1px solid #ddd;">

{{-- Bagian Cetak Laporan --}}
<div style="margin-top: 20px;">
    <h2>📂 Pusat Laporan</h2>
    <p>Unduh data sistem dalam format CSV.</p>
    
    <div style="display: flex; gap: 15px; flex-wrap: wrap; margin-top: 15px;">
        <a href="{{ route('admin.export.users') }}" class="btn" 
           style="background: #343a40; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: flex; align-items: center; gap: 8px;">
           📄 Laporan Data User
        </a>

        <a href="{{ route('admin.export.books') }}" class="btn" 
           style="background: #343a40; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: flex; align-items: center; gap: 8px;">
           📚 Laporan Data Buku
        </a>
    </div>
</div>

{{-- Menu Cepat --}}
<div style="margin-top: 40px;">
    <h2>⚡ Menu Cepat</h2>
    <div style="display: flex; gap: 10px; margin-top: 10px;">
        <a href="{{ route('admin.users.create') }}" class="btn" style="background: #36b9cc; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            + Tambah User Baru
        </a>
        <a href="{{ route('book.add') }}" class="btn" style="background: #36b9cc; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            + Tambah Buku Baru
        </a>
    </div>
</div>
@endsection