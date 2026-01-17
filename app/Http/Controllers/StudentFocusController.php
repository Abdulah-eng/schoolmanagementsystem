<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\FocusSession;
use App\Models\SessionGoal;
use App\Models\MicroBreakLog;

class StudentFocusController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $sessions = $user->focusSessions()->latest()->limit(20)->get();
        $today = now()->startOfDay();
        $todaySessions = $user->focusSessions()->where('created_at', '>=', $today)->get();
        $totalSeconds = $todaySessions->sum('elapsed_seconds');
        return response()->json([
            'sessions' => $sessions,
            'today' => [
                'count' => $todaySessions->count(),
                'total_seconds' => $totalSeconds,
            ],
        ]);
    }

    public function start(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_type' => 'required|in:pomodoro,deep_work,quick_focus',
            'planned_minutes' => 'required|integer|min:1|max:180',
            'settings' => 'nullable|array',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        // remove any active sessions before starting a new one
        FocusSession::where('user_id', Auth::id())
            ->whereIn('status', ['running','paused'])
            ->delete();

        $session = FocusSession::create([
            'user_id' => Auth::id(),
            'session_type' => $data['session_type'],
            'planned_minutes' => $data['planned_minutes'],
            'elapsed_seconds' => 0,
            'status' => 'running',
            'started_at' => now(),
            'settings' => $data['settings'] ?? [],
        ]);

        return response()->json($session, 201);
    }

    public function pause(FocusSession $focusSession)
    {
        $this->authorizeSession($focusSession);
        if ($focusSession->status === 'running') {
            $focusSession->update([
                'status' => 'paused',
                'paused_at' => now(),
            ]);
        }
        return response()->json($focusSession);
    }

    public function resume(FocusSession $focusSession)
    {
        $this->authorizeSession($focusSession);
        if ($focusSession->status === 'paused') {
            $focusSession->update([
                'status' => 'running',
                'paused_at' => null,
            ]);
        }
        return response()->json($focusSession);
    }

    public function complete(FocusSession $focusSession)
    {
        $this->authorizeSession($focusSession);
        if (in_array($focusSession->status, ['running','paused'])) {
            $focusSession->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        }
        return response()->json($focusSession);
    }

    public function updateElapsed(FocusSession $focusSession, Request $request)
    {
        $this->authorizeSession($focusSession);
        $validator = Validator::make($request->all(), [
            'elapsed_seconds' => 'required|integer|min:0|max:86400',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $focusSession->update(['elapsed_seconds' => $validator->validated()['elapsed_seconds']]);
        return response()->json($focusSession);
    }

    protected function authorizeSession(FocusSession $session): void
    {
        abort_if($session->user_id !== Auth::id(), 403);
    }

    public function cancelAll()
    {
        FocusSession::where('user_id', Auth::id())
            ->whereIn('status', ['running','paused'])
            ->delete();
        return response()->noContent();
    }

    // Goals
    public function goalsIndex()
    {
        return response()->json(SessionGoal::where('user_id', Auth::id())->latest()->get());
    }

    public function goalsStore(Request $request)
    {
        $data = Validator::make($request->all(), ['title' => 'required|string|max:255'])->validate();
        $goal = SessionGoal::create(['user_id' => Auth::id(), 'title' => $data['title'], 'completed' => false]);
        return response()->json($goal, 201);
    }

    public function goalsToggle(SessionGoal $goal)
    {
        abort_if($goal->user_id !== Auth::id(), 403);
        $goal->update(['completed' => ! $goal->completed]);
        return response()->json($goal);
    }

    public function goalsDestroy(SessionGoal $goal)
    {
        abort_if($goal->user_id !== Auth::id(), 403);
        $goal->delete();
        return response()->noContent();
    }

    // Micro breaks
    public function logMicroBreak(Request $request)
    {
        $data = Validator::make($request->all(), [
            'activity' => 'required|string|max:50',
            'duration_seconds' => 'nullable|integer|min:10|max:1200',
        ])->validate();
        $log = MicroBreakLog::create([
            'user_id' => Auth::id(),
            'activity' => $data['activity'],
            'duration_seconds' => $data['duration_seconds'] ?? 120,
            'performed_at' => now(),
        ]);
        return response()->json($log, 201);
    }
}
