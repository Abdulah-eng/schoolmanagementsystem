<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'subject',
        'due_date',
        'progress_percent',
    ];

    protected $casts = [
        'due_date' => 'date',
        'progress_percent' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(ProjectTask::class);
    }

    public function comments()
    {
        return $this->hasMany(ProjectComment::class);
    }
}

