@extends('layout')

@section('content')
    <div class="form-container">
        <h2>Edit Buku: {{ $book->title }}</h2>
        <p style="margin-bottom: 20px; color: gray;">Penulis: {{ $book->author }}</p>

        <form action="{{ route('book.update', $book->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group" style="margin-bottom: 15px;">
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

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="stock">Stok Buku</label>
                <input type="number" id="stock" name="stock" class="form-control" 
                       value="{{ $book->stock ?? 0 }}" min="0" required>
            </div>

            <div class="form-group">
                <label for="rating">Rating (1-10)</label>
                <input type="number" id="rating" name="rating" class="form-control" 
                       value="{{ $book->rating }}" min="1" max="10" required>
            </div>
            
            <div class="form-group">
                <label for="notes">Catatan Singkat</label>
                <textarea id="notes" name="notes" class="form-control" rows="5">{{ $book->notes }}</textarea>
            </div>
            
            <div class="form-group">
                <label for="date_read">Tanggal Selesai Baca</label>
                <input type="date" id="date_read" name="date_read" class="form-control"
                    value="{{ $book->date_read ? \Carbon\Carbon::parse($book->date_read)->format('Y-m-d') : '' }}">
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label>Cover Buku</label>
                
                @if($book->cover_image)
                    <div style="margin-bottom: 10px;">
                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="Cover Saat Ini" style="height: 100px; border-radius: 5px; border: 1px solid #ddd;">
                        <p style="font-size: 12px; color: gray; margin-top: 5px;">Cover saat ini (Dari Upload)</p>
                    </div>
                @elseif(isset($book->cover_i))
                     <div style="margin-bottom: 10px;">
                        <img src="https://covers.openlibrary.org/b/id/{{ $book->cover_i }}-M.jpg" alt="Cover API" style="height: 100px; border-radius: 5px;">
                        <p style="font-size: 12px; color: gray; margin-top: 5px;">Cover saat ini (Dari API)</p>
                    </div>
                @endif

                <input type="file" name="cover_image" class="form-control" accept="image/*">
                <small style="color: gray; font-size: 0.85em; display: block; margin-top: 5px;">
                    Biarkan kosong jika tidak ingin mengubah cover.
                </small>
            </div>

            <button type="submit" class="btn">Simpan Perubahan</button>

        </form>
    </div>
@endsection