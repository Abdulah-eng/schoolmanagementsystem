<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StudentProgressController extends Controller
{
    public function page()
    {
        return view('student.progress');
    }

    public function data(Request $request)
    {
        $userId = Auth::id();
        $today = Carbon::today();
        $start7 = Carbon::today()->subDays(6);

        // Focus stats
        $focusToday = DB::table('focus_sessions')
            ->where('user_id', $userId)
            ->whereDate('started_at', '>=', $today)
            ->get();
        $focusMinutes = $focusToday->sum(fn($r) => intval(($r->elapsed_seconds ?? 0) / 60));
        $focusCount = $focusToday->count();

        // Goals today
        $goalsToday = DB::table('session_goals')
            ->where('user_id', $userId)
            ->whereDate('created_at', '>=', $today)
            ->get();
        $goalsCompleted = $goalsToday->where('completed', true)->count();

        // Breathing today
        $breathToday = DB::table('breathing_sessions')
            ->where('user_id', $userId)
            ->whereDate('started_at', '>=', $today)
            ->count();

        // Cognitive scores
        $cognitive = DB::table('cognitive_scores')
            ->where('user_id', $userId)
            ->select('skill_type','current_score','highest_score','total_sessions')
            ->get();
        $cognitiveMap = [];
        foreach ($cognitive as $row) {
            $cognitiveMap[$row->skill_type] = [
                'current' => (int) $row->current_score,
                'highest' => (int) $row->highest_score,
                'sessions' => (int) $row->total_sessions,
            ];
        }

        // Projects
        $projectsCount = 0; $projectsProgressAvg = 0; $commentsCount = 0;
        if (DB::getSchemaBuilder()->hasTable('projects')) {
            $projects = DB::table('projects')->where('user_id', $userId)->get();
            $projectsCount = $projects->count();
            $projectsProgressAvg = $projectsCount ? intval($projects->avg('progress_percent')) : 0;
        }
        if (DB::getSchemaBuilder()->hasTable('project_comments')) {
            $commentsCount = DB::table('project_comments')
                ->join('projects','project_comments.project_id','=','projects.id')
                ->where('projects.user_id', $userId)
                ->count();
        }

        // 7-day focus time series
        $series = [];
        for ($i = 0; $i < 7; $i++) {
            $day = Carbon::today()->subDays(6 - $i);
            $rows = DB::table('focus_sessions')
                ->where('user_id', $userId)
                ->whereDate('started_at', $day)
                ->get();
            $mins = $rows->sum(fn($r) => intval(($r->elapsed_seconds ?? 0) / 60));
            $series[] = [
                'date' => $day->format('M d'),
                'minutes' => $mins,
            ];
        }

        return response()->json([
            'focus' => [
                'sessions_today' => $focusCount,
                'minutes_today' => $focusMinutes,
                'goals_today' => [
                    'total' => $goalsToday->count(),
                    'completed' => $goalsCompleted,
                ],
            ],
            'breathing' => [ 'sessions_today' => $breathToday ],
            'cognitive' => $cognitiveMap,
            'projects' => [
                'count' => $projectsCount,
                'avg_progress' => $projectsProgressAvg,
                'comments' => $commentsCount,
            ],
            'focus_last_7_days' => $series,
        ]);
    }
}


