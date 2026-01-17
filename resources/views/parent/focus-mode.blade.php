@extends('parent.layouts.app')

@section('title', 'Home Focus Mode - Parent Portal')

@section('content')
<div class="space-y-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Home Focus Mode</h1>
        @if($child)
            <p class="text-gray-600 mt-2">Help {{ $child->name }} stay focused at home</p>
        @else
            <p class="text-gray-600 mt-2">No child linked to this parent account yet.</p>
        @endif
    </div>

    <!-- Child Focus Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 {{ $child ? '' : 'opacity-50' }}">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Today's Focus</p>
                    <p class="text-2xl font-bold text-gray-900" id="today-focus">
                        @if($child)
                            {{ round($totalFocusTime / 60, 1) }}m
                        @else
                            0m
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Weekly Average</p>
                    <p class="text-2xl font-bold text-gray-900" id="week-avg">0m</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Focus Streak</p>
                    <p class="text-2xl font-bold text-gray-900" id="focus-streak">0 days</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Start Focus Session -->
    <div class="bg-white rounded-lg shadow-md p-6 {{ $child ? '' : 'opacity-50 pointer-events-none' }}">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">
            @if($child)
                Start Focus Session for {{ $child->name }}
            @else
                Start Focus Session
            @endif
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Session Type</label>
                <select id="session-type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                    <option value="pomodoro">Pomodoro (25 min)</option>
                    <option value="deep_work">Deep Work (45 min)</option>
                    <option value="quick_focus">Quick Focus (15 min)</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Custom Duration (minutes)</label>
                <input type="number" id="custom-duration" min="5" max="180" value="25" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
            </div>
        </div>
        
        <div class="mt-6">
            <button id="start-session" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                Start Focus Session
            </button>
        </div>
    </div>

    <!-- Recent Sessions -->
    <div class="bg-white rounded-lg shadow-md p-6 {{ $child ? '' : 'opacity-50' }}">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Recent Focus Sessions</h2>
        
        @if($child)
        <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="sessions-table">
                    @forelse($recentSessions as $session)
                    <tr data-session-id="{{ $session->id }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $session->created_at->format('M j, Y g:i A') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ ucfirst(str_replace('_', ' ', $session->session_type)) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ round($session->elapsed_seconds / 60, 1) }}m
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($session->status === 'completed') bg-green-100 text-green-800
                                    @elseif($session->status === 'running') bg-blue-100 text-blue-800
                                    @elseif($session->status === 'paused') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($session->status) }}
                                </span>
                                @if(in_array($session->status, ['running', 'paused']))
                                <button class="complete-session-btn text-xs text-green-600 hover:text-green-800 font-medium" data-session-id="{{ $session->id }}">
                                    Complete
                                </button>
                                <button class="cancel-session-btn text-xs text-red-600 hover:text-red-800 font-medium" data-session-id="{{ $session->id }}">
                                    Cancel
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No focus sessions yet</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8 text-gray-500">
            <p>No child linked to this parent account. Please contact support to link a child account.</p>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load statistics only if child exists
    @if($child)
    loadStats();
    @endif
    
    // Session type change handler
    const sessionTypeEl = document.getElementById('session-type');
    const startButton = document.getElementById('start-session');
    if (sessionTypeEl) {
      sessionTypeEl.addEventListener('change', function() {
        const durations = {
            'pomodoro': 25,
            'deep_work': 45,
            'quick_focus': 15
        };
        const customDurationEl = document.getElementById('custom-duration');
        if (customDurationEl && durations[this.value]) {
            customDurationEl.value = durations[this.value];
        }
      });
    }
    
    // Start session handler
    if (startButton) startButton.addEventListener('click', function() {
        const sessionType = document.getElementById('session-type').value;
        const plannedMinutes = parseInt(document.getElementById('custom-duration').value);
        
        fetch('{{ route("parent.focus.start") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                session_type: sessionType,
                planned_minutes: plannedMinutes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Error: ' + data.error);
            } else {
                alert('Focus session started successfully!');
                loadStats();
                loadRecentSessions();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to start focus session. Please try again.');
        })
        .finally(() => {
            if (startButton) {
                startButton.disabled = false;
                startButton.textContent = 'Start Focus Session';
            }
        });
    });
    
    function loadStats() {
        const todayFocusEl = document.getElementById('today-focus');
        const weekAvgEl = document.getElementById('week-avg');
        const focusStreakEl = document.getElementById('focus-streak');
        
        if (!todayFocusEl || !weekAvgEl || !focusStreakEl) {
            return;
        }
        
        fetch('{{ route("parent.focus.stats") }}')
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to load stats');
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                console.error('Error loading stats:', data.error);
                todayFocusEl.textContent = '0m';
                weekAvgEl.textContent = '0m';
                focusStreakEl.textContent = '0 days';
                return;
            }
            
            if (data.today) {
                todayFocusEl.textContent = (data.today.total_minutes || 0) + 'm';
            }
            if (data.week) {
                weekAvgEl.textContent = (data.week.avg_daily || 0) + 'm';
            }
            if (data.streak !== undefined) {
                focusStreakEl.textContent = (data.streak || 0) + ' days';
            }
        })
        .catch(error => {
            console.error('Error loading stats:', error);
            todayFocusEl.textContent = '0m';
            weekAvgEl.textContent = '0m';
            focusStreakEl.textContent = '0 days';
        });
    }
    
    function loadRecentSessions() {
        // Reload the page to show updated sessions
        window.location.reload();
    }
    
    // Check for active session and auto-complete if needed
    function checkActiveSession() {
        fetch('{{ route("parent.focus.active") }}')
        .then(response => response.json())
        .then(data => {
            if (data.completed) {
                // Session was auto-completed, reload page
                loadRecentSessions();
            } else if (data.active) {
                // Update running session display if needed
                const sessionRow = document.querySelector(`tr[data-session-id="${data.session.id}"]`);
                if (sessionRow) {
                    const durationCell = sessionRow.querySelector('td:nth-child(3)');
                    if (durationCell) {
                        durationCell.textContent = Math.round(data.elapsed_seconds / 60) + 'm';
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error checking active session:', error);
        });
    }
    
    // Complete session button
    document.addEventListener('click', async function(e) {
        if (e.target.classList.contains('complete-session-btn')) {
            const sessionId = e.target.getAttribute('data-session-id');
            if (!confirm('Mark this session as completed?')) return;
            
            try {
                const response = await fetch(`{{ url('/parent/focus/sessions') }}/${sessionId}/complete`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                if (data.success) {
                    alert('Session marked as completed!');
                    loadRecentSessions();
                } else {
                    alert('Error: ' + (data.error || 'Failed to complete session'));
                }
            } catch(error) {
                console.error('Error:', error);
                alert('Failed to complete session');
            }
        }
        
        // Cancel session button
        if (e.target.classList.contains('cancel-session-btn')) {
            const sessionId = e.target.getAttribute('data-session-id');
            if (!confirm('Cancel this session? This cannot be undone.')) return;
            
            try {
                const response = await fetch(`{{ url('/parent/focus/sessions') }}/${sessionId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                if (data.success) {
                    alert('Session cancelled!');
                    loadRecentSessions();
                } else {
                    alert('Error: ' + (data.error || 'Failed to cancel session'));
                }
            } catch(error) {
                console.error('Error:', error);
                alert('Failed to cancel session');
            }
        }
    });
    
    // Refresh stats and check active session every 30 seconds
    @if($child)
    setInterval(loadStats, 30000);
    setInterval(checkActiveSession, 10000); // Check every 10 seconds for auto-completion
    checkActiveSession(); // Check immediately on load
    @endif
});
</script>
@endpush
@endsection
