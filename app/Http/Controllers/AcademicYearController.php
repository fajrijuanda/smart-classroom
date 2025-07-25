<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicYear;

class AcademicYearController extends Controller
{
    // Mendapatkan tahun akademik aktif
    public function getCurrentAcademicYear()
    {
        $academicYear = AcademicYear::where('is_current', true)
            ->with('semester')
            ->firstOrFail();

        return response()->json($academicYear);
    }
}