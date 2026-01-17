@extends('parent.layouts.app')

@section('title', 'My Children - Parent Portal')

@section('content')
<div class="space-y-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Children</h1>
        <p class="text-gray-600 mt-2">Manage your children's accounts</p>
    </div>

    <!-- Children List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <div class="flex items-center space-x-4 mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <span class="text-xl font-bold text-blue-600">A</span>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Alex Johnson</h3>
                    <p class="text-sm text-gray-600">Grade 9 • Section A</p>
                </div>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Today's Focus</span>
                    <span class="font-semibold">45 minutes</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">This Week</span>
                    <span class="font-semibold">3.2 hours</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Focus Streak</span>
                    <span class="font-semibold">7 days</span>
                </div>
            </div>
            <div class="mt-4 flex space-x-2">
                <a href="{{ route('parent.focus.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Focus Mode</a>
                <a href="{{ route('parent.progress') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">Progress</a>
            </div>
        </div>
    </div>
</div>
@endsection
