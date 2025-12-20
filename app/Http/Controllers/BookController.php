<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Http;

class BookController extends Controller
{
    // MENAMPILKAN DASHBOARD DENGAN FILTER KATEGORI
   public function index(Request $request)
    {
        // ... (Kode sorting & filter kategori di atas TETAP SAMA, jangan dihapus) ...
        $sortOption = $request->query('sort', 'id');
        $categoryFilter = $request->query('category');
        $order = 'DESC';
        $column = 'id';
        
        // ... (Logika if/else sorting TETAP SAMA) ...
        if ($sortOption === 'rating') { $column = 'rating'; } 
        elseif ($sortOption === 'date') { $column = 'date_read'; } 
        elseif ($sortOption === 'title') { $column = 'title'; $order = 'ASC'; }

        // PERUBAHAN DI SINI:
        // Hapus "where('user_id', auth()->id())" agar menampilkan SEMUA buku
        $query = Book::query(); 

        if ($categoryFilter) {
            $query->where('category', $categoryFilter);
        }

        $books = $query->orderBy($column, $order)->get();

        // Ambil kategori dari semua buku
        $categories = Book::whereNotNull('category')->distinct()->pluck('category');

        return view('index', compact('books', 'categories'));
    }
    public function addForm()
    {
        return view('add', ['results' => null]);
    }

    public function searchApi(Request $request)
    {
        // ... (Kode searchApi TETAP SAMA seperti sebelumnya, tidak perlu diubah) ...
        // Agar kode tidak kepanjangan di chat, biarkan bagian searchApi ini seperti kode lama Anda.

        // Cukup pastikan return view-nya tetap sama:
        // return view('add', ['results' => $results]);

        // --- SEMENTARA SAYA COPY ULANG BAGIAN INI AGAR ANDA BISA LANGSUNG PASTE FULL FILE ---
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
        }

        return view('add', ['results' => $results]);
    }

    // SIMPAN BUKU (UPDATE: Tambah Category)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'rating' => 'required|numeric|min:1|max:10',
            'category' => 'nullable|string|max:50', // Validasi kategori
        ]);

        Book::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'author' => $request->author,
            'category' => $request->category, // <--- Simpan Kategori
            'isbn' => $request->isbn,
            'rating' => $request->rating,
            'notes' => $request->notes,
            'date_read' => $request->date_read ?: null,
        ]);

        return redirect()->route('home')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function editForm($id)
    {
        $book = Book::where('user_id', auth()->id())->findOrFail($id);
        return view('edit', compact('book'));
    }

    // UPDATE BUKU (UPDATE: Tambah Category)
    public function update(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|numeric|min:1|max:10',
            'category' => 'nullable|string|max:50',
        ]);

        $book = Book::where('user_id', auth()->id())->findOrFail($id);

        $book->update([
            'rating' => $request->rating,
            'notes' => $request->notes,
            'category' => $request->category, // <--- Update Kategori
            'date_read' => $request->date_read ?: null,
        ]);

        return redirect()->route('home');
    }

   public function exportCsv()
    {
        // Admin export semua buku, User biasa export buku yang mereka buat/miliki (jika ada)
        // Atau kita set agar export ini mendownload seluruh katalog perpustakaan
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
            
            // Header Kolom CSV
            fputcsv($file, ['ID', 'Judul', 'Penulis', 'Kategori', 'ISBN', 'Rating', 'Sentimen AI', 'Status', 'Catatan']);

            foreach ($books as $book) {
                // Cek status peminjaman
                $status = $book->isBorrowed() ? 'Sedang Dipinjam' : 'Tersedia';

                fputcsv($file, [
                    $book->id,
                    $book->title,
                    $book->author,
                    $book->category ?? '-',    // Kolom Kategori
                    $book->isbn,
                    $book->rating,
                    $book->sentiment ?? '-',   // Kolom Sentimen
                    $status,                   // Status Peminjaman
                    $book->notes
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroy($id)
    {
        $book = Book::where('user_id', auth()->id())->where('id', $id)->first();
        if ($book) {
            $book->delete();
        }
        return redirect()->route('home');
    }
}