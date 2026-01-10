@extends('layout')

@section('content')
<div style="max-width: 500px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    <h2>Ubah Jadwal Event</h2>
    <hr style="margin-bottom: 20px;">

    <form action="{{ route('admin.events.update', $event->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 15px;">
            <label style="font-weight: bold; display: block; margin-bottom: 5px;">Judul Event</label>
            <input type="text" name="title" value="{{ old('title', $event->title) }}" required 
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="font-weight: bold; display: block; margin-bottom: 5px;">Tanggal Event</label>
            <input type="date" name="event_date" value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}" required 
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
        </div>

        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button type="submit" style="background: #4e73df; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                Update Event
            </button>
            <a href="{{ route('events.index') }}" style="background: #858796; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection