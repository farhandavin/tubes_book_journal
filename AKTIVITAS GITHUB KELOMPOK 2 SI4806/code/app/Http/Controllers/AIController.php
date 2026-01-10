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

    // 2. LOGIKA TANYA AI (REKOMENDASI)
    public function askAI(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:1000',
        ]);

        $apiKey = env('GEMINI_API_KEY');
        $userPrompt = $request->input('prompt');

        // PERBAIKAN: Menggunakan model 1.5-flash yang stabil
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}";

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'contents' => [
                        ['parts' => [['text' => $userPrompt]]]
                    ]
                ]);

            $result = $response->json();
            
            // Cek error dari Google
            if(isset($result['error'])) {
                 return back()->with('error', 'Error AI: ' . $result['error']['message']);
            }

            $answer = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, AI tidak dapat memproses permintaan Anda.';

            return view('ai.index', ['recommendation' => $answer]);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal terhubung ke AI: ' . $e->getMessage());
        }
    }

    // 3. LOGIKA ANALISIS SENTIMEN PER BUKU
    public function analyzeSentiment($id)
    {
        $book = Book::where('user_id', auth()->id())->findOrFail($id);

        if (empty($book->notes)) {
            return back()->with('error', 'Isi catatan/ulasan buku terlebih dahulu untuk dianalisis.');
        }

        $apiKey = env('GEMINI_API_KEY');
        
        // PERBAIKAN: Menggunakan model 1.5-flash yang stabil
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}";
        
        $prompt = "Analisis sentimen dari ulasan buku berikut: \"{$book->notes}\". "
                . "Jawab HANYA dengan satu kata: POSITIF, NETRAL, atau NEGATIF. Jangan ada kata lain.";

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]]
                    ]
                ]);
            
            $result = $response->json();

            // Cek error dari Google
            if(isset($result['error'])) {
                 return back()->with('error', 'Error AI: ' . $result['error']['message']);
            }
            
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