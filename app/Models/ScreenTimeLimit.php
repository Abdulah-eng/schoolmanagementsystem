<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScreenTimeLimit extends Model
{
    protected $fillable = [
        'user_id',
        'daily_limit_minutes',
        'weekday_limit_minutes',
        'weekend_limit_minutes',
        'bedtime_hour',
        'wakeup_hour',
        'blocked_apps',
        'is_active',
    ];

    protected $casts = [
        'blocked_apps' => 'array',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
