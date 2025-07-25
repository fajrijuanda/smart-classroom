<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AttendanceController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // Profile routes
    Route::prefix('profile')->group(function () {
        Route::put('/', [ProfileController::class, 'update']);
        Route::get('/face-images', [ProfileController::class, 'getFaceImages']);
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
});