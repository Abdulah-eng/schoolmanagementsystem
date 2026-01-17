<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Student;
use App\Models\FocusSession;
use App\Models\CognitiveSession;
use App\Models\Message;

class ParentDashboardController extends Controller
{
    /**
     * Show parent dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get parent's children (children are users with parent_id pointing to this user)
        $children = User::where('parent_id', $user->id)
            ->where('role', 'student')
            ->get();
        
        // Get children's user IDs
        $childrenUserIds = $children->pluck('id');
        
        // Get recent activity from children
        $recentSessions = FocusSession::whereIn('user_id', $childrenUserIds)
            ->with('user')
            ->latest()
            ->limit(10)
            ->get();
        
        // Get cognitive activity from children
        $cognitiveActivity = CognitiveSession::whereIn('user_id', $childrenUserIds)
            ->with('user')
            ->latest()
            ->limit(5)
            ->get();
        
        // Get recent messages
        $recentMessages = Message::where('recipient_id', $user->id)
            ->orWhere('sender_id', $user->id)
            ->with(['sender', 'recipient'])
            ->latest()
            ->limit(5)
            ->get();
        
        // Calculate statistics
        $stats = $this->calculateStats($childrenUserIds);
        
        // Get weekly activity for each child
        $weeklyActivity = $this->getWeeklyActivity($childrenUserIds);
        
        return view('parent.dashboard', compact(
            'children',
            'recentSessions', 
            'cognitiveActivity', 
            'recentMessages', 
            'stats',
            'weeklyActivity'
        ));
    }
    
    /**
     * Calculate dashboard statistics
     */
    private function calculateStats($childrenUserIds)
    {
        $today = now()->startOfDay();
        $weekAgo = $today->copy()->subWeek();
        $monthAgo = $today->copy()->subMonth();
        
        // Focus session stats
        $todaySessions = FocusSession::whereIn('user_id', $childrenUserIds)
            ->where('created_at', '>=', $today)
            ->get();
            
        $weekSessions = FocusSession::whereIn('user_id', $childrenUserIds)
            ->where('created_at', '>=', $weekAgo)
            ->get();
            
        $monthSessions = FocusSession::whereIn('user_id', $childrenUserIds)
            ->where('created_at', '>=', $monthAgo)
            ->get();
        
        // Cognitive activity stats
        $cognitiveSessions = CognitiveSession::whereIn('user_id', $childrenUserIds)
            ->where('created_at', '>=', $weekAgo)
            ->get();
        
        return [
            'total_children' => $childrenUserIds->count(),
            'today_focus_sessions' => $todaySessions->count(),
            'today_focus_minutes' => round($todaySessions->sum('elapsed_seconds') / 60, 1),
            'week_focus_sessions' => $weekSessions->count(),
            'week_focus_minutes' => round($weekSessions->sum('elapsed_seconds') / 60, 1),
            'month_focus_sessions' => $monthSessions->count(),
            'month_focus_minutes' => round($monthSessions->sum('elapsed_seconds') / 60, 1),
            'cognitive_sessions' => $cognitiveSessions->count(),
            'avg_session_length' => $weekSessions->count() > 0 ? round($weekSessions->avg('elapsed_seconds') / 60, 1) : 0,
            'avg_cognitive_score' => $cognitiveSessions->count() > 0 ? round($cognitiveSessions->avg('score'), 1) : 0,
        ];
    }
    
    /**
     * Get weekly activity data for children
     */
    private function getWeeklyActivity($childrenUserIds)
    {
        $weekAgo = now()->subWeek();
        $activity = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $nextDate = $date->copy()->addDay();
            
            $sessions = FocusSession::whereIn('user_id', $childrenUserIds)
                ->whereBetween('created_at', [$date, $nextDate])
                ->get();
                
            $cognitive = CognitiveSession::whereIn('user_id', $childrenUserIds)
                ->whereBetween('created_at', [$date, $nextDate])
                ->get();
            
            $activity[] = [
                'date' => $date->format('M j'),
                'day' => $date->format('l'),
                'focus_sessions' => $sessions->count(),
                'focus_minutes' => round($sessions->sum('elapsed_seconds') / 60, 1),
                'cognitive_sessions' => $cognitive->count(),
                'avg_cognitive_score' => $cognitive->count() > 0 ? round($cognitive->avg('score'), 1) : 0,
            ];
        }
        
        return $activity;
    }
    
    /**
     * Get child performance data
     */
    public function getChildPerformance(Request $request)
    {
        $user = Auth::user();
        $childId = $request->get('child_id');
        
        // Get child user (children are users with parent_id pointing to this user)
        $child = User::where('parent_id', $user->id)
            ->where('role', 'student')
            ->where('id', $childId)
            ->first();
        
        if (!$child) {
            return response()->json(['error' => 'Child not found'], 404);
        }
        
        $weekAgo = now()->subWeek();
        $monthAgo = now()->subMonth();
        
        $sessions = FocusSession::where('user_id', $child->id)
            ->where('created_at', '>=', $weekAgo)
            ->get();
            
        $cognitiveSessions = CognitiveSession::where('user_id', $child->id)
            ->where('created_at', '>=', $weekAgo)
            ->get();
        
        $monthSessions = FocusSession::where('user_id', $child->id)
            ->where('created_at', '>=', $monthAgo)
            ->get();
        
        $performance = [
            'child_name' => $child->name,
            'week_focus_sessions' => $sessions->count(),
            'week_focus_minutes' => round($sessions->sum('elapsed_seconds') / 60, 1),
            'month_focus_sessions' => $monthSessions->count(),
            'month_focus_minutes' => round($monthSessions->sum('elapsed_seconds') / 60, 1),
            'cognitive_sessions' => $cognitiveSessions->count(),
            'avg_cognitive_score' => $cognitiveSessions->count() > 0 ? round($cognitiveSessions->avg('score'), 1) : 0,
            'avg_session_length' => $sessions->count() > 0 ? round($sessions->avg('elapsed_seconds') / 60, 1) : 0,
        ];
        
        return response()->json($performance);
    }
}
