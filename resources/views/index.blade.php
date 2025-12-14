@extends('layout')

@section('content')
<h1>Koleksi Buku Saya</h1>
<p>Ini adalah semua buku yang telah Anda baca dan ulas.</p>

<div class="sort-container" style="margin-bottom: 2rem;">
    <form action="{{ route('home') }}" method="GET" style="display: flex; align-items: center; gap: 10px;">
        <label for="sort" style="font-weight: 600;">Urutkan Berdasarkan:</label>
        <select name="sort" id="sort" class="form-control" onchange="this.form.submit()" style="width: 200px;">
            <option value="id" {{ request('sort') == 'id' ? 'selected' : '' }}>Terbaru Ditambahkan</option>
            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
            <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }}>Tanggal Baca</option>
            <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Judul (A-Z)</option>
        </select>

        <a href="{{ route('book.export') }}" class="btn" style="background-color: #28a745; padding: 8px 15px; font-size: 0.9rem;">
            Export ke Excel/CSV
        </a>
    </form>
</div>

<div class="book-grid">
    @if($books->count() > 0)
        @foreach($books as $book)
            <div class="book-card">
                <div class="book-card-cover">
                    <img src="https://covers.openlibrary.org/b/isbn/{{ $book->isbn }}-L.jpg" 
                         alt="Sampul {{ $book->title }}" 
                         onerror="this.onerror=null;this.src='https://via.placeholder.com/250x350?text=No+Cover';">
                </div>
                <div class="book-card-content">
                    <h3>{{ $book->title }}</h3>
                    <p>oleh {{ $book->author }}</p>
                    <p class="rating">Rating: {{ $book->rating }} / 10 ⭐</p>
                    <p class="notes">{{ $book->notes }}</p>
                    
                    <div class="card-actions">
                        <a href="{{ route('book.edit', $book->id) }}" class="btn btn-secondary">Edit</a>
                        
                        <form action="{{ route('book.delete', $book->id) }}" method="POST" style="display: inline;">
                            @csrf <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <p>Anda belum menambahkan buku apapun. <a href="{{ route('book.add') }}">Mulai tambahkan sekarang!</a></p>
    @endif
</div>
@endsection