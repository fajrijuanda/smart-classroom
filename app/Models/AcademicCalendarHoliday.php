<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicCalendarHoliday extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_calendar_id',
        'holiday_date',
        'name',
        'description',
    ];

    protected $casts = [
        'holiday_date' => 'date',
    ];

    public function academicCalendar()
    {
        return $this->belongsTo(AcademicCalendar::class);
    }
}