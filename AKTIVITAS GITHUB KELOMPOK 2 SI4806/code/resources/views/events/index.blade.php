@extends('layout')

@section('content')
    <h1>Agenda & Event Perpustakaan</h1>

    {{-- FORM TAMBAH EVENT (Hanya Admin) --}}
    @if(auth()->user()->role == 'admin')
        <div style="background: #f4f4f4; padding: 15px; margin-bottom: 20px; border-radius: 8px;">
            <h3 style="margin-top: 0;">Tambah Event Baru</h3>
            <form action="{{ route('admin.events.store') }}" method="POST" style="display: flex; gap: 10px;">
                @csrf
                <input type="text" name="title" placeholder="Judul Event" required style="padding: 8px; flex: 2;">
                <input type="date" name="event_date" required style="padding: 8px; flex: 1;">
                <button type="submit" style="padding: 8px 15px; background: #4e73df; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    Tambah
                </button>
            </form>
        </div>
    @endif

    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
        
        {{-- BAGIAN TABEL LIST EVENT --}}
        <div style="flex: 2; min-width: 300px;">
            <table border="1" style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th style="padding: 10px;">Tanggal</th>
                        <th style="padding: 10px;">Nama Event</th>
                        @if(auth()->user()->role == 'admin')
                            <th style="padding: 10px;">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                    <tr>
                        <td style="padding: 10px;">
                            {{ $event->event_date->format('d M Y') }}
                        </td>
                        <td style="padding: 10px;">
                            {{ $event->title }}
                        </td>
                        
                        {{-- KOLOM AKSI (GABUNGAN) --}}
                        @if(auth()->user()->role == 'admin')
                            <td style="padding: 10px;">
                                <div style="display: flex; gap: 5px;">
                                    
                                    {{-- TOMBOL EDIT --}}
                                    <a href="{{ route('admin.events.edit', $event->id) }}" 
                                       style="background: #36b9cc; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; font-size: 0.8rem;">
                                       Edit
                                    </a>
            
                                    {{-- TOMBOL HAPUS --}}
                                    <form action="{{ route('admin.events.delete', $event->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus event ini?')">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" style="background: #e74a3b; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 0.8rem;">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                    @endforeach

                    @if($events->isEmpty())
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 20px; color: gray;">Belum ada event terjadwal.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- BAGIAN KALENDER MINI --}}
        <div style="flex: 1; min-width: 250px; border: 1px solid #ddd; padding: 15px; text-align: center; border-radius: 8px; height: fit-content;">
            <h4 style="margin-bottom: 15px; margin-top: 0;">{{ date('F Y') }}</h4>
            
            <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 5px; font-size: 0.9rem;">
                {{-- Header Hari --}}
                <div style="font-weight: bold;">M</div>
                <div style="font-weight: bold;">S</div>
                <div style="font-weight: bold;">S</div>
                <div style="font-weight: bold;">R</div>
                <div style="font-weight: bold;">K</div>
                <div style="font-weight: bold;">J</div>
                <div style="font-weight: bold;">S</div>

                {{-- Loop Tanggal --}}
                @for($i = 1; $i <= 31; $i++)
                    @php
                        // Cek apakah ada event di tanggal ini
                        $hasEvent = in_array($i, $events->pluck('event_date')->map(fn($d) => (int)$d->format('j'))->toArray());
                    @endphp
                    <div style="
                        padding: 8px; 
                        border-radius: 4px;
                        background-color: {{ $hasEvent ? '#4e73df' : '#f8f9fa' }}; 
                        color: {{ $hasEvent ? 'white' : '#333' }};
                        border: 1px solid #eee;">
                        {{ $i }}
                    </div>
                @endfor
            </div>
            
            <div style="margin-top: 15px; font-size: 0.8rem; display: flex; align-items: center; justify-content: center; gap: 5px;">
                <span style="width: 10px; height: 10px; background: #4e73df; display: inline-block; border-radius: 50%;"></span>
                <span>= Ada Event</span>
            </div>
        </div>

    </div>
@endsection