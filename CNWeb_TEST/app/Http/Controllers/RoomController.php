<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Guest;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::with('guest')->paginate(5);
        $guests = Guest::all();
        return view('rooms.index', compact('rooms', 'guests'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Room::create();
        return redirect()->route('rooms.index')->with('success', 'Room created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $room = Room::findOrFail($id);
        $room->load('guest');
        return view('rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $room = Room::findOrFail($id);
        $guests = Guest::all();
        return view('rooms.edit', compact('room', 'guests'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $room = Room::findOrFail($id);

        $validated = $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'room_number' => 'required|unique:rooms,room_number,' . $id . '|max:20',
            'room_type' => 'required|in:Single,Double,Suite',
            'price_per_night' => 'required|numeric|min:0',
            'check_in_date' => 'required|date',
            'check_out_date' => 'nullable|date|after:check_in_date',
            'status' => 'required|in:Available,Occupied,Maintenance',
        ]);

        $room->update($validated);
        return redirect()->route('rooms.index')->with('success', 'Room updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $room = Room::findOrFail($id);
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully!');
    }
}
