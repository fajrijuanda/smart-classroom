<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\Room;

class RoomController extends Controller
{
    // Mendapatkan semua gedung dengan ruangan
    public function getBuildingsWithRooms()
    {
        $buildings = Building::with('rooms')->get();
        return response()->json($buildings);
    }

    // Mendapatkan ruangan berdasarkan device ID
    public function getRoomByDevice(Request $request)
    {
        $request->validate(['device_id' => 'required|string']);

        $room = Room::where('device_id', $request->device_id)
            ->with('building')
            ->firstOrFail();

        return response()->json($room);
    }
}