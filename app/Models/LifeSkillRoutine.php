<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LifeSkillRoutine extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity',
        'time',
        'duration',
    ];

    protected $casts = [
        'time' => 'datetime:H:i',
        'duration' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedDurationAttribute()
    {
        if ($this->duration < 60) {
            return $this->duration . ' min';
        } elseif ($this->duration == 60) {
            return '1 hour';
        } else {
            $hours = floor($this->duration / 60);
            $minutes = $this->duration % 60;
            if ($minutes == 0) {
                return $hours . ' hours';
            } else {
                return $hours . 'h ' . $minutes . 'm';
            }
        }
    }

    public function getFormattedTimeAttribute()
    {
        return $this->time->format('g:i A');
    }
}
