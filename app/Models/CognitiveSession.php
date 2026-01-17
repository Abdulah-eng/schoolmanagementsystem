<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CognitiveSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'skill_type',
        'difficulty_level',
        'status',
        'score',
        'is_correct',
        'time_taken',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_correct' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeBySkillType($query, $skillType)
    {
        return $query->where('skill_type', $skillType);
    }

    public function scopeHighScores($query)
    {
        return $query->where('score', '>', 0)->orderBy('score', 'desc');
    }
}
