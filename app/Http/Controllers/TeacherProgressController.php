<?php

namespace App\Http\Controllers;

use App\Models\CognitiveSession;
use App\Models\FocusSession;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class TeacherProgressController extends Controller
{
    /**
     * Show aggregated progress for the teacher's students.
     */
    public function index()
    {
        $teacher = Auth::user();

        $students = Student::whereHas('courses', function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })
            ->with('user')
            ->get();

        $userIds = $students->pluck('user_id');
        $weekAgo = now()->subWeek();

        $focusSessions = FocusSession::whereIn('user_id', $userIds)
            ->where('created_at', '>=', $weekAgo)
            ->get();

        $cognitiveSessions = CognitiveSession::whereIn('user_id', $userIds)
            ->where('created_at', '>=', $weekAgo)
            ->get();

        $weeklyMinutes = round($focusSessions->sum('elapsed_seconds') / 60, 1);
        $avgDailyMinutes = $weeklyMinutes > 0 ? round($weeklyMinutes / 7, 1) : 0;
        $avgCognitiveScore = $cognitiveSessions->count() > 0 ? round($cognitiveSessions->avg('score'), 1) : 0;

        $activeStudents = $focusSessions->groupBy('user_id')->count();

        $perStudent = $students->map(function (Student $student) use ($focusSessions, $cognitiveSessions) {
            $studentFocus = $focusSessions->where('user_id', $student->user_id);
            $studentCognitive = $cognitiveSessions->where('user_id', $student->user_id);

            return [
                'student' => $student,
                'focus_minutes' => round($studentFocus->sum('elapsed_seconds') / 60, 1),
                'focus_sessions' => $studentFocus->count(),
                'cognitive_sessions' => $studentCognitive->count(),
                'avg_cognitive_score' => $studentCognitive->count() > 0 ? round($studentCognitive->avg('score'), 1) : 0,
            ];
        });

        return view('teacher.progress', [
            'avg_daily_minutes' => $avgDailyMinutes,
            'avg_cognitive_score' => $avgCognitiveScore,
            'active_students' => $activeStudents,
            'students' => $perStudent,
        ]);
    }
}
