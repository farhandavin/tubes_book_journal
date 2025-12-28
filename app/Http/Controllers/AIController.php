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
        // 1. Debug: Cek apakah ID buku masuk
        // dd("Masuk fungsi analyze dengan ID: " . $id); 

        $book = Book::where('user_id', auth()->id())->findOrFail($id);

        if (empty($book->notes)) {
            return back()->with('error', 'Isi catatan/ulasan buku terlebih dahulu.');
        }

        $apiKey = env('GEMINI_API_KEY');
        
        // 2. Debug: Pastikan API Key terbaca (JANGAN TAMPILKAN INI KE ORANG LAIN)
        if (empty($apiKey)) {
            dd("ERROR: API Key tidak terbaca dari .env. Cek file .env Anda!");
        }

        // Gunakan model yang stabil: gemini-1.5-flash
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}";

        $prompt = "Analisis sentimen dari teks ini: \"{$book->notes}\". "
                . "Jawab HANYA satu kata: POSITIF, NETRAL, atau NEGATIF.";

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]]
                    ]
                ]);

            $result = $response->json();

            // 3. DEBUGGING UTAMA: 
            // Hapus tanda komentar (//) pada baris di bawah ini untuk melihat respon asli Google
            // dd($result); 

            // Cek jika Google memberikan Error
            if (isset($result['error'])) {
                // Tampilkan error spesifik di layar
                dd("Google Error:", $result['error']); 
            }

            // Ambil jawaban
            $sentiment = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'NETRAL';
            
            // Bersihkan format (kadang AI menjawab "Sentimen: Positif", kita ambil kata kuncinya saja)
            $sentiment = strtoupper(trim(str_replace(["\n", "\r", "*", "."], '', $sentiment)));
            
            // Fallback jika AI meracau
            if (!in_array($sentiment, ['POSITIF', 'NETRAL', 'NEGATIF'])) {
                // Debugging jika jawaban aneh
                // dd("Jawaban AI aneh: " . $sentiment); 
                $sentiment = 'NETRAL'; 
            }

            $book->update(['sentiment' => $sentiment]);

            return back()->with('success', 'Analisis sentimen berhasil: ' . $sentiment);

        } catch (\Exception $e) {
            // Tampilkan error koneksi
            dd("Exception Error: " . $e->getMessage());
        }
    }
}