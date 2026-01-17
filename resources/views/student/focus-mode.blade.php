@extends('student.layouts.app')

@section('title', 'Focus Mode - EduFocus')

@section('content')
<div class="space-y-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Focus Mode</h1>
        <p class="text-gray-600 mt-2">Stay focused and productive with timed study sessions</p>
    </div>

    <!-- Focus Timer -->
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <div class="mb-8">
            <div class="text-6xl font-bold text-gray-900 mb-4" id="timer">25:00</div>
            <div class="text-lg text-gray-600">Minutes remaining</div>
        </div>
        
        <div class="flex justify-center space-x-4 mb-8">
            <button id="start-btn" class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition-colors text-lg font-semibold">
                Start Focus Session
            </button>
            <button id="pause-btn" class="bg-yellow-600 text-white px-8 py-3 rounded-lg hover:bg-yellow-700 transition-colors text-lg font-semibold hidden">
                Pause
            </button>
            <button id="reset-btn" class="bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition-colors text-lg font-semibold">
                Reset
            </button>
        </div>
        
        <!-- Session Type Selection -->
        <div class="mb-8">
            <label class="block text-sm font-medium text-gray-700 mb-3">Session Type</label>
            <div class="flex justify-center space-x-3">
                <button class="session-type px-4 py-2 border-2 border-blue-500 bg-blue-50 text-blue-700 rounded-lg font-medium" data-time="25" data-type="pomodoro">
                    Pomodoro (25 min)
                </button>
                <button class="session-type px-4 py-2 border-2 border-gray-300 text-gray-600 rounded-lg hover:border-blue-500 hover:bg-blue-50 hover:text-blue-700" data-time="45" data-type="deep_work">
                    Deep Work (45 min)
                </button>
                <button class="session-type px-4 py-2 border-2 border-gray-300 text-gray-600 rounded-lg hover:border-blue-500 hover:bg-blue-50 hover:text-blue-700" data-time="15" data-type="quick_focus">
                    Quick Focus (15 min)
                </button>
            </div>
        </div>
    </div>

    <!-- Focus Settings -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Focus Settings</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Break Duration</label>
                    <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                        <option value="5">5 minutes</option>
                        <option value="10" selected>10 minutes</option>
                        <option value="15">15 minutes</option>
                    </select>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" id="notifications" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" checked>
                    <label for="notifications" class="ml-2 text-sm text-gray-700">Desktop notifications</label>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Today's Progress</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Focus Sessions</span>
                    <span id="focus-count-today" class="font-semibold text-gray-900">0</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Focus Time</span>
                    <span id="focus-total-today" class="font-semibold text-gray-900">0m</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Current Streak</span>
                    <span class="font-semibold text-green-600">7 days</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Pre-Session Preparation (moved after timer) -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-2">Pre-Session Preparation</h2>
        <a class="inline-flex items-center text-blue-700 font-semibold mb-2" href="#">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h10a4 4 0 100-8H7a4 4 0 00-4 4z"/></svg>
            Breathwork Exercise
        </a>
        <p class="text-gray-600 mb-4">Take 5 deep breaths to prepare your mind</p>
        <div class="flex items-center space-x-6">
            <div class="w-16 h-16 rounded-full bg-blue-100 border-4 border-blue-300 animate-pulse"></div>
            <div class="text-gray-700 space-y-2">
                <div> <span class="font-medium">Breathe In</span> (4s)</div>
                <div> <span class="font-medium">Hold</span> (4s)</div>
                <div> <span class="font-medium">Breathe Out</span> (6s)</div>
            </div>
        </div>
        <div class="mt-4">
            <button id="breath-start" class="bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700">Start Breathing</button>
        </div>
    </div>

    <!-- Session Goals -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Session Goals</h3>
        <div class="flex items-center space-x-3 mb-4">
            <input id="goal-input" type="text" placeholder="What do you want to accomplish?" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
            <button id="goal-add" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Add Goal</button>
        </div>
        <ul id="goal-list" class="space-y-2"></ul>
    </div>

    <!-- Micro-Break Activities -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Micro-Break Activities</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <button data-activity="walk" class="p-4 rounded-xl bg-indigo-50 text-indigo-700 font-medium hover:bg-indigo-100">2-minute walk</button>
            <button data-activity="drink_water" class="p-4 rounded-xl bg-indigo-50 text-indigo-700 font-medium hover:bg-indigo-100">Drink water</button>
            <button data-activity="stretch" class="p-4 rounded-xl bg-indigo-50 text-indigo-700 font-medium hover:bg-indigo-100">Stretch arms</button>
            <button data-activity="eye_rest" class="p-4 rounded-xl bg-indigo-50 text-indigo-700 font-medium hover:bg-indigo-100">Eye rest (20-20-20)</button>
        </div>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let timer;
    let timeLeft = 25 * 60;
    let sessionType = 'pomodoro';
    let currentSessionId = null;

    const timerDisplay = document.getElementById('timer');
    const startBtn = document.getElementById('start-btn');
    const pauseBtn = document.getElementById('pause-btn');
    const resetBtn = document.getElementById('reset-btn');
    const sessionTypeBtns = document.querySelectorAll('.session-type');

    function updateTimer() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }

    async function api(url, method = 'GET', body) {
        const headers = { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json', 'Content-Type': 'application/json' };
        const res = await fetch(url, { method, headers, body: body ? JSON.stringify(body) : undefined, credentials: 'same-origin' });
        if (!res.ok) {
            const text = await res.text();
            throw new Error(`HTTP ${res.status}: ${text}`);
        }
        return res.json();
    }

    function startLocalTimer() {
        timer = setInterval(async () => {
            timeLeft--;
            updateTimer();
            const elapsed = (sessionType === 'pomodoro' ? 25*60 : sessionType === 'deep_work' ? 45*60 : 15*60) - timeLeft;
            if (currentSessionId) {
                try { await api(`{{ route('student.focus.elapsed', ['focusSession' => 'ID']) }}`.replace('ID', currentSessionId), 'POST', { elapsed_seconds: elapsed }); } catch (e) {}
            }
            if (timeLeft <= 0) {
                clearInterval(timer);
                try { if (currentSessionId) await api(`{{ route('student.focus.complete', ['focusSession' => 'ID']) }}`.replace('ID', currentSessionId), 'POST'); } catch (e) {}
                alert('Focus session completed!');
                startBtn.classList.remove('hidden');
                pauseBtn.classList.add('hidden');
            }
        }, 1000);
    }

    // load today's stats
    (async function loadToday() {
        try {
            const data = await api(`{{ route('student.focus.index') }}`);
            document.getElementById('focus-count-today').textContent = data.today.count;
            const mins = Math.round((data.today.total_seconds || 0) / 60);
            document.getElementById('focus-total-today').textContent = `${mins}m`;
        } catch {}
    })();

    startBtn.addEventListener('click', async function() {
        try {
            const planned = Math.round(timeLeft / 60);
            const data = await api(`{{ route('student.focus.start') }}`, 'POST', { session_type: sessionType, planned_minutes: planned, settings: {} });
            currentSessionId = data.id;
            startBtn.classList.add('hidden');
            pauseBtn.classList.remove('hidden');
            startLocalTimer();
        } catch (e) {
            if (window.showToast) window.showToast('Unable to start session', 'error');
        }
    });

    pauseBtn.addEventListener('click', async function() {
        try {
            if (currentSessionId) await api(`{{ route('student.focus.pause', ['focusSession' => 'ID']) }}`.replace('ID', currentSessionId), 'POST');
            clearInterval(timer);
            startBtn.textContent = 'Resume';
            startBtn.classList.remove('hidden');
            pauseBtn.classList.add('hidden');
        } catch (e) { if (window.showToast) window.showToast('Paused', 'success', 1500); }
    });

    resetBtn.addEventListener('click', async function() {
        clearInterval(timer);
        try { await api(`{{ route('student.focus.cancel_all') }}`, 'POST'); if (window.showToast) window.showToast('Session reset', 'success', 1500); } catch (e) {}
        timeLeft = 25 * 60;
        sessionType = 'pomodoro';
        updateTimer();
        startBtn.textContent = 'Start Focus Session';
        startBtn.classList.remove('hidden');
        pauseBtn.classList.add('hidden');
        currentSessionId = null;
    });

    sessionTypeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            sessionTypeBtns.forEach(b => {
                b.classList.remove('border-blue-500', 'bg-blue-50', 'text-blue-700');
                b.classList.add('border-gray-300', 'text-gray-600');
            });
            this.classList.remove('border-gray-300', 'text-gray-600');
            this.classList.add('border-blue-500', 'bg-blue-50', 'text-blue-700');
            const time = parseInt(this.dataset.time);
            timeLeft = time * 60;
            sessionType = this.dataset.type;
            updateTimer();
        });
    });

    // Breathing session
    const breathBtn = document.getElementById('breath-start');
    if (breathBtn) {
        breathBtn.addEventListener('click', async function() {
            try {
                const session = await api(`{{ route('student.breathing.start') }}`, 'POST', { cycles: 5, inhale_seconds: 4, hold_seconds: 4, exhale_seconds: 6 });
                // Modal countdown with segmented progress
                const total = 4 + 4 + 6;
                openModal({
                    title: 'Breathwork (4-4-6) ×5',
                    html: `
                        <div class="space-y-4">
                            <div id="breath-phase" class="text-center text-lg font-medium text-gray-800">Get Ready</div>
                            <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                                <div id="breath-progress" class="h-3 bg-indigo-600 w-0"></div>
                            </div>
                            <div class="text-center text-sm text-gray-600">Cycle <span id="breath-cycle">1</span>/5 • <span id="breath-count">0</span>s</div>
                        </div>
                    `
                });
                const phaseEl = document.getElementById('breath-phase');
                const progEl = document.getElementById('breath-progress');
                const countEl = document.getElementById('breath-count');
                const cycleEl = document.getElementById('breath-cycle');

                let cycle = 1; let sec = 0;
                const timerId = setInterval(async () => {
                    sec++;
                    countEl.textContent = sec;
                    const phaseSec = sec % total;
                    // Determine phase
                    if (phaseSec <= 4 && phaseSec !== 0) phaseEl.textContent = 'Breathe In';
                    else if (phaseSec > 4 && phaseSec <= 8) phaseEl.textContent = 'Hold';
                    else phaseEl.textContent = 'Breathe Out';
                    // Progress width
                    const pct = (phaseSec === 0 ? total : phaseSec) / total * 100;
                    progEl.style.width = pct + '%';

                    if (sec % total === 0) {
                        cycle++;
                        cycleEl.textContent = Math.min(cycle, 5);
                        if (cycle > 5) {
                            clearInterval(timerId);
                            await api(`{{ route('student.breathing.complete', ['breathingSession' => 'ID']) }}`.replace('ID', session.id), 'POST').catch(()=>{});
                            closeModal();
                            if (window.showToast) window.showToast('Breathing complete. Ready to focus.', 'success');
                        }
                    }
                }, 1000);
            } catch (e) {
                if (window.showToast) window.showToast('Unable to start breathing', 'error');
            }
        });
    }

    // Goals UI
    const goalList = document.getElementById('goal-list');
    const goalInput = document.getElementById('goal-input');
    const goalAdd = document.getElementById('goal-add');

    async function loadGoals() {
        try {
            const goals = await api(`{{ route('student.focus.goals.index') }}`);
            goalList.innerHTML = '';
            goals.forEach(g => addGoalRow(g));
        } catch {}
    }

    function addGoalRow(goal) {
        const li = document.createElement('li');
        li.className = 'flex items-center justify-between p-3 rounded-lg bg-gray-50';
        li.dataset.id = goal.id;
        li.innerHTML = `
            <label class="flex items-center space-x-3">
                <input type="checkbox" class="goal-toggle" ${goal.completed ? 'checked' : ''}>
                <span class="${goal.completed ? 'line-through text-gray-500' : 'text-gray-800'}">${goal.title}</span>
            </label>
            <button class="goal-delete text-red-600 hover:text-red-700">🗑</button>
        `;
        goalList.appendChild(li);
    }

    goalAdd?.addEventListener('click', async () => {
        const title = (goalInput?.value || '').trim();
        if (!title) return;
        try {
            const g = await api(`{{ route('student.focus.goals.store') }}`, 'POST', { title });
            addGoalRow(g);
            goalInput.value = '';
        } catch {}
    });

    goalList?.addEventListener('click', async (e) => {
        const row = e.target.closest('li');
        if (!row) return;
        const id = row.dataset.id;
        if (e.target.classList.contains('goal-delete')) {
            try { await api(`{{ route('student.focus.goals.destroy', ['goal' => 'ID']) }}`.replace('ID', id), 'DELETE'); row.remove(); } catch {}
        } else if (e.target.classList.contains('goal-toggle')) {
            try {
                const g = await api(`{{ route('student.focus.goals.toggle', ['goal' => 'ID']) }}`.replace('ID', id), 'POST');
                row.querySelector('span').className = g.completed ? 'line-through text-gray-500' : 'text-gray-800';
            } catch {}
        }
    });

    // Micro-break logging
    document.querySelectorAll('[data-activity]')?.forEach(btn => {
        btn.addEventListener('click', async function() {
            const activity = this.getAttribute('data-activity');
            try { await api(`{{ route('student.focus.micro_break') }}`, 'POST', { activity }); if (window.showToast) window.showToast('Logged micro-break: ' + activity.replace('_',' '), 'success'); } catch {}
        });
    });

    loadGoals();
});
</script>
@endpush
@endsection
