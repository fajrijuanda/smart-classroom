<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
// app/Models/Schedule.php
protected $casts = [
    'start_time' => 'datetime',
    'end_time' => 'datetime',
];

public function course()
{
    return $this->belongsTo(Course::class);
}

public function room()
{
    return $this->belongsTo(Room::class);
}

public function attendances()
{
    return $this->hasMany(Attendance::class);
}}
