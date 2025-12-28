@extends('layout')

@section('content')
<h1>Perpustakaan Digital</h1>
<p>Temukan, pinjam, dan baca buku favorit Anda di sini.</p>

<div class="filter-container" style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 2rem;">
    <form action="{{ route('home') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: center;">
        
        <div>
            <label for="category" style="font-weight: 600;">Kategori:</label>
            <select name="category" id="category" class="form-control" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                        {{ $cat }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="sort" style="font-weight: 600;">Urutkan:</label>
            <select name="sort" id="sort" class="form-control" onchange="this.form.submit()">
                <option value="id" {{ request('sort') == 'id' ? 'selected' : '' }}>Terbaru</option>
                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }}>Tanggal Baca</option>
                <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Judul (A-Z)</option>
            </select>
        </div>

        <a href="{{ route('home') }}" class="btn" style="background-color: #6c757d; color: white; text-decoration: none; padding: 5px 10px; border-radius: 4px; font-size: 0.9rem;">
            Reset Filter
        </a>

        <div style="margin-left: auto;">
            <a href="{{ route('book.export') }}" class="btn" style="background-color: #28a745; padding: 8px 15px; color: white; text-decoration: none; border-radius: 4px;">
                Export CSV
            </a>
        </div>
    </form>
</div>

<div class="book-grid">
    @if($books->count() > 0)
        @foreach($books as $book)
            <div class="book-card">
                
                <div class="book-card-cover">
                    @if($book->cover_image)
                        <img src="{{ asset('storage/' . $book->cover_image) }}" 
                             alt="Sampul {{ $book->title }}" 
                             style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <img src="https://covers.openlibrary.org/b/isbn/{{ $book->isbn }}-L.jpg" 
                             alt="Sampul {{ $book->title }}" 
                             onerror="this.onerror=null;this.src='https://via.placeholder.com/250x350?text=No+Cover';">
                    @endif
                </div>

                <div class="book-card-content">
                    <span class="badge" style="background: #e9ecef; color: #495057; padding: 3px 8px; border-radius: 12px; font-size: 0.8rem; display: inline-block; margin-bottom: 5px;">
                        {{ $book->category ?? 'Tanpa Kategori' }}
                    </span>
                    
                    <h3>{{ $book->title }}</h3>
                    <p style="color: #666; font-size: 0.9rem;">oleh {{ $book->author }}</p>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin: 10px 0;">
                        <span class="rating" style="color: #f39c12; font-weight: bold;">★ {{ $book->rating }}/10</span>
                        <span style="font-size: 0.85rem; font-weight: bold; color: {{ $book->stock > 0 ? '#28a745' : '#dc3545' }}">
                            Stok: {{ $book->stock }}
                        </span>
                    </div>

                    <p class="notes" style="font-style: italic; color: #555; font-size: 0.9rem;">"{{ Str::limit($book->notes, 80) }}"</p>
                    
                    <div style="margin-top: 10px;">
                        @if($book->sentiment)
                            <span class="badge" 
                                style="padding: 5px 10px; border-radius: 5px; color: white; font-weight: bold; font-size: 0.8rem;
                                background-color: {{ $book->sentiment == 'POSITIF' ? '#28a745' : ($book->sentiment == 'NEGATIF' ? '#dc3545' : '#6c757d') }};">
                                Mood AI: {{ $book->sentiment }}
                            </span>
                        @else
                            @if($book->notes)
                                <form action="{{ route('book.analyze', $book->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn" style="background-color: #6f42c1; color: white; padding: 4px 10px; font-size: 0.8rem; border: none; border-radius: 4px; cursor: pointer;">
                                        ✨ Cek Mood AI
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                    
                    <div class="card-actions" style="margin-top: 15px; border-top: 1px solid #eee; padding-top: 10px;">
                        
                        {{-- VIEW ADMIN / PEMILIK --}}
                        @if(auth()->user()?->role == 'admin' || auth()->id() == $book->user_id)
                            <a href="{{ route('book.edit', $book->id) }}" class="btn btn-secondary" style="font-size: 0.8rem;">Edit</a>
                            <form action="{{ route('book.delete', $book->id) }}" method="POST" style="display: inline;">
                                @csrf 
                                <button type="submit" class="btn btn-danger" style="font-size: 0.8rem;" onclick="return confirm('Hapus buku ini?')">Hapus</button>
                            </form>
                        
                        {{-- VIEW USER --}}
                        @else
                            <div style="display: flex; flex-direction: column; gap: 5px;">
                                @if($book->isBorrowed())
                                    <button class="btn" disabled style="background: grey; cursor: not-allowed; font-size: 0.8rem; color: white; width: 100%;">Sedang Dipinjam</button>
                                
                                @elseif($book->stock > 0)
                                    <form action="{{ route('book.borrow', $book->id) }}" method="POST" style="width: 100%;">
                                        @csrf
                                        <button type="submit" class="btn" style="background: #007bff; color: white; font-size: 0.8rem; width: 100%;">Pinjam Buku</button>
                                    </form>
                                
                                @else
                                    <button class="btn" disabled style="background: #e2e6ea; color: #6c757d; cursor: not-allowed; font-size: 0.8rem; border: 1px solid #ccc; width: 100%;">Stok Habis</button>
                                @endif

                                <button onclick="openReviewModal({{ $book->id }}, '{{ addslashes($book->title) }}')" 
                                    class="btn" 
                                    style="background: #ffc107; color: #000; font-size: 0.8rem; border: none; cursor: pointer; width: 100%;">
                                    ★ Beri Ulasan
                                </button>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div style="grid-column: 1/-1; text-align: center; padding: 40px;">
            <p>Tidak ada buku yang ditemukan.</p>
            @auth
                <a href="{{ route('book.add') }}" style="color: blue; text-decoration: underline;">Tambah buku baru</a>
            @endauth
        </div>
    @endif
</div>

<div id="reviewModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 500px; border-radius: 8px; position: relative;">
        
        <span onclick="closeReviewModal()" style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        
        <h2 style="margin-top: 0;">Ulas Buku: <span id="modalBookTitle"></span></h2>
        
        <form action="{{ route('reviews.store') }}" method="POST">
            @csrf
            <input type="hidden" name="book_id" id="modalBookId">

            <div style="margin-bottom: 15px;">
                <label style="font-weight: bold;">Rating:</label><br>
                <select name="rating" required style="width: 100%; padding: 8px; margin-top: 5px;">
                    <option value="5">⭐⭐⭐⭐⭐ (Sangat Bagus)</option>
                    <option value="4">⭐⭐⭐⭐ (Bagus)</option>
                    <option value="3">⭐⭐⭐ (Cukup)</option>
                    <option value="2">⭐⭐ (Kurang)</option>
                    <option value="1">⭐ (Buruk)</option>
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="font-weight: bold;">Komentar:</label><br>
                <textarea name="comment" rows="3" placeholder="Tulis pendapatmu..." style="width: 100%; padding: 8px; margin-top: 5px;"></textarea>
            </div>

            <button type="submit" style="background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; width: 100%;">
                Kirim Ulasan
            </button>
        </form>
    </div>
</div>

<script>
    function openReviewModal(bookId, bookTitle) {
        document.getElementById('reviewModal').style.display = 'block';
        document.getElementById('modalBookId').value = bookId;
        document.getElementById('modalBookTitle').innerText = bookTitle;
    }

    function closeReviewModal() {
        document.getElementById('reviewModal').style.display = 'none';
    }

    // Tutup modal jika klik di luar area putih
    window.onclick = function(event) {
        var modal = document.getElementById('reviewModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

@endsection