<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Profile\ProfileController; // Pastikan controller di-import

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // TAMBAHKAN DUA BARIS INI
    Route::get('/profile/create', [ProfileController::class, 'create'])->name('profile.create');
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.store');

    // Presence
    Route::get('/presence', function () {
        return Inertia::render('CameraPresence');
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';