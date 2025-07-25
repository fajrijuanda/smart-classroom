<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function record(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'device_id' => 'required|exists:rooms,device_id',
        ]);

        $user = $request->user();
        $schedule = Schedule::find($request->schedule_id);

        // Verify device ID matches room
        $room = $schedule->room;
        if ($room->device_id !== $request->device_id) {
            return response()->json(['message' => 'Invalid device for this schedule'], 400);
        }

        // Check if already recorded
        $existing = Attendance::where('user_id', $user->id)
            ->where('schedule_id', $request->schedule_id)
            ->exists();

        if ($existing) {
            return response()->json(['message' => 'Attendance already recorded'], 409);
        }

        // Determine status (on-time or late)
        $now = Carbon::now();
        $startTime = Carbon::parse($schedule->start_time);
        $status = $now->diffInMinutes($startTime) > 15 ? 'late' : 'present';

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'schedule_id' => $request->schedule_id,
            'recorded_at' => $now,
            'status' => $status,
        ]);

        return response()->json([
            'message' => 'Attendance recorded successfully',
            'attendance' => $attendance
        ]);
    }

    public function studentReport(Request $request)
    {
        $user = $request->user();
        $attendances = Attendance::with('schedule.course')
            ->where('user_id', $user->id)
            ->get();

        return response()->json($attendances);
    }

    public function courseReport(Request $request, Course $course)
    {
        $user = $request->user();
        
        if ($user->id !== $course->lecturer_id && $user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $report = Schedule::with(['attendances' => function ($query) {
                $query->with('user');
            }])
            ->where('course_id', $course->id)
            ->get();

        return response()->json($report);
    }
}