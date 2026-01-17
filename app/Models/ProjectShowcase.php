<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectShowcase extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'grade',
        'youtube_url',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];
}

