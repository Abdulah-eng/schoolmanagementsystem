@extends('admin.layouts.app')

@section('title', 'System Logs - Admin Portal')

@section('content')
<div class="space-y-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">System Logs</h1>
        <p class="text-gray-600 mt-2">View and monitor system logs</p>
    </div>

    <!-- Log Controls -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Application Logs</h2>
            <div class="flex space-x-2">
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Refresh
                </button>
                <button class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    Download
                </button>
            </div>
        </div>
        
        <div class="bg-gray-900 text-green-400 p-4 rounded-lg font-mono text-sm overflow-x-auto">
            <div class="space-y-1">
                <div>[2025-10-19 02:08:00] local.INFO: User login successful {"user_id":1,"email":"admin@edufocus.com"}</div>
                <div>[2025-10-19 02:07:59] local.INFO: Focus session started {"user_id":2,"session_type":"pomodoro"}</div>
                <div>[2025-10-19 02:07:55] local.INFO: Database connection established</div>
                <div>[2025-10-19 02:07:54] local.INFO: Application started</div>
            </div>
        </div>
    </div>
</div>
@endsection
