<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Student;
use App\Models\FocusSession;
use App\Models\BreathingSession;
use App\Models\Project;

class ParentProgressController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get the parent's children (attach a demo student if none exists)
        $children = $user->children()->where('role', 'student')->get();
        if ($children->isEmpty()) {
            $demoStudent = \App\Models\User::where('role', 'student')->first();
            if ($demoStudent) {
                $demoStudent->update(['parent_id' => $user->id]);
                $children = $user->children()->where('role', 'student')->get();
            }
        }

        if ($children->isEmpty()) {
            // Still no children; render an empty state instead of redirecting
            return view('parent.progress', [
                'students' => collect(),
                'selectedStudent' => null,
                'days' => [],
                'focusMinutesByDay' => [],
                'currentWeekHours' => 0,
                'weeklyAvgHours' => 0,
                'changePercent' => 0,
                'moodStats' => [],
                'positiveDays' => 0,
                'commonMood' => 'N/A',
                'stressLevel' => 'N/A',
                'assignmentRows' => [],
                'assignmentPie' => ['Completed' => 0, 'Remaining' => 0, 'Overdue' => 0],
                'subjects' => ['Math', 'Science', 'English'],
            ]);
        }
        
        $selectedStudentId = (int) $request->get('student_id', $request->session()->get('parent.selected_student_id', 0));
        $selectedStudent = $selectedStudentId ? $children->firstWhere('id', $selectedStudentId) : $children->first();
        
        // Store selected student in session
        if ($selectedStudent) {
            $request->session()->put('parent.selected_student_id', $selectedStudent->id);
        }

        $userId = $selectedStudent?->id; // User model, not Student model

        $startOfWeek = Carbon::now()->startOfWeek();
        $days = [];
        $focusMinutesByDay = [];

        for ($i = 0; $i < 7; $i++) {
            $day = (clone $startOfWeek)->addDays($i);
            $days[] = $day->format('D');
            if ($userId) {
                $seconds = FocusSession::where('user_id', $userId)
                    ->whereDate('created_at', $day->toDateString())
                    ->sum('elapsed_seconds');
                $focusMinutesByDay[] = (int) round($seconds / 60);
            } else {
                $focusMinutesByDay[] = 0;
            }
        }

        $currentWeekSeconds = $userId ? FocusSession::where('user_id', $userId)
            ->whereBetween('created_at', [$startOfWeek, (clone $startOfWeek)->endOfWeek()])
            ->sum('elapsed_seconds') : 0;

        $prevWeekStart = (clone $startOfWeek)->subWeek();
        $prevWeekSeconds = $userId ? FocusSession::where('user_id', $userId)
            ->whereBetween('created_at', [$prevWeekStart, (clone $prevWeekStart)->endOfWeek()])
            ->sum('elapsed_seconds') : 0;

        $currentWeekHours = round($currentWeekSeconds / 3600, 1);
        $weeklyAvgHours = round(array_sum($focusMinutesByDay) / 60 / 7, 1);
        $changePercent = $prevWeekSeconds > 0
            ? round((($currentWeekSeconds - $prevWeekSeconds) / max($prevWeekSeconds, 1)) * 100)
            : ($currentWeekSeconds > 0 ? 100 : 0);

        $rangeStart = (clone $startOfWeek)->subDays(30);
        $moodStats = [
            'Happy' => $userId ? BreathingSession::where('user_id', $userId)->where('status', 'completed')->where('created_at', '>=', $rangeStart)->count() : 0,
            'Focused' => $userId ? FocusSession::where('user_id', $userId)->where('status', 'completed')->where('created_at', '>=', $rangeStart)->count() : 0,
            'Tired' => $userId ? FocusSession::where('user_id', $userId)->where('status', 'paused')->where('created_at', '>=', $rangeStart)->count() : 0,
            'Stressed' => $userId ? FocusSession::where('user_id', $userId)->where('status', 'cancelled')->where('created_at', '>=', $rangeStart)->count() : 0,
            'Excited' => $userId ? BreathingSession::where('user_id', $userId)->where('status', 'running')->where('created_at', '>=', $rangeStart)->count() : 0,
        ];

        $positiveDays = array_sum($moodStats);
        
        // Calculate common mood (most frequent)
        $commonMood = 'Focused';
        if ($positiveDays > 0) {
            $maxCount = max($moodStats);
            $commonMood = array_search($maxCount, $moodStats);
        }
        
        // Calculate stress level based on cancelled sessions and paused sessions
        $totalSessions = $userId ? FocusSession::where('user_id', $userId)->where('created_at', '>=', $rangeStart)->count() : 0;
        $cancelledCount = $moodStats['Stressed'] ?? 0;
        $pausedCount = $moodStats['Tired'] ?? 0;
        $stressRatio = $totalSessions > 0 ? (($cancelledCount + $pausedCount) / $totalSessions) : 0;
        $stressLevel = $stressRatio > 0.3 ? 'High' : ($stressRatio > 0.15 ? 'Medium' : 'Low');

        // Assignment completion by subject using projects & tasks, and course assignments
        $assignmentRows = [];
        $pieCompleted = 0; $pieRemaining = 0; $pieOverdue = 0;
        if ($userId) {
            // Get student projects
            $projects = Project::withCount([
                'tasks as total_tasks',
                'tasks as completed_tasks' => function ($q) { $q->where('is_done', true); }
            ])->where('user_id', $userId)->get();

            // Get course assignments for the student
            $student = \App\Models\Student::where('user_id', $userId)->first();
            $courseAssignments = collect();
            if ($student) {
                // Get courses the student is enrolled in
                $enrolledCourses = $student->courses()->with(['assignments' => function($q) {
                    $q->where('is_published', true);
                }])->get();
                
                foreach ($enrolledCourses as $course) {
                    foreach ($course->assignments as $assignment) {
                        $submission = $assignment->submissions()->where('student_id', $student->id)->first();
                        $courseAssignments->push([
                            'subject' => $course->course_name,
                            'assignment' => $assignment,
                            'submitted' => $submission !== null,
                            'score' => $submission ? ($submission->score ?? 0) : 0,
                            'max_points' => $assignment->max_points ?? 100,
                            'due_date' => $assignment->due_date,
                        ]);
                    }
                }
            }

            // Group projects by subject
            $projectGrouped = $projects->groupBy(function ($p) { return $p->subject ?: 'General'; });
            foreach ($projectGrouped as $subject => $list) {
                $totalCompleted = (int) $list->sum('completed_tasks');
                $totalTasks = (int) $list->sum('total_tasks');
                $avgScore = (int) round($list->avg('progress_percent'));

                $feedback = $avgScore >= 90 ? 'Excellent writing skills' : ($avgScore >= 80 ? 'Shows strong problem-solving skills' : 'Great curiosity, needs to focus on details');
                $status = $avgScore >= 90 ? 'Excelling' : ($avgScore >= 80 ? 'On Track' : 'Needs Attention');

                $assignmentRows[] = [
                    'subject' => $subject,
                    'completed' => $totalTasks > 0 ? ($totalCompleted . '/' . $totalTasks) : '0/0',
                    'avg' => $avgScore,
                    'feedback' => $feedback,
                    'status' => $status,
                ];

                $pieCompleted += $totalCompleted;
                $pieRemaining += max(0, $totalTasks - $totalCompleted);
                $pieOverdue += $list->whereNotNull('due_date')->filter(function($p){ return $p->due_date->isPast() && $p->progress_percent < 100; })->count();
            }
            
            // Group course assignments by subject (course name)
            $assignmentGrouped = $courseAssignments->groupBy('subject');
            foreach ($assignmentGrouped as $subject => $assignments) {
                $totalAssignments = $assignments->count();
                $submittedCount = $assignments->where('submitted', true)->count();
                $totalScore = $assignments->where('submitted', true)->sum('score');
                $totalMaxPoints = $assignments->sum('max_points');
                $avgScore = $submittedCount > 0 && $totalMaxPoints > 0 ? round(($totalScore / $totalMaxPoints) * 100) : 0;
                
                // Check if this subject already exists from projects
                $existingIndex = array_search($subject, array_column($assignmentRows, 'subject'));
                if ($existingIndex !== false) {
                    // Merge with existing project data
                    $existing = $assignmentRows[$existingIndex];
                    $projectCompleted = explode('/', $existing['completed']);
                    $totalFromProjects = (int)($projectCompleted[1] ?? 0);
                    $completedFromProjects = (int)($projectCompleted[0] ?? 0);
                    
                    $assignmentRows[$existingIndex]['completed'] = ($completedFromProjects + $submittedCount) . '/' . ($totalFromProjects + $totalAssignments);
                    $assignmentRows[$existingIndex]['avg'] = round(($existing['avg'] + $avgScore) / 2);
                } else {
                    // Add new row for course assignments
                    $feedback = $avgScore >= 90 ? 'Excellent performance' : ($avgScore >= 80 ? 'Good work' : 'Keep practicing');
                    $status = $avgScore >= 90 ? 'Excelling' : ($avgScore >= 80 ? 'On Track' : 'Needs Attention');
                    
                    $assignmentRows[] = [
                        'subject' => $subject,
                        'completed' => $submittedCount . '/' . $totalAssignments,
                        'avg' => $avgScore,
                        'feedback' => $feedback,
                        'status' => $status,
                    ];
                }
                
                $pieCompleted += $submittedCount;
                $pieRemaining += ($totalAssignments - $submittedCount);
                $overdue = $assignments->filter(function($a) {
                    return $a['due_date'] && $a['due_date']->isPast() && !$a['submitted'];
                })->count();
                $pieOverdue += $overdue;
            }
        }

        // Get unique subjects from projects for filter buttons
        $subjects = $userId ? Project::where('user_id', $userId)->whereNotNull('subject')->distinct()->pluck('subject')->toArray() : [];
        if (empty($subjects)) {
            $subjects = ['Math', 'Science', 'English']; // Default subjects
        }
        
        return view('parent.progress', [
            'students' => $children,
            'selectedStudent' => $selectedStudent,
            'days' => $days,
            'focusMinutesByDay' => $focusMinutesByDay,
            'currentWeekHours' => $currentWeekHours,
            'weeklyAvgHours' => $weeklyAvgHours,
            'changePercent' => $changePercent,
            'moodStats' => $moodStats,
            'positiveDays' => $positiveDays,
            'commonMood' => $commonMood,
            'stressLevel' => $stressLevel,
            'assignmentRows' => $assignmentRows,
            'assignmentPie' => [
                'Completed' => $pieCompleted,
                'Remaining' => $pieRemaining,
                'Overdue' => $pieOverdue,
            ],
            'subjects' => $subjects,
        ]);
    }
}





