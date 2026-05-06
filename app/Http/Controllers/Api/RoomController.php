<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        return response()->json($rooms);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'block' => 'nullable|string|max:10',
            'name' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:255',
        ]);

        $room = Room::create($validated);
        return response()->json($room, 201);
    }

    public function show($id)
    {
        $room = Room::with('schedules')->findOrFail($id);
        return response()->json($room);
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);
        $validated = $request->validate([
            'block' => 'nullable|string|max:10',
            'name' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:255',
        ]);

        $room->update($validated);
        return response()->json($room);
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();
        return response()->json(null, 204);
    }
}
