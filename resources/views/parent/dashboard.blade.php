@extends('parent.layouts.app')

@section('title', 'Parent Dashboard')
@section('page_title', 'Parent Dashboard')

@section('content')
<div class="space-y-6">
	<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
		<div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-gray-100">
			<p class="text-sm text-gray-600">Total Children</p>
			<p class="text-4xl font-semibold mt-2">{{ $stats['total_children'] }}</p>
			<p class="text-xs text-gray-400 mt-2">Active students</p>
		</div>
		<div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-gray-100">
			<p class="text-sm text-gray-600">Today's Focus</p>
			<p class="text-4xl font-semibold mt-2">{{ $stats['today_focus_minutes'] }}m</p>
			<p class="text-xs text-gray-400 mt-2">{{ $stats['today_focus_sessions'] }} sessions</p>
		</div>
		<div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-gray-100">
			<p class="text-sm text-gray-600">This Week</p>
			<p class="text-4xl font-semibold mt-2">{{ $stats['week_focus_minutes'] }}m</p>
			<p class="text-xs text-gray-400 mt-2">{{ $stats['week_focus_sessions'] }} sessions</p>
		</div>
		<div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-gray-100">
			<p class="text-sm text-gray-600">Cognitive Score</p>
			<p class="text-4xl font-semibold mt-2">{{ $stats['avg_cognitive_score'] }}</p>
			<p class="text-xs text-gray-400 mt-2">{{ $stats['cognitive_sessions'] }} sessions</p>
		</div>
	</div>

	<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
		<div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm ring-1 ring-gray-100">
			<h2 class="font-semibold mb-4 flex items-center gap-2"><span class="text-lg">Weekly Activity</span></h2>
			<div class="overflow-hidden rounded-xl border border-gray-100">
				<table class="min-w-full divide-y divide-gray-100 text-sm">
					<thead class="bg-gray-50">
						<tr class="text-left text-gray-600">
							<th class="px-4 py-3 font-medium">Day</th>
							<th class="px-4 py-3 font-medium">Focus Time</th>
							<th class="px-4 py-3 font-medium">Sessions</th>
							<th class="px-4 py-3 font-medium">Cognitive</th>
							<th class="px-4 py-3 font-medium">Score</th>
						</tr>
					</thead>
					<tbody class="divide-y divide-gray-100 bg-white">
						@forelse($weeklyActivity as $day)
						<tr>
							<td class="px-4 py-3 text-gray-700">{{ $day['day'] }}</td>
							<td class="px-4 py-3">
								<span class="px-2 py-1 rounded bg-blue-100 text-blue-700">{{ $day['focus_minutes'] }}m</span>
							</td>
							<td class="px-4 py-3 text-gray-700">{{ $day['focus_sessions'] }}</td>
							<td class="px-4 py-3 text-gray-700">{{ $day['cognitive_sessions'] }}</td>
							<td class="px-4 py-3">
								@if($day['avg_cognitive_score'] > 0)
									<span class="px-2 py-1 rounded bg-green-100 text-green-700">{{ $day['avg_cognitive_score'] }}</span>
								@else
									<span class="text-gray-400">-</span>
								@endif
							</td>
						</tr>
						@empty
						<tr>
							<td colspan="5" class="px-4 py-8 text-center text-gray-500">No activity data available</td>
						</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
		<div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-gray-100">
			<div class="flex items-center justify-between mb-4">
				<h2 class="font-semibold text-lg">Notifications</h2>
				<a href="{{ route('parent.messages.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">
					View Messages
				</a>
			</div>
			<ul class="space-y-4 text-sm">
				@forelse($recentMessages as $message)
				<li class="flex items-start justify-between">
					<div>
						<p class="font-medium text-gray-800">{{ $message->subject }}</p>
						<p class="text-gray-500">{{ \Illuminate\Support\Str::limit($message->content, 50) }}</p>
					</div>
					<span class="text-gray-400">{{ $message->created_at->diffForHumans() }}</span>
				</li>
				@empty
				<li class="text-center text-gray-500 py-4">No recent messages</li>
				@endforelse
			</ul>
		</div>
	</div>
</div>
@endsection


