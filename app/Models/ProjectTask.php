<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'is_done',
        'order',
    ];

    protected $casts = [
        'is_done' => 'boolean',
        'order' => 'integer',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

