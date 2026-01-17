<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EduFocus') }} - @yield('title', 'AI-Powered School Management')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('home') }}" class="text-2xl font-bold text-primary-600">
                                EduFocus
                            </a>
                        </div>
                    </div>

                    @auth
                        <div class="flex items-center space-x-4">
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <span class="sr-only">Open user menu</span>
                                    <div class="h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center">
                                        <span class="text-sm font-medium text-primary-700">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                </button>

                                <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5">
                                    <div class="px-4 py-2 text-sm text-gray-700 border-b">
                                        <div class="font-medium">{{ auth()->user()->name }}</div>
                                        <div class="text-gray-500">{{ ucfirst(auth()->user()->role) }}</div>
                                    </div>
                                    
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Admin Dashboard</a>
                                    @elseif(auth()->user()->isParent())
                                        <a href="{{ route('parent.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Parent Dashboard</a>
                                    @else
                                        <a href="{{ route('student.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Student Dashboard</a>
                                    @endif
                                    
                                    <a href="{{ route('ai.homework-help') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">AI Homework Help</a>
                                    <a href="{{ route('ai.study-plan') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">AI Study Plan</a>
                                    
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign out</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700">Sign in</a>
                            <a href="{{ route('register') }}" class="btn-primary">Sign up</a>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-8">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-auto">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="text-center text-gray-500 text-sm">
                    &copy; {{ date('Y') }} EduFocus. AI-Powered School Management System.
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
