<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;

class AcademicYearController extends Controller
{
    public function getCurrentAcademicYear()
    {
        $academicYear = AcademicYear::where('is_current', true)
            ->with('semester')
            ->firstOrFail();

        return response()->json($academicYear);
    }
}
