@extends('student.layouts.app')

@section('title', 'Progress - EduFocus')

@section('content')
<div class="space-y-6">
	<div class="mb-8">
		<h1 class="text-3xl font-bold text-gray-900">Progress</h1>
		<p class="text-gray-600 mt-2">Live overview of your activity and growth</p>
	</div>

	<!-- Top stats -->
	<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
		<div class="bg-white rounded-xl shadow-sm p-6">
			<div class="text-gray-500">Focus Sessions (Today)</div>
			<div id="stat-focus-count" class="text-3xl font-bold text-gray-900">0</div>
		</div>
		<div class="bg-white rounded-xl shadow-sm p-6">
			<div class="text-gray-500">Focused Minutes (Today)</div>
			<div id="stat-focus-mins" class="text-3xl font-bold text-gray-900">0</div>
		</div>
		<div class="bg-white rounded-xl shadow-sm p-6">
			<div class="text-gray-500">Breathing Sessions (Today)</div>
			<div id="stat-breath" class="text-3xl font-bold text-gray-900">0</div>
		</div>
		<div class="bg-white rounded-xl shadow-sm p-6">
			<div class="text-gray-500">Projects Avg Progress</div>
			<div id="stat-proj-avg" class="text-3xl font-bold text-gray-900">0%</div>
		</div>
	</div>

	<!-- Focus last 7 days -->
	<div class="bg-white rounded-xl shadow-sm p-6">
		<div class="font-semibold text-gray-900 mb-4">Focus Minutes (Last 7 Days)</div>
		<div id="focus-series" class="grid grid-cols-7 gap-3"></div>
	</div>

	<!-- Cognitive -->
	<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
		<div class="bg-white rounded-xl shadow-sm p-6">
			<div class="text-gray-500">Memory Score</div>
			<div id="cog-memory" class="text-2xl font-bold text-gray-900">0</div>
		</div>
		<div class="bg-white rounded-xl shadow-sm p-6">
			<div class="text-gray-500">Planning Score</div>
			<div id="cog-planning" class="text-2xl font-bold text-gray-900">0</div>
		</div>
		<div class="bg-white rounded-xl shadow-sm p-6">
			<div class="text-gray-500">Flexibility Score</div>
			<div id="cog-flexibility" class="text-2xl font-bold text-gray-900">0</div>
		</div>
		<div class="bg-white rounded-xl shadow-sm p-6">
			<div class="text-gray-500">Creative Score</div>
			<div id="cog-creative" class="text-2xl font-bold text-gray-900">0</div>
		</div>
	</div>

	<!-- Goals today -->
	<div class="bg-white rounded-xl shadow-sm p-6">
		<div class="font-semibold text-gray-900 mb-2">Session Goals (Today)</div>
		<div class="text-gray-700" id="goals-today">0/0 completed</div>
	</div>

	<!-- Projects summary -->
	<div class="bg-white rounded-xl shadow-sm p-6">
		<div class="font-semibold text-gray-900 mb-2">Projects</div>
		<div class="text-gray-700"><span id="proj-count">0</span> active • <span id="proj-comments">0</span> comments</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
async function loadProgress(){
	try{
		const res = await fetch('/student/progress/data');
		if(!res.ok) return;
		const d = await res.json();
		document.getElementById('stat-focus-count').textContent = d.focus.sessions_today;
		document.getElementById('stat-focus-mins').textContent = d.focus.minutes_today;
		document.getElementById('stat-breath').textContent = d.breathing.sessions_today;
		document.getElementById('stat-proj-avg').textContent = (d.projects.avg_progress||0) + '%';
		document.getElementById('goals-today').textContent = `${d.focus.goals_today.completed}/${d.focus.goals_today.total} completed`;
		document.getElementById('proj-count').textContent = d.projects.count;
		document.getElementById('proj-comments').textContent = d.projects.comments;
		document.getElementById('cog-memory').textContent = d.cognitive.memory?.current || 0;
		document.getElementById('cog-planning').textContent = d.cognitive.planning?.current || 0;
		document.getElementById('cog-flexibility').textContent = d.cognitive.flexibility?.current || 0;
		document.getElementById('cog-creative').textContent = d.cognitive.creative?.current || 0;

		const wrap = document.getElementById('focus-series');
		wrap.innerHTML = '';
		(d.focus_last_7_days||[]).forEach(pt => {
			const col = document.createElement('div');
			col.className = 'flex flex-col items-center';
			const bar = document.createElement('div');
			bar.className = 'w-8 bg-blue-500 rounded';
			// scale height relative to the max in series for better visualization
			const maxMinutes = Math.max(...(d.focus_last_7_days || []).map(p => p.minutes || 0), 1);
			const pct = (pt.minutes / maxMinutes) * 100;
			bar.style.height = Math.max(6, Math.min(120, pct)) + 'px';
			const lab = document.createElement('div');
			lab.className = 'text-xs text-gray-600 mt-1';
			lab.textContent = pt.date;
			col.appendChild(bar); col.appendChild(lab);
			wrap.appendChild(col);
		});
	}catch(e){ console.error(e); }
}

document.addEventListener('DOMContentLoaded', loadProgress);
</script>
@endpush
