@extends('student.layouts.app')

@section('title', 'Projects - EduFocus')

@section('content')
<div class="space-y-6">
	<div class="mb-8">
		<h1 class="text-3xl font-bold text-gray-900 flex items-center">
			<span class="mr-2">💡</span>
			Creative Projects
		</h1>
	</div>

	<!-- Current Projects -->
	<div class="bg-white rounded-2xl shadow-md overflow-hidden">
		<div class="px-6 py-3 bg-yellow-500 text-white font-semibold flex items-center">
			<span class="mr-2">≡</span>
			Current Projects
		</div>
		<div class="p-6">
			<div id="projects-grid" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
				<!-- Cards injected by JS -->
			</div>
		</div>
	</div>

	<!-- Weekly Challenge -->
	<div class="bg-white rounded-2xl shadow-md overflow-hidden">
		<div class="px-6 py-3 bg-green-600 text-white font-semibold">
			Weekly Challenge
		</div>
		<div class="p-6">
			<h3 id="challenge-title" class="text-2xl font-semibold text-gray-900 mb-2"></h3>
			<p id="challenge-desc" class="text-gray-700 mb-4"></p>
			<ul id="challenge-req" class="list-disc list-inside text-gray-700 mb-4"></ul>
			<button id="challenge-accept" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Accept Challenge</button>
		</div>
	</div>

	<!-- Collaboration Board -->
	<div class="bg-white rounded-2xl shadow-md overflow-hidden">
		<div class="px-6 py-3 bg-blue-600 text-white font-semibold flex items-center">
			<span class="mr-2">👥</span>
			Collaboration Board
		</div>
		<div class="p-6">
			<div id="collab-list" class="space-y-4 mb-4"></div>
			<div class="flex space-x-3">
				<input id="collab-input" class="flex-1 border border-gray-300 rounded-lg px-3 py-2" placeholder="Add your comment...">
				<button id="collab-post" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Post</button>
			</div>
		</div>
	</div>

	<!-- Project Showcase -->
	<div class="bg-white rounded-2xl shadow-md overflow-hidden">
		<div class="px-6 py-3 bg-cyan-500 text-white font-semibold flex items-center">
			<span class="mr-2">⭐</span>
			Project Showcase
		</div>
		<div class="p-6">
			<div id="showcase-grid" class="grid grid-cols-1 md:grid-cols-2 gap-6"></div>
		</div>
	</div>
</div>
@endsection
@push('scripts')
<script>
let projects = [];
let recentComments = [];
let showcase = [];
let weeklyChallenge = null;

async function loadProjectsData() {
	try {
		const res = await fetch('{{ route("student.projects.data") }}');
		if (!res.ok) {
			console.error('Failed to load projects data:', res.status);
			return;
		}
		const data = await res.json();
		projects = data.projects || [];
		recentComments = data.recent_comments || [];
		showcase = data.showcase || [];
		weeklyChallenge = data.weekly_challenge || null;
		renderProjects();
		renderChallenge();
		renderCollab();
		renderShowcase();
	} catch (error) {
		console.error('Error loading projects data:', error);
	}
}

function renderProjects() {
	const grid = document.getElementById('projects-grid');
	if (!grid) return;
	
	grid.innerHTML = '';

	// Add existing projects
	if (projects && projects.length > 0) {
		projects.forEach(p => {
			const card = document.createElement('div');
			card.className = 'rounded-xl border border-gray-200 p-5 shadow-sm';
			card.innerHTML = `
				<div class="text-sm text-gray-500 mb-1">${p.subject || 'Project'}</div>
				<h3 class="text-xl font-semibold text-gray-900 mb-1">${p.title || 'Untitled Project'}</h3>
				<div class="text-gray-600 mb-3">Due: ${p.due_date ? new Date(p.due_date).toLocaleDateString() : 'No due date'}</div>
				<div class="w-full bg-gray-200 rounded-full h-3 mb-3">
					<div class="h-3 bg-yellow-500 rounded-full" style="width: ${p.progress_percent || 0}%"></div>
				</div>
				<div class="text-xs text-gray-500 mb-2">Progress: ${p.progress_percent || 0}%</div>
				<button class="px-3 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition-colors" onclick="continueProject(${p.id})">${(p.progress_percent||0)>0?'Continue':'Start'}</button>
			`;
			grid.appendChild(card);
		});
	}

	// Add Start New Project card
	const add = document.createElement('div');
	add.className = 'rounded-xl border border-dashed border-gray-300 p-5 flex flex-col items-center justify-center text-center min-h-[200px]';
	add.innerHTML = `
		<div class="w-14 h-14 rounded-full bg-gray-200 flex items-center justify-center mb-3 text-2xl text-gray-600">+</div>
		<div class="text-xl font-semibold text-gray-900 mb-2">Start New Project</div>
		<button class="px-4 py-2 border border-yellow-500 text-yellow-600 rounded-lg hover:bg-yellow-50 transition-colors" onclick="openNewProject()">Browse Ideas</button>
	`;
	grid.appendChild(add);
}

function renderChallenge() {
	if (!weeklyChallenge) {
		const titleEl = document.getElementById('challenge-title');
		const descEl = document.getElementById('challenge-desc');
		const listEl = document.getElementById('challenge-req');
		if (titleEl) titleEl.textContent = 'No challenge available';
		if (descEl) descEl.textContent = 'Check back next week for a new challenge!';
		if (listEl) listEl.innerHTML = '';
		return;
	}
	const titleEl = document.getElementById('challenge-title');
	const descEl = document.getElementById('challenge-desc');
	const listEl = document.getElementById('challenge-req');
	if (titleEl) titleEl.textContent = weeklyChallenge.title || 'Weekly Challenge';
	if (descEl) descEl.textContent = weeklyChallenge.description || '';
	if (listEl) {
		listEl.innerHTML = '';
		(weeklyChallenge.requirements||[]).forEach(r => {
			const li = document.createElement('li');
			li.textContent = r; 
			listEl.appendChild(li);
		});
	}
}

function renderCollab() {
	const wrap = document.getElementById('collab-list');
	if (!wrap) return;
	
	wrap.innerHTML = '';
	
	if (!recentComments || recentComments.length === 0) {
		wrap.innerHTML = '<div class="text-gray-500 text-center py-4">No comments yet. Be the first to comment!</div>';
		return;
	}
	
	recentComments.forEach(c => {
		const div = document.createElement('div');
		div.className = 'border border-gray-200 rounded-lg p-3';
		div.innerHTML = `
			<div class="flex items-center justify-between mb-1">
				<div class="font-medium text-gray-900">${c.user_name || 'You'}</div>
				<div class="text-xs text-gray-500">${c.created_at || ''}</div>
			</div>
			<div class="text-gray-700">${c.content || ''}</div>
		`;
		wrap.appendChild(div);
	});
}

function renderShowcase() {
	const grid = document.getElementById('showcase-grid');
	if (!grid) return;
	
	grid.innerHTML = '';
	
	if (!showcase || showcase.length === 0) {
		grid.innerHTML = '<div class="col-span-2 text-gray-500 text-center py-8">No showcase projects available yet.</div>';
		return;
	}
	
	showcase.forEach(s => {
		if (!s.youtube_url) return;
		const card = document.createElement('div');
		card.className = 'rounded-xl border border-gray-200 p-4 shadow-sm';
		const youtubeId = extractYouTubeId(s.youtube_url);
		if (!youtubeId) return;
		card.innerHTML = `
			<div class="aspect-video bg-black rounded-lg mb-3 overflow-hidden">
				<iframe class="w-full h-full" src="https://www.youtube.com/embed/${youtubeId}" title="${s.title || 'Project Showcase'}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			</div>
			<div class="text-lg font-semibold text-gray-900">${s.title || 'Untitled Project'}</div>
			<div class="text-sm text-gray-600">By: ${s.author || 'Unknown'}${s.grade ? ' | Grade: ' + s.grade : ''}</div>
		`;
		grid.appendChild(card);
	});
}

function extractYouTubeId(url){
	try {
		const u = new URL(url);
		if (u.hostname.includes('youtu.be')) return u.pathname.substring(1);
		const id = u.searchParams.get('v');
		return id || '';
	} catch { return ''; }
}

async function openNewProject() {
	const title = prompt('Project title');
	if (!title) return;
	try {
		const res = await fetch('{{ route("student.projects.create") }}', {
			method: 'POST',
			headers: { 
				'Content-Type': 'application/json', 
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
			},
			body: JSON.stringify({ title })
		});
		if (res.ok) {
			loadProjectsData();
		} else {
			const error = await res.json();
			alert('Error: ' + (error.message || 'Failed to create project'));
		}
	} catch (error) {
		console.error('Error creating project:', error);
		alert('Failed to create project');
	}
}

function continueProject(id){
	// Navigate to project detail page
	window.location.href = `/student/projects/${id}`;
}

// Collaboration input
document.addEventListener('DOMContentLoaded', () => {
	loadProjectsData();
	const btn = document.getElementById('collab-post');
	if (btn) {
		btn.addEventListener('click', async () => {
			const input = document.getElementById('collab-input');
			const content = input.value.trim(); 
			if(!content) return;
			
			// post to latest/first project if exists
			const pid = projects[0]?.id; 
			if (!pid) {
				alert('Please create a project first before posting comments.');
				return;
			}
			
			try {
				const res = await fetch(`{{ url('/student/projects') }}/${pid}/comment`, {
					method: 'POST',
					headers: { 
						'Content-Type': 'application/json', 
						'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
					},
					body: JSON.stringify({ content })
				});
				if (res.ok) { 
					input.value=''; 
					loadProjectsData(); 
				} else {
					const error = await res.json();
					alert('Error: ' + (error.message || 'Failed to post comment'));
				}
			} catch (error) {
				console.error('Error posting comment:', error);
				alert('Failed to post comment');
			}
		});
	}
	
	// Challenge accept button
	const challengeBtn = document.getElementById('challenge-accept');
	if (challengeBtn) {
		challengeBtn.addEventListener('click', () => {
			alert('Challenge accepted! Create a new project to get started.');
			openNewProject();
		});
	}
});
</script>
@endpush

