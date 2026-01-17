<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiInteraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'interaction_type',
        'user_input',
        'ai_response',
        'metadata',
        'interaction_time',
    ];

    protected $casts = [
        'metadata' => 'array',
        'interaction_time' => 'datetime',
    ];

    /**
     * Get the user that made this interaction
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get interactions by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('interaction_type', $type);
    }

    /**
     * Scope to get recent interactions
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('interaction_time', '>=', now()->subDays($days));
    }
}
