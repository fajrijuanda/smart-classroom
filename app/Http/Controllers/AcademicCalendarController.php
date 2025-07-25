<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicCalendar;
use App\Models\AcademicCalendarHoliday;
use App\Models\AcademicCalendarMeeting;

class AcademicCalendarController extends Controller
{
    // Mendapatkan kalender aktif
    public function getCurrentCalendar()
    {
        $calendar = AcademicCalendar::where('is_current', true)
            ->with(['academicYear', 'semester', 'holidays', 'meetings'])
            ->first();

        return response()->json($calendar);
    }

    // Menambahkan hari libur
    public function addHoliday(Request $request, AcademicCalendar $calendar)
    {
        $request->validate([
            'holiday_date' => 'required|date',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $holiday = $calendar->holidays()->create($request->all());

        return response()->json([
            'message' => 'Holiday added successfully',
            'holiday' => $holiday
        ], 201);
    }

    // Menambahkan jadwal pertemuan
    public function addMeeting(Request $request, AcademicCalendar $calendar)
    {
        $request->validate([
            'meeting_date' => 'required|date',
            'meeting_number' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        $meeting = $calendar->meetings()->create($request->all());

        return response()->json([
            'message' => 'Meeting added successfully',
            'meeting' => $meeting
        ], 201);
    }
}