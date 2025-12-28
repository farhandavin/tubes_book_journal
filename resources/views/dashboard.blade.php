@extends('layout')

@section('content')
<div class="space-y-6">
    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
            <p class="text-gray-500 mt-2">Senang melihat Anda kembali di BookJournal. Apa rencana bacaan Anda hari ini?</p>
        </div>
        <div class="hidden md:block">
            <a href="{{ route('book.add') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition shadow-md">
                + Tambah Koleksi Baru
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-indigo-100 rounded-lg text-indigo-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Koleksi Buku</p>
                    <h3 class="text-2xl font-bold text-gray-800">Jelajahi</h3>
                </div>
            </div>
            <a href="{{ route('home') }}" class="mt-4 block text-center py-2 bg-gray-50 text-indigo-600 rounded-lg hover:bg-indigo-50 transition text-sm font-medium">Lihat Semua Buku</a>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-green-100 rounded-lg text-green-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Status Pinjaman</p>
                    <h3 class="text-2xl font-bold text-gray-800">Cek Sesi</h3>
                </div>
            </div>
            <a href="{{ route('my.books') }}" class="mt-4 block text-center py-2 bg-gray-50 text-green-600 rounded-lg hover:bg-green-50 transition text-sm font-medium">Buku Yang Saya Pinjam</a>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-purple-100 rounded-lg text-purple-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Rekomendasi AI</p>
                    <h3 class="text-2xl font-bold text-gray-800">Tanya AI</h3>
                </div>
            </div>
            <a href="{{ route('ai.index') }}" class="mt-4 block text-center py-2 bg-gray-50 text-purple-600 rounded-lg hover:bg-purple-50 transition text-sm font-medium">Minta Saran Bacaan</a>
        </div>
    </div>
</div>
@endsection