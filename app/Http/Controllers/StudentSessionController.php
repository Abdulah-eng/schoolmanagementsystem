<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\FocusSession;
use App\Models\BreathingSession;
use App\Models\CognitiveSession;
use App\Models\LifeSkillSchedule;
use App\Models\LifeSkillCommunication;

class StudentSessionController extends Controller
{
    /**
     * Show the integrated session page
     */
    public function index()
    {
        $user = Auth::user();
        $prefs = $user->preferences;
        
        // Check if profile is completed
        if (!$prefs || !$prefs->profile_completed) {
            return redirect()->route('student.profile.create');
        }

        return view('student.session', compact('prefs'));
    }

    /**
     * Start a 40-minute integrated session
     */
    public function start(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'learning_goal' => 'nullable|string|max:500',
            'subject' => 'nullable|string|max:100',
            'topic' => 'nullable|string|max:200',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        
        // Create main session record
        $focusSession = FocusSession::create([
            'user_id' => $user->id,
            'planned_minutes' => 40,
            'status' => 'running',
            'session_type' => 'pomodoro', // Using existing enum value
            'started_at' => now(),
        ]);

        // Store session data
        session([
            'integrated_session' => [
                'session_id' => $focusSession->id,
                'user_id' => $user->id,
                'started_at' => now()->toDateTimeString(),
                'learning_goal' => $request->learning_goal ?? null,
                'subject' => $request->subject ?? null,
                'topic' => $request->topic ?? null,
                'current_phase' => 'breathing',
                'phases_completed' => [],
                'breathing_session_id' => null,
                'cognitive_session_id' => null,
                'life_skill_session_id' => null,
            ]
        ]);

        return response()->json([
            'success' => true,
            'session_id' => $focusSession->id,
            'message' => 'Integrated session started',
        ]);
    }

    /**
     * Start breathing phase (2 minutes)
     */
    public function startBreathing(Request $request)
    {
        $sessionData = session('integrated_session');
        if (!$sessionData) {
            return response()->json(['error' => 'No active session'], 400);
        }

        $user = Auth::user();
        
        // Create breathing session
        $breathingSession = BreathingSession::create([
            'user_id' => $user->id,
            'cycles' => 10,
            'inhale_seconds' => 4,
            'hold_seconds' => 4,
            'exhale_seconds' => 6,
            'status' => 'running',
            'started_at' => now(),
        ]);

        // Update session data
        session([
            'integrated_session' => array_merge($sessionData, [
                'current_phase' => 'breathing',
                'breathing_session_id' => $breathingSession->id,
                'breathing_started_at' => now()->toDateTimeString(),
            ])
        ]);

        return response()->json([
            'success' => true,
            'breathing_session_id' => $breathingSession->id,
            'duration_seconds' => 120, // 2 minutes
        ]);
    }

    /**
     * Complete breathing phase
     */
    public function completeBreathing(Request $request)
    {
        $sessionData = session('integrated_session');
        if (!$sessionData || !$sessionData['breathing_session_id']) {
            return response()->json(['error' => 'No active breathing session'], 400);
        }

        $breathingSession = BreathingSession::find($sessionData['breathing_session_id']);
        if ($breathingSession) {
            $breathingSession->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        }

        // Mark phase as completed
        $phasesCompleted = $sessionData['phases_completed'] ?? [];
        $phasesCompleted[] = 'breathing';

        session([
            'integrated_session' => array_merge($sessionData, [
                'current_phase' => 'learning',
                'phases_completed' => $phasesCompleted,
            ])
        ]);

        return response()->json([
            'success' => true,
            'next_phase' => 'learning',
            'next_phase_duration' => 1200, // 20 minutes
        ]);
    }

    /**
     * Start learning phase (20 minutes)
     */
    public function startLearning(Request $request)
    {
        $sessionData = session('integrated_session');
        if (!$sessionData) {
            return response()->json(['error' => 'No active session'], 400);
        }

        session([
            'integrated_session' => array_merge($sessionData, [
                'current_phase' => 'learning',
                'learning_started_at' => now()->toDateTimeString(),
            ])
        ]);

        return response()->json([
            'success' => true,
            'duration_seconds' => 1200, // 20 minutes
        ]);
    }

    /**
     * Complete learning phase
     */
    public function completeLearning(Request $request)
    {
        $sessionData = session('integrated_session');
        if (!$sessionData) {
            return response()->json(['error' => 'No active session'], 400);
        }

        $phasesCompleted = $sessionData['phases_completed'] ?? [];
        if (!in_array('learning', $phasesCompleted)) {
            $phasesCompleted[] = 'learning';
        }

        session([
            'integrated_session' => array_merge($sessionData, [
                'current_phase' => 'cognitive',
                'phases_completed' => $phasesCompleted,
            ])
        ]);

        return response()->json([
            'success' => true,
            'next_phase' => 'cognitive',
            'next_phase_duration' => 480, // 8 minutes
        ]);
    }

    /**
     * Start cognitive phase (8 minutes)
     */
    public function startCognitive(Request $request)
    {
        $sessionData = session('integrated_session');
        if (!$sessionData) {
            return response()->json(['error' => 'No active session'], 400);
        }

        $user = Auth::user();
        
        // Create cognitive session
        $cognitiveSession = CognitiveSession::create([
            'user_id' => $user->id,
            'skill_type' => 'memory',
            'status' => 'active',
            'started_at' => now(),
        ]);

        session([
            'integrated_session' => array_merge($sessionData, [
                'current_phase' => 'cognitive',
                'cognitive_session_id' => $cognitiveSession->id,
                'cognitive_started_at' => now()->toDateTimeString(),
            ])
        ]);

        return response()->json([
            'success' => true,
            'cognitive_session_id' => $cognitiveSession->id,
            'duration_seconds' => 480, // 8 minutes
        ]);
    }

    /**
     * Complete cognitive phase
     */
    public function completeCognitive(Request $request)
    {
        $sessionData = session('integrated_session');
        if (!$sessionData) {
            return response()->json(['error' => 'No active session'], 400);
        }

        if ($sessionData['cognitive_session_id']) {
            $cognitiveSession = CognitiveSession::find($sessionData['cognitive_session_id']);
            if ($cognitiveSession) {
                $cognitiveSession->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);
            }
        }

        $phasesCompleted = $sessionData['phases_completed'] ?? [];
        if (!in_array('cognitive', $phasesCompleted)) {
            $phasesCompleted[] = 'cognitive';
        }

        session([
            'integrated_session' => array_merge($sessionData, [
                'current_phase' => 'life_skills',
                'phases_completed' => $phasesCompleted,
            ])
        ]);

        return response()->json([
            'success' => true,
            'next_phase' => 'life_skills',
            'next_phase_duration' => 600, // 10 minutes
        ]);
    }

    /**
     * Start life skills phase (10 minutes)
     */
    public function startLifeSkills(Request $request)
    {
        $sessionData = session('integrated_session');
        if (!$sessionData) {
            return response()->json(['error' => 'No active session'], 400);
        }

        $user = Auth::user();
        
        // Create life skill communication scenario
        $lifeSkillSession = LifeSkillCommunication::create([
            'user_id' => $user->id,
            'scenario_type' => 'group-project', // Using valid enum value
            'status' => 'active',
            'started_at' => now(),
        ]);

        session([
            'integrated_session' => array_merge($sessionData, [
                'current_phase' => 'life_skills',
                'life_skill_session_id' => $lifeSkillSession->id,
                'life_skills_started_at' => now()->toDateTimeString(),
            ])
        ]);

        return response()->json([
            'success' => true,
            'life_skill_session_id' => $lifeSkillSession->id,
            'duration_seconds' => 600, // 10 minutes
        ]);
    }

    /**
     * Complete life skills phase
     */
    public function completeLifeSkills(Request $request)
    {
        $sessionData = session('integrated_session');
        if (!$sessionData) {
            return response()->json(['error' => 'No active session'], 400);
        }

        if ($sessionData['life_skill_session_id']) {
            $lifeSkillSession = LifeSkillCommunication::find($sessionData['life_skill_session_id']);
            if ($lifeSkillSession) {
                $lifeSkillSession->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);
            }
        }

        $phasesCompleted = $sessionData['phases_completed'] ?? [];
        if (!in_array('life_skills', $phasesCompleted)) {
            $phasesCompleted[] = 'life_skills';
        }

        session([
            'integrated_session' => array_merge($sessionData, [
                'current_phase' => 'completed',
                'phases_completed' => $phasesCompleted,
            ])
        ]);

        return response()->json([
            'success' => true,
            'next_phase' => 'completed',
        ]);
    }

    /**
     * Complete the entire integrated session
     */
    public function complete(Request $request)
    {
        $sessionData = session('integrated_session');
        if (!$sessionData) {
            return response()->json(['error' => 'No active session'], 400);
        }

        $focusSession = FocusSession::find($sessionData['session_id']);
        if ($focusSession) {
            $startedAt = \Carbon\Carbon::parse($sessionData['started_at']);
            $elapsedMinutes = now()->diffInMinutes($startedAt);
            $focusSession->update([
                'status' => 'completed',
                'completed_at' => now(),
                'elapsed_seconds' => $elapsedMinutes * 60,
            ]);
        }

        session()->forget('integrated_session');

        return response()->json([
            'success' => true,
            'message' => 'Integrated session completed successfully!',
        ]);
    }

    /**
     * Get current session status
     */
    public function status()
    {
        $sessionData = session('integrated_session');
        
        if (!$sessionData) {
            return response()->json([
                'active' => false,
                'message' => 'No active session',
            ]);
        }

        $startedAt = \Carbon\Carbon::parse($sessionData['started_at']);
        $elapsedSeconds = now()->diffInSeconds($startedAt);
        $totalSeconds = 40 * 60; // 40 minutes
        $progress = min(100, ($elapsedSeconds / $totalSeconds) * 100);

        return response()->json([
            'active' => true,
            'session_id' => $sessionData['session_id'] ?? null,
            'current_phase' => $sessionData['current_phase'],
            'phases_completed' => $sessionData['phases_completed'] ?? [],
            'elapsed_seconds' => $elapsedSeconds,
            'total_seconds' => $totalSeconds,
            'progress' => round($progress, 2),
            'started_at' => $sessionData['started_at'],
        ]);
    }
}

