<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Student;
use App\Models\FocusSession;
use App\Models\CognitiveSession;
use App\Models\Message;

class TeacherDashboardController extends Controller
{
    /**
     * Show teacher dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get teacher's courses with relationships
        $courses = Course::where('teacher_id', $user->id)
            ->with(['students', 'assignments'])
            ->get();
        
        // Get total students across all courses
        $totalStudents = $courses->sum(function($course) {
            return $course->students->count();
        });
        
        // Get recent activity
        $recentSessions = FocusSession::whereHas('user', function($query) use ($courses) {
            $studentIds = $courses->flatMap->students->pluck('user_id');
            $query->whereIn('user_id', $studentIds);
        })->with('user')->latest()->limit(10)->get();
        
        // Get cognitive activity
        $cognitiveActivity = CognitiveSession::whereHas('user', function($query) use ($courses) {
            $studentIds = $courses->flatMap->students->pluck('user_id');
            $query->whereIn('user_id', $studentIds);
        })->with('user')->latest()->limit(5)->get();
        
        // Get recent messages
        $recentMessages = Message::where('recipient_id', $user->id)
            ->orWhere('sender_id', $user->id)
            ->with(['sender', 'recipient'])
            ->latest()
            ->limit(5)
            ->get();
        
        // Calculate statistics
        $stats = $this->calculateStats($courses);
        
        return view('teacher.dashboard', compact(
            'courses', 
            'totalStudents', 
            'recentSessions', 
            'cognitiveActivity', 
            'recentMessages', 
            'stats'
        ));
    }
    
    /**
     * Calculate dashboard statistics
     */
    private function calculateStats($courses)
    {
        $studentIds = $courses->flatMap->students->pluck('user_id')->unique();
        
        $today = now()->startOfDay();
        $weekAgo = $today->copy()->subWeek();
        
        // Focus session stats
        $todaySessions = FocusSession::whereIn('user_id', $studentIds)
            ->where('created_at', '>=', $today)
            ->get();
            
        $weekSessions = FocusSession::whereIn('user_id', $studentIds)
            ->where('created_at', '>=', $weekAgo)
            ->get();
        
        // Cognitive activity stats
        $cognitiveSessions = CognitiveSession::whereIn('user_id', $studentIds)
            ->where('created_at', '>=', $weekAgo)
            ->get();
        
        return [
            'total_courses' => $courses->count(),
            'total_students' => $studentIds->count(),
            'today_focus_sessions' => $todaySessions->count(),
            'today_focus_minutes' => round($todaySessions->sum('elapsed_seconds') / 60, 1),
            'week_focus_sessions' => $weekSessions->count(),
            'week_focus_minutes' => round($weekSessions->sum('elapsed_seconds') / 60, 1),
            'cognitive_sessions' => $cognitiveSessions->count(),
            'avg_session_length' => $weekSessions->count() > 0 ? round($weekSessions->avg('elapsed_seconds') / 60, 1) : 0,
        ];
    }
    
    /**
     * Get class performance data
     */
    public function getClassPerformance(Request $request)
    {
        $user = Auth::user();
        $courseId = $request->get('course_id');
        
        $course = Course::where('teacher_id', $user->id)
            ->where('id', $courseId)
            ->with('students.user')
            ->first();
        
        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }
        
        $studentIds = $course->students->pluck('user_id');
        
        $weekAgo = now()->subWeek();
        
        $performance = [];
        
        foreach ($course->students as $student) {
            $sessions = FocusSession::where('user_id', $student->user_id)
                ->where('created_at', '>=', $weekAgo)
                ->get();
                
            $cognitiveSessions = CognitiveSession::where('user_id', $student->user_id)
                ->where('created_at', '>=', $weekAgo)
                ->get();
            
            $performance[] = [
                'student_id' => $student->id,
                'student_name' => $student->user->name,
                'focus_sessions' => $sessions->count(),
                'focus_minutes' => round($sessions->sum('elapsed_seconds') / 60, 1),
                'cognitive_sessions' => $cognitiveSessions->count(),
                'avg_cognitive_score' => $cognitiveSessions->count() > 0 ? round($cognitiveSessions->avg('score'), 1) : 0,
            ];
        }
        
        return response()->json($performance);
    }
}
