<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Http; // Untuk request ke API OpenLibrary

class BookController extends Controller
{
    // MENAMPILKAN DASHBOARD
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

        // Ambil buku hanya milik user yang sedang login
        $books = Book::where('user_id', auth()->id())
                     ->orderBy($column, $order)
                     ->get();

        return view('index', compact('books'));
    }

    // HALAMAN FORM TAMBAH
    public function addForm()
    {
        return view('add', ['results' => null]);
    }

    // CARI BUKU API
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
            // Error handling diam
        }

        return view('add', ['results' => $results]);
    }

    // SIMPAN BUKU KE DB (Bagian yang Diperbaiki)
    public function store(Request $request)
    {
        // 1. VALIDASI INPUT (user_id dihapus dari sini)
        $request->validate([
            'title'  => 'required',
            'rating' => 'required|numeric|min:1|max:10', // Pastikan format string ini benar
        ]);

        // 2. SIMPAN KE DATABASE (user_id dimasukkan di sini)
        Book::create([
            'user_id'   => auth()->id(), // <--- DITAMBAHKAN DI SINI
            'title'     => $request->title,
            'author'    => $request->author,
            'isbn'      => $request->isbn,
            'rating'    => $request->rating,
            'notes'     => $request->notes,
            'date_read' => $request->date_read ?: null,
        ]);

        return redirect()->route('home')->with('success', 'Buku berhasil ditambahkan!');
    }

    // HALAMAN EDIT
    public function editForm($id)
    {
        // Pastikan hanya bisa edit buku milik sendiri
        $book = Book::where('user_id', auth()->id())->findOrFail($id);
        return view('edit', compact('book'));
    }

    // UPDATE BUKU
    public function update(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|numeric|min:1|max:10',
        ]);

        $book = Book::where('user_id', auth()->id())->findOrFail($id);
        
        $book->update([
            'rating'    => $request->rating,
            'notes'     => $request->notes,
            'date_read' => $request->date_read ?: null,
        ]);

        return redirect()->route('home');
    }

    // EXPORT CSV
    public function exportCsv()
    {
        $books = Book::where('user_id', auth()->id())->get();
        $csvFileName = 'laporan-buku-' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($books) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Judul', 'Penulis', 'Rating', 'Tanggal Baca', 'Catatan']);

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

    // HAPUS BUKU
    public function destroy($id)
    {
        // Pastikan delete hanya buku milik user yg login
        $book = Book::where('user_id', auth()->id())->where('id', $id)->first();
        
        if ($book) {
            $book->delete();
        }
        
        return redirect()->route('home');
    }
}