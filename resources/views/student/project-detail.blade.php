@extends('student.layouts.app')

@section('title', $project->title . ' - Project Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $project->title }}</h1>
            <p class="text-gray-600 mt-2">
                @if($project->subject)
                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm mr-2">{{ $project->subject }}</span>
                @endif
                @if($project->due_date)
                    <span class="text-gray-500">Due: {{ $project->due_date->format('M j, Y') }}</span>
                @endif
            </p>
        </div>
        <a href="{{ route('student.projects.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">← Back to Projects</a>
    </div>

    <!-- Progress Section -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-900">Progress</h2>
            <span class="text-2xl font-bold text-indigo-600">{{ $project->progress_percent ?? 0 }}%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-4">
            <div class="h-4 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full transition-all duration-500" 
                 style="width: {{ $project->progress_percent ?? 0 }}%"></div>
        </div>
        <div class="mt-2 text-sm text-gray-600">
            {{ $project->tasks->where('is_done', true)->count() }} of {{ $project->tasks->count() }} tasks completed
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Tasks Section -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Tasks Management -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Tasks</h2>
                    <button id="add-task-btn" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium">
                        + Add Task
                    </button>
                </div>
                
                <!-- Add Task Form (hidden initially) -->
                <div id="add-task-form" class="hidden mb-4 p-4 bg-gray-50 rounded-lg">
                    <input type="text" id="new-task-title" placeholder="Enter task title..." 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg mb-2">
                    <div class="flex space-x-2">
                        <button id="save-task-btn" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
                            Save
                        </button>
                        <button id="cancel-task-btn" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
                
                <!-- Tasks List -->
                <div id="tasks-list" class="space-y-2">
                    @forelse($project->tasks as $task)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors task-item" data-task-id="{{ $task->id }}">
                        <input type="checkbox" 
                            class="h-5 w-5 text-indigo-600 rounded focus:ring-indigo-500 task-checkbox" 
                            data-task-id="{{ $task->id }}"
                            {{ $task->is_done ? 'checked' : '' }}>
                        <label class="ml-3 flex-1 {{ $task->is_done ? 'line-through text-gray-500' : 'text-gray-900' }}">
                            {{ $task->title }}
                        </label>
                        <button class="delete-task-btn text-red-600 hover:text-red-800 text-sm" data-task-id="{{ $task->id }}">
                            Delete
                        </button>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500">
                        <p>No tasks yet. Click "Add Task" to get started!</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Comments Section -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Comments</h2>
                
                <!-- Add Comment Form -->
                <div class="mb-4">
                    <textarea id="comment-input" rows="3" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Add a comment..."></textarea>
                    <button id="post-comment-btn" class="mt-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium">
                        Post Comment
                    </button>
                </div>
                
                <!-- Comments List -->
                <div id="comments-list" class="space-y-4">
                    @forelse($project->comments as $comment)
                    <div class="border-l-4 border-indigo-500 pl-4 py-2 comment-item">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-medium text-gray-900">{{ $comment->user->name ?? 'You' }}</span>
                            <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-gray-700">{{ $comment->content }}</p>
                    </div>
                    @empty
                    <div class="text-center py-4 text-gray-500">
                        <p>No comments yet. Be the first to comment!</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Project Info -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Project Information</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" id="project-title" value="{{ $project->title }}" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                        <input type="text" id="project-subject" value="{{ $project->subject ?? '' }}" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="e.g., Mathematics">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                        <input type="date" id="project-due-date" value="{{ $project->due_date ? $project->due_date->format('Y-m-d') : '' }}" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <button id="save-project-btn" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                        Save Changes
                    </button>
                </div>
            </div>

            <!-- Submit to Showcase -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Submit to Showcase</h3>
                <p class="text-sm text-gray-600 mb-4">Share your completed project with others!</p>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">YouTube URL</label>
                        <input type="url" id="showcase-youtube-url" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg" 
                            placeholder="https://youtube.com/watch?v=...">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Grade (Optional)</label>
                        <input type="text" id="showcase-grade" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg" 
                            placeholder="e.g., Grade 9">
                    </div>
                    <button id="submit-showcase-btn" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                        Submit to Showcase
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    const projectId = {{ $project->id }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    
    async function api(url, body, method = 'POST') {
        const headers = {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        };
        const res = await fetch(url, { method, headers, body: body ? JSON.stringify(body) : null });
        if (!res.ok) {
            const error = await res.json().catch(() => ({ message: 'Unknown error' }));
            throw new Error(error.message || error.error || 'Request failed');
        }
        return res.json();
    }
    
    // Add Task
    const addTaskBtn = document.getElementById('add-task-btn');
    const addTaskForm = document.getElementById('add-task-form');
    const saveTaskBtn = document.getElementById('save-task-btn');
    const cancelTaskBtn = document.getElementById('cancel-task-btn');
    const newTaskTitle = document.getElementById('new-task-title');
    
    if (addTaskBtn) {
        addTaskBtn.addEventListener('click', () => {
            addTaskForm.classList.remove('hidden');
            newTaskTitle.focus();
        });
    }
    
    if (cancelTaskBtn) {
        cancelTaskBtn.addEventListener('click', () => {
            addTaskForm.classList.add('hidden');
            newTaskTitle.value = '';
        });
    }
    
    if (saveTaskBtn) {
        saveTaskBtn.addEventListener('click', async () => {
            const title = newTaskTitle.value.trim();
            if (!title) {
                alert('Please enter a task title');
                return;
            }
            
            try {
                const data = await api(`{{ route('student.projects.tasks.add', ['project' => $project->id]) }}`, { title });
                location.reload(); // Reload to show new task
            } catch(e) {
                console.error('Error adding task:', e);
                alert('Error: ' + e.message);
            }
        });
    }
    
    // Toggle Task
    document.addEventListener('change', async (e) => {
        if (e.target.classList.contains('task-checkbox')) {
            const taskId = e.target.getAttribute('data-task-id');
            try {
                const data = await api(`{{ url('/student/projects/tasks') }}/${taskId}/toggle`, {}, 'POST');
                // Update progress
                const progressBar = document.querySelector('.bg-gradient-to-r');
                const progressPercent = document.querySelector('.text-2xl');
                if (progressBar && data.progress !== undefined) {
                    progressBar.style.width = data.progress + '%';
                }
                if (progressPercent && data.progress !== undefined) {
                    progressPercent.textContent = data.progress + '%';
                }
                // Update label
                const label = e.target.nextElementSibling;
                if (label) {
                    if (e.target.checked) {
                        label.classList.add('line-through', 'text-gray-500');
                        label.classList.remove('text-gray-900');
                    } else {
                        label.classList.remove('line-through', 'text-gray-500');
                        label.classList.add('text-gray-900');
                    }
                }
            } catch(e) {
                alert('Error: ' + e.message);
                e.target.checked = !e.target.checked; // Revert
            }
        }
    });
    
    // Delete Task
    document.addEventListener('click', async (e) => {
        if (e.target.classList.contains('delete-task-btn')) {
            if (!confirm('Are you sure you want to delete this task?')) return;
            
            const taskId = e.target.getAttribute('data-task-id');
            try {
                // Laravel requires method spoofing for DELETE
                const formData = new FormData();
                formData.append('_method', 'DELETE');
                
                const res = await fetch(`{{ url('/student/projects/tasks') }}/${taskId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                if (!res.ok) {
                    const error = await res.json().catch(() => ({ message: 'Unknown error' }));
                    throw new Error(error.message || error.error || 'Request failed');
                }
                
                e.target.closest('.task-item').remove();
                location.reload(); // Reload to update progress
            } catch(e) {
                console.error('Error deleting task:', e);
                alert('Error: ' + e.message);
            }
        }
    });
    
    // Post Comment
    const postCommentBtn = document.getElementById('post-comment-btn');
    const commentInput = document.getElementById('comment-input');
    
    if (postCommentBtn) {
        postCommentBtn.addEventListener('click', async () => {
            const content = commentInput.value.trim();
            if (!content) {
                alert('Please enter a comment');
                return;
            }
            
            try {
                const data = await api(`{{ route('student.projects.comment', ['project' => $project->id]) }}`, { content });
                location.reload(); // Reload to show new comment
            } catch(e) {
                console.error('Error posting comment:', e);
                alert('Error: ' + e.message);
            }
        });
    }
    
    // Save Project
    const saveProjectBtn = document.getElementById('save-project-btn');
    
    if (saveProjectBtn) {
        saveProjectBtn.addEventListener('click', async () => {
            const title = document.getElementById('project-title').value.trim();
            const subject = document.getElementById('project-subject').value.trim();
            const dueDate = document.getElementById('project-due-date').value;
            
            if (!title) {
                alert('Title is required');
                return;
            }
            
            try {
                // Laravel requires method spoofing for PUT
                const formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('title', title);
                if (subject) formData.append('subject', subject);
                if (dueDate) formData.append('due_date', dueDate);
                
                const res = await fetch(`{{ route('student.projects.update', ['project' => $project->id]) }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                if (!res.ok) {
                    const error = await res.json().catch(() => ({ message: 'Unknown error' }));
                    throw new Error(error.message || error.error || 'Request failed');
                }
                
                const data = await res.json();
                alert('Project updated successfully!');
                location.reload();
            } catch(e) {
                console.error('Error updating project:', e);
                alert('Error: ' + e.message);
            }
        });
    }
    
    // Submit to Showcase
    const submitShowcaseBtn = document.getElementById('submit-showcase-btn');
    
    if (submitShowcaseBtn) {
        submitShowcaseBtn.addEventListener('click', async () => {
            const youtubeUrl = document.getElementById('showcase-youtube-url').value.trim();
            const grade = document.getElementById('showcase-grade').value.trim();
            
            if (!youtubeUrl) {
                alert('YouTube URL is required');
                return;
            }
            
            try {
                const data = await api(`{{ route('student.projects.submit-showcase', ['project' => $project->id]) }}`, {
                    youtube_url: youtubeUrl,
                    grade: grade || null
                });
                alert('Project submitted to showcase successfully!');
                document.getElementById('showcase-youtube-url').value = '';
                document.getElementById('showcase-grade').value = '';
            } catch(e) {
                console.error('Error submitting showcase:', e);
                alert('Error: ' + e.message);
            }
        });
    }
})();
</script>
@endpush
