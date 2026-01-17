<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CognitiveScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'skill_type',
        'current_score',
        'highest_score',
        'total_sessions',
        'average_score',
    ];

    protected $casts = [
        'current_score' => 'integer',
        'highest_score' => 'integer',
        'total_sessions' => 'integer',
        'average_score' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeBySkillType($query, $skillType)
    {
        return $query->where('skill_type', $skillType);
    }

    public function scopeTopScores($query, $limit = 10)
    {
        return $query->orderBy('current_score', 'desc')->limit($limit);
    }

    public function getLevelAttribute()
    {
        // Calculate level based on score (every 100 points = 1 level)
        return min(10, max(1, intval($this->current_score / 100) + 1));
    }

    public function getProgressToNextLevelAttribute()
    {
        $currentLevel = $this->level;
        $pointsForCurrentLevel = ($currentLevel - 1) * 100;
        $pointsForNextLevel = $currentLevel * 100;
        $pointsInCurrentLevel = $this->current_score - $pointsForCurrentLevel;
        $pointsNeededForNextLevel = $pointsForNextLevel - $pointsForCurrentLevel;
        
        if ($currentLevel >= 10) {
            return 100; // Max level reached
        }
        
        return min(100, max(0, ($pointsInCurrentLevel / 100) * 100));
    }
}
