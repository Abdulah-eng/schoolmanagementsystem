@extends('teacher.layouts.app')

@section('title', 'Teacher Dashboard - EduFocus')

@section('content')
<div class="space-y-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Teacher Dashboard</h1>
        <p class="text-gray-600 mt-2">Manage your classes and track student progress</p>
    </div>

    <!-- Statistics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Courses</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_courses'] }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_students'] }}</p>
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
                    <p class="text-sm font-medium text-gray-600">Today's Focus</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['today_focus_minutes'] }}m</p>
                    <p class="text-xs text-gray-500">{{ $stats['today_focus_sessions'] }} sessions</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Avg Session</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['avg_session_length'] }}m</p>
                    <p class="text-xs text-gray-500">This week</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses Overview -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-900">My Courses</h2>
            <a href="{{ route('teacher.courses.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                Add Course
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($courses as $course)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <h3 class="font-semibold text-gray-900 mb-2">{{ $course->course_name }}</h3>
                <p class="text-sm text-gray-600 mb-3">{{ $course->description }}</p>
                <div class="flex items-center justify-between text-sm text-gray-500">
                    <span>{{ $course->students->count() }} students</span>
                    <span>{{ $course->assignments->count() ?? 0 }} assignments</span>
                </div>
                <div class="mt-3 flex space-x-2">
                    <a href="{{ route('teacher.courses.show', $course->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View Details</a>
                    <a href="{{ route('teacher.assignments.index', ['course_id' => $course->id]) }}" class="text-green-600 hover:text-green-800 text-sm font-medium">Assignments</a>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-8 text-gray-500">
                <p>No courses yet. Create your first course to get started.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Focus Sessions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Recent Focus Sessions</h2>
            <div class="space-y-3">
                @forelse($recentSessions as $session)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900">{{ $session->user->name }}</p>
                        <p class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $session->session_type)) }} • {{ round($session->elapsed_seconds / 60, 1) }}m</p>
                    </div>
                    <span class="text-xs text-gray-500">{{ $session->created_at->diffForHumans() }}</span>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No recent focus sessions</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Messages -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Recent Messages</h2>
            <div class="space-y-3">
                @forelse($recentMessages as $message)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900">{{ $message->sender->name ?? 'System' }}</p>
                        <p class="text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($message->content, 50) }}</p>
                    </div>
                    <span class="text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</span>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No recent messages</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
