@extends('layouts.app')

@section('title', 'Sign In - EduFocus')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Sign in to your account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Access your EduFocus dashboard
            </p>
        </div>
        
        <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
            @csrf
            
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-md">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input id="email" name="email" type="email" required class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('email') border-red-500 @enderror" placeholder="Enter your email" value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" name="password" type="password" required class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('password') border-red-500 @enderror" placeholder="Enter your password">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">I'm a:</label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="relative flex cursor-pointer rounded-lg p-3 border-2 border-gray-300 focus:outline-none">
                            <input type="radio" name="role" value="student" class="sr-only" {{ old('role') == 'student' ? 'checked' : '' }}>
                            <span class="flex flex-1">
                                <span class="flex flex-col">
                                    <span class="block text-sm font-medium text-gray-900">Student</span>
                                </span>
                            </span>
                        </label>
                        
                        <label class="relative flex cursor-pointer rounded-lg p-3 border-2 border-gray-300 focus:outline-none">
                            <input type="radio" name="role" value="parent" class="sr-only" {{ old('role') == 'parent' ? 'checked' : '' }}>
                            <span class="flex flex-1">
                                <span class="flex flex-col">
                                    <span class="block text-sm font-medium text-gray-900">Parent</span>
                                </span>
                            </span>
                        </label>
                        
                        <label class="relative flex cursor-pointer rounded-lg p-3 border-2 border-gray-300 focus:outline-none">
                            <input type="radio" name="role" value="admin" class="sr-only" {{ old('role') == 'admin' ? 'checked' : '' }}>
                            <span class="flex flex-1">
                                <span class="flex flex-col">
                                    <span class="block text-sm font-medium text-gray-900">Admin</span>
                                </span>
                            </span>
                        </label>
                    </div>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Sign In
                </button>
            </div>
            
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                        Sign up here
                    </a>
                </p>
            </div>
        </form>
        
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleInputs = document.querySelectorAll('input[name="role"]');
    
    roleInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Remove checked state from all labels
            document.querySelectorAll('label').forEach(label => {
                label.classList.remove('ring-2', 'ring-blue-500', 'border-blue-500');
                label.classList.add('border-gray-300');
            });
            
            // Add checked state to selected label
            if (this.checked) {
                const label = this.closest('label');
                label.classList.add('ring-2', 'ring-blue-500', 'border-blue-500');
                label.classList.remove('border-gray-300');
            }
        });
    });
    
    // Set initial state
    const checkedInput = document.querySelector('input[name="role"]:checked');
    if (checkedInput) {
        const label = checkedInput.closest('label');
        label.classList.add('ring-2', 'ring-blue-500', 'border-blue-500');
        label.classList.remove('border-gray-300');
    }
});
</script>
@endpush
@endsection
