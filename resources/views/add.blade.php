@extends('layout')

@section('content')
<div class="form-container">
    <h2>Cari & Tambah Buku Baru</h2>
    <p>Cari buku berdasarkan judul untuk menambahkannya ke jurnal Anda.</p>
    
    <form action="{{ route('book.search') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="query">Judul Buku</label>
            <input type="text" id="query" name="query" class="form-control" required>
        </div>
        <button type="submit" class="btn">Cari</button>
    </form>
</div>

@if(isset($results))
    <h2>Hasil Pencarian</h2>
    <div class="book-grid">
        @if(count($results) > 0)
            @foreach($results as $book)
                <div class="search-result-card">
                     <div class="book-card-cover">
                        <img src="https://covers.openlibrary.org/b/id/{{ $book->cover_i }}-L.jpg" 
                             alt="Sampul {{ $book->title }}" 
                             onerror="this.onerror=null;this.src='https://via.placeholder.com/250x350?text=No+Cover';">
                    </div>
                    <div class="book-card-content">
                        <h3>{{ $book->title }}</h3>
                        <p>oleh {{ is_array($book->author_name) ? implode(', ', $book->author_name) : 'Tidak diketahui' }}</p>
                        
                        <form action="{{ route('book.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="title" value="{{ $book->title }}">
                            <input type="hidden" name="author" value="{{ is_array($book->author_name) ? implode(', ', $book->author_name) : 'Tidak diketahui' }}">
                            <input type="hidden" name="isbn" value="{{ isset($book->isbn[0]) ? $book->isbn[0] : '' }}">
                            
                            <div class="form-group">
                                <label for="rating">Rating (1-10)</label>
                                <input type="number" name="rating" class="form-control" min="1" max="10" required>
                            </div>
                            <div class="form-group">
                                <label for="notes">Catatan Singkat</label>
                                <textarea name="notes" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="date_read">Tanggal Selesai Baca</label>
                                <input type="date" name="date_read" class="form-control">
                            </div>
                            <button type="submit" class="btn">Tambahkan ke Jurnal</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @else
            <p>Tidak ada hasil yang ditemukan.</p>
        @endif
    </div>
@endif
@endsection