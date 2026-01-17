<aside class="hidden lg:fixed lg:inset-y-0 lg:left-0 lg:z-50 lg:flex lg:w-72 lg:flex-col bg-gray-900 text-gray-100">
    <div class="flex grow flex-col gap-y-2 overflow-y-auto px-6 py-6">
        <div class="flex h-16 items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center">
                <span class="text-lg font-bold">E</span>
            </div>
            <span class="text-xl font-semibold">EduFocus</span>
        </div>
        <nav class="flex flex-1 flex-col">
            <ul role="list" class="flex flex-1 flex-col gap-y-1">
                <li>
                    <a href="{{ route('teacher.dashboard') }}" class="group flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium {{ Route::is('teacher.dashboard') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ Route::is('teacher.dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6" /></svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('teacher.courses.index') }}" class="group flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium {{ Route::is('teacher.courses.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ Route::is('teacher.courses.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                        <span>Courses</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('teacher.assignments.index') }}" class="group flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium {{ Route::is('teacher.assignments.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ Route::is('teacher.assignments.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                        <span>Assignments</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('teacher.students') }}" class="group flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium {{ Route::is('teacher.students.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ Route::is('teacher.students.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                        <span>Students</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('teacher.progress') }}" class="group flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium {{ Route::is('teacher.progress.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ Route::is('teacher.progress.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                        <span>Progress</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('teacher.messages.index') }}" class="group flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium {{ Route::is('teacher.messages.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ Route::is('teacher.messages.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                        <span>Messages</span>
                    </a>
                </li>
                <li class="mt-auto">
                    <a href="{{ route('teacher.settings') }}" class="group flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium {{ Route::is('teacher.settings') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ Route::is('teacher.settings') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.065 2.572c.94 1.543-.827 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.065c-1.543.94-3.31-.827-2.37-2.37A1.724 1.724 0 004.317 14.7c-1.756-.426-1.756-2.924 0-3.35.61-.148 1.093-.631 1.241-1.241.426-1.756 2.924-1.756 3.35 0 .148.61.631 1.093 1.241 1.241z" /></svg>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
