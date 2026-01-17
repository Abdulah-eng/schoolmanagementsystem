<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\ScreenTimeLimit;

class ParentScreenTimeController extends Controller
{
    /**
     * Show screen time management
     */
    public function index()
    {
        $user = Auth::user();
        
        $child = $this->getOrAttachChild($user);
        
        $screenTimeLimit = $child ? ScreenTimeLimit::where('user_id', $child->id)->first() : null;
        
        return view('parent.screen-time', compact('child', 'screenTimeLimit'));
    }
    
    /**
     * Set screen time limits
     */
    public function setLimits(Request $request)
    {
        $user = Auth::user();
        
        $child = $this->getOrAttachChild($user);
        
        if (!$child) {
            return response()->json(['error' => 'No child found'], 404);
        }
        
        $request->validate([
            'daily_limit_minutes' => 'required|integer|min:30|max:480', // 30 minutes to 8 hours
            'weekday_limit_minutes' => 'required|integer|min:30|max:480',
            'weekend_limit_minutes' => 'required|integer|min:30|max:600',
            'bedtime_hour' => 'required|integer|min:18|max:23',
            'wakeup_hour' => 'required|integer|min:5|max:10',
            'blocked_apps' => 'nullable|array',
            'blocked_apps.*' => 'string|max:100',
        ]);
        
        $screenTimeLimit = ScreenTimeLimit::updateOrCreate(
            ['user_id' => $child->id],
            [
                'daily_limit_minutes' => $request->daily_limit_minutes,
                'weekday_limit_minutes' => $request->weekday_limit_minutes,
                'weekend_limit_minutes' => $request->weekend_limit_minutes,
                'bedtime_hour' => $request->bedtime_hour,
                'wakeup_hour' => $request->wakeup_hour,
                'blocked_apps' => $request->blocked_apps ?? [],
                'is_active' => $request->has('is_active'),
            ]
        );
        
        return response()->json($screenTimeLimit);
    }
    
    /**
     * Get screen time usage
     */
    public function getUsage()
    {
        $user = Auth::user();
        
        $child = $this->getOrAttachChild($user);
        
        if (!$child) {
            return response()->json(['error' => 'No child found'], 404);
        }
        
        $today = now()->startOfDay();
        $weekAgo = $today->copy()->subWeek();
        
        // Get focus sessions as proxy for screen time
        $todaySessions = \App\Models\FocusSession::where('user_id', $child->id)
            ->where('created_at', '>=', $today)
            ->get();
            
        $weekSessions = \App\Models\FocusSession::where('user_id', $child->id)
            ->where('created_at', '>=', $weekAgo)
            ->get();
        
        $screenTimeLimit = ScreenTimeLimit::where('user_id', $child->id)->first();
        
        $usage = [
            'today' => [
                'minutes_used' => round($todaySessions->sum('elapsed_seconds') / 60, 1),
                'limit_minutes' => $screenTimeLimit ? $screenTimeLimit->daily_limit_minutes : 120,
                'percentage' => $screenTimeLimit ? round(($todaySessions->sum('elapsed_seconds') / 60) / $screenTimeLimit->daily_limit_minutes * 100, 1) : 0,
            ],
            'week' => [
                'minutes_used' => round($weekSessions->sum('elapsed_seconds') / 60, 1),
                'avg_daily' => round($weekSessions->sum('elapsed_seconds') / 60 / 7, 1),
            ],
            'limits' => $screenTimeLimit,
        ];
        
        return response()->json($usage);
    }
    
    /**
     * Toggle screen time restrictions
     */
    public function toggleRestrictions(Request $request)
    {
        $user = Auth::user();
        
        $child = $this->getOrAttachChild($user);
        
        if (!$child) {
            return response()->json(['error' => 'No child found'], 404);
        }
        
        $screenTimeLimit = ScreenTimeLimit::where('user_id', $child->id)->first();
        
        if ($screenTimeLimit) {
            $screenTimeLimit->update(['is_active' => $request->is_active]);
        }
        
        return response()->json(['success' => true]);
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

        $studentUser = \App\Models\User::where('role', 'student')->first();
        if ($studentUser) {
            $studentUser->update(['parent_id' => $parent->id]);
            return $studentUser;
        }

        return null;
    }
}
