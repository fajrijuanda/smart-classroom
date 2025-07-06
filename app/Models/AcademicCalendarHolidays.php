<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicCalendarHolidays extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    use \Illuminate\Notifications\Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'academic_calendar_id',
        'holiday_date',
        'name',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'holiday_date' => 'datetime',
        ];
    }
}
