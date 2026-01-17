<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Student;

class TeacherAssignmentController extends Controller
{
    /**
     * Show assignments for a course
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $courseId = $request->get('course_id');
        
        // Get all courses for this teacher
        $courses = Course::where('teacher_id', $user->id)->get();
        
        if ($courseId) {
            // If a specific course is selected, show assignments for that course
            $course = Course::where('teacher_id', $user->id)
                ->where('id', $courseId)
                ->with('assignments.submissions')
                ->first();
            
            if (!$course) {
                return redirect()->route('teacher.assignments.index')->with('error', 'Course not found');
            }
        } else {
            // If no course selected, show all assignments for all courses
            $course = null;
        }
        
        // Get all assignments for this teacher's courses
        $assignments = Assignment::whereHas('course', function($query) use ($user) {
            $query->where('teacher_id', $user->id);
        })->with(['course', 'submissions'])->latest()->get();
        
        return view('teacher.assignments', compact('course', 'courses', 'assignments'));
    }
    
    /**
     * Create new assignment
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $courseId = $request->get('course_id');
        
        // Get all courses for this teacher
        $courses = Course::where('teacher_id', $user->id)->get();
        
        if ($courses->isEmpty()) {
            return redirect()->route('teacher.courses.index')
                ->with('error', 'Please create a course first before adding assignments.');
        }
        
        // If course_id is provided, use it; otherwise use the first course
        if ($courseId) {
            $course = $courses->firstWhere('id', $courseId);
            if (!$course) {
                return redirect()->route('teacher.assignments.create')
                    ->with('error', 'Course not found');
            }
        } else {
            // If no course_id provided, use the first course or let user select
            $course = $courses->first();
        }
        
        return view('teacher.assignments.create', compact('course', 'courses'));
    }
    
    /**
     * Store new assignment
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date|after:today',
            'max_points' => 'required|integer|min:1|max:1000',
            'assignment_type' => 'required|in:homework,project,quiz,exam',
        ], [
            'due_date.after' => 'The due date must be after today.',
        ]);
        
        $course = Course::where('teacher_id', $user->id)
            ->where('id', $request->course_id)
            ->first();
        
        if (!$course) {
            return back()->with('error', 'Course not found');
        }
        
        $assignment = Assignment::create([
            'course_id' => $request->course_id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'max_points' => $request->max_points,
            'assignment_type' => $request->assignment_type,
            'is_published' => $request->has('is_published'),
        ]);
        
        return redirect()->route('teacher.assignments.index', ['course_id' => $request->course_id])
            ->with('success', 'Assignment created successfully');
    }
    
    /**
     * Show assignment details
     */
    public function show(Assignment $assignment)
    {
        $user = Auth::user();
        
        // Verify teacher owns this assignment
        if ($assignment->course->teacher_id !== $user->id) {
            abort(403);
        }
        
        $assignment->load(['course', 'submissions.student.user']);
        
        return view('teacher.assignments.show', compact('assignment'));
    }
    
    /**
     * Grade assignment submission
     */
    public function gradeSubmission(Request $request, Assignment $assignment)
    {
        $user = Auth::user();
        
        // Verify teacher owns this assignment
        if ($assignment->course->teacher_id !== $user->id) {
            abort(403);
        }
        
        $request->validate([
            'submission_id' => 'required|exists:assignment_submissions,id',
            'points_earned' => 'required|integer|min:0|max:' . $assignment->max_points,
            'feedback' => 'nullable|string|max:1000',
        ]);
        
        $submission = $assignment->submissions()->findOrFail($request->submission_id);
        
        $submission->update([
            'points_earned' => $request->points_earned,
            'feedback' => $request->feedback,
            'graded_at' => now(),
            'graded_by' => $user->id,
        ]);
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Get assignment statistics
     */
    public function getStats(Assignment $assignment)
    {
        $user = Auth::user();
        
        // Verify teacher owns this assignment
        if ($assignment->course->teacher_id !== $user->id) {
            abort(403);
        }
        
        $submissions = $assignment->submissions;
        $totalStudents = $assignment->course->students->count();
        
        $stats = [
            'total_students' => $totalStudents,
            'submissions_received' => $submissions->count(),
            'submission_rate' => $totalStudents > 0 ? round(($submissions->count() / $totalStudents) * 100, 1) : 0,
            'graded_submissions' => $submissions->whereNotNull('graded_at')->count(),
            'average_score' => $submissions->whereNotNull('points_earned')->count() > 0 
                ? round($submissions->whereNotNull('points_earned')->avg('points_earned'), 1) 
                : 0,
            'late_submissions' => $submissions->where('submitted_at', '>', $assignment->due_date)->count(),
        ];
        
        return response()->json($stats);
    }
}
