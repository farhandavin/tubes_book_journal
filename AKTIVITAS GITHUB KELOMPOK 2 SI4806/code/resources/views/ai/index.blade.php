@extends('layout')

@section('content')
<div class="container" style="text-align:center; margin-top:50px; max-width: 800px; margin-left: auto; margin-right: auto;">
    <h2>🤖 AI Librarian</h2>
    <p>Tanyakan rekomendasi buku atau topik apa saja kepada AI.</p>
    
    {{-- 1. TAMPILKAN ERROR VALIDASI (Jika input kosong) --}}
    @if ($errors->any())
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
            <ul style="list-style: none; padding: 0; margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>⚠️ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 2. TAMPILKAN ERROR SISTEM/API (PENTING: Ini yang sebelumnya hilang) --}}
    @if (session('error'))
        <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
            <strong>Terjadi Kesalahan:</strong><br>
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('ai.ask') }}" method="POST">
        @csrf
        
        <div style="margin-bottom: 20px;">
            <textarea name="prompt" rows="4" placeholder="Contoh: Saya suka buku Harry Potter, tolong rekomendasikan buku fantasi serupa..." 
                style="width: 100%; padding: 15px; border-radius: 8px; border: 1px solid #ccc; font-family: inherit;">{{ old('prompt') }}</textarea>
        </div>

        <button type="submit" class="btn" style="background-color: #4e73df; color: white; padding: 12px 24px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
            ✨ Minta Rekomendasi
        </button>
    </form>

    {{-- 3. HASIL REKOMENDASI --}}
    @if(isset($recommendation))
        <div class="result-box" style="margin-top:30px; text-align: left; background:#f8f9fa; border: 1px solid #e3e6f0; border-radius:10px; padding:25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h3 style="border-bottom: 2px solid #4e73df; padding-bottom: 10px; margin-bottom: 15px; color: #4e73df;">
                💡 Jawaban AI:
            </h3>
            <div style="line-height: 1.6; color: #333;">
                {!! nl2br(e($recommendation)) !!}
            </div>
        </div>
    @endif
</div>
@endsection