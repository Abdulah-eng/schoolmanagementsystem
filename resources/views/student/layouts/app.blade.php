<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'EduFocus')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/student/dropdowns.js', 'resources/js/student/toast.js', 'resources/js/student/modal.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex lg:pl-72">
        @include('student.components.sidebar')
        
        <div class="flex-1 flex flex-col">
            @include('student.components.header')
            @include('components.toast')
            @include('components.modal')
            
            <main class="flex-1 p-6 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
