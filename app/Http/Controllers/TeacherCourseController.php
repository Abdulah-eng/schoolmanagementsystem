<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TeacherCourseController extends Controller
{
    /**
     * List courses for the authenticated teacher.
     */
    public function index()
    {
        $teacher = Auth::user();

        $courses = Course::with([
                'students.user',
                'assignments' => function ($query) {
                    $query->latest();
                },
            ])
            ->where('teacher_id', $teacher->id)
            ->orderByDesc('created_at')
            ->get();

        return view('teacher.courses', [
            'courses' => $courses,
        ]);
    }

    /**
     * Store a new course for the teacher.
     */
    public function store(Request $request)
    {
        $teacher = Auth::user();

        $data = $request->validate([
            'course_name'   => 'required|string|max:255',
            'description'   => 'required|string',
            'grade_level'   => 'required|string|max:50',
            'credits'       => 'required|integer|min:1|max:20',
            'course_code'   => 'nullable|string|max:20|unique:courses,course_code',
            'is_active'     => 'sometimes|boolean',
        ]);

        $data['course_code'] = $data['course_code'] ?? Str::upper(Str::random(6));
        $data['teacher_id'] = $teacher->id;
        $data['is_active'] = $data['is_active'] ?? true;

        Course::create($data);

        return redirect()
            ->route('teacher.courses.index')
            ->with('success', 'Course created successfully.');
    }

    /**
     * Show course details with enrollments and assignments.
     */
    public function show(Course $course)
    {
        $teacher = Auth::user();

        if ($course->teacher_id !== $teacher->id) {
            abort(403);
        }

        $course->load([
            'students.user',
            'assignments.submissions',
        ]);

        return view('teacher.course-show', [
            'course' => $course,
        ]);
    }
}
