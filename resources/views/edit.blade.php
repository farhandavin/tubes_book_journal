@extends('layout')

@section('content')
    <div class="form-container">
        <h2>Edit Ulasan untuk: {{ $book->title }}</h2>

        <form action="{{ route('book.update', $book->id) }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="rating">Rating (1-10)</label>
                <input type="number" id="rating" name="rating" class="form-control" value="{{ $book->rating }}" min="1" max="10" required>
            </div>
            
            <div class="form-group">
                <label for="notes">Catatan Singkat</label>
                <textarea id="notes" name="notes" class="form-control" rows="5">{{ $book->notes }}</textarea>
            </div>
            
            <div class="form-group">
                <label for="date_read">Tanggal Selesai Baca</label>
                <input type="date" id="date_read" name="date_read" class="form-control"
                    value="{{ $book->date_read ? $book->date_read->format('Y-m-d') : '' }}">
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label>Kategori</label>
                <select name="category" class="form-control" style="width: 100%; padding: 8px;">
                    <option value="">-- Pilih Kategori --</option>
                    @php $cats = ['Fiksi', 'Non-Fiksi', 'Sains', 'Sejarah', 'Biografi', 'Teknologi', 'Lainnya']; @endphp
                    @foreach($cats as $cat)
                        <option value="{{ $cat }}" {{ $book->category == $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn">Simpan Perubahan</button>

        </form>
    </div>
@endsection