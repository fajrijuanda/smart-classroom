<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcademicCalendar;
use Illuminate\Support\Facades\Validator;

class AcademicCalendarController extends Controller
{
    public function getCurrentCalendar()
    {
        $calendar = AcademicCalendar::where('is_current', true)
            ->with(['academicYear', 'semester', 'holidays', 'meetings'])
            ->first();

        return response()->json($calendar);
    }

    public function addHoliday(Request $request, AcademicCalendar $calendar)
    {
        $validator = Validator::make($request->all(), [
            'holiday_date' => 'required|date',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $holiday = $calendar->holidays()->create($request->all());

        return response()->json([
            'message' => 'Holiday added successfully',
            'holiday' => $holiday
        ], 201);
    }

    public function addMeeting(Request $request, AcademicCalendar $calendar)
    {
        $validator = Validator::make($request->all(), [
            'meeting_date' => 'required|date',
            'meeting_number' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $meeting = $calendar->meetings()->create($request->all());

        return response()->json([
            'message' => 'Meeting added successfully',
            'meeting' => $meeting
        ], 201);
    }
}
