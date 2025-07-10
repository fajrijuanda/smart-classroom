<?php
// routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PresenceController; 

Route::get('/detect', [PresenceController::class, 'detect']);
