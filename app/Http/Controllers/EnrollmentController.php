<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    /**
     * Show enrollment page for a course (teacher)
     */
    public function show(Course $course)
    {
        $user = Auth::user();
        
        // Check if user is teacher of this course or admin
        if ($user->role === 'teacher' && $course->teacher_id !== $user->id) {
            abort(403, 'You can only enroll students in your own courses.');
        }
        
        if (!in_array($user->role, ['teacher', 'admin'])) {
            abort(403, 'Unauthorized');
        }
        
        $course->load('students.user');
        $enrolledStudentIds = $course->students->pluck('id')->toArray();
        
        // Get all student users (users with role='student')
        $studentUsers = User::where('role', 'student')
            ->with('student')
            ->get();
        
        // Get or create Student records for users that don't have them
        $availableStudents = collect();
        foreach ($studentUsers as $user) {
            // If user doesn't have a Student record, create one
            if (!$user->student) {
                try {
                    // Generate unique student_id
                    $maxAttempts = 10;
                    $attempt = 0;
                    $studentId = null;
                    
                    while ($attempt < $maxAttempts) {
                        $candidateId = Student::generateStudentId();
                        if (!Student::where('student_id', $candidateId)->exists()) {
                            $studentId = $candidateId;
                            break;
                        }
                        $attempt++;
                        // Add small delay to ensure different timestamp
                        usleep(1000);
                    }
                    
                    if (!$studentId) {
                        // Fallback: use timestamp-based ID
                        $studentId = date('Y') . str_pad(time() % 10000, 4, '0', STR_PAD_LEFT);
                    }
                    
                    $student = Student::create([
                        'user_id' => $user->id,
                        'student_id' => $studentId,
                        'grade_level' => 'Grade 9', // Default, can be updated later
                        'section' => 'A',
                        'enrollment_date' => now(),
                        'parent_name' => $user->parent ? $user->parent->name : 'N/A',
                        'parent_phone' => $user->parent ? $user->parent->phone : 'N/A',
                        'medical_info' => null,
                    ]);
                    $user->refresh();
                    $user->load('student');
                } catch (\Exception $e) {
                    // If creation fails, try to get existing record
                    $user->load('student');
                    // Log error but continue
                    \Log::warning("Failed to create Student record for user {$user->id}: " . $e->getMessage());
                }
            }
            
            // Only include if not already enrolled and has a student record
            if ($user->student && !in_array($user->student->id, $enrolledStudentIds)) {
                $availableStudents->push($user->student);
            }
        }
        
        // Sort by grade level and name
        $availableStudents = $availableStudents->sortBy(function($student) {
            return ($student->grade_level ?? '') . ' ' . ($student->user->name ?? '');
        })->values();
        
        return view('enrollment.show', [
            'course' => $course,
            'enrolledStudents' => $course->students,
            'availableStudents' => $availableStudents,
        ]);
    }
    
    /**
     * Enroll students in a course
     */
    public function enroll(Request $request, Course $course)
    {
        $user = Auth::user();
        
        // Check authorization
        if ($user->role === 'teacher' && $course->teacher_id !== $user->id) {
            return response()->json(['error' => 'You can only enroll students in your own courses.'], 403);
        }
        
        if (!in_array($user->role, ['teacher', 'admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'required|exists:students,id',
        ]);
        
        $enrolled = 0;
        $errors = [];
        
        foreach ($request->student_ids as $studentId) {
            // Check if already enrolled
            $exists = DB::table('student_course')
                ->where('student_id', $studentId)
                ->where('course_id', $course->id)
                ->exists();
            
            if (!$exists) {
                try {
                    DB::table('student_course')->insert([
                        'student_id' => $studentId,
                        'course_id' => $course->id,
                        'status' => 'enrolled',
                        'progress' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $enrolled++;
                } catch (\Exception $e) {
                    $errors[] = "Failed to enroll student ID {$studentId}: " . $e->getMessage();
                }
            }
        }
        
        if ($enrolled > 0) {
            return response()->json([
                'success' => true,
                'message' => "Successfully enrolled {$enrolled} student(s).",
                'enrolled' => $enrolled,
            ]);
        } else {
            return response()->json([
                'error' => 'No students were enrolled. They may already be enrolled.',
                'errors' => $errors,
            ], 422);
        }
    }
    
    /**
     * Unenroll a student from a course
     */
    public function unenroll(Request $request, Course $course, Student $student)
    {
        $user = Auth::user();
        
        // Check authorization
        if ($user->role === 'teacher' && $course->teacher_id !== $user->id) {
            return response()->json(['error' => 'You can only unenroll students from your own courses.'], 403);
        }
        
        if (!in_array($user->role, ['teacher', 'admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Check if student is enrolled
        $enrollment = DB::table('student_course')
            ->where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->first();
        
        if (!$enrollment) {
            return response()->json(['error' => 'Student is not enrolled in this course.'], 404);
        }
        
        DB::table('student_course')
            ->where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Student unenrolled successfully.',
        ]);
    }
    
    /**
     * Admin: Show all enrollments
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role !== 'admin') {
            abort(403, 'Only admins can view all enrollments.');
        }
        
        $courses = Course::with(['teacher', 'students.user'])->latest()->get();
        $students = Student::with('user')->orderBy('grade_level')->get();
        
        return view('admin.enrollments', [
            'courses' => $courses,
            'students' => $students,
        ]);
    }
}
