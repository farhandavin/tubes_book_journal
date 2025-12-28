@extends('layout')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md mt-10 mb-10">
    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Edit Buku: {{ $book->title }}</h2>
        <p class="text-gray-500">Penulis: {{ $book->author }}</p>
    </div>

    {{-- Pesan Error Validasi --}}
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 text-red-600 rounded-lg">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM UTAMA: UPDATE DATA --}}
    <form action="{{ route('book.update', $book->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700 font-medium">Judul Buku</label>
            <input type="text" name="title" value="{{ old('title', $book->title) }}" required 
                   class="w-full mt-1 p-2 border rounded-lg focus:ring focus:ring-blue-300 @error('title') border-red-500 @enderror">
            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium">Penulis</label>
            <input type="text" name="author" value="{{ old('author', $book->author) }}" required 
                   class="w-full mt-1 p-2 border rounded-lg focus:ring focus:ring-blue-300">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium">Kategori</label>
            <select name="category" class="w-full mt-1 p-2 border rounded-lg focus:ring focus:ring-blue-300">
                <option value="">-- Pilih Kategori --</option>
                @php $cats = ['Fiksi', 'Non-Fiksi', 'Sains', 'Sejarah', 'Biografi', 'Teknologi', 'Lainnya']; @endphp
                @foreach($cats as $cat)
                    <option value="{{ $cat }}" {{ old('category', $book->category) == $cat ? 'selected' : '' }}>
                        {{ $cat }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 font-medium">Rating (1-10)</label>
                <input type="number" name="rating" value="{{ old('rating', $book->rating) }}" min="1" max="10" step="0.1" 
                       class="w-full mt-1 p-2 border rounded-lg focus:ring focus:ring-blue-300">
            </div>
            <div>
                <label class="block text-gray-700 font-medium">Stok Buku</label>
                <input type="number" name="stock" value="{{ old('stock', $book->stock) }}" min="0" required 
                       class="w-full mt-1 p-2 border rounded-lg focus:ring focus:ring-blue-300">
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium">Tanggal Selesai Baca</label>
            <input type="date" name="date_read" 
                   value="{{ old('date_read', $book->date_read ? \Carbon\Carbon::parse($book->date_read)->format('Y-m-d') : '') }}"
                   class="w-full mt-1 p-2 border rounded-lg focus:ring focus:ring-blue-300">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium">Catatan / Review Singkat</label>
            <textarea name="notes" rows="4" class="w-full mt-1 p-2 border rounded-lg focus:ring focus:ring-blue-300">{{ old('notes', $book->notes) }}</textarea>
        </div>

        {{-- Section Sampul --}}
        <div class="mb-6">
            <label class="block text-gray-700 font-medium mb-2">Sampul Buku</label>
            <div class="flex items-start gap-4 mb-3">
                @if($book->cover_image)
                    <div>
                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="Cover" class="h-24 w-auto rounded border shadow-sm">
                        <p class="text-[10px] text-gray-500 mt-1 text-center">Upload</p>
                    </div>
                @elseif(isset($book->cover_i))
                    <div>
                        <img src="https://covers.openlibrary.org/b/id/{{ $book->cover_i }}-M.jpg" alt="API" class="h-24 w-auto rounded border shadow-sm">
                        <p class="text-[10px] text-gray-500 mt-1 text-center">API</p>
                    </div>
                @endif
            </div>
            <input type="file" name="cover_image" accept="image/*" class="w-full text-sm">
        </div>

        {{-- Tombol Navigasi --}}
        <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
            <a href="{{ route('home') }}" class="px-5 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">Batal</a>
            <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-md transition">
                Simpan Perubahan
            </button>
        </div>
    </form>

    {{-- ZONA BAHAYA: HAPUS BUKU --}}
    <div class="mt-12 pt-6 border-t-2 border-red-50">
        <h3 class="text-sm font-bold text-red-600 uppercase tracking-wider mb-3">Zona Bahaya</h3>
        <div class="bg-red-50 p-4 rounded-lg flex items-center justify-between">
            <div>
                <p class="text-sm text-red-700 font-semibold">Hapus Buku Ini</p>
                <p class="text-xs text-red-600">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <form action="{{ route('book.delete', $book->id) }}" method="POST">
                @csrf 
                @method('DELETE')
                <button type="submit" 
                        class="py-2 px-4 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-lg transition shadow-sm"
                        onclick="return confirm('Apakah Anda yakin ingin menghapus buku {{ $book->title }}? Semua data akan hilang selamanya.')">
                    Hapus Permanen
                </button>
            </form>
        </div>
    </div>
</div>
@endsection