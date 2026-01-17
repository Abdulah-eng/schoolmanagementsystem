@extends('admin.layouts.app')

@section('title', 'User Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">User Details</h1>
        <div class="flex space-x-4">
            <a href="{{ route('admin.users.edit', $user) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-md shadow-md">
                Edit User
            </a>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-md shadow-md">
                Back to Users
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Information -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">User Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Full Name</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email Address</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Role</label>
                        <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 
                               ($user->role === 'teacher' ? 'bg-blue-100 text-blue-800' : 
                               ($user->role === 'parent' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800')) }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->phone ?? 'Not provided' }}</p>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Address</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->address ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Stats -->
        <div class="space-y-6">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Status</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Status</span>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Created</span>
                        <span class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Last Updated</span>
                        <span class="text-sm text-gray-900">{{ $user->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            @if($user->role === 'student')
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Student Information</h3>
                    @if($user->student)
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Student ID</span>
                                <span class="text-sm text-gray-900">{{ $user->student->student_id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Grade Level</span>
                                <span class="text-sm text-gray-900">{{ $user->student->grade_level }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Section</span>
                                <span class="text-sm text-gray-900">{{ $user->student->section }}</span>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">No student information available.</p>
                    @endif
                </div>
            @endif

            @if($user->role === 'parent')
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Children</h3>
                    @if($user->children->count() > 0)
                        <div class="space-y-2">
                            @foreach($user->children as $child)
                                <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                    <span class="text-sm text-gray-900">{{ $child->name }}</span>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        {{ ucfirst($child->role) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500">No children registered.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
