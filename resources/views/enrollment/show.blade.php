@php
    $user = Auth::user();
    $isAdmin = $user->role === 'admin';
    $layout = $isAdmin ? 'admin.layouts.app' : 'teacher.layouts.app';
@endphp
@extends($layout)

@section('title', 'Enroll Students - ' . $course->course_name)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Enroll Students</h1>
            <p class="text-gray-600 mt-2">{{ $course->course_name }} - {{ $course->course_code }}</p>
        </div>
        @if($isAdmin)
            <a href="{{ route('admin.enrollments') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">← Back to Enrollments</a>
        @else
            <a href="{{ route('teacher.courses.show', $course->id) }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">← Back to Course</a>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Available Students -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Available Students</h2>
            
            <form id="enroll-form">
                @csrf
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse($availableStudents as $student)
                    <div class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <input type="checkbox" 
                            name="student_ids[]" 
                            value="{{ $student->id }}" 
                            id="student-{{ $student->id }}"
                            class="h-4 w-4 text-indigo-600 rounded focus:ring-indigo-500">
                        <label for="student-{{ $student->id }}" class="ml-3 flex-1 cursor-pointer">
                            <div class="font-medium text-gray-900">{{ $student->user->name ?? 'Unnamed Student' }}</div>
                            <div class="text-sm text-gray-500">
                                {{ $student->user->email ?? '' }} • Grade {{ $student->grade_level ?? 'N/A' }}
                            </div>
                        </label>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">All students are already enrolled in this course.</p>
                    @endforelse
                </div>
                
                @if($availableStudents->count() > 0)
                <div class="mt-4">
                    <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                        Enroll Selected Students
                    </button>
                </div>
                @endif
            </form>
        </div>

        <!-- Enrolled Students -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-900">Enrolled Students</h2>
                <span class="text-sm text-gray-500">{{ $enrolledStudents->count() }} enrolled</span>
            </div>
            
            <div class="space-y-3 max-h-96 overflow-y-auto" id="enrolled-list">
                @forelse($enrolledStudents as $student)
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg enrolled-student" data-student-id="{{ $student->id }}">
                    <div>
                        <div class="font-medium text-gray-900">{{ $student->user->name ?? 'Unnamed Student' }}</div>
                        <div class="text-sm text-gray-500">
                            {{ $student->user->email ?? '' }} • Grade {{ $student->grade_level ?? 'N/A' }}
                        </div>
                    </div>
                    <button class="unenroll-btn text-red-600 hover:text-red-800 text-sm font-medium" data-student-id="{{ $student->id }}">
                        Remove
                    </button>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No students enrolled yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const enrollForm = document.getElementById('enroll-form');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const courseId = {{ $course->id }};
    
    // Enroll students
    if (enrollForm) {
        enrollForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const checkboxes = document.querySelectorAll('input[name="student_ids[]"]:checked');
            const studentIds = Array.from(checkboxes).map(cb => parseInt(cb.value));
            
            if (studentIds.length === 0) {
                alert('Please select at least one student to enroll.');
                return;
            }
            
            try {
                const enrollRoute = @if($isAdmin) '{{ route("admin.enrollment.enroll", ["course" => $course->id]) }}' @else '{{ route("teacher.enrollment.enroll", ["course" => $course->id]) }}' @endif;
                const response = await fetch(enrollRoute, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ student_ids: studentIds })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert(data.error || 'Failed to enroll students');
                }
            } catch(error) {
                console.error('Error:', error);
                alert('Failed to enroll students. Please try again.');
            }
        });
    }
    
    // Unenroll student
    document.addEventListener('click', async function(e) {
        if (e.target.classList.contains('unenroll-btn')) {
            const studentId = e.target.getAttribute('data-student-id');
            
            if (!confirm('Are you sure you want to remove this student from the course?')) {
                return;
            }
            
            try {
                const unenrollRoute = @if($isAdmin) `{{ url('/admin/enrollment') }}/${courseId}/students/${studentId}/unenroll` @else `{{ url('/teacher/enrollment') }}/${courseId}/students/${studentId}/unenroll` @endif;
                const response = await fetch(unenrollRoute, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert(data.error || 'Failed to unenroll student');
                }
            } catch(error) {
                console.error('Error:', error);
                alert('Failed to unenroll student. Please try again.');
            }
        }
    });
});
</script>
@endpush
@endsection
