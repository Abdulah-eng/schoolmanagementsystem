<?php

namespace App\Http\Controllers;

use App\Models\CognitiveSession;
use App\Models\FocusSession;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class TeacherStudentController extends Controller
{
    /**
     * Display students taught by the authenticated teacher with recent progress.
     */
    public function index()
    {
        $teacher = Auth::user();

        $students = Student::whereHas('courses', function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })
            ->with(['user', 'courses' => function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            }])
            ->get();

        $userIds = $students->pluck('user_id');
        $weekAgo = now()->subWeek();

        $focusByUser = FocusSession::whereIn('user_id', $userIds)
            ->where('created_at', '>=', $weekAgo)
            ->get()
            ->groupBy('user_id');

        $cognitiveByUser = CognitiveSession::whereIn('user_id', $userIds)
            ->where('created_at', '>=', $weekAgo)
            ->get()
            ->groupBy('user_id');

        $studentSummaries = $students->map(function (Student $student) use ($focusByUser, $cognitiveByUser) {
            $focusSessions = $focusByUser->get($student->user_id, collect());
            $cogSessions = $cognitiveByUser->get($student->user_id, collect());

            $focusMinutes = round($focusSessions->sum('elapsed_seconds') / 60, 1);
            $focusCount = $focusSessions->count();
            $cogCount = $cogSessions->count();
            $avgCogScore = $cogCount > 0 ? round($cogSessions->avg('score'), 1) : 0;

            return [
                'student' => $student,
                'focus_minutes' => $focusMinutes,
                'focus_sessions' => $focusCount,
                'cognitive_sessions' => $cogCount,
                'avg_cognitive_score' => $avgCogScore,
            ];
        });

        return view('teacher.students', [
            'students' => $studentSummaries,
        ]);
    }
}
