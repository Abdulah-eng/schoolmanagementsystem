@extends('admin.layouts.app')

@section('title', 'Analytics - Admin Portal')

@section('content')
<div class="space-y-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Analytics</h1>
        <p class="text-gray-600 mt-2">System-wide analytics and insights</p>
    </div>

    <!-- Analytics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Total Users</h3>
            <p class="text-3xl font-bold text-blue-600">1,234</p>
            <p class="text-sm text-gray-500">+12% from last month</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Active Sessions</h3>
            <p class="text-3xl font-bold text-green-600">456</p>
            <p class="text-sm text-gray-500">Today</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Focus Time</h3>
            <p class="text-3xl font-bold text-orange-600">2,340h</p>
            <p class="text-sm text-gray-500">This month</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Engagement</h3>
            <p class="text-3xl font-bold text-purple-600">87%</p>
            <p class="text-sm text-gray-500">Average</p>
        </div>
    </div>

    <!-- Charts Placeholder -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">User Growth</h2>
            <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
                <p class="text-gray-500">User growth chart will be displayed here</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Activity Trends</h2>
            <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
                <p class="text-gray-500">Activity trends chart will be displayed here</p>
            </div>
        </div>
    </div>
</div>
@endsection
