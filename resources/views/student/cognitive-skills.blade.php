@extends('student.layouts.app')

@section('title', 'Cognitive Skills - EduFocus')

@section('content')
<div class="space-y-6">
	<div class="mb-8">
		<h1 class="text-3xl font-bold text-gray-900 flex items-center">
			<span class="text-blue-600 mr-3">🧠</span>
			Cognitive Skills
		</h1>
		<p class="text-gray-600 mt-2">Enhance your mental abilities through interactive challenges</p>
	</div>

	<!-- Cognitive Skills Cards -->
	<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
		<!-- Memory Challenge -->
		<div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-blue-500">
			<div class="flex items-center mb-4">
				<div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
					<span class="text-blue-600 text-xl">🔷</span>
				</div>
				<div>
					<h3 class="text-xl font-semibold text-gray-900">Memory Challenge</h3>
					<p class="text-gray-600 text-sm">Improve your working memory with pattern recall</p>
				</div>
			</div>
			<div class="mt-4">
				<span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded-full font-medium">New</span>
			</div>
			<button onclick="startMemoryChallenge()" class="mt-4 w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
				Start Challenge
			</button>
		</div>

		<!-- Planning Puzzle -->
		<div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-yellow-500">
			<div class="flex items-center mb-4">
				<div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
					<span class="text-yellow-600 text-xl">🔗</span>
				</div>
				<div>
					<h3 class="text-xl font-semibold text-gray-900">Planning Puzzle</h3>
					<p class="text-gray-600 text-sm">Solve problems with optimal step sequencing</p>
				</div>
			</div>
			<div class="mt-4">
				<span class="inline-block px-3 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">3 Levels</span>
			</div>
			<button onclick="startPlanningPuzzle()" class="mt-4 w-full bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors">
				Start Puzzle
			</button>
		</div>

		<!-- Flexibility Test -->
		<div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-red-500">
			<div class="flex items-center mb-4">
				<div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
					<span class="text-red-600 text-xl">🔄</span>
				</div>
				<div>
					<h3 class="text-xl font-semibold text-gray-900">Flexibility Test</h3>
					<p class="text-gray-600 text-sm">Adapt quickly to changing rules</p>
				</div>
			</div>
			<div class="mt-4">
				<span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full font-medium">High Score: 850</span>
			</div>
			<button onclick="startFlexibilityTest()" class="mt-4 w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
				Start Test
			</button>
		</div>
	</div>

	<!-- Creativity Corner and Progress -->
	<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
		<!-- Creativity Corner -->
		<div class="bg-white rounded-lg shadow-md">
			<div class="px-6 py-3 bg-green-600 text-white rounded-t-lg font-semibold flex items-center">
				<span class="mr-2">💡</span>
				Creativity Corner
			</div>
			<div class="p-6">
				<div class="mb-4">
					<h3 class="text-lg font-semibold text-gray-900 mb-2 flex items-center">
						<span class="mr-2">🧩</span>
						Offline Puzzle
					</h3>
					<p class="text-gray-600 mb-4">Create a story using these 5 random words: <span id="random-words" class="font-semibold text-blue-600">mountain, whisper, clock, bubble, key</span></p>
				</div>
				<textarea id="story-input" class="w-full h-32 border border-gray-300 rounded-lg px-3 py-2 resize-none" placeholder="Write your story here..."></textarea>
				<div class="mt-4 flex justify-between items-center">
					<button onclick="generateNewWords()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
						New Words
					</button>
					<button onclick="submitStory()" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
						Submit
					</button>
				</div>
			</div>
		</div>

		<!-- Progress Section -->
		<div class="bg-white rounded-lg shadow-md">
			<div class="px-6 py-3 bg-blue-600 text-white rounded-t-lg font-semibold flex items-center">
				<span class="mr-2">📊</span>
				Progress
			</div>
			<div class="p-6">
				<div class="space-y-4">
					<div>
						<div class="flex justify-between items-center mb-2">
							<span class="text-gray-700 font-medium">Creative Thinking</span>
							<span class="text-green-600 font-semibold" id="creative-progress">{{ $progress['creative'] }}%</span>
						</div>
						<div class="w-full h-3 bg-gray-200 rounded-full">
							<div class="h-3 bg-green-500 rounded-full transition-all duration-500" id="creative-bar" style="width: {{ $progress['creative'] }}%"></div>
						</div>
					</div>
					<div>
						<div class="flex justify-between items-center mb-2">
							<span class="text-gray-700 font-medium">Problem Solving</span>
							<span class="text-green-600 font-semibold" id="problem-progress">{{ $progress['planning'] }}%</span>
						</div>
						<div class="w-full h-3 bg-gray-200 rounded-full">
							<div class="h-3 bg-green-500 rounded-full transition-all duration-500" id="problem-bar" style="width: {{ $progress['planning'] }}%"></div>
						</div>
					</div>
					<div>
						<div class="flex justify-between items-center mb-2">
							<span class="text-gray-700 font-medium">Memory Skills</span>
							<span class="text-green-600 font-semibold" id="memory-progress">{{ $progress['memory'] }}%</span>
						</div>
						<div class="w-full h-3 bg-gray-200 rounded-full">
							<div class="h-3 bg-green-500 rounded-full transition-all duration-500" id="memory-bar" style="width: {{ $progress['memory'] }}%"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Memory Challenge Modal -->
<div id="memory-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
	<div class="flex items-center justify-center min-h-screen p-4">
		<div class="bg-white rounded-lg max-w-md w-full p-6">
			<h3 class="text-xl font-semibold mb-4">Memory Challenge</h3>
			<div id="memory-game" class="text-center">
				<div id="memory-pattern" class="text-4xl mb-4 font-mono"></div>
				<div id="memory-input" class="hidden">
					<p class="text-gray-600 mb-4">Repeat the pattern:</p>
					<input type="text" id="pattern-input" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-center text-xl font-mono" placeholder="Enter pattern...">
					<button onclick="checkMemoryPattern()" class="mt-4 w-full bg-blue-600 text-white px-4 py-2 rounded-lg">Submit</button>
				</div>
				<div id="memory-result" class="hidden"></div>
			</div>
			<button onclick="closeMemoryModal()" class="mt-4 w-full bg-gray-300 text-gray-700 px-4 py-2 rounded-lg">Close</button>
		</div>
	</div>

</div>

<!-- Planning Puzzle Modal -->
<div id="planning-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
	<div class="flex items-center justify-center min-h-screen p-4">
		<div class="bg-white rounded-lg max-w-md w-full p-6">
			<h3 class="text-xl font-semibold mb-4">Planning Puzzle</h3>
			<div id="planning-game" class="text-center">
				<div id="puzzle-description" class="text-gray-600 mb-4"></div>
				<div id="puzzle-options" class="space-y-2"></div>
				<button id="puzzle-check-btn" onclick="checkPlanningOrder()" class="mt-4 w-full bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors">Check Order</button>
				<div id="puzzle-result" class="hidden mt-4 p-3 rounded-lg"></div>
			</div>
			<button onclick="closePlanningModal()" class="mt-4 w-full bg-gray-300 text-gray-700 px-4 py-2 rounded-lg">Close</button>
		</div>
	</div>

</div>

<!-- Flexibility Test Modal -->
<div id="flexibility-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
	<div class="flex items-center justify-center min-h-screen p-4">
		<div class="bg-white rounded-lg max-w-md w-full p-6">
			<h3 class="text-xl font-semibold mb-4">Flexibility Test</h3>
			<div id="flexibility-game" class="text-center">
				<div id="test-instruction" class="text-gray-600 mb-4"></div>
				<div id="test-options" class="space-y-2"></div>
				<div id="test-result" class="hidden mt-4 p-3 rounded-lg"></div>
			</div>
			<button onclick="closeFlexibilityModal()" class="mt-4 w-full bg-gray-300 text-gray-700 px-4 py-2 rounded-lg">Close</button>
		</div>
	</div>

</div>
@endsection

@push('scripts')
<script>
let currentMemoryPattern = '';
let currentMemoryLevel = 1;
let currentPuzzleLevel = 1;
let currentFlexibilityLevel = 1;
let currentSessionId = null; // To store session ID for backend communication
let currentWords = null; // To store words used for story submission
let currentPuzzleCorrectOrder = null; // To store correct puzzle order
let currentFlexibilityCorrectAnswers = null; // To store correct flexibility answers

// Memory Challenge
function startMemoryChallenge() {
	document.getElementById('memory-modal').classList.remove('hidden');
	generateMemoryPattern();
}

function generateMemoryPattern() {
	const length = 3 + currentMemoryLevel;
	const symbols = ['🔴', '🔵', '🟡', '🟢', '🟣'];
	currentMemoryPattern = '';
	
	for (let i = 0; i < length; i++) {
		currentMemoryPattern += symbols[Math.floor(Math.random() * symbols.length)];
	}
	
	document.getElementById('memory-pattern').textContent = currentMemoryPattern;
	document.getElementById('memory-input').classList.add('hidden');
	document.getElementById('memory-result').classList.add('hidden');
	
	setTimeout(() => {
		document.getElementById('memory-pattern').textContent = '❓';
		document.getElementById('memory-input').classList.remove('hidden');
		document.getElementById('pattern-input').focus();
	}, 2000);
}

function checkMemoryPattern() {
	const input = document.getElementById('pattern-input').value;
	const resultDiv = document.getElementById('memory-result');
	
	if (input === currentMemoryPattern) {
		resultDiv.innerHTML = '<div class="text-green-600 font-semibold">Correct! Well done!</div>';
		currentMemoryLevel++;
		updateProgress('memory', 10);
	} else {
		resultDiv.innerHTML = '<div class="text-red-600 font-semibold">Incorrect. Try again!</div>';
	}
	
	resultDiv.classList.remove('hidden');
	document.getElementById('pattern-input').value = '';
}

// Planning Puzzle
function startPlanningPuzzle() {
	document.getElementById('planning-modal').classList.remove('hidden');
	// Start session in backend
	fetch('/student/cognitive-skills/planning/start', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
		},
		body: JSON.stringify({ level: currentPuzzleLevel })
	})
	.then(r => r.ok ? r.json() : null)
	.then(data => {
		if (data && data.session_id) {
			currentSessionId = data.session_id;
		}
	})
	.finally(() => {
		generatePlanningPuzzle();
	});
}

function generatePlanningPuzzle() {
	const puzzles = [
		{
			description: "Arrange these steps in the correct order to make a sandwich:",
			steps: ["Put bread on plate", "Add cheese", "Add meat", "Close sandwich", "Cut diagonally"],
			correct: [0, 2, 1, 3, 4]
		},
		{
			description: "Order these steps for morning routine:",
			steps: ["Get dressed", "Brush teeth", "Eat breakfast", "Pack bag", "Leave house"],
			correct: [0, 1, 2, 3, 4]
		},
		{
			description: "Sequence for solving a math problem:",
			steps: ["Read problem", "Identify variables", "Choose method", "Solve", "Check answer"],
			correct: [0, 1, 2, 3, 4]
		}
	];
	
	const puzzle = puzzles[(currentPuzzleLevel - 1) % puzzles.length];
	currentPuzzleCorrectOrder = puzzle.correct;
	document.getElementById('puzzle-description').textContent = puzzle.description;
	
	const optionsDiv = document.getElementById('puzzle-options');
	optionsDiv.innerHTML = '';
	
	puzzle.steps.forEach((step, index) => {
		const div = document.createElement('div');
		div.className = 'flex items-center space-x-2 p-2 border rounded bg-white cursor-move';
		div.setAttribute('draggable', 'true');
		div.dataset.index = index;
		div.innerHTML = `
			<span class="text-gray-500 handle">⬍</span>
			<span class="step-text">${step}</span>
		`;
		optionsDiv.appendChild(div);
	});
	
	setupDragAndDrop(optionsDiv);
	document.getElementById('puzzle-result').classList.add('hidden');
}

function setupDragAndDrop(container) {
	let dragEl = null;
	Array.from(container.children).forEach(child => {
		child.addEventListener('dragstart', (e) => {
			dragEl = child;
			e.dataTransfer.effectAllowed = 'move';
			child.classList.add('opacity-60');
		});
		child.addEventListener('dragend', () => {
			dragEl = null;
			child.classList.remove('opacity-60');
		});
		child.addEventListener('dragover', (e) => {
			e.preventDefault();
		});
		child.addEventListener('drop', (e) => {
			e.preventDefault();
			if (!dragEl || dragEl === child) return;
			const bounding = child.getBoundingClientRect();
			const offset = e.clientY - bounding.top;
			const shouldInsertBefore = offset < bounding.height / 2;
			if (shouldInsertBefore) {
				container.insertBefore(dragEl, child);
			} else {
				container.insertBefore(dragEl, child.nextSibling);
			}
		});
	});
}

function getCurrentPuzzleOrder() {
	const container = document.getElementById('puzzle-options');
	return Array.from(container.children).map(el => parseInt(el.dataset.index, 10));
}

function checkPlanningOrder() {
	const resultDiv = document.getElementById('puzzle-result');
	const currentOrder = getCurrentPuzzleOrder();
	const isCorrect = JSON.stringify(currentOrder) === JSON.stringify(currentPuzzleCorrectOrder);
	
	if (isCorrect) {
		resultDiv.className = 'hidden mt-4 p-3 rounded-lg';
		resultDiv.innerHTML = '<div class="text-green-700 bg-green-100 px-3 py-2 rounded">Correct order! Great planning.</div>';
		resultDiv.classList.remove('hidden');
		currentPuzzleLevel++;
		updateProgress('problem', 15);
		// Complete in backend
		fetch('/student/cognitive-skills/planning/complete', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
			},
			body: JSON.stringify({ session_id: currentSessionId, correct: true })
		}).catch(() => {});
	} else {
		resultDiv.className = 'hidden mt-4 p-3 rounded-lg';
		resultDiv.innerHTML = '<div class="text-red-700 bg-red-100 px-3 py-2 rounded">Not quite. Try reordering the steps.</div>';
		resultDiv.classList.remove('hidden');
		// Optionally record attempt
		fetch('/student/cognitive-skills/planning/complete', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
			},
			body: JSON.stringify({ session_id: currentSessionId, correct: false })
		}).catch(() => {});
	}
}

// Flexibility Test
function startFlexibilityTest() {
	document.getElementById('flexibility-modal').classList.remove('hidden');
	generateFlexibilityTest();
}

function generateFlexibilityTest() {
	const tests = [
		{
			instruction: "The rule has changed! Now you must click the OPPOSITE of what you see:",
			options: ["Click RED if you see BLUE", "Click BLUE if you see RED", "Click GREEN if you see YELLOW"],
			correct: 0
		},
		{
			instruction: "New rule: Click the SECOND option when you see the FIRST:",
			options: ["Option A", "Option B", "Option C"],
			correct: 1
		}
	];
	
	const test = tests[(currentFlexibilityLevel - 1) % tests.length];
	document.getElementById('test-instruction').textContent = test.instruction;
	
	const optionsDiv = document.getElementById('test-options');
	optionsDiv.innerHTML = '';
	
	test.options.forEach((option, index) => {
		const button = document.createElement('button');
		button.className = 'w-full p-3 border border-gray-300 rounded-lg hover:bg-gray-50';
		button.textContent = option;
		button.onclick = () => checkFlexibilityAnswer(index, test.correct);
		optionsDiv.appendChild(button);
	});
}

function checkFlexibilityAnswer(selected, correct) {
	const resultDiv = document.getElementById('test-result');
	
	if (selected === correct) {
		resultDiv.innerHTML = '<div class="text-green-600 font-semibold">Correct! You adapted well!</div>';
		currentFlexibilityLevel++;
		updateProgress('problem', 15);
	} else {
		resultDiv.innerHTML = '<div class="text-red-600 font-semibold">Wrong! The rule changed again!</div>';
	}
	
	resultDiv.classList.remove('hidden');
}

// Creativity Corner
function generateNewWords() {
	const wordLists = [
		['ocean', 'whisper', 'mountain', 'clock', 'bubble'],
		['forest', 'river', 'castle', 'mirror', 'feather'],
		['desert', 'cave', 'bridge', 'lamp', 'flower'],
		['island', 'volcano', 'temple', 'book', 'crystal'],
		['jungle', 'waterfall', 'tower', 'key', 'star']
	];
	
	const randomList = wordLists[Math.floor(Math.random() * wordLists.length)];
	document.getElementById('random-words').textContent = randomList.join(', ');
	currentWords = randomList;
}

function submitStory() {
	const story = document.getElementById('story-input').value;
	if (story.trim().length < 50) {
		if (window.showToast) {
			window.showToast('Please write a longer story (at least 50 characters)', 'error');
		} else {
			alert('Please write a longer story (at least 50 characters)');
		}
		return;
	}
	
	// Submit story to backend
	submitStoryToBackend(story);
}

async function submitStoryToBackend(story) {
	try {
		const response = await fetch('/student/cognitive-skills/story', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
			},
			body: JSON.stringify({ 
				story: story,
				words_used: currentWords || ['mountain', 'whisper', 'clock', 'bubble', 'key']
			})
		});
		
		if (response.ok) {
			const data = await response.json();
			if (data.success) {
				if (window.showToast) {
					window.showToast('Story submitted successfully!', 'success');
				} else {
					alert('Story submitted successfully!');
				}
				document.getElementById('story-input').value = '';
				updateProgress('creative', 20);
			} else {
				throw new Error(data.message || 'Failed to submit story');
			}
		} else {
			throw new Error('Failed to submit story');
		}
	} catch (error) {
		if (window.showToast) {
			window.showToast('Failed to submit story. Please try again.', 'error');
		} else {
			alert('Failed to submit story. Please try again.');
		}
	}
}

// Progress Updates
function updateProgress(type, points) {
	// Update progress bars based on type
	const progressMap = {
		'creative': { element: 'creative-progress', bar: 'creative-bar', current: 75 },
		'problem': { element: 'problem-progress', bar: 'problem-bar', current: 60 },
		'memory': { element: 'memory-progress', bar: 'memory-bar', current: 45 }
	};
	
	const progress = progressMap[type];
	if (progress) {
		const newProgress = Math.min(100, progress.current + points);
		document.getElementById(progress.element).textContent = newProgress + '%';
		document.getElementById(progress.bar).style.width = newProgress + '%';
		progress.current = newProgress;
	}
}

// Modal Controls
function closeMemoryModal() {
	document.getElementById('memory-modal').classList.add('hidden');
}

function closePlanningModal() {
	document.getElementById('planning-modal').classList.add('hidden');
}

function closeFlexibilityModal() {
	document.getElementById('flexibility-modal').classList.add('hidden');
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
	generateNewWords();
});
</script>
@endpush
