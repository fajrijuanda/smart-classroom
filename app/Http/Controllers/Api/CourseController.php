<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'lecturer') {
            $courses = Course::where('lecturer_id', $user->id)->get();
        } elseif ($user->role === 'student') {
            $courses = $user->enrollments()->with('course')->get()->pluck('course');
        } else {
            $courses = Course::all();
        }

        return response()->json($courses);
    }

    public function show(Course $course)
    {
        $course->load('lecturer', 'schedules.room.building');
        return response()->json($course);
    }

    public function enroll(Request $request, Course $course)
    {
        $user = Auth::user();

        if ($user->role !== 'student') {
            return response()->json(['message' => 'Only students can enroll'], 403);
        }

        if ($user->enrollments()->where('course_id', $course->id)->exists()) {
            return response()->json(['message' => 'Already enrolled in this course'], 409);
        }

        if ($course->enrollments()->count() >= $course->capacity) {
            return response()->json(['message' => 'Course is full'], 400);
        }

        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'academic_year_id' => 1,
        ]);

        return response()->json(['message' => 'Enrolled successfully']);
    }
}
