@extends('layout')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard Admin</h1>
            <p class="text-gray-500 mt-1">Ringkasan data perpustakaan dan menu manajemen.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <span class="px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg text-sm font-medium">
                {{ now()->format('l, d F Y') }}
            </span>
        </div>
    </div>

    {{-- Kartu Statistik (Stats Cards) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition relative overflow-hidden group">
            <div class="absolute right-0 top-0 h-full w-2 bg-blue-500 group-hover:w-full transition-all duration-300 opacity-10"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Pengguna</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $totalUsers }}</h3>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg text-blue-600">
                    {{-- Icon User --}}
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
            <a href="{{ route('admin.users') }}" class="mt-4 inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-700">
                Kelola User 
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition relative overflow-hidden group">
            <div class="absolute right-0 top-0 h-full w-2 bg-emerald-500 group-hover:w-full transition-all duration-300 opacity-10"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Buku</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $totalBooks }}</h3>
                </div>
                <div class="p-3 bg-emerald-100 rounded-lg text-emerald-600">
                    {{-- Icon Book --}}
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
            </div>
            <a href="{{ route('home') }}" class="mt-4 inline-flex items-center text-sm font-medium text-emerald-600 hover:text-emerald-700">
                Lihat Katalog
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition relative overflow-hidden group">
            <div class="absolute right-0 top-0 h-full w-2 bg-amber-500 group-hover:w-full transition-all duration-300 opacity-10"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Sedang Dipinjam</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $activeLoans }}</h3>
                </div>
                <div class="p-3 bg-amber-100 rounded-lg text-amber-600">
                    {{-- Icon Clock --}}
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <a href="{{ route('admin.borrowings.index') }}" class="mt-4 inline-flex items-center text-sm font-medium text-amber-600 hover:text-amber-700">
                Lihat Peminjaman
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Bagian Cetak Laporan --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center space-x-3 mb-6">
                <div class="p-2 bg-gray-100 rounded-lg text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Pusat Laporan</h2>
            </div>
            <p class="text-gray-500 mb-6 text-sm">Unduh data sistem perpustakaan dalam format CSV untuk keperluan rekapitulasi.</p>
            
            <div class="space-y-3">
                <a href="{{ route('admin.export.users') }}" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition group border border-gray-100">
                    <div class="flex items-center space-x-3">
                        <span class="text-gray-600 group-hover:text-gray-800 font-medium">📄 Laporan Data User</span>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </a>

                <a href="{{ route('admin.export.books') }}" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition group border border-gray-100">
                    <div class="flex items-center space-x-3">
                        <span class="text-gray-600 group-hover:text-gray-800 font-medium">📚 Laporan Data Buku</span>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </a>
            </div>
        </div>

        {{-- Menu Cepat --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center space-x-3 mb-6">
                <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Menu Cepat</h2>
            </div>
            <p class="text-gray-500 mb-6 text-sm">Akses cepat untuk menambahkan data baru ke dalam sistem.</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('admin.users.create') }}" class="flex flex-col items-center justify-center p-6 bg-blue-50 border border-blue-100 rounded-xl hover:bg-blue-100 transition group cursor-pointer text-center">
                    <div class="p-3 bg-white rounded-full text-blue-600 shadow-sm mb-3 group-hover:scale-110 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    </div>
                    <span class="font-semibold text-blue-900">Tambah User</span>
                    <span class="text-xs text-blue-600 mt-1">Buat akun anggota baru</span>
                </a>

                <a href="{{ route('book.add') }}" class="flex flex-col items-center justify-center p-6 bg-indigo-50 border border-indigo-100 rounded-xl hover:bg-indigo-100 transition group cursor-pointer text-center">
                    <div class="p-3 bg-white rounded-full text-indigo-600 shadow-sm mb-3 group-hover:scale-110 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <span class="font-semibold text-indigo-900">Tambah Buku</span>
                    <span class="text-xs text-indigo-600 mt-1">Input koleksi buku baru</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection