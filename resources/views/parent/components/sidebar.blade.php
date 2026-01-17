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
					<a href="{{ route('parent.dashboard') }}" class="group flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium {{ Route::is('parent.dashboard') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
						<svg class="w-5 h-5 {{ Route::is('parent.dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6" /></svg>
						<span>Dashboard</span>
					</a>
				</li>
				<li>
					<a href="{{ route('parent.progress') }}" class="group flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium {{ Route::is('parent.progress') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
						<svg class="w-5 h-5 {{ Route::is('parent.progress') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3v18m-4-4l4 4 4-4M7 7h10M7 11h10" /></svg>
						<span>Progress</span>
					</a>
				</li>
				<li>
					<a href="{{ route('parent.focus.index') }}" class="group flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium {{ Route::is('parent.focus.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
						<svg class="w-5 h-5 {{ Route::is('parent.focus.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
						<span>Home Focus</span>
					</a>
				</li>
				<li>
					<a href="{{ route('parent.screen-time.index') }}" class="group flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium {{ Route::is('parent.screen-time.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
						<svg class="w-5 h-5 {{ Route::is('parent.screen-time.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
						<span>Screen Time</span>
					</a>
				</li>
				<li>
					<a href="{{ route('parent.messages.index') }}" class="group flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium {{ Route::is('parent.messages.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
						<svg class="w-5 h-5 {{ Route::is('parent.messages.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
						<span>Messages</span>
					</a>
				</li>
				<li class="mt-auto">
					<a href="{{ route('parent.settings') }}" class="group flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium {{ Route::is('parent.settings') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
						<svg class="w-5 h-5 {{ Route::is('parent.settings') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.065 2.572c.94 1.543-.827 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.065c-1.543.94-3.31-.827-2.37-2.37A1.724 1.724 0 004.317 14.7c-1.756-.426-1.756-2.924 0-3.35.61-.148 1.093-.631 1.241-1.241.426-1.756 2.924-1.756 3.35 0 .148.61.631 1.093 1.241 1.241z" /></svg>
						<span>Settings</span>
					</a>
				</li>
			</ul>
		</nav>
	</div>
</aside>


