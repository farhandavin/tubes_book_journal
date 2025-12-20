<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Book;

class AIController extends Controller
{
    // Halaman rekomendasi (Opsional, kita fokus ke analisis sentimen dulu)
    public function index() {
        return view('ai.index', ['recommendation' => null]);
    }

    // LOGIKA BARU: ANALISIS SENTIMEN PER BUKU
    public function analyzeSentiment($id)
    {
        // 1. Cari buku milik user
        $book = Book::where('user_id', auth()->id())->findOrFail($id);

        // 2. Cek apakah ada catatan
        if (empty($book->notes)) {
            return back()->with('error', 'Isi catatan/ulasan buku terlebih dahulu untuk dianalisis.');
        }

        // 3. Siapkan Prompt
        $apiKey = env('GEMINI_API_KEY');
        // Prompt khusus agar jawabannya singkat padat
        $prompt = "Analisis sentimen dari ulasan buku berikut: \"{$book->notes}\". "
                . "Jawab HANYA dengan satu kata: POSITIF, NETRAL, atau NEGATIF. Jangan ada kata lain.";

        try {
            // 4. Kirim ke Gemini API
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]]
                    ]
                ]);
            
            $result = $response->json();
            
            // Ambil teks jawaban
            $sentiment = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'NETRAL';
            
            // Bersihkan format (hapus spasi/enter/bintang bold jika ada)
            $sentiment = strtoupper(trim(str_replace(["\n", "\r", "*", "."], '', $sentiment)));

            // Validasi manual agar hanya tersimpan 3 kata kunci tersebut
            if (!in_array($sentiment, ['POSITIF', 'NETRAL', 'NEGATIF'])) {
                $sentiment = 'NETRAL'; // Default jika AI ngelantur
            }

            // 5. Simpan ke Database
            $book->update(['sentiment' => $sentiment]);

            return back()->with('success', 'Analisis sentimen berhasil: ' . $sentiment);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal terhubung ke AI: ' . $e->getMessage());
        }
    }
}