@extends('teacher.layouts.app')

@section('title', 'Create Assignment')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Create New Assignment
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Add a new assignment for your course
            </p>
        </div>

        @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <div class="bg-white shadow rounded-lg">
            <form method="POST" action="{{ route('teacher.assignments.store') }}" class="p-6 space-y-6">
                @csrf

                <!-- Course Selection -->
                <div>
                    <label for="course_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Course <span class="text-red-500">*</span>
                    </label>
                    <select name="course_id" id="course_id" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                        @foreach($courses as $courseOption)
                            <option value="{{ $courseOption->id }}" {{ ($course && $course->id == $courseOption->id) ? 'selected' : '' }}>
                                {{ $courseOption->course_name }} ({{ $courseOption->course_code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Assignment Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                        placeholder="e.g., Chapter 5 Homework">
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" id="description" rows="4" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                        placeholder="Provide detailed instructions for this assignment...">{{ old('description') }}</textarea>
                </div>

                <!-- Assignment Type and Max Points -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="assignment_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Assignment Type <span class="text-red-500">*</span>
                        </label>
                        <select name="assignment_type" id="assignment_type" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                            <option value="homework" {{ old('assignment_type') == 'homework' ? 'selected' : '' }}>Homework</option>
                            <option value="project" {{ old('assignment_type') == 'project' ? 'selected' : '' }}>Project</option>
                            <option value="quiz" {{ old('assignment_type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                            <option value="exam" {{ old('assignment_type') == 'exam' ? 'selected' : '' }}>Exam</option>
                        </select>
                    </div>

                    <div>
                        <label for="max_points" class="block text-sm font-medium text-gray-700 mb-2">
                            Maximum Points <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="max_points" id="max_points" value="{{ old('max_points', 100) }}" 
                            min="1" max="1000" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                    </div>
                </div>

                <!-- Due Date -->
                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Due Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" required
                        min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                    <p class="mt-1 text-xs text-gray-500">Due date must be in the future</p>
                </div>

                <!-- Published Status -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_published" id="is_published" value="1" 
                        {{ old('is_published', true) ? 'checked' : '' }}
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="is_published" class="ml-2 block text-sm text-gray-700">
                        Publish assignment immediately (students can see it)
                    </label>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('teacher.assignments.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                        Create Assignment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
