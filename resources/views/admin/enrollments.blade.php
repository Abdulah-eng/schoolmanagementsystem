@extends('admin.layouts.app')

@section('title', 'Student Enrollment Management')

@section('content')
<div class="space-y-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Student Enrollment Management</h1>
        <p class="text-gray-600 mt-2">Manage student enrollments across all courses</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Courses List -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">All Courses</h2>
        
        <div class="space-y-4">
            @forelse($courses as $course)
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $course->course_name }}</h3>
                        <p class="text-sm text-gray-600">
                            {{ $course->course_code }} • Grade {{ $course->grade_level }} • 
                            Teacher: {{ $course->teacher->name ?? 'N/A' }}
                        </p>
                    </div>
                    <a href="{{ route('admin.courses.enroll', $course->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                        Manage Enrollments
                    </a>
                </div>
                
                <div class="mt-3">
                    <p class="text-sm text-gray-600 mb-2">
                        <strong>{{ $course->students->count() }}</strong> students enrolled
                    </p>
                    @if($course->students->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($course->students->take(5) as $student)
                        <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">
                            {{ $student->user->name ?? 'Unnamed' }}
                        </span>
                        @endforeach
                        @if($course->students->count() > 5)
                        <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">
                            +{{ $course->students->count() - 5 }} more
                        </span>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">No courses found.</p>
            @endforelse
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Courses</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $courses->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Students</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $students->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Enrollments</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $courses->sum(fn($c) => $c->students->count()) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
