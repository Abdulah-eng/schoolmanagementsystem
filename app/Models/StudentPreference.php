<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'grade_year',
        'curriculum_board',
        'learning_style',
        'weekly_goal',
        'skill_area',
        'meta',
        'profile_completed',
    ];

    protected $casts = [
        'meta' => 'array',
        'profile_completed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}









