<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function getTodaySchedules(Request $request)
    {
        $user = $request->user();
        $today = Carbon::today()->toDateString();

        $schedules = Schedule::whereHas('course', function ($query) use ($user) {
                $query->where('lecturer_id', $user->id);
            })
            ->whereDate('start_time', $today)
            ->with(['course', 'room.building'])
            ->get();

        return response()->json($schedules);
    }

    public function getStudentSchedules(Request $request)
    {
        $user = $request->user();
        $schedules = $user->schedules()
            ->with(['course', 'room.building'])
            ->get();

        return response()->json($schedules);
    }
}