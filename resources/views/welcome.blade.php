@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
<div class="relative overflow-hidden">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-primary-600 to-primary-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                    Welcome to <span class="text-yellow-300">EduFocus</span>
                </h1>
                <p class="text-xl md:text-2xl text-primary-100 mb-8 max-w-3xl mx-auto">
                    The next-generation AI-powered school management system designed to enhance learning, streamline administration, and empower students, parents, and educators.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @guest
                        <a href="{{ route('register') }}" class="btn-primary text-lg px-8 py-3">
                            Get Started Today
                        </a>
                        <a href="{{ route('login') }}" class="btn-secondary text-lg px-8 py-3">
                            Sign In
                        </a>
                    @else
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="btn-primary text-lg px-8 py-3">
                                Admin Dashboard
                            </a>
                        @elseif(auth()->user()->isParent())
                            <a href="{{ route('parent.dashboard') }}" class="btn-primary text-lg px-8 py-3">
                                Parent Dashboard
                            </a>
                        @else
                            <a href="{{ route('student.dashboard') }}" class="btn-primary text-lg px-8 py-3">
                                Student Dashboard
                            </a>
                        @endif
                    @endguest
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Powerful Features for Modern Education
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Discover how AI technology is revolutionizing the way we manage schools and support learning.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- AI Homework Help -->
                <div class="card text-center">
                    <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">AI Homework Help</h3>
                    <p class="text-gray-600">
                        Get instant, personalized help with homework assignments across all subjects. Our AI understands your grade level and provides appropriate explanations.
                    </p>
                </div>

                <!-- Smart Study Plans -->
                <div class="card text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Smart Study Plans</h3>
                    <p class="text-gray-600">
                        Generate personalized study schedules based on your subjects, available time, and upcoming exams. AI-optimized for maximum learning efficiency.
                    </p>
                </div>

                <!-- Role-Based Access -->
                <div class="card text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Role-Based Access</h3>
                    <p class="text-gray-600">
                        Secure, role-specific dashboards for administrators, parents, and students. Each role gets access to relevant information and tools.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-gray-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Ready to Transform Your School?
            </h2>
            <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
                Join the future of education management with AI-powered insights and streamlined processes.
            </p>
            @guest
                <a href="{{ route('register') }}" class="btn-primary text-lg px-8 py-3">
                    Start Your Free Trial
                </a>
            @else
                <a href="{{ route('ai.homework-help') }}" class="btn-primary text-lg px-8 py-3">
                    Try AI Features
                </a>
            @endguest
        </div>
    </div>
</div>
@endsection
