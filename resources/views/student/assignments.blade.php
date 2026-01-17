@extends('student.layouts.app')

@section('title', 'My Assignments')

@section('content')
<div class="space-y-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Assignments</h1>
        <p class="text-gray-600 mt-2">View and submit your course assignments</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tabs -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8">
            <button onclick="showTab('upcoming')" id="tab-upcoming" class="tab-button border-b-2 border-indigo-500 py-4 px-1 text-sm font-medium text-indigo-600">
                Upcoming ({{ $upcoming->count() }})
            </button>
            <button onclick="showTab('submitted')" id="tab-submitted" class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Submitted ({{ $submitted->count() }})
            </button>
            <button onclick="showTab('overdue')" id="tab-overdue" class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Overdue ({{ $overdue->count() }})
            </button>
        </nav>
    </div>

    <!-- Upcoming Assignments -->
    <div id="content-upcoming" class="tab-content">
        @forelse($upcoming as $assignment)
        <div class="bg-white rounded-lg shadow-md p-6 mb-4">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-xl font-semibold text-gray-900">{{ $assignment->title }}</h3>
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                            {{ ucfirst($assignment->assignment_type) }}
                        </span>
                    </div>
                    <p class="text-gray-600 mb-3">{{ $assignment->course->course_name }}</p>
                    <p class="text-sm text-gray-700 mb-4">{{ $assignment->description }}</p>
                    <div class="flex items-center gap-4 text-sm text-gray-600">
                        <span>📅 Due: {{ $assignment->due_date->format('M j, Y g:i A') }}</span>
                        <span>📊 Points: {{ $assignment->max_points }}</span>
                        <span class="text-orange-600 font-medium">
                            {{ $assignment->due_date->diffForHumans() }}
                        </span>
                    </div>
                </div>
                <a href="{{ route('student.assignments.show', $assignment->id) }}" class="ml-4 bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                    {{ $assignment->submissions->first() ? 'View Submission' : 'Submit Assignment' }}
                </a>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <p class="text-gray-500 text-lg">No upcoming assignments</p>
        </div>
        @endforelse
    </div>

    <!-- Submitted Assignments -->
    <div id="content-submitted" class="tab-content hidden">
        @forelse($submitted as $assignment)
        <div class="bg-white rounded-lg shadow-md p-6 mb-4">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-xl font-semibold text-gray-900">{{ $assignment->title }}</h3>
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                            Submitted
                        </span>
                        @if($assignment->submissions->first() && $assignment->submissions->first()->graded_at)
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                            Graded
                        </span>
                        @endif
                    </div>
                    <p class="text-gray-600 mb-3">{{ $assignment->course->course_name }}</p>
                    @php $submission = $assignment->submissions->first(); @endphp
                    @if($submission)
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <p class="text-sm text-gray-600 mb-2">Submitted: {{ $submission->submitted_at->format('M j, Y g:i A') }}</p>
                        @if($submission->graded_at)
                        <div class="flex items-center gap-4 text-sm">
                            <span class="font-semibold text-gray-900">
                                Score: {{ $submission->points_earned ?? 0 }}/{{ $assignment->max_points }}
                            </span>
                            @if($submission->feedback)
                            <span class="text-gray-600">Feedback: {{ $submission->feedback }}</span>
                            @endif
                        </div>
                        @else
                        <span class="text-orange-600 text-sm">Awaiting grading</span>
                        @endif
                    </div>
                    @endif
                </div>
                <a href="{{ route('student.assignments.show', $assignment->id) }}" class="ml-4 bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition-colors font-medium">
                    View Details
                </a>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <p class="text-gray-500 text-lg">No submitted assignments</p>
        </div>
        @endforelse
    </div>

    <!-- Overdue Assignments -->
    <div id="content-overdue" class="tab-content hidden">
        @forelse($overdue as $assignment)
        <div class="bg-white rounded-lg shadow-md p-6 mb-4 border-l-4 border-red-500">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-xl font-semibold text-gray-900">{{ $assignment->title }}</h3>
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                            Overdue
                        </span>
                    </div>
                    <p class="text-gray-600 mb-3">{{ $assignment->course->course_name }}</p>
                    <p class="text-sm text-gray-700 mb-4">{{ $assignment->description }}</p>
                    <div class="flex items-center gap-4 text-sm text-red-600">
                        <span>📅 Was due: {{ $assignment->due_date->format('M j, Y g:i A') }}</span>
                        <span class="font-medium">{{ $assignment->due_date->diffForHumans() }}</span>
                    </div>
                </div>
                <a href="{{ route('student.assignments.show', $assignment->id) }}" class="ml-4 bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors font-medium">
                    Submit Now
                </a>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <p class="text-gray-500 text-lg">No overdue assignments</p>
        </div>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active state from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('border-indigo-500', 'text-indigo-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Activate selected tab button
    const activeButton = document.getElementById('tab-' + tabName);
    activeButton.classList.remove('border-transparent', 'text-gray-500');
    activeButton.classList.add('border-indigo-500', 'text-indigo-600');
}
</script>
@endpush
@endsection
