@extends('teacher.layouts.app')

@section('title', $course->course_name . ' - Course Details')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $course->course_name }}</h1>
            <p class="text-gray-600 mt-2">{{ $course->description }}</p>
        </div>
        <a href="{{ route('teacher.courses.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">← Back to courses</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white shadow rounded-lg p-4">
            <p class="text-sm text-gray-500">Course code</p>
            <p class="text-xl font-semibold text-gray-900">{{ $course->course_code }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-4">
            <p class="text-sm text-gray-500">Grade level</p>
            <p class="text-xl font-semibold text-gray-900">{{ $course->grade_level }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-4">
            <p class="text-sm text-gray-500">Students</p>
            <p class="text-xl font-semibold text-gray-900">{{ $course->students->count() }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Students</h2>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500">{{ $course->students->count() }} enrolled</span>
                    <a href="{{ route('teacher.courses.enroll', $course->id) }}" class="bg-indigo-600 text-white px-3 py-1 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                        Enroll Students
                    </a>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($course->students as $student)
                <div class="py-3 flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-900">{{ $student->user->name ?? 'Unnamed' }}</p>
                        <p class="text-sm text-gray-500">{{ $student->user->email ?? '' }}</p>
                    </div>
                    <span class="text-sm text-gray-500">Grade {{ $student->grade_level }}</span>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No students enrolled yet.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Assignments</h2>
                <a href="{{ route('teacher.assignments.index', ['course_id' => $course->id]) }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Manage</a>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($course->assignments as $assignment)
                <div class="py-3">
                    <div class="flex items-center justify-between">
                        <p class="font-medium text-gray-900">{{ $assignment->title }}</p>
                        <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-700">{{ ucfirst($assignment->assignment_type) }}</span>
                    </div>
                    <p class="text-sm text-gray-500">Due {{ $assignment->due_date->format('M j, Y') }} • {{ $assignment->submissions->count() }} submissions</p>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No assignments yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
