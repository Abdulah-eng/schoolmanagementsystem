<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Student;
use App\Models\Course;

class StudentAssignmentController extends Controller
{
    /**
     * Show all assignments for the student
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get student record with courses
        $student = Student::where('user_id', $user->id)->with('courses')->first();
        
        if (!$student) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Student profile not found. Please complete your profile.');
        }
        
        // Get courses the student is enrolled in
        $enrolledCourseIds = $student->courses->pluck('id')->toArray();
        
        // Get all published assignments from enrolled courses
        $assignments = Assignment::whereIn('course_id', $enrolledCourseIds)
            ->where('is_published', true)
            ->with(['course', 'submissions' => function($query) use ($student) {
                $query->where('student_id', $student->id);
            }])
            ->orderBy('due_date')
            ->get();
        
        // Categorize assignments
        $upcoming = $assignments->filter(function($assignment) {
            return $assignment->due_date->isFuture() && !$assignment->submissions->first();
        });
        
        $submitted = $assignments->filter(function($assignment) {
            return $assignment->submissions->first();
        });
        
        $overdue = $assignments->filter(function($assignment) {
            return $assignment->due_date->isPast() && !$assignment->submissions->first();
        });
        
        return view('student.assignments', compact('assignments', 'upcoming', 'submitted', 'overdue'));
    }
    
    /**
     * Show assignment details and submission form
     */
    public function show(Assignment $assignment)
    {
        $user = Auth::user();
        
        // Get student record with courses
        $student = Student::where('user_id', $user->id)->with('courses')->first();
        
        if (!$student) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Student profile not found.');
        }
        
        // Check if student is enrolled in the course
        $isEnrolled = $student->courses->contains('id', $assignment->course_id);
        
        if (!$isEnrolled) {
            abort(403, 'You are not enrolled in this course.');
        }
        
        // Get existing submission if any
        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', $student->id)
            ->first();
        
        $assignment->load('course');
        
        return view('student.assignment-show', compact('assignment', 'submission', 'student'));
    }
    
    /**
     * Submit assignment
     */
    public function submit(Request $request, Assignment $assignment)
    {
        $user = Auth::user();
        
        // Get student record with courses
        $student = Student::where('user_id', $user->id)->with('courses')->first();
        
        if (!$student) {
            return response()->json(['error' => 'Student profile not found.'], 404);
        }
        
        // Check if student is enrolled in the course
        $isEnrolled = $student->courses->contains('id', $assignment->course_id);
        
        if (!$isEnrolled) {
            return response()->json(['error' => 'You are not enrolled in this course.'], 403);
        }
        
        // Check if assignment is published
        if (!$assignment->is_published) {
            return response()->json(['error' => 'This assignment is not available for submission.'], 403);
        }
        
        $request->validate([
            'submission_content' => 'required|string|min:10',
            'attachment_url' => 'nullable|url|max:500',
        ]);
        
        // Check if already submitted
        $existingSubmission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', $student->id)
            ->first();
        
        if ($existingSubmission) {
            // Update existing submission
            $existingSubmission->update([
                'submission_content' => $request->submission_content,
                'attachment_url' => $request->attachment_url,
                'submitted_at' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Assignment updated successfully.',
                'submission' => $existingSubmission,
            ]);
        } else {
            // Create new submission
            $submission = AssignmentSubmission::create([
                'assignment_id' => $assignment->id,
                'student_id' => $student->id,
                'submission_content' => $request->submission_content,
                'attachment_url' => $request->attachment_url,
                'submitted_at' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Assignment submitted successfully.',
                'submission' => $submission,
            ]);
        }
    }
}
