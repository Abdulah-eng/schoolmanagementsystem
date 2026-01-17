<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LifeSkillCommunication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'scenario_type',
        'status',
        'started_at',
        'completed_at',
        'reflection',
        'confidence_rating',
        'time_spent',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'confidence_rating' => 'integer',
        'time_spent' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getScenarioTitleAttribute()
    {
        $titles = [
            'group-project' => 'Group Project Communication',
            'teacher-meeting' => 'Teacher Meeting Communication',
            'parent-conversation' => 'Parent Conversation Communication',
        ];
        
        return $titles[$this->scenario_type] ?? 'Communication Scenario';
    }

    public function getStatusBadgeAttribute()
    {
        if ($this->status === 'active') {
            return '<span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Active</span>';
        } elseif ($this->status === 'completed') {
            return '<span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Completed</span>';
        } else {
            return '<span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Unknown</span>';
        }
    }

    public function getConfidenceLevelAttribute()
    {
        if ($this->confidence_rating === null) {
            return 'Not rated';
        }
        
        $levels = [
            1 => 'Very Low',
            2 => 'Low',
            3 => 'Medium',
            4 => 'High',
            5 => 'Very High',
        ];
        
        return $levels[$this->confidence_rating] ?? 'Unknown';
    }

    public function getTimeSpentFormattedAttribute()
    {
        if ($this->time_spent === null) {
            return 'Not recorded';
        }
        
        $minutes = $this->time_spent;
        if ($minutes < 60) {
            return $minutes . ' min';
        } else {
            $hours = floor($minutes / 60);
            $remainingMinutes = $minutes % 60;
            if ($remainingMinutes == 0) {
                return $hours . ' hour' . ($hours > 1 ? 's' : '');
            } else {
                return $hours . 'h ' . $remainingMinutes . 'm';
            }
        }
    }
}
