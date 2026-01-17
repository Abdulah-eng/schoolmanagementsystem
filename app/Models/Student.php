<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'grade_level',
        'section',
        'enrollment_date',
        'parent_name',
        'parent_phone',
        'medical_info',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
    ];

    /**
     * Get the user that owns the student record
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the courses for this student
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'student_course');
    }

    /**
     * Generate a unique student ID
     */
    public static function generateStudentId(): string
    {
        $year = date('Y');
        $lastStudent = self::whereYear('created_at', $year)->latest()->first();
        $sequence = $lastStudent ? intval(substr($lastStudent->student_id, -4)) + 1 : 1;
        
        return $year . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
