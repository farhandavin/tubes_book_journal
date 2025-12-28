@extends('layout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-10 text-center sm:text-left">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Perpustakaan Digital</h1>
        <p class="mt-2 text-gray-500 text-lg">Temukan inspirasi dalam setiap halaman.</p>
    </div>

    <div class="mb-8 p-4 bg-white rounded-xl shadow-sm border border-gray-100">
        <form action="{{ route('home') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center justify-between">
            
            <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                <div class="relative w-full sm:w-48">
                    <select name="category" onchange="this.form.submit()" class="w-full pl-3 pr-10 py-2 text-sm border-gray-200 focus:border-black focus:ring-black rounded-lg shadow-sm cursor-pointer transition-colors hover:bg-gray-50">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="relative w-full sm:w-48">
                    <select name="sort" onchange="this.form.submit()" class="w-full pl-3 pr-10 py-2 text-sm border-gray-200 focus:border-black focus:ring-black rounded-lg shadow-sm cursor-pointer transition-colors hover:bg-gray-50">
                        <option value="id" {{ request('sort') == 'id' ? 'selected' : '' }}>Terbaru</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                        <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Judul (A-Z)</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-3 w-full md:w-auto justify-end">
                <a href="{{ route('home') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                    Reset
                </a>
                <a href="{{ route('book.export') }}" class="inline-flex items-center px-4 py-2 bg-black text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export CSV
                </a>
            </div>
        </form>
    </div>

    @if($books->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($books as $book)
                <div class="group bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col h-full relative">
                    
                    <div class="relative aspect-[2/3] overflow-hidden bg-gray-100">
                        @if($book->cover_image)
                            <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="object-cover w-full h-full transform group-hover:scale-105 transition-transform duration-500">
                        @else
                            <img src="https://covers.openlibrary.org/b/isbn/{{ $book->isbn }}-L.jpg" alt="{{ $book->title }}" class="object-cover w-full h-full" onerror="this.onerror=null;this.src='https://via.placeholder.com/250x350?text=No+Cover';">
                        @endif
                        
                        <div class="absolute top-3 left-3">
                            <span class="px-2 py-1 text-xs font-medium bg-white/90 backdrop-blur-sm text-gray-800 rounded-md shadow-sm">
                                {{ $book->category ?? 'Umum' }}
                            </span>
                        </div>
                    </div>

                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-semibold text-gray-900 line-clamp-1 group-hover:text-blue-600 transition-colors">{{ $book->title }}</h3>
                            <div class="flex items-center gap-1 text-yellow-500 text-sm font-bold">
                                <span>★</span><span>{{ $book->rating }}</span>
                            </div>
                        </div>
                        
                        <p class="text-sm text-gray-500 mb-4">oleh <span class="text-gray-700">{{ $book->author }}</span></p>

                        <div class="flex-grow mb-4">
                            <p class="text-xs text-gray-400 italic line-clamp-2">"{{ $book->notes }}"</p>
                        </div>

                        <div class="pt-4 border-t border-gray-50 mt-auto flex flex-col gap-2">
                            
                            <div class="flex justify-between items-center mb-2 text-xs">
                                @if($book->sentiment)
                                    <span class="px-2 py-0.5 rounded text-white font-medium" 
                                          style="background-color: {{ $book->sentiment == 'POSITIF' ? '#10B981' : ($book->sentiment == 'NEGATIF' ? '#EF4444' : '#6B7280') }};">
                                        {{ $book->sentiment }}
                                    </span>
                                @else
                                    <span class="text-gray-300">Belum dianalisis</span>
                                @endif

                                <span class="{{ $book->stock > 0 ? 'text-green-600' : 'text-red-500' }} font-medium">
                                    {{ $book->stock > 0 ? 'Stok: '.$book->stock : 'Habis' }}
                                </span>
                            </div>

                            @if(auth()->user()?->role == 'admin' || auth()->id() == $book->user_id)
                                <div class="grid grid-cols-2 gap-2">
                                    <a href="{{ route('book.edit', $book->id) }}" class="text-center py-2 px-3 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs rounded-lg transition-colors">Edit</a>
                                    <form action="{{ route('book.delete', $book->id) }}" method="POST" class="w-full">
                                        @csrf
                                        @method('DELETE') 
                                        <button type="submit" class="w-full py-2 px-3 bg-red-50 hover:bg-red-100 text-red-600 text-xs rounded-lg transition-colors" onclick="return confirm('Hapus?')">Hapus</button>
                                    </form>
                                </div>
                            @else
                                @if($book->isBorrowed())
                                    <button disabled class="w-full py-2 bg-gray-100 text-gray-400 text-xs font-medium rounded-lg cursor-not-allowed">Dipinjam</button>
                                @elseif($book->stock > 0)
                                    <form action="{{ route('book.borrow', $book->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full py-2 bg-black hover:bg-gray-800 text-white text-xs font-medium rounded-lg transition-colors">Pinjam Buku</button>
                                    </form>
                                @else
                                    <button disabled class="w-full py-2 bg-gray-100 text-gray-400 text-xs font-medium rounded-lg cursor-not-allowed">Stok Habis</button>
                                @endif
                                <button onclick="openReviewModal({{ $book->id }}, '{{ addslashes($book->title) }}')" class="w-full py-2 border border-gray-200 hover:border-gray-300 text-gray-600 text-xs font-medium rounded-lg transition-colors mt-2">
                                    Beri Ulasan
                                </button>
                            @endif

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="flex flex-col items-center justify-center py-20 bg-white rounded-xl border border-dashed border-gray-300">
            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            <p class="text-gray-500 text-lg">Tidak ada buku yang ditemukan.</p>
            @auth
                <a href="{{ route('book.add') }}" class="mt-2 text-black underline hover:text-gray-600">Tambah buku baru</a>
            @endauth
        </div>
    @endif
</div>

<div id="reviewModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeReviewModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Ulas Buku: <span id="modalBookTitle" class="font-bold"></span></h3>
                        <div class="mt-4">
                            <form action="{{ route('reviews.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="book_id" id="modalBookId">
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                                    <select name="rating" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-black focus:ring-black sm:text-sm">
                                        <option value="5">⭐⭐⭐⭐⭐ - Luar Biasa</option>
                                        <option value="4">⭐⭐⭐⭐ - Bagus</option>
                                        <option value="3">⭐⭐⭐ - Cukup</option>
                                        <option value="2">⭐⭐ - Kurang</option>
                                        <option value="1">⭐ - Buruk</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Komentar</label>
                                    <textarea name="comment" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-black focus:ring-black sm:text-sm" placeholder="Bagikan pendapat Anda..."></textarea>
                                </div>

                                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-black text-base font-medium text-white hover:bg-gray-800 focus:outline-none sm:col-start-2 sm:text-sm">
                                        Kirim
                                    </button>
                                    <button type="button" onclick="closeReviewModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:col-start-1 sm:text-sm">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openReviewModal(bookId, bookTitle) {
        document.getElementById('reviewModal').classList.remove('hidden');
        document.getElementById('modalBookId').value = bookId;
        document.getElementById('modalBookTitle').innerText = bookTitle;
    }
    function closeReviewModal() {
        document.getElementById('reviewModal').classList.add('hidden');
    }
</script>
@endsection