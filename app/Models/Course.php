<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_code',
        'course_name',
        'description',
        'grade_level',
        'credits',
        'teacher_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the teacher for this course
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the students enrolled in this course
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_course');
    }

    /**
     * Get assignments that belong to this course
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Provide a friendly accessor so older blades using "name" still work.
     */
    public function getNameAttribute(): string
    {
        return $this->course_name;
    }

    /**
     * Scope to get only active courses
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get courses by grade level
     */
    public function scopeByGradeLevel($query, $gradeLevel)
    {
        return $query->where('grade_level', $gradeLevel);
    }
}
