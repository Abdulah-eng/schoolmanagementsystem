@extends('student.layouts.app')

@section('title', 'Student Dashboard - EduFocus')

@section('content')
<div class="space-y-6">
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <!-- Welcome Section -->
    <div class="mb-8 text-center">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Welcome, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-600 text-lg">Ready to start your learning journey?</p>
    </div>

    <!-- Single Call-to-Action: Start Session -->
    <div class="max-w-3xl mx-auto">
        <a href="{{ route('student.session') }}" class="group block">
            <div class="bg-gradient-to-br from-blue-500 via-purple-600 to-pink-600 rounded-3xl shadow-2xl p-12 text-white transform transition-all duration-300 hover:scale-105 hover:shadow-3xl">
                <div class="flex items-center justify-center w-32 h-32 bg-white/20 rounded-full mb-8 group-hover:bg-white/30 transition-colors mx-auto">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-4xl font-bold mb-4 text-center">Start Your Learning Session</h2>
                <p class="text-blue-100 mb-8 text-xl text-center">Begin your 40-minute integrated learning journey with breathing, focused study, cognitive exercises, and life skills practice</p>
                <div class="flex items-center justify-center text-white/90 font-semibold text-lg">
                    <span>Start 40-Minute Session</span>
                    <svg class="w-6 h-6 ml-3 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </div>
                <div class="mt-8 grid grid-cols-4 gap-4 text-center text-sm">
                    <div>
                        <div class="text-2xl font-bold">🌬️</div>
                        <div class="mt-2">2 min</div>
                        <div class="text-blue-200">Breathing</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold">📚</div>
                        <div class="mt-2">20 min</div>
                        <div class="text-blue-200">Learning</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold">🧠</div>
                        <div class="mt-2">8 min</div>
                        <div class="text-blue-200">Cognitive</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold">🌟</div>
                        <div class="mt-2">10 min</div>
                        <div class="text-blue-200">Life Skills</div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto mt-12">
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="text-3xl font-bold text-blue-600 mb-2">{{ $stats['total_sessions'] }}</div>
            <div class="text-gray-600">Sessions Completed</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="text-3xl font-bold text-purple-600 mb-2">{{ $stats['total_minutes'] }}m</div>
            <div class="text-gray-600">Total Focus Minutes</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="text-3xl font-bold text-green-600 mb-2">{{ $stats['day_streak'] }}</div>
            <div class="text-gray-600">Day Streak</div>
        </div>
    </div>

    <!-- Upcoming Assignments -->
    @if($upcomingAssignments->count() > 0)
    <div class="max-w-4xl mx-auto mt-12">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-gray-900">Upcoming Assignments</h2>
                <a href="{{ route('student.assignments.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                    View All →
                </a>
            </div>
            <div class="space-y-3">
                @foreach($upcomingAssignments as $assignment)
                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $assignment->title }}</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $assignment->course->course_name }} • 
                                Due: {{ $assignment->due_date->format('M j, Y') }}
                                @if($assignment->submissions->first())
                                    <span class="ml-2 text-green-600 font-medium">✓ Submitted</span>
                                @else
                                    <span class="ml-2 text-orange-600 font-medium">Pending</span>
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('student.assignments.show', $assignment->id) }}" class="ml-4 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                            {{ $assignment->submissions->first() ? 'View' : 'Submit' }}
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
