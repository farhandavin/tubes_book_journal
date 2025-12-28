<?php

namespace App\Http\Controllers;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('event_date', 'asc')->get();
        return view('events.index', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required', 'event_date' => 'required|date']);
        Event::create($request->all());
        return back()->with('success', 'Event berhasil ditambah!');
    }

    public function destroy($id)
    {
        Event::findOrFail($id)->delete();
        return back()->with('success', 'Event dihapus.');
    }
}
