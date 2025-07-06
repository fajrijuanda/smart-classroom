<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    use \Illuminate\Notifications\Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'code',
        'lecturer_id', // foreign key to User model
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
    // app/Models/Course.php
    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollment', 'course_id', 'user_id')
                    ->withPivot('status') // 'enrolled', 'completed', 'dropped'
                    ->withTimestamps();
    }
}
