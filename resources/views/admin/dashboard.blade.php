@extends('admin.layouts.app')

@section('title', 'Admin Dashboard - EduFocus')

@section('content')
<div class="space-y-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
        <p class="text-gray-600 mt-2">System overview and management</p>
    </div>

    <!-- Statistics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Students</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_students'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Teachers</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_teachers'] }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Parents</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_parents'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Today's Activity</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Focus Sessions</span>
                    <span class="font-semibold">{{ $stats['today_sessions'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Focus Minutes</span>
                    <span class="font-semibold">{{ $stats['today_focus_minutes'] }}m</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Cognitive Sessions</span>
                    <span class="font-semibold">{{ $stats['cognitive_sessions'] }}</span>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">This Week</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Focus Sessions</span>
                    <span class="font-semibold">{{ $stats['week_sessions'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Focus Minutes</span>
                    <span class="font-semibold">{{ $stats['week_focus_minutes'] }}m</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Total Courses</span>
                    <span class="font-semibold">{{ $stats['total_courses'] }}</span>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">System Health</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Database</span>
                    <span class="text-green-600 font-semibold">✓ Healthy</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Cache</span>
                    <span class="text-green-600 font-semibold">✓ Healthy</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Storage</span>
                    <span class="text-green-600 font-semibold">✓ Healthy</span>
                </div>
            </div>
        </div>
    </div>

    <!-- User Distribution Chart -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">User Distribution</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-600">{{ $userDistribution['students'] }}</div>
                <div class="text-sm text-gray-600">Students</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-orange-600">{{ $userDistribution['teachers'] }}</div>
                <div class="text-sm text-gray-600">Teachers</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-purple-600">{{ $userDistribution['parents'] }}</div>
                <div class="text-sm text-gray-600">Parents</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-gray-600">{{ $userDistribution['admins'] }}</div>
                <div class="text-sm text-gray-600">Admins</div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Recent Users</h2>
            <div class="space-y-3">
                @forelse($recentUsers as $user)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900">{{ $user->name }}</p>
                        <p class="text-sm text-gray-600">{{ ucfirst($user->role) }} • {{ $user->email }}</p>
                    </div>
                    <span class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</span>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No recent users</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Sessions -->
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
                <p class="text-gray-500 text-center py-4">No recent sessions</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
