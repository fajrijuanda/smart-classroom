<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
