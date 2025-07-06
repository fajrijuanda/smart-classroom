<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicCalendarMeeting extends Model
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
        'meeting_number',
        'meeting_date',
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
            'meeting_date' => 'datetime',
        ];
    }
}
