<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function record(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'schedule_id' => 'required|exists:schedules,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = $request->user();
        $schedule = Schedule::find($request->schedule_id);

        $room = $schedule->room;
        /*if ($room->device_id !== $request->device_id) {
            return response()->json(['message' => 'Invalid device for this schedule'], 400);
        }*/

        $existing = Attendance::where('user_id', $user->id)
            ->where('schedule_id', $request->schedule_id)
            ->exists();

        if ($existing) {
            return response()->json(['message' => 'Attendance already recorded'], 409);
        }

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
