<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Http; // Untuk request ke API OpenLibrary

class BookController extends Controller
{
    // MENAMPILKAN DASHBOARD (index.js: app.get("/"))
    public function index(Request $request)
    {
        $sortOption = $request->query('sort', 'id');
        $order = 'DESC';
        $column = 'id';

        if ($sortOption === 'rating') {
            $column = 'rating';
        } elseif ($sortOption === 'date') {
            $column = 'date_read';
        } elseif ($sortOption === 'title') {
            $column = 'title';
            $order = 'ASC';
        }

        $books = Book::where('user_id', auth()->id())->orderBy($column, $order)->get();

        return view('index', compact('books'));
    }

    // HALAMAN FORM TAMBAH (index.js: app.get("/add"))
    public function addForm()
    {
        return view('add', ['results' => null]);
    }

    // CARI BUKU API (index.js: app.post("/search"))
    public function searchApi(Request $request)
    {
        $query = $request->input('query');
        $results = [];

        try {
            // Panggil API OpenLibrary
            $response = Http::get("https://openlibrary.org/search.json", [
                'q' => $query,
                'limit' => 20
            ]);

            $docs = $response->json()['docs'] ?? [];

            // Proses data agar sesuai format yang diinginkan (Logika "ia" isbn_)
            foreach ($docs as $book) {
                $processedIsbn = null;

                // Cek logic ISBN sama seperti di index.js
                if (isset($book['ia'])) {
                    foreach ($book['ia'] as $item) {
                        if (str_starts_with($item, 'isbn_')) {
                            $processedIsbn = substr($item, 5); // Hapus "isbn_"
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
            // Error handling diam (kosongkan hasil)
        }

        return view('add', ['results' => $results]);
    }

    // SIMPAN BUKU KE DB (index.js: app.post("/add"))
    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'user_id' => auth()->id(),
            'title' => 'required',
            'rating' => 'required|numeric|min:1|max:10',
        ]);

        Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'rating' => $request->rating,
            'notes' => $request->notes,
            'date_read' => $request->date_read ?: null,
        ]);

        return redirect()->route('home')->with('success', 'Buku berhasil ditambahkan!');
    }

    // HALAMAN EDIT (index.js: app.get("/edit/:id"))
    public function editForm($id)
    {
        $book = Book::findOrFail($id);
        return view('edit', compact('book'));
    }

    // UPDATE BUKU (index.js: app.post("/edit/:id"))
    public function update(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|numeric|min:1|max:10',
        ]);

        $book = Book::findOrFail($id);
        $book->update([
            'rating' => $request->rating,
            'notes' => $request->notes,
            'date_read' => $request->date_read ?: null,
        ]);

        return redirect()->route('home');
    }

    public function exportCsv()
    {
        $books = Book::where('user_id', auth()->id())->get();
        $csvFileName = 'laporan-buku-' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($books) {
            $file = fopen('php://output', 'w');

            // Header Kolom
            fputcsv($file, ['Judul', 'Penulis', 'Rating', 'Tanggal Baca', 'Catatan']);

            // Isi Data
            foreach ($books as $book) {
                fputcsv($file, [
                    $book->title,
                    $book->author,
                    $book->rating,
                    $book->date_read,
                    $book->notes
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // HAPUS BUKU (index.js: app.post("/delete/:id"))
    public function destroy($id)
    {
        Book::destroy($id);
        return redirect()->route('home');
    }
}

