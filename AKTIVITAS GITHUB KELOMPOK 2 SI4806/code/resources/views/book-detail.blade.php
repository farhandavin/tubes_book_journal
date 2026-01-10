@extends('layout')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    {{-- Breadcrumb --}}
    <nav class="flex mb-8 text-sm text-gray-500">
        <a href="{{ route('home') }}" class="hover:text-black transition-colors">Beranda</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900 font-medium truncate">{{ $book->title }}</span>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
        {{-- Kolom Kiri: Cover & Quick Actions --}}
        <div class="md:col-span-1">
            <div class="sticky top-24">
                <div class="aspect-[2/3] rounded-2xl overflow-hidden shadow-2xl mb-6 relative group">
                    @if($book->cover_image)
                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="object-cover w-full h-full transform group-hover:scale-105 transition-transform duration-700">
                    @else
                        <img src="https://covers.openlibrary.org/b/isbn/{{ $book->isbn }}-L.jpg" alt="{{ $book->title }}" class="object-cover w-full h-full">
                    @endif
                    
                    <div class="absolute top-4 left-4">
                         <span class="px-3 py-1 text-xs font-bold bg-white/90 backdrop-blur-md text-gray-900 rounded-full shadow-lg">
                            {{ $book->category ?? 'Umum' }}
                        </span>
                    </div>
                </div>

                <div class="space-y-3">
                    @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->id() === $book->user_id))
                        <a href="{{ route('book.edit', $book->id) }}" class="block w-full text-center py-3 px-4 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold rounded-xl transition-all">
                            Edit Buku
                        </a>
                        <form action="{{ route('book.delete', $book->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus buku ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="block w-full py-3 px-4 bg-red-50 hover:bg-red-100 text-red-600 font-semibold rounded-xl transition-all">
                                Hapus Buku
                            </button>
                        </form>
                    @elseif($book->isBorrowed())
                         <button disabled class="w-full py-3 bg-gray-100 text-gray-400 font-semibold rounded-xl cursor-not-allowed">
                            Sedang Dipinjam
                        </button>
                    @elseif($book->stock > 0)
                        <form action="{{ route('book.borrow', $book->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-3 bg-black hover:bg-gray-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                                Pinjam Buku Ini
                            </button>
                        </form>
                    @else
                        <button disabled class="w-full py-3 bg-gray-100 text-gray-400 font-semibold rounded-xl cursor-not-allowed">
                            Stok Habis
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Detail & Reviews --}}
        <div class="md:col-span-2">
            <div class="mb-10">
                <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight leading-tight mb-2">{{ $book->title }}</h1>
                <p class="text-xl text-gray-600 font-medium mb-4">oleh {{ $book->author }}</p>
                
                <div class="flex items-center gap-4 text-sm mb-6">
                    <div class="flex items-center text-yellow-500 font-bold bg-yellow-50 px-2 py-1 rounded-lg">
                        <span class="mr-1">★</span> {{ $book->rating }}
                    </div>
                    <span class="text-gray-300">|</span>
                    <div class="text-gray-600">
                        ISBN: <span class="font-mono text-gray-900">{{ $book->isbn }}</span>
                    </div>
                     <span class="text-gray-300">|</span>
                    <div class="{{ $book->stock > 0 ? 'text-green-600' : 'text-red-500' }} font-bold">
                        {{ $book->stock > 0 ? 'Stok: ' . $book->stock : 'Stok Habis' }}
                    </div>
                </div>

                @if($book->notes)
                    <div class="bg-gray-50 border-l-4 border-black p-6 rounded-r-xl italic text-gray-700 leading-relaxed mb-8">
                        "{{ $book->notes }}"
                    </div>
                @endif
                
                @if($book->sentiment)
                    <div class="mb-8">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-2">Analisis AI</h3>
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white shadow-sm"
                            style="background-color: {{ $book->sentiment == 'POSITIF' ? '#10B981' : ($book->sentiment == 'NEGATIF' ? '#EF4444' : '#6B7280') }};">
                            @if($book->sentiment == 'POSITIF') ⚡ Positif @elseif($book->sentiment == 'NEGATIF') 🌧️ Negatif @else 😐 Netral @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="border-t border-gray-100 pt-10">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">Ulasan Pembaca</h2>
                    <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-sm font-bold">{{ $book->reviews->count() }} Ulasan</span>
                </div>

                {{-- Form Ulasan --}}
                @auth
                    <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6 mb-10">
                        <h3 class="font-bold text-gray-900 mb-4">Tulis Ulasan Anda</h3>
                        <form action="{{ route('reviews.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                            
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Rating</label>
                                <div class="flex gap-4">
                                    @foreach(range(5, 1) as $star)
                                        <label class="cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition-colors flex items-center gap-1 border border-transparent hover:border-gray-200">
                                            <input type="radio" name="rating" value="{{ $star }}" class="text-black focus:ring-black" required> 
                                            <span class="text-yellow-500 text-lg">★</span> <span class="font-bold text-sm text-gray-700">{{ $star }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Komentar</label>
                                <textarea name="comment" rows="3" class="w-full border-gray-200 rounded-xl focus:border-black focus:ring-black transition-shadow resize-none" placeholder="Apa pendapat Anda tentang buku ini?"></textarea>
                            </div>

                            <button type="submit" class="px-6 py-2.5 bg-black text-white font-semibold rounded-xl hover:bg-gray-800 transition-colors shadow-lg">
                                Kirim Ulasan
                            </button>
                        </form>
                    </div>
                @else
                    <div class="bg-gray-50 rounded-xl p-6 text-center text-gray-600 mb-10">
                        <a href="{{ route('login') }}" class="text-black font-bold underline">Login</a> untuk menulis ulasan.
                    </div>
                @endauth

                {{-- List Ulasan --}}
                <div class="space-y-6">
                    @forelse($book->reviews as $review)
                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center font-bold text-gray-600">
                                    {{ substr($review->user->name ?? 'A', 0, 1) }}
                                </div>
                            </div>
                            <div class="flex-grow">
                                <div class="flex items-center justify-between mb-1">
                                    <h4 class="font-bold text-gray-900">{{ $review->user->name ?? 'Pengguna' }}</h4>
                                    <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex text-yellow-400 text-sm mb-2">
                                    @for($i=1; $i<=5; $i++)
                                        <span>{{ $i <= $review->rating ? '★' : '☆' }}</span>
                                    @endfor
                                </div>
                                <p class="text-gray-600 leading-relaxed">{{ $review->comment }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-10">Belum ada ulasan. Jadilah yang pertama mengulas!</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
