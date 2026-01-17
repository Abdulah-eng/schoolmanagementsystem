<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FocusSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_type',
        'planned_minutes',
        'elapsed_seconds',
        'status',
        'started_at',
        'paused_at',
        'completed_at',
        'settings',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'paused_at' => 'datetime',
        'completed_at' => 'datetime',
        'settings' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}








