<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Book;

class AIController extends Controller
{
    // 1. Halaman Form Rekomendasi
    public function index() {
        return view('ai.index', ['recommendation' => null]);
    }

    // 2. LOGIKA TANYA AI (REKOMENDASI) - Fungsi ini yang hilang sebelumnya
    public function askAI(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'prompt' => 'required|string|max:1000',
        ]);

        $apiKey = env('GEMINI_API_KEY');
        $userPrompt = $request->input('prompt');

        try {
            // Kirim request ke Gemini
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                    'contents' => [
                        ['parts' => [['text' => $userPrompt]]]
                    ]
                ]);

            $result = $response->json();
            
            // Ambil jawaban AI
            $answer = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, AI tidak dapat memproses permintaan Anda saat ini.';

            // Kembalikan ke halaman view dengan hasil rekomendasi
            return view('ai.index', ['recommendation' => $answer]);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal terhubung ke AI: ' . $e->getMessage());
        }
    }

    // 3. LOGIKA ANALISIS SENTIMEN PER BUKU
    public function analyzeSentiment($id)
    {
        // Cari buku milik user
        $book = Book::where('user_id', auth()->id())->findOrFail($id);

        // Cek apakah ada catatan
        if (empty($book->notes)) {
            return back()->with('error', 'Isi catatan/ulasan buku terlebih dahulu untuk dianalisis.');
        }

        // Siapkan Prompt
        $apiKey = env('GEMINI_API_KEY');
        $prompt = "Analisis sentimen dari ulasan buku berikut: \"{$book->notes}\". "
                . "Jawab HANYA dengan satu kata: POSITIF, NETRAL, atau NEGATIF. Jangan ada kata lain.";

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]]
                    ]
                ]);
            
            $result = $response->json();
            
            $sentiment = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'NETRAL';
            $sentiment = strtoupper(trim(str_replace(["\n", "\r", "*", "."], '', $sentiment)));

            if (!in_array($sentiment, ['POSITIF', 'NETRAL', 'NEGATIF'])) {
                $sentiment = 'NETRAL';
            }

            $book->update(['sentiment' => $sentiment]);

            return back()->with('success', 'Analisis sentimen berhasil: ' . $sentiment);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal terhubung ke AI: ' . $e->getMessage());
        }
    }
}