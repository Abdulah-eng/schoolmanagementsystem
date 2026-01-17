<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\FocusSession;
use App\Models\MicroBreakLog;

class StudentEnhancedLearningController extends Controller
{
    private $neuroscienceInterval = 15; // minutes

    public function index()
    {
        $user = Auth::user();
        $courses = \App\Models\Course::active()->get();
        $currentSession = FocusSession::where('user_id', $user->id)
            ->where('status', '!=', 'completed')
            ->where('status', '!=', 'cancelled')
            ->latest()
            ->first();

        return view('student.learning', compact('courses', 'currentSession'));
    }

    public function startSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:100',
            'topic' => 'required|string|max:200',
            'duration_minutes' => 'required|integer|min:15|max:120',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $session = FocusSession::create([
            'user_id' => $user->id,
            'session_type' => 'pomodoro',
            'planned_minutes' => $request->input('duration_minutes', 60),
            'status' => 'running',
            'started_at' => now(),
        ]);

        return response()->json(['session_id' => $session->id]);
    }

    public function completeBreak(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required|exists:focus_sessions,id',
            'type' => 'required|in:breathing,visualization,physical,quiz',
            'duration' => 'nullable|integer|min:1|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $session = FocusSession::findOrFail($request->session_id);
        
        if ($session->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        MicroBreakLog::create([
            'user_id' => Auth::id(),
            'activity' => $request->type,
            'duration_seconds' => ($request->duration ?? 2) * 60,
            'performed_at' => now(),
        ]);

        return response()->json(['message' => ucfirst($request->type) . ' break logged']);
    }

    public function getNextInterval(Request $request)
    {
        $user = Auth::user();
        $session = FocusSession::where('user_id', $user->id)
            ->where('status', 'running')
            ->latest()
            ->first();

        if (!$session) {
            return response()->json(['error' => 'No active session'], 404);
        }

        $startedAt = $session->started_at;
        $elapsedMinutes = now()->diffInMinutes($startedAt);
        $nextBreakIn = $this->neuroscienceInterval - ($elapsedMinutes % $this->neuroscienceInterval);

        return response()->json([
            'elapsed_minutes' => $elapsedMinutes,
            'next_break_in_minutes' => $nextBreakIn,
            'should_take_break' => $nextBreakIn <= 1,
        ]);
    }

    public function completeSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required|exists:focus_sessions,id',
            'actual_minutes' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $session = FocusSession::findOrFail($request->session_id);
        
        if ($session->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $actualMinutes = $request->actual_minutes ?? now()->diffInMinutes($session->started_at);
        
        $session->update([
            'status' => 'completed',
            'completed_at' => now(),
            'elapsed_seconds' => $actualMinutes * 60,
        ]);

        return response()->json(['message' => 'Session completed successfully']);
    }
}

