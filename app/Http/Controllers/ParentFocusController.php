<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FocusSession;
use App\Models\Student;

class ParentFocusController extends Controller
{
    /**
     * Show home focus mode for parent's child
     */
    public function index()
    {
        $user = Auth::user();
        
        $child = $this->getOrAttachChild($user);
        
        $recentSessions = $child ? FocusSession::where('user_id', $child->id)
            ->latest()
            ->limit(10)
            ->get() : collect();
            
        $todaySessions = $child ? FocusSession::where('user_id', $child->id)
            ->whereDate('created_at', today())
            ->get() : collect();
            
        $totalFocusTime = $todaySessions->sum('elapsed_seconds');
        
        return view('parent.focus-mode', compact('child', 'recentSessions', 'totalFocusTime'));
    }
    
    /**
     * Start a focus session for the child
     */
    public function startSession(Request $request)
    {
        $user = Auth::user();
        
        $child = $this->getOrAttachChild($user);
        
        if (!$child) {
            return response()->json(['error' => 'No child found'], 404);
        }
        
        $request->validate([
            'session_type' => 'required|in:pomodoro,deep_work,quick_focus',
            'planned_minutes' => 'required|integer|min:1|max:180',
        ]);
        
        // Cancel any active sessions
        FocusSession::where('user_id', $child->id)
            ->whereIn('status', ['running', 'paused'])
            ->delete();
        
        $session = FocusSession::create([
            'user_id' => $child->id,
            'session_type' => $request->session_type,
            'planned_minutes' => $request->planned_minutes,
            'elapsed_seconds' => 0,
            'status' => 'running',
            'started_at' => now(),
            'settings' => $request->settings ?? [],
        ]);
        
        return response()->json($session);
    }
    
    /**
     * Get child's focus statistics
     */
    public function getStats()
    {
        $user = Auth::user();
        
        $child = $this->getOrAttachChild($user);
        
        if (!$child) {
            return response()->json(['error' => 'No child found'], 404);
        }
        
        $today = now()->startOfDay();
        $weekAgo = $today->copy()->subWeek();
        
        $todaySessions = FocusSession::where('user_id', $child->id)
            ->where('created_at', '>=', $today)
            ->get();
            
        $weekSessions = FocusSession::where('user_id', $child->id)
            ->where('created_at', '>=', $weekAgo)
            ->get();
        
        $stats = [
            'today' => [
                'sessions' => $todaySessions->count(),
                'total_minutes' => round($todaySessions->sum('elapsed_seconds') / 60, 1),
                'avg_session' => $todaySessions->count() > 0 ? round($todaySessions->avg('elapsed_seconds') / 60, 1) : 0,
            ],
            'week' => [
                'sessions' => $weekSessions->count(),
                'total_minutes' => round($weekSessions->sum('elapsed_seconds') / 60, 1),
                'avg_daily' => round($weekSessions->sum('elapsed_seconds') / 60 / 7, 1),
            ],
            'streak' => $this->calculateStreak($child->id),
        ];
        
        return response()->json($stats);
    }
    
    /**
     * Calculate focus streak
     */
    private function calculateStreak($userId)
    {
        $streak = 0;
        $date = now()->startOfDay();
        
        while (true) {
            $hasSession = FocusSession::where('user_id', $userId)
                ->whereDate('created_at', $date)
                ->where('status', 'completed')
                ->exists();
                
            if ($hasSession) {
                $streak++;
                $date->subDay();
            } else {
                break;
            }
        }
        
        return $streak;
    }

    /**
     * Get the first child; if none, try to attach a demo student to this parent.
     */
    private function getOrAttachChild($parent)
    {
        $child = $parent->children()->where('role', 'student')->first();
        if ($child) {
            return $child;
        }

        // Try to attach an existing demo student if available
        $studentUser = \App\Models\User::where('role', 'student')->first();
        if ($studentUser) {
            $studentUser->update(['parent_id' => $parent->id]);
            return $studentUser;
        }

        return null;
    }
    
    /**
     * Complete a focus session
     */
    public function completeSession(Request $request, FocusSession $session)
    {
        $user = Auth::user();
        $child = $this->getOrAttachChild($user);
        
        if (!$child || $session->user_id !== $child->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        if (in_array($session->status, ['running', 'paused'])) {
            $startedAt = $session->started_at ?? $session->created_at;
            $elapsedMinutes = now()->diffInMinutes($startedAt);
            
            $session->update([
                'status' => 'completed',
                'completed_at' => now(),
                'elapsed_seconds' => $elapsedMinutes * 60,
            ]);
        }
        
        return response()->json(['success' => true, 'session' => $session]);
    }
    
    /**
     * Cancel a focus session
     */
    public function cancelSession(Request $request, FocusSession $session)
    {
        $user = Auth::user();
        $child = $this->getOrAttachChild($user);
        
        if (!$child || $session->user_id !== $child->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        if (in_array($session->status, ['running', 'paused'])) {
            $session->update([
                'status' => 'cancelled',
            ]);
        }
        
        return response()->json(['success' => true, 'session' => $session]);
    }
    
    /**
     * Get active session status
     */
    public function getActiveSession()
    {
        $user = Auth::user();
        $child = $this->getOrAttachChild($user);
        
        if (!$child) {
            return response()->json(['error' => 'No child found'], 404);
        }
        
        $session = FocusSession::where('user_id', $child->id)
            ->whereIn('status', ['running', 'paused'])
            ->latest()
            ->first();
        
        if (!$session) {
            return response()->json(['active' => false]);
        }
        
        $startedAt = $session->started_at ?? $session->created_at;
        $elapsedSeconds = now()->diffInSeconds($startedAt);
        $plannedSeconds = $session->planned_minutes * 60;
        $remainingSeconds = max(0, $plannedSeconds - $elapsedSeconds);
        $progress = $plannedSeconds > 0 ? min(100, ($elapsedSeconds / $plannedSeconds) * 100) : 0;
        
        // Auto-complete if time is up
        if ($session->status === 'running' && $remainingSeconds <= 0) {
            $session->update([
                'status' => 'completed',
                'completed_at' => now(),
                'elapsed_seconds' => $plannedSeconds,
            ]);
            return response()->json(['active' => false, 'completed' => true]);
        }
        
        return response()->json([
            'active' => true,
            'session' => $session,
            'elapsed_seconds' => $elapsedSeconds,
            'remaining_seconds' => $remainingSeconds,
            'progress' => $progress,
        ]);
    }
}
