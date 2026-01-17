@extends('teacher.layouts.app')

@section('title', 'Assignments')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Assignments
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Manage assignments for your courses
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('teacher.assignments.create', request('course_id') ? ['course_id' => request('course_id')] : []) }}" 
                   class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Create Assignment
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mt-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mt-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Course Filter -->
        @if($courses->count() > 0)
        <div class="mt-6">
            <div class="bg-white shadow rounded-lg p-4">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Filter by Course</h3>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('teacher.assignments.index') }}" 
                       class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium {{ !request('course_id') ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                        All Courses
                    </a>
                    @foreach($courses as $courseItem)
                    <a href="{{ route('teacher.assignments.index', ['course_id' => $courseItem->id]) }}" 
                       class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request('course_id') == $courseItem->id ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                        {{ $courseItem->course_name }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Assignments List -->
        <div class="mt-6">
            @if($assignments->count() > 0)
                <div class="bg-white shadow overflow-hidden sm:rounded-md">
                    <ul class="divide-y divide-gray-200">
                        @foreach($assignments as $assignment)
                        <li>
                            <div class="px-4 py-4 flex items-center justify-between hover:bg-gray-50">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="flex items-center">
                                            <p class="text-sm font-medium text-indigo-600 truncate">
                                                {{ $assignment->title }}
                                            </p>
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($assignment->assignment_type === 'homework') bg-blue-100 text-blue-800
                                                @elseif($assignment->assignment_type === 'project') bg-green-100 text-green-800
                                                @elseif($assignment->assignment_type === 'quiz') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($assignment->assignment_type) }}
                                            </span>
                                        </div>
                                        <div class="mt-1 flex items-center text-sm text-gray-500">
                                            <p class="truncate">{{ $assignment->course->course_name }}</p>
                                            <span class="mx-2">•</span>
                                            <p>Due {{ $assignment->due_date->format('M j, Y') }}</p>
                                            <span class="mx-2">•</span>
                                            <p>{{ $assignment->max_points }} points</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="text-sm text-gray-500">
                                        <span class="font-medium">{{ $assignment->submissions->count() }}</span> submissions
                                    </div>
                                    <a href="{{ route('teacher.assignments.show', $assignment) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No assignments</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new assignment.</p>
                    <div class="mt-6">
                        <a href="{{ route('teacher.assignments.create', request('course_id') ? ['course_id' => request('course_id')] : []) }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Create Assignment
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
