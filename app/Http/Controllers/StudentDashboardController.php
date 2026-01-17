<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\StudentPreference;
use App\Models\FocusSession;
use App\Models\Student;
use App\Models\Assignment;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $prefs = $user->preferences;
        
        // Check if profile is completed
        if (!$prefs || !$prefs->profile_completed) {
            return redirect()->route('student.profile.create');
        }

        $stats = $this->calculateStats($user->id);
        
        // Get upcoming assignments
        $student = Student::where('user_id', $user->id)->with('courses')->first();
        $upcomingAssignments = collect();
        
        if ($student) {
            $enrolledCourseIds = $student->courses->pluck('id')->toArray();
            $upcomingAssignments = Assignment::whereIn('course_id', $enrolledCourseIds)
                ->where('is_published', true)
                ->where('due_date', '>=', now())
                ->with(['course', 'submissions' => function($query) use ($student) {
                    $query->where('student_id', $student->id);
                }])
                ->orderBy('due_date')
                ->limit(5)
                ->get();
        }

        return view('student.dashboard', compact('prefs', 'stats', 'upcomingAssignments'));
    }

    /**
     * Calculate simple dashboard statistics for the student.
     */
    private function calculateStats(int $userId): array
    {
        $sessions = FocusSession::where('user_id', $userId)
            ->where('status', 'completed')
            ->orderByDesc('created_at')
            ->get();

        $totalSessions = $sessions->count();
        $totalMinutes = round($sessions->sum('elapsed_seconds') / 60, 1);

        // Day streak: count consecutive days with at least one session, starting from today
        $datesWithSessions = $sessions
            ->pluck('created_at')
            ->map(fn ($dt) => $dt->toDateString())
            ->unique()
            ->values();

        $streak = 0;
        $currentDate = now()->toDateString();

        while ($datesWithSessions->contains($currentDate)) {
            $streak++;
            $currentDate = now()->subDays($streak)->toDateString();
        }

        return [
            'total_sessions' => $totalSessions,
            'total_minutes' => $totalMinutes,
            'day_streak' => $streak,
        ];
    }

    public function savePreferences(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'grade_year' => 'nullable|string|max:50',
            'curriculum_board' => 'nullable|string|max:50',
            'learning_style' => 'nullable|string|max:50',
            'weekly_goal' => 'nullable|string|max:255',
            'skill_area' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        $data = $validator->validated();

        $prefs = StudentPreference::updateOrCreate(
            ['user_id' => $user->id],
            array_merge($data, ['user_id' => $user->id])
        );

        return redirect()->route('student.dashboard')->with('status', 'Preferences saved');
    }
}
