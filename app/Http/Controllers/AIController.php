<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Book;

class AIController extends Controller
{
    public function index() {
        return view('ai.index', ['recommendation' => null]);
    }

    public function askAI() {
        // 1. Ambil 5 judul buku terakhir yang disukai user (rating >= 7)
        $books = Book::where('user_id', auth()->id())
                     ->where('rating', '>=', 7)
                     ->limit(5)
                     ->pluck('title')
                     ->toArray();

        if (empty($books)) {
            return view('ai.index', ['recommendation' => 'Anda belum menilai cukup buku untuk mendapatkan rekomendasi.']);
        }

        $bookList = implode(', ', $books);
        
        // 2. Kirim Prompt ke AI (Contoh menggunakan Gemini API)
        // Pastikan Anda punya API Key di .env: GEMINI_API_KEY=xyz...
        $apiKey = env('GEMINI_API_KEY'); 
        $prompt = "Saya menyukai buku-buku ini: $bookList. Berikan saya 3 rekomendasi buku lain yang mirip beserta alasan singkat dalam bahasa Indonesia.";

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key={$apiKey}", [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]]
                    ]
                ]);
            
            $result = $response->json();
            $recommendation = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Gagal mendapatkan respon AI.';
            
        } catch (\Exception $e) {
            $recommendation = "Terjadi kesalahan koneksi ke AI.";
        }

        return view('ai.index', ['recommendation' => $recommendation]);
    }
}