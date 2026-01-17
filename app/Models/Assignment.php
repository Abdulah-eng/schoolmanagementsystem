<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'due_date',
        'max_points',
        'assignment_type',
        'is_published',
    ];

    protected $casts = [
        'due_date' => 'date',
        'is_published' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
