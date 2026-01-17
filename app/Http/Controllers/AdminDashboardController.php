<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Student;
use App\Models\Course;
use App\Models\FocusSession;
use App\Models\CognitiveSession;
use App\Models\Message;

class AdminDashboardController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get system statistics
        $stats = $this->getSystemStats();
        
        // Get recent activity
        $recentUsers = User::latest()->limit(5)->get();
        $recentSessions = FocusSession::with('user')->latest()->limit(10)->get();
        $recentMessages = Message::with(['sender', 'recipient'])->latest()->limit(5)->get();
        
        // Get user distribution
        $userDistribution = [
            'students' => User::where('role', 'student')->count(),
            'teachers' => User::where('role', 'teacher')->count(),
            'parents' => User::where('role', 'parent')->count(),
            'admins' => User::where('role', 'admin')->count(),
        ];
        
        // Get weekly activity
        $weeklyActivity = $this->getWeeklyActivity();
        
        return view('admin.dashboard', compact(
            'stats', 
            'recentUsers', 
            'recentSessions', 
            'recentMessages', 
            'userDistribution',
            'weeklyActivity'
        ));
    }
    
    /**
     * Get system statistics
     */
    private function getSystemStats()
    {
        $today = now()->startOfDay();
        $weekAgo = $today->copy()->subWeek();
        $monthAgo = $today->copy()->subMonth();
        
        return [
            'total_users' => User::count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_teachers' => User::where('role', 'teacher')->count(),
            'total_parents' => User::where('role', 'parent')->count(),
            'total_courses' => Course::count(),
            'today_sessions' => FocusSession::where('created_at', '>=', $today)->count(),
            'week_sessions' => FocusSession::where('created_at', '>=', $weekAgo)->count(),
            'month_sessions' => FocusSession::where('created_at', '>=', $monthAgo)->count(),
            'today_focus_minutes' => round(FocusSession::where('created_at', '>=', $today)->sum('elapsed_seconds') / 60, 1),
            'week_focus_minutes' => round(FocusSession::where('created_at', '>=', $weekAgo)->sum('elapsed_seconds') / 60, 1),
            'cognitive_sessions' => CognitiveSession::where('created_at', '>=', $weekAgo)->count(),
            'total_messages' => Message::count(),
        ];
    }
    
    /**
     * Get weekly activity data
     */
    private function getWeeklyActivity()
    {
        $weekAgo = now()->subWeek();
        $activity = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $nextDate = $date->copy()->addDay();
            
            $sessions = FocusSession::whereBetween('created_at', [$date, $nextDate])->count();
            $cognitive = CognitiveSession::whereBetween('created_at', [$date, $nextDate])->count();
            $messages = Message::whereBetween('created_at', [$date, $nextDate])->count();
            
            $activity[] = [
                'date' => $date->format('M j'),
                'sessions' => $sessions,
                'cognitive' => $cognitive,
                'messages' => $messages,
            ];
        }
        
        return $activity;
    }
    
    /**
     * Get analytics data
     */
    public function getAnalytics(Request $request)
    {
        $period = $request->get('period', 'week');
        
        $startDate = match($period) {
            'day' => now()->startOfDay(),
            'week' => now()->subWeek()->startOfDay(),
            'month' => now()->subMonth()->startOfDay(),
            'year' => now()->subYear()->startOfDay(),
            default => now()->subWeek()->startOfDay(),
        };
        
        $analytics = [
            'focus_sessions' => FocusSession::where('created_at', '>=', $startDate)->count(),
            'focus_minutes' => round(FocusSession::where('created_at', '>=', $startDate)->sum('elapsed_seconds') / 60, 1),
            'cognitive_sessions' => CognitiveSession::where('created_at', '>=', $startDate)->count(),
            'avg_cognitive_score' => CognitiveSession::where('created_at', '>=', $startDate)->avg('score') ?? 0,
            'active_users' => User::whereHas('focusSessions', function($query) use ($startDate) {
                $query->where('created_at', '>=', $startDate);
            })->count(),
            'new_users' => User::where('created_at', '>=', $startDate)->count(),
        ];
        
        return response()->json($analytics);
    }
}
