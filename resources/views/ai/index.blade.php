@extends('layout')

@section('content')
<div class="container" style="text-align:center; margin-top:50px;">
    <h2>🤖 AI Librarian</h2>
    <p>Biarkan AI menganalisis selera baca Anda dan memberikan rekomendasi.</p>
    
    <form action="{{ route('ai.ask') }}" method="POST">
        @csrf
        <button type="submit" class="btn">Minta Rekomendasi</button>
    </form>

    @if($recommendation)
        <div class="result-box" style="margin-top:20px; padding:20px; background:#f0f0f0; border-radius:10px;">
            <h3>Saran untuk Anda:</h3>
            <p>{!! nl2br(e($recommendation)) !!}</p>
        </div>
    @endif
</div>
@endsection