@extends('student.layouts.app')

@section('title', $assignment->title . ' - Assignment')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $assignment->title }}</h1>
            <p class="text-gray-600 mt-2">{{ $assignment->course->course_name }}</p>
        </div>
        <a href="{{ route('student.assignments.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">← Back to Assignments</a>
    </div>

    <!-- Assignment Details -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-sm text-gray-600">Due Date</p>
                <p class="text-lg font-semibold text-gray-900 {{ $assignment->due_date->isPast() && !$submission ? 'text-red-600' : '' }}">
                    {{ $assignment->due_date->format('F j, Y g:i A') }}
                </p>
                @if($assignment->due_date->isPast() && !$submission)
                <p class="text-sm text-red-600 mt-1">⚠️ Overdue</p>
                @endif
            </div>
            <div>
                <p class="text-sm text-gray-600">Points</p>
                <p class="text-lg font-semibold text-gray-900">{{ $assignment->max_points }} points</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Type</p>
                <p class="text-lg font-semibold text-gray-900">{{ ucfirst($assignment->assignment_type) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Status</p>
                @if($submission)
                <p class="text-lg font-semibold text-green-600">✓ Submitted</p>
                @else
                <p class="text-lg font-semibold text-orange-600">Not Submitted</p>
                @endif
            </div>
        </div>

        <div class="border-t border-gray-200 pt-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-3">Description</h2>
            <div class="prose max-w-none text-gray-700">
                {!! nl2br(e($assignment->description)) !!}
            </div>
        </div>
    </div>

    <!-- Submission Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">
            {{ $submission ? 'Update Submission' : 'Submit Assignment' }}
        </h2>

        @if($submission)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
            <p class="text-sm text-blue-800">
                <strong>Previously submitted:</strong> {{ $submission->submitted_at->format('M j, Y g:i A') }}
            </p>
            @if($submission->graded_at)
            <div class="mt-2">
                <p class="text-sm text-blue-800">
                    <strong>Score:</strong> {{ $submission->points_earned ?? 0 }}/{{ $assignment->max_points }}
                </p>
                @if($submission->feedback)
                <p class="text-sm text-blue-800 mt-1">
                    <strong>Feedback:</strong> {{ $submission->feedback }}
                </p>
                @endif
            </div>
            @endif
        </div>
        @endif

        <form id="submission-form">
            @csrf
            <div class="mb-4">
                <label for="submission_content" class="block text-sm font-medium text-gray-700 mb-2">
                    Your Answer / Submission
                </label>
                <textarea 
                    id="submission_content" 
                    name="submission_content" 
                    rows="10" 
                    required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                    placeholder="Type your answer or submission here...">{{ $submission->submission_content ?? '' }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Minimum 10 characters required</p>
            </div>

            <div class="mb-4">
                <label for="attachment_url" class="block text-sm font-medium text-gray-700 mb-2">
                    Attachment URL (Optional)
                </label>
                <input 
                    type="url" 
                    id="attachment_url" 
                    name="attachment_url" 
                    value="{{ $submission->attachment_url ?? '' }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                    placeholder="https://example.com/file.pdf">
                <p class="text-xs text-gray-500 mt-1">Link to Google Drive, Dropbox, or other file sharing service</p>
            </div>

            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    @if($assignment->due_date->isPast() && !$submission)
                    <span class="text-red-600 font-medium">⚠️ This assignment is overdue</span>
                    @elseif($assignment->due_date->isFuture())
                    <span>Due in {{ $assignment->due_date->diffForHumans() }}</span>
                    @endif
                </div>
                <button 
                    type="submit" 
                    id="submit-btn"
                    class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                    <span id="submit-text">{{ $submission ? 'Update Submission' : 'Submit Assignment' }}</span>
                    <span id="submit-loading" class="hidden">Submitting...</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('submission-form');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const submitLoading = document.getElementById('submit-loading');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const content = document.getElementById('submission_content').value.trim();
            const attachmentUrl = document.getElementById('attachment_url').value.trim();
            
            if (content.length < 10) {
                alert('Please provide at least 10 characters in your submission.');
                return;
            }
            
            // Disable button
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitLoading.classList.remove('hidden');
            
            try {
                const response = await fetch('{{ route("student.assignments.submit", $assignment->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        submission_content: content,
                        attachment_url: attachmentUrl || null
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert(data.error || 'Failed to submit assignment');
                }
            } catch(error) {
                console.error('Error:', error);
                alert('Failed to submit assignment. Please try again.');
            } finally {
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                submitLoading.classList.add('hidden');
            }
        });
    }
});
</script>
@endpush
@endsection
