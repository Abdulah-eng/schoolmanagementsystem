<header class="bg-white border-b border-gray-200 px-6 py-4">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <!-- Mobile menu button (hidden on desktop) -->
            <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <div class="flex items-center space-x-4">
            <!-- Notifications -->
            <div class="relative">
                <button class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <!-- Notification badge -->
                    @if(isset($unreadMessageCount) && $unreadMessageCount > 0)
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ $unreadMessageCount > 99 ? '99+' : $unreadMessageCount }}</span>
                    @endif
                </button>
            </div>

            <!-- Quick Start Dropdown -->
            <div class="relative">
                <button data-dropdown="quick-start" class="flex items-center space-x-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <span>Quick Start</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <!-- Dropdown menu -->
                <div data-dropdown-menu="quick-start" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 hidden">
                    <a href="{{ route('student.session') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 font-semibold">Start Integrated Session</a>
                    <a href="{{ route('student.progress') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">View Progress</a>
                    <a href="{{ route('student.projects.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">View Projects</a>
                </div>
            </div>

            <!-- User dropdown -->
            <div class="relative">
                <button data-dropdown="user" class="flex items-center space-x-2 p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                        {{ substr(auth()->user()->name ?? 'S', 0, 1) }}
                    </div>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <!-- User dropdown menu -->
                <div data-dropdown-menu="user" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 hidden">
                    <a href="{{ route('student.profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                    <a href="{{ route('student.settings') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Settings</a>
                    <hr class="my-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Sign Out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
