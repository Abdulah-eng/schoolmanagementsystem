@extends('layouts.app')

@section('title', 'Sign In')

@section('content')
@include('components.welcome-modal')

<section class="bg-gradient-to-br from-gray-50 to-white">
    <div class="mx-auto grid max-w-7xl grid-cols-1 gap-10 px-4 py-16 md:grid-cols-2 md:py-24 lg:px-8">
        <div class="flex flex-col justify-center">
            <h1 class="mb-6 text-5xl font-extrabold tracking-tight text-gray-900 md:text-6xl">
                Personalized<br />
                Learning Platform
            </h1>
            <p class="mb-8 max-w-xl text-lg leading-relaxed text-gray-600">
                AI-powered education for students, teachers, and parents.
            </p>
            <ul class="mb-10 space-y-4 text-gray-700">
                <li class="flex items-start"><span class="mr-3 mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-primary-600 text-white">✓</span>Distraction-free focus sessions</li>
                <li class="flex items-start"><span class="mr-3 mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-primary-600 text-white">✓</span>Personalized learning paths</li>
                <li class="flex items-start"><span class="mr-3 mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-primary-600 text-white">✓</span>Cognitive & emotional training</li>
                <li class="flex items-start"><span class="mr-3 mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-primary-600 text-white">✓</span>Creative project showcase</li>
            </ul>

            <div class="mt-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-md">
                <div class="flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 180" class="h-48 w-auto">
                        <defs>
                            <linearGradient id="deskGrad" x1="0" x2="1" y1="0" y2="1">
                                <stop offset="0%" stop-color="#dbeafe" />
                                <stop offset="100%" stop-color="#bfdbfe" />
                            </linearGradient>
                        </defs>
                        <rect x="0" y="0" width="300" height="180" rx="16" fill="#f8fafc" />
                        <rect x="20" y="120" width="260" height="18" rx="9" fill="url(#deskGrad)" />
                        <circle cx="95" cy="82" r="28" fill="#93c5fd" />
                        <rect x="75" y="100" width="40" height="20" rx="6" fill="#60a5fa" />
                        <rect x="145" y="60" width="110" height="12" rx="6" fill="#cbd5e1" />
                        <rect x="145" y="80" width="90" height="10" rx="5" fill="#e2e8f0" />
                        <rect x="145" y="98" width="70" height="10" rx="5" fill="#e2e8f0" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xl md:p-8">
                <h2 class="mb-6 text-center text-2xl font-semibold text-gray-900">Sign In</h2>

                <div class="space-y-3">
                    <button class="w-full rounded-lg border border-primary-300 bg-white px-4 py-2.5 text-primary-700 hover:bg-primary-50">Continue with Google</button>
                    <button class="w-full rounded-lg border border-primary-300 bg-white px-4 py-2.5 text-primary-700 hover:bg-primary-50">Continue with Microsoft</button>
                    <button class="w-full rounded-lg border border-primary-300 bg-white px-4 py-2.5 text-primary-700 hover:bg-primary-50">Continue with Classlink</button>
                </div>

                <div class="my-6 flex items-center">
                    <div class="h-px flex-1 bg-gray-200"></div>
                    <span class="mx-3 text-sm text-gray-500">OR</span>
                    <div class="h-px flex-1 bg-gray-200"></div>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-4" id="login-form">
                    @csrf
                    <input type="hidden" name="role" id="selected-role" value="student" />
                    
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="text-red-800 text-sm">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 @error('email') border-red-500 @enderror" placeholder="name@example.com" />
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 @error('password') border-red-500 @enderror" placeholder="••••••••" />
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="w-full rounded-lg bg-primary-600 px-4 py-2.5 font-semibold text-white hover:bg-primary-700">Sign In</button>
                </form>

                <div class="mt-6">
                    <div class="mb-2 text-center text-sm text-gray-500">I'm a:</div>
                    <div id="role-toggle" class="mx-auto inline-flex rounded-xl border border-gray-200 bg-gray-50 p-1 text-sm">
                        <button type="button" data-role="student" class="role-btn rounded-lg px-4 py-2 font-medium text-gray-600 hover:text-gray-900">Student</button>
                        <button type="button" data-role="teacher" class="role-btn rounded-lg px-4 py-2 font-medium text-gray-600 hover:text-gray-900">Teacher</button>
                        <button type="button" data-role="parent" class="role-btn rounded-lg px-4 py-2 font-medium text-gray-600 hover:text-gray-900">Parent</button>
                        <button type="button" data-role="admin" class="role-btn rounded-lg px-4 py-2 font-medium text-gray-600 hover:text-gray-900">Admin</button>
                    </div>
                </div>
                
                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-600">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="font-medium text-primary-600 hover:text-primary-500">
                            Sign up here
                        </a>
                    </p>
                </div>
                
            </div>
        </div>
    </div>
</section>

@vite(['resources/js/components/welcome-modal.js'])

@push('scripts')
<script>
    (function() {
        var STORAGE_KEY = 'edufocus_selected_role';
        function setActive(role) {
            var buttons = document.querySelectorAll('#role-toggle .role-btn');
            buttons.forEach(function(btn){
                var isActive = btn.getAttribute('data-role') === role;
                btn.classList.toggle('bg-white', isActive);
                btn.classList.toggle('text-primary-700', isActive);
                btn.classList.toggle('shadow', isActive);
            });
            var hidden = document.getElementById('selected-role');
            if (hidden) hidden.value = role;
        }
        document.addEventListener('DOMContentLoaded', function(){
            var saved = localStorage.getItem(STORAGE_KEY) || 'student';
            setActive(saved);
            document.querySelectorAll('#role-toggle .role-btn').forEach(function(btn){
                btn.addEventListener('click', function(){
                    var role = btn.getAttribute('data-role');
                    localStorage.setItem(STORAGE_KEY, role);
                    setActive(role);
                });
            });

            var signupLink = document.querySelector('a[href$="register"]');
            if (signupLink) {
                var role = localStorage.getItem(STORAGE_KEY) || 'student';
                signupLink.href = signupLink.href.split('?')[0] + '?role=' + encodeURIComponent(role);
                document.querySelectorAll('#role-toggle .role-btn').forEach(function(btn){
                    btn.addEventListener('click', function(){
                        var r = btn.getAttribute('data-role');
                        signupLink.href = signupLink.href.split('?')[0] + '?role=' + encodeURIComponent(r);
                    });
                });
            }
        });
    })();
</script>
@endpush

@endsection


