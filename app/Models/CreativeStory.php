<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreativeStory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'story_content',
        'words_used',
        'word_count',
        'submitted_at',
    ];

    protected $casts = [
        'words_used' => 'array',
        'submitted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('submitted_at', '>=', now()->subDays($days));
    }

    public function scopeByWordCount($query, $minWords = 0, $maxWords = null)
    {
        $query->where('word_count', '>=', $minWords);
        
        if ($maxWords) {
            $query->where('word_count', '<=', $maxWords);
        }
        
        return $query;
    }

    public function getExcerptAttribute($length = 100)
    {
        return substr($this->story_content, 0, $length) . (strlen($this->story_content) > $length ? '...' : '');
    }

    public function getWordsUsedListAttribute()
    {
        return is_array($this->words_used) ? $this->words_used : [];
    }
}
