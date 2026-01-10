<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // 1. Tampilkan Daftar Event
    public function index()
    {
        $events = Event::orderBy('event_date', 'asc')->get();
        return view('events.index', compact('events'));
    }

    // 2. Simpan Event Baru
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'event_date' => 'required|date'
        ]);

        Event::create($request->all());

        return back()->with('success', 'Event berhasil ditambah!');
    }

    // 3. [BARU] Form Edit Event
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('events.edit', compact('event'));
    }

    // 4. [BARU] Proses Update Event
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'event_date' => 'required|date',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
        ]);

        $event = Event::findOrFail($id);
        
        $event->update([
            'title' => $request->title,
            'event_date' => $request->event_date,
            'description' => $request->description,
            'location' => $request->location,
        ]);

        return redirect()->route('events.index')->with('success', 'Event berhasil diperbarui!');
    }

    // 5. Hapus Event
    public function destroy($id)
    {
        Event::findOrFail($id)->delete();
        return back()->with('success', 'Event dihapus.');
    }
}