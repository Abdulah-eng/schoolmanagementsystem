@extends('teacher.layouts.app')

@section('title', 'My Courses - Teacher Portal')

@section('content')
<div class="space-y-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Courses</h1>
        <p class="text-gray-600 mt-2">Manage your courses and students</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Add Course Form -->
    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Add New Course</h2>
        <form method="POST" action="{{ route('teacher.courses.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Course name</label>
                <input name="course_name" value="{{ old('course_name') }}" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Grade level</label>
                <input name="grade_level" value="{{ old('grade_level') }}" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Credits</label>
                <input type="number" name="credits" value="{{ old('credits', 1) }}" min="1" max="20" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Course code (optional)</label>
                <input name="course_code" value="{{ old('course_code') }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Auto-generated if left blank" />
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="3" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
            </div>
            <div class="md:col-span-2 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                    Save Course
                </button>
            </div>
        </form>
    </div>

    <!-- Courses Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($courses as $course)
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $course->course_name }}</h3>
                    <span class="text-xs px-2 py-1 rounded-full {{ $course->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $course->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <p class="text-gray-600 mb-4">{{ $course->description }}</p>
                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                    <span>{{ $course->students->count() }} students</span>
                    <span>{{ $course->grade_level }}</span>
                </div>
                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                    <span>Assignments: {{ $course->assignments->count() }}</span>
                    <span>Code: {{ $course->course_code }}</span>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('teacher.courses.show', $course) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View Details</a>
                    <a href="{{ route('teacher.assignments.index', ['course_id' => $course->id]) }}" class="text-green-600 hover:text-green-800 text-sm font-medium">Assignments</a>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200 md:col-span-2 lg:col-span-3 text-center">
                <p class="text-gray-600">No courses yet. Use the form above to add one.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
