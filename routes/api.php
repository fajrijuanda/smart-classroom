<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\AcademicCalendarController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\AcademicYearController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // Profile routes
    Route::prefix('profile')->group(function () {
        Route::put('/', [ProfileController::class, 'update']);
        Route::get('/face-images', [ProfileController::class, 'getFaceImages']);
        Route::post('/face-images', [ProfileController::class, 'manageFaceImages']);
    });
    
    // Course routes
    Route::prefix('courses')->group(function () {
        Route::get('/', [CourseController::class, 'index']);
        Route::get('/{course}', [CourseController::class, 'show']);
        Route::post('/{course}/enroll', [CourseController::class, 'enroll']);
    });
    
    // Attendance routes
    Route::prefix('attendance')->group(function () {
        Route::post('/record', [AttendanceController::class, 'record']);
        Route::get('/report', [AttendanceController::class, 'studentReport']);
        Route::get('/report/{course}', [AttendanceController::class, 'courseReport']);
    });

    // Academic Calendar routes
    Route::prefix('academic-calendars')->group(function () {
        Route::get('/current', [AcademicCalendarController::class, 'getCurrentCalendar']);
        Route::post('/{calendar}/holidays', [AcademicCalendarController::class, 'addHoliday']);
        Route::post('/{calendar}/meetings', [AcademicCalendarController::class, 'addMeeting']);
    });

    // Room routes
    Route::prefix('rooms')->group(function () {
        Route::get('/buildings', [RoomController::class, 'getBuildingsWithRooms']);
        Route::get('/by-device', [RoomController::class, 'getRoomByDevice']);
    });

    // Schedule routes
    Route::prefix('schedules')->group(function () {
        Route::get('/today', [ScheduleController::class, 'getTodaySchedules']);
        Route::get('/my-schedules', [ScheduleController::class, 'getStudentSchedules']);
    });

    // Academic Year routes
    Route::get('/academic-years/current', [AcademicYearController::class, 'getCurrentAcademicYear']);
});