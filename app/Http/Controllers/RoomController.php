<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\Room;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    public function getBuildingsWithRooms()
    {
        $buildings = Building::with('rooms')->get();
        return response()->json($buildings);
    }

    public function getRoomByDevice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $room = Room::where('device_id', $request->device_id)
            ->with('building')
            ->firstOrFail();

        return response()->json($room);
    }
}
