@extends('layout')

@section('content')
<h1>Rak Peminjaman Saya</h1>
<p>Daftar buku yang sedang atau pernah Anda pinjam.</p>

<div class="book-grid">
    @foreach($borrowings as $borrow)
        <div class="book-card" style="{{ $borrow->status == 'dikembalikan' ? 'opacity: 0.6;' : '' }}">
            <div class="book-card-cover">
                <img src="https://covers.openlibrary.org/b/isbn/{{ $borrow->book->isbn }}-L.jpg" 
                     alt="Sampul" onerror="this.onerror=null;this.src='https://via.placeholder.com/250x350?text=No+Cover';">
            </div>
            <div class="book-card-content">
                <h3>{{ $borrow->book->title }}</h3>
                <p>Status: 
                    <span style="font-weight: bold; color: {{ $borrow->status == 'dipinjam' ? 'green' : 'grey' }}">
                        {{ strtoupper($borrow->status) }}
                    </span>
                </p>
                <p><small>Dipinjam: {{ $borrow->borrowed_at->format('d M Y') }}</small></p>
                
                @if($borrow->status == 'dipinjam')
                    <div style="margin-top: 15px;">
                        <a href="#" onclick="alert('Fitur baca e-book akan segera hadir!')" class="btn" style="background: #17a2b8; padding: 5px 10px; font-size: 0.8rem;">📖 Baca</a>
                        
                        <form action="{{ route('borrow.return', $borrow->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 0.8rem;">Kembalikan</button>
                        </form>
                    </div>
                @else
                    <p><small>Dikembalikan: {{ $borrow->returned_at ? $borrow->returned_at->format('d M Y') : '-' }}</small></p>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection