<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // 'student', 'lecturer', 'admin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // app/Models/User.php
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Jika seorang user adalah dosen
    public function taughtCourses()
    {
        return $this->hasMany(Course::class, 'lecturer_id');
    }

    // Jika seorang user adalah mahasiswa
    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'enrollment', 'user_id', 'course_id')
                    ->withPivot('status') // 'active', 'completed', 'dropped'
                    ->withTimestamps();
    }
    // Jika seorang user adalah admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
