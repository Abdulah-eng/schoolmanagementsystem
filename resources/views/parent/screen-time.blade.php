@extends('parent.layouts.app')

@section('title', 'Screen Time Management - Parent Portal')

@section('content')
<div class="space-y-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Screen Time Management</h1>
        @if($child)
            <p class="text-gray-600 mt-2">Control {{ $child->name }}'s screen time and app usage</p>
        @else
            <p class="text-gray-600 mt-2">No child linked to this parent account yet.</p>
        @endif
    </div>

    <!-- Current Usage Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Today's Usage</p>
                    <p class="text-2xl font-bold text-gray-900" id="today-usage">0m</p>
                    <p class="text-xs text-gray-500" id="today-limit">Limit: 120m</p>
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
                    <p class="text-sm font-medium text-gray-600">Status</p>
                    <p class="text-lg font-bold" id="restriction-status">
                        <span class="text-green-600">Active</span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Screen Time Limits Configuration -->
    <div class="bg-white rounded-lg shadow-md p-6 {{ $child ? '' : 'opacity-50 pointer-events-none' }}">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Set Screen Time Limits</h2>
        
        <form id="screen-time-form" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Daily Limit (minutes)</label>
                    <input type="number" name="daily_limit_minutes" min="30" max="480" value="{{ $screenTimeLimit->daily_limit_minutes ?? 120 }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Weekday Limit (minutes)</label>
                    <input type="number" name="weekday_limit_minutes" min="30" max="480" value="{{ $screenTimeLimit->weekday_limit_minutes ?? 120 }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Weekend Limit (minutes)</label>
                    <input type="number" name="weekend_limit_minutes" min="30" max="600" value="{{ $screenTimeLimit->weekend_limit_minutes ?? 180 }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bedtime (24-hour format)</label>
                    <input type="number" name="bedtime_hour" min="18" max="23" value="{{ $screenTimeLimit->bedtime_hour ?? 21 }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Wake-up Time (24-hour format)</label>
                    <input type="number" name="wakeup_hour" min="5" max="10" value="{{ $screenTimeLimit->wakeup_hour ?? 7 }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Blocked Apps</label>
                <div class="space-y-2">
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="blocked_apps[]" value="social_media" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ in_array('social_media', $screenTimeLimit->blocked_apps ?? []) ? 'checked' : '' }}>
                        <label class="text-sm text-gray-700">Social Media Apps</label>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="blocked_apps[]" value="games" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ in_array('games', $screenTimeLimit->blocked_apps ?? []) ? 'checked' : '' }}>
                        <label class="text-sm text-gray-700">Gaming Apps</label>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="blocked_apps[]" value="entertainment" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ in_array('entertainment', $screenTimeLimit->blocked_apps ?? []) ? 'checked' : '' }}>
                        <label class="text-sm text-gray-700">Entertainment Apps</label>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Save Settings
                </button>
                
                <button type="button" id="toggle-restrictions" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    Toggle Restrictions
                </button>
            </div>
        </form>
    </div>

    <!-- Usage Chart Placeholder -->
    <div class="bg-white rounded-lg shadow-md p-6 {{ $child ? '' : 'opacity-50 pointer-events-none' }}">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Weekly Usage Overview</h2>
        <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
            <p class="text-gray-500">Usage chart will be displayed here</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load usage data
    loadUsage();
    
    // Form submission
    document.getElementById('screen-time-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        
        // Collect blocked apps
        data.blocked_apps = Array.from(document.querySelectorAll('input[name="blocked_apps[]"]:checked')).map(cb => cb.value);
        
        fetch('{{ route("parent.screen-time.set-limits") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Error: ' + data.error);
            } else {
                alert('Screen time settings saved successfully!');
                loadUsage();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to save settings');
        });
    });
    
    // Toggle restrictions
    document.getElementById('toggle-restrictions').addEventListener('click', function() {
        const isActive = document.getElementById('restriction-status').textContent.trim() === 'Active';
        
        fetch('{{ route("parent.screen-time.toggle") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ is_active: !isActive })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadUsage();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
    
    function loadUsage() {
        fetch('{{ route("parent.screen-time.usage") }}')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error loading usage:', data.error);
                return;
            }
            
            document.getElementById('today-usage').textContent = data.today.minutes_used + 'm';
            document.getElementById('today-limit').textContent = 'Limit: ' + data.today.limit_minutes + 'm';
            document.getElementById('week-avg').textContent = data.week.avg_daily + 'm';
            
            const statusEl = document.getElementById('restriction-status');
            if (data.limits && data.limits.is_active) {
                statusEl.innerHTML = '<span class="text-green-600">Active</span>';
            } else {
                statusEl.innerHTML = '<span class="text-red-600">Inactive</span>';
            }
        })
        .catch(error => {
            console.error('Error loading usage:', error);
        });
    }
    
    // Refresh usage every minute
    setInterval(loadUsage, 60000);
});
</script>
@endpush
@endsection
