<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage; // 1. Penting: Import Storage

class BookController extends Controller
{
    // --- MENAMPILKAN DASHBOARD ---
    public function index(Request $request)
    {
        $sortOption = $request->query('sort', 'id');
        $categoryFilter = $request->query('category');
        $order = 'DESC';
        $column = 'id';
        
        if ($sortOption === 'rating') { $column = 'rating'; } 
        elseif ($sortOption === 'date') { $column = 'date_read'; } 
        elseif ($sortOption === 'title') { $column = 'title'; $order = 'ASC'; }

        // Menampilkan SEMUA buku (sesuai request sebelumnya)
        $query = Book::query(); 

        if ($categoryFilter) {
            $query->where('category', $categoryFilter);
        }

        $books = $query->orderBy($column, $order)->get();
        $categories = Book::whereNotNull('category')->distinct()->pluck('category');

        return view('index', compact('books', 'categories'));
    }

    public function addForm()
    {
        return view('add', ['results' => null]);
    }

    // --- SEARCH API (OPENLIBRARY) ---
    public function searchApi(Request $request)
    {
        $query = $request->input('query');
        $results = [];

        try {
            $response = Http::get("https://openlibrary.org/search.json", [
                'q' => $query,
                'limit' => 20
            ]);

            $docs = $response->json()['docs'] ?? [];

            foreach ($docs as $book) {
                $processedIsbn = null;
                if (isset($book['ia'])) {
                    foreach ($book['ia'] as $item) {
                        if (str_starts_with($item, 'isbn_')) {
                            $processedIsbn = substr($item, 5);
                            break;
                        }
                    }
                }
                $results[] = (object) [
                    'title' => $book['title'] ?? 'Unknown Title',
                    'author_name' => isset($book['author_name']) ? $book['author_name'] : ['Tidak diketahui'],
                    'cover_i' => $book['cover_i'] ?? null,
                    'isbn' => $processedIsbn ? [$processedIsbn] : ($book['isbn'] ?? [])
                ];
            }
        } catch (\Exception $e) {
            // Handle error silent
        }

        return view('add', ['results' => $results]);
    }

    // --- STORE (SIMPAN BUKU BARU) ---
    public function store(Request $request)
    {
        // 2. Validasi digabung (Data buku + Gambar + Stok)
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'rating' => 'required|numeric|min:1|max:10',
            'stock' => 'required|integer|min:0', // Validasi Stok
            'category' => 'nullable|string|max:50',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi Gambar
        ]);

        // Siapkan data dasar
        $data = [
            'user_id' => auth()->id(),
            'title' => $request->title,
            'author' => $request->author,
            'category' => $request->category,
            'isbn' => $request->isbn,
            'rating' => $request->rating,
            'stock' => $request->stock, // Simpan Stok
            'notes' => $request->notes,
            'date_read' => $request->date_read ?: null,
        ];

        // 3. Logika Upload Gambar Baru
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        Book::create($data);

        return redirect()->route('home')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function editForm($id)
    {
        $user = auth()->user();
        
        // Admin bisa edit semua buku, user biasa hanya bisa edit buku miliknya
        if ($user->role === 'admin') {
            $book = Book::findOrFail($id);
        } else {
            $book = Book::where('user_id', $user->id)->findOrFail($id);
        }
        
        return view('edit', compact('book'));
    }

    // --- UPDATE BUKU ---
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        
        // Admin bisa update semua buku, user biasa hanya bisa update buku miliknya
        if ($user->role === 'admin') {
            $book = Book::findOrFail($id);
        } else {
            $book = Book::where('user_id', $user->id)->findOrFail($id);
        }

        // 4. Validasi Update
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'rating' => 'required|numeric|min:1|max:10',
            'stock' => 'required|integer|min:0',
            'category' => 'nullable|string|max:50',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'title' => $request->title,
            'author' => $request->author,
            'rating' => $request->rating,
            'stock' => $request->stock,
            'category' => $request->category,
            'notes' => $request->notes,
            'date_read' => $request->date_read ?: null,
        ];

        // 5. Logika Ganti Gambar (Hapus lama, Upload baru)
        if ($request->hasFile('cover_image')) {
            // Hapus gambar lama dari storage jika ada
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            // Simpan gambar baru
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $book->update($data);

        return redirect()->route('home')->with('success', 'Buku berhasil diperbarui.');
    }

    // --- EXPORT CSV ---
    public function exportCsv()
    {
        $books = Book::all(); 
        $csvFileName = 'laporan-perpustakaan-' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($books) {
            $file = fopen('php://output', 'w');
            
            // Header CSV (Saya tambahkan kolom Stok)
            fputcsv($file, ['ID', 'Judul', 'Penulis', 'Kategori', 'ISBN', 'Stok', 'Rating', 'Sentimen AI', 'Status', 'Catatan']);

            foreach ($books as $book) {
                $status = $book->isBorrowed() ? 'Sedang Dipinjam' : 'Tersedia';

                fputcsv($file, [
                    $book->id,
                    $book->title,
                    $book->author,
                    $book->category ?? '-',
                    $book->isbn,
                    $book->stock, // Export stok juga
                    $book->rating,
                    $book->sentiment ?? '-',
                    $status,
                    $book->notes
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // --- HAPUS BUKU ---
    public function destroy($id)
    {
        $user = auth()->user();
        
        // Admin bisa hapus semua buku, user biasa hanya bisa hapus buku miliknya
        if ($user->role === 'admin') {
            $book = Book::find($id);
        } else {
            $book = Book::where('user_id', $user->id)->where('id', $id)->first();
        }
        
        if ($book) {
            // 6. Hapus gambar fisik saat buku dihapus (Kebersihan Server)
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            
            $book->delete();
            return redirect()->route('home')->with('success', 'Buku berhasil dihapus.');
        }
        
        return redirect()->route('home')->with('error', 'Buku tidak ditemukan atau Anda tidak memiliki akses.');
    }
}