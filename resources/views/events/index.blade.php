@extends('layout')

@section('content')
<h1>Agenda & Event Perpustakaan</h1>

@if(auth()->user()->role == 'admin')
    <div style="background: #f4f4f4; padding: 15px; margin-bottom: 20px;">
        <h3>Tambah Event Baru</h3>
        <form action="{{ route('admin.events.store') }}" method="POST">
            @csrf
            <input type="text" name="title" placeholder="Judul Event" required>
            <input type="date" name="event_date" required>
            <button type="submit">Tambah</button>
        </form>
    </div>
@endif

<div style="display: flex; gap: 20px;">
    <div style="flex: 2;">
        <table border="1" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Event</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                <tr>
                    <td>{{ $event->event_date->format('d M Y') }}</td>
                    <td>{{ $event->title }}</td>
                    <td>
                        @if(auth()->user()->role == 'admin')
                            <form action="{{ route('admin.events.delete', $event->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit">Hapus</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="flex: 1; border: 1px solid #ddd; padding: 10px; text-align: center;">
        <h4 style="margin-bottom: 5px;">{{ date('F Y') }}</h4>
        <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 5px; font-size: 0.8rem;">
            @for($i = 1; $i <= 31; $i++)
                <div style="padding: 5px; border: 1px solid #eee; {{ in_array($i, $events->pluck('event_date')->map(fn($d) => $d->format('j'))->toArray()) ? 'background: #007bff; color: white;' : '' }}">
                    {{ $i }}
                </div>
            @endfor
        </div>
        <p style="font-size: 0.7rem; margin-top: 10px;">*Warna biru menandakan ada event</p>
    </div>
</div>
@endsection