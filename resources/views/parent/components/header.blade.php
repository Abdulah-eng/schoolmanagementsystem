<header class="border-b bg-white">
	<div class="mx-auto max-w-7xl px-6 py-4 flex items-center justify-between">
		<h1 class="text-xl font-semibold">@yield('page_title', 'Parent Dashboard')</h1>
		<div class="flex items-center gap-3">
			<span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
			<form method="POST" action="{{ route('logout') }}">
				@csrf
				<button class="text-sm text-gray-600 hover:text-gray-900">Logout</button>
			</form>
		</div>
	</div>
</header>


