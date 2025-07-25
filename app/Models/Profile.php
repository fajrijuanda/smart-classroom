<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nim',
        'bio',
        'avatar',
        'phone',
        'address',
        'website',
        'social_links',
        'face_images',
    ];

    protected $casts = [
        'social_links' => 'array',
        'face_images' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}