@extends('student.layouts.app')

@section('title', 'Integrated Learning Session - EduFocus')

@section('content')
<div class="space-y-6">
    <!-- Session Header -->
    <div class="mb-8 text-center">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Integrated Learning Session</h1>
        <p class="text-gray-600 text-lg">A structured 40-minute journey through focused learning</p>
    </div>

    <!-- Session Starter (shown when no active session) -->
    <div id="session-starter" class="max-w-2xl mx-auto bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl shadow-xl p-8 text-white">
        <h2 class="text-2xl font-bold mb-4">Ready to Begin?</h2>
        <p class="mb-6 text-blue-100">Set your learning goal and start your integrated session</p>
        
        <form id="session-start-form" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-2">Learning Goal (Optional)</label>
                <textarea id="learning-goal" rows="3" placeholder="What do you want to achieve in this session?" 
                    class="w-full px-4 py-2 rounded-lg text-gray-900"></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Subject (Optional)</label>
                    <input type="text" id="session-subject" placeholder="e.g., Mathematics" 
                        class="w-full px-4 py-2 rounded-lg text-gray-900">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Topic (Optional)</label>
                    <input type="text" id="session-topic" placeholder="e.g., Algebra" 
                        class="w-full px-4 py-2 rounded-lg text-gray-900">
                </div>
            </div>
            <button type="submit" class="w-full bg-white text-blue-600 font-semibold px-8 py-3 rounded-lg hover:bg-blue-50 transition-colors">
                Start 40-Minute Session
            </button>
        </form>
    </div>

    <!-- Active Session View (hidden initially) -->
    <div id="active-session" class="hidden">
        <!-- Progress Bar -->
        <div class="max-w-4xl mx-auto mb-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Session Progress</span>
                    <span id="progress-percent" class="text-sm font-bold text-blue-600">0%</span>
                </div>
                <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                    <div id="progress-bar" class="h-full bg-gradient-to-r from-blue-500 to-purple-600 transition-all duration-1000" style="width: 0%"></div>
                </div>
                <div class="mt-2 flex items-center justify-between text-xs text-gray-500">
                    <span id="elapsed-time">00:00</span>
                    <span>40:00</span>
                </div>
            </div>
        </div>

        <!-- Current Phase Display -->
        <div id="phase-container" class="max-w-4xl mx-auto">
            <!-- Phase content will be dynamically loaded here -->
        </div>
    </div>
</div>

<!-- Phase Templates (hidden) -->
<div id="phase-templates" class="hidden">
    <!-- Breathing Phase -->
    <div id="template-breathing" class="bg-white rounded-2xl shadow-xl p-8 text-center">
        <div class="mb-6">
            <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-5xl">🌬️</span>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Breathing Exercise</h2>
            <p class="text-gray-600">Take 2 minutes to center yourself and prepare for learning</p>
        </div>
        <div class="bg-blue-50 rounded-lg p-6 mb-6">
            <div class="flex items-center justify-center space-x-4 mb-4">
                <div id="breathing-circle" class="w-32 h-32 bg-blue-500 rounded-full flex items-center justify-center text-white text-2xl font-bold transition-all duration-4000">
                    <span id="breathing-text">Breathe In</span>
                </div>
            </div>
            <div class="space-y-2 text-gray-700">
                <p class="font-medium">Follow this pattern:</p>
                <p>Inhale for 4 seconds → Hold for 4 seconds → Exhale for 6 seconds</p>
                <p class="text-sm text-gray-600 mt-2">Repeat for 2 minutes</p>
            </div>
        </div>
        <div id="breathing-timer" class="text-3xl font-bold text-blue-600 mb-4">02:00</div>
        <button id="complete-breathing-btn" class="hidden bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 font-medium">
            Continue to Learning Phase
        </button>
    </div>

    <!-- Learning Phase -->
    <div id="template-learning" class="bg-white rounded-2xl shadow-xl p-8">
        <div class="mb-6 text-center">
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-5xl">📚</span>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Focused Learning</h2>
            <p class="text-gray-600">20 minutes of concentrated study time</p>
        </div>
        <div id="learning-timer" class="text-center text-4xl font-bold text-green-600 mb-6">20:00</div>
        <div class="bg-green-50 rounded-lg p-6 mb-6">
            <h3 class="font-semibold text-gray-900 mb-4">Your Learning Resources</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <input type="text" id="learning-subject" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="e.g., Mathematics">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Topic</label>
                    <input type="text" id="learning-topic" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="e.g., Quadratic Equations">
                </div>
            </div>
            <div class="mt-4">
                <button id="get-explanation-btn" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                    Get AI Explanation
                </button>
                <button id="generate-quiz-btn" class="ml-2 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Generate Quiz
                </button>
            </div>
            <div id="learning-content" class="mt-4 p-4 bg-white rounded border border-gray-200 min-h-[200px]"></div>
        </div>
        <button id="complete-learning-btn" class="hidden w-full bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 font-medium">
            Continue to Cognitive Exercise
        </button>
    </div>

    <!-- Cognitive Phase -->
    <div id="template-cognitive" class="bg-white rounded-2xl shadow-xl p-8 text-center">
        <div class="mb-6">
            <div class="w-24 h-24 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-5xl">🧠</span>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Cognitive Exercise</h2>
            <p class="text-gray-600">8 minutes of mental challenges</p>
        </div>
        <div id="cognitive-timer" class="text-4xl font-bold text-purple-600 mb-6">08:00</div>
        <div class="bg-purple-50 rounded-lg p-6 mb-6">
            <h3 class="font-semibold text-gray-900 mb-4">Memory Challenge</h3>
            <div id="cognitive-game" class="space-y-4">
                <p class="text-gray-700">Remember this sequence:</p>
                <div id="sequence-display" class="text-2xl font-bold text-purple-600 space-x-2"></div>
                <div id="sequence-input" class="hidden">
                    <input type="text" id="user-sequence" class="border border-gray-300 rounded-lg px-4 py-2 text-center text-xl" placeholder="Enter sequence">
                    <button id="check-sequence-btn" class="mt-2 bg-purple-600 text-white px-6 py-2 rounded-lg">Check</button>
                </div>
            </div>
        </div>
        <button id="complete-cognitive-btn" class="hidden w-full bg-purple-600 text-white px-8 py-3 rounded-lg hover:bg-purple-700 font-medium">
            Continue to Life Skills Practice
        </button>
    </div>

    <!-- Life Skills Phase -->
    <div id="template-life-skills" class="bg-white rounded-2xl shadow-xl p-8">
        <div class="mb-6 text-center">
            <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-5xl">🌟</span>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Life Skills Practice</h2>
            <p class="text-gray-600">10 minutes of practical problem-solving</p>
        </div>
        <div id="life-skills-timer" class="text-center text-4xl font-bold text-yellow-600 mb-6">10:00</div>
        <div class="bg-yellow-50 rounded-lg p-6 mb-6">
            <h3 class="font-semibold text-gray-900 mb-4">Problem-Solving Scenario</h3>
            <div id="scenario-content" class="space-y-4">
                <div class="bg-white rounded-lg p-4 border border-yellow-200">
                    <p class="text-gray-700 mb-3"><strong>Scenario:</strong> You have a group project due in 3 days, but one team member hasn't contributed. How would you handle this situation?</p>
                    <div class="space-y-2">
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="scenario-choice" value="1" class="text-yellow-600">
                            <span>Confront them directly in front of the group</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="scenario-choice" value="2" class="text-yellow-600">
                            <span>Talk to them privately and offer help</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="scenario-choice" value="3" class="text-yellow-600">
                            <span>Do their part yourself to avoid conflict</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="scenario-choice" value="4" class="text-yellow-600">
                            <span>Report them to the teacher immediately</span>
                        </label>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Your Reflection</label>
                    <textarea id="scenario-reflection" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                        placeholder="Why did you choose this approach?"></textarea>
                </div>
            </div>
        </div>
        <button id="complete-life-skills-btn" class="hidden w-full bg-yellow-600 text-white px-8 py-3 rounded-lg hover:bg-yellow-700 font-medium">
            Complete Session
        </button>
    </div>

    <!-- Completion Phase -->
    <div id="template-completed" class="bg-white rounded-2xl shadow-xl p-8 text-center">
        <div class="mb-6">
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-5xl">🎉</span>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Session Completed!</h2>
            <p class="text-gray-600">Great job completing your integrated learning session</p>
        </div>
        <div class="bg-green-50 rounded-lg p-6 mb-6">
            <h3 class="font-semibold text-gray-900 mb-4">Session Summary</h3>
            <div class="grid grid-cols-2 gap-4 text-left">
                <div>
                    <p class="text-sm text-gray-600">Total Time</p>
                    <p class="text-xl font-bold text-gray-900">40 minutes</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Phases Completed</p>
                    <p class="text-xl font-bold text-gray-900" id="phases-count">4/4</p>
                </div>
            </div>
        </div>
        <a href="{{ route('student.dashboard') }}" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 font-medium">
            Return to Dashboard
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
    let sessionStartTime = null;
    let sessionInterval = null;
    let phaseTimer = null;
    let currentPhase = null;
    let currentSessionId = null;
    let breathingCycle = 0;
    let breathingState = 'inhale'; // inhale, hold, exhale
    
    function qs(id){
        const el = document.getElementById(id);
        if (!el) {
            console.warn('Element not found:', id);
        }
        return el;
    }
    
    async function api(url, body, method = 'POST'){
        try {
            const headers = { 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '', 
                'Accept':'application/json', 
                'Content-Type':'application/json' 
            };
            const res = await fetch(url,{method, headers, body: body ? JSON.stringify(body) : null});
            if(!res.ok){ 
                const errorText = await res.text();
                let errorMsg = errorText;
                try {
                    const errorJson = JSON.parse(errorText);
                    errorMsg = errorJson.message || errorJson.error || errorText;
                } catch(e) {}
                throw new Error(errorMsg);
            }
            return res.json();
        } catch(e) {
            console.error('API Error:', e);
            throw e;
        }
    }
    
    // Check for existing session on page load
    async function checkExistingSession() {
        try {
            const status = await api("{{ route('student.session.status') }}", null, 'GET');
            if (status.active) {
                // Restore session
                currentSessionId = status.session_id;
                sessionStartTime = new Date(status.started_at);
                currentPhase = status.current_phase;
                
                const starter = qs('session-starter');
                const active = qs('active-session');
                if (starter) starter.classList.add('hidden');
                if (active) active.classList.remove('hidden');
                
                // Start progress tracking
                if (sessionInterval) clearInterval(sessionInterval);
                sessionInterval = setInterval(updateProgress, 1000);
                updateProgress();
                
                // Restore the appropriate phase (don't call API again, just show UI)
                if (status.current_phase === 'breathing') {
                    restoreBreathingPhase();
                } else if (status.current_phase === 'learning') {
                    restoreLearningPhase();
                } else if (status.current_phase === 'cognitive') {
                    restoreCognitivePhase();
                } else if (status.current_phase === 'life_skills') {
                    restoreLifeSkillsPhase();
                } else if (status.current_phase === 'completed') {
                    completeSession();
                }
            }
        } catch(e) {
            console.error('Error checking session status:', e);
            // If status check fails, assume no active session
        }
    }
    
    // Restore functions (show UI without calling API)
    function restoreBreathingPhase() {
        currentPhase = 'breathing';
        const template = qs('template-breathing');
        if (!template) return;
        const phaseContainer = qs('phase-container');
        if (!phaseContainer) return;
        const clonedTemplate = template.cloneNode(true);
        clonedTemplate.id = 'current-phase';
        clonedTemplate.classList.remove('hidden');
        phaseContainer.innerHTML = '';
        phaseContainer.appendChild(clonedTemplate);
        startPhaseTimer(120, 'breathing-timer', () => {
            const btn = qs('complete-breathing-btn');
            if (btn) btn.classList.remove('hidden');
        });
        startBreathingAnimation();
    }
    
    function restoreLearningPhase() {
        currentPhase = 'learning';
        const template = qs('template-learning');
        if (!template) return;
        const phaseContainer = qs('phase-container');
        if (!phaseContainer) return;
        const clonedTemplate = template.cloneNode(true);
        clonedTemplate.id = 'current-phase';
        clonedTemplate.classList.remove('hidden');
        phaseContainer.innerHTML = '';
        phaseContainer.appendChild(clonedTemplate);
        startPhaseTimer(1200, 'learning-timer', () => {
            const btn = qs('complete-learning-btn');
            if (btn) btn.classList.remove('hidden');
        });
    }
    
    function restoreCognitivePhase() {
        currentPhase = 'cognitive';
        const template = qs('template-cognitive');
        if (!template) return;
        const phaseContainer = qs('phase-container');
        if (!phaseContainer) return;
        const clonedTemplate = template.cloneNode(true);
        clonedTemplate.id = 'current-phase';
        clonedTemplate.classList.remove('hidden');
        phaseContainer.innerHTML = '';
        phaseContainer.appendChild(clonedTemplate);
        startPhaseTimer(480, 'cognitive-timer', () => {
            const btn = qs('complete-cognitive-btn');
            if (btn) btn.classList.remove('hidden');
        });
    }
    
    function restoreLifeSkillsPhase() {
        currentPhase = 'life_skills';
        const template = qs('template-life-skills');
        if (!template) return;
        const phaseContainer = qs('phase-container');
        if (!phaseContainer) return;
        const clonedTemplate = template.cloneNode(true);
        clonedTemplate.id = 'current-phase';
        clonedTemplate.classList.remove('hidden');
        phaseContainer.innerHTML = '';
        phaseContainer.appendChild(clonedTemplate);
        startPhaseTimer(600, 'life-skills-timer', () => {
            const btn = qs('complete-life-skills-btn');
            if (btn) btn.classList.remove('hidden');
        });
    }
    
    // Start Session
    const startForm = qs('session-start-form');
    if (startForm) {
        startForm.addEventListener('submit', async function(e){
            e.preventDefault();
            const learningGoalEl = qs('learning-goal');
            const subjectEl = qs('session-subject');
            const topicEl = qs('session-topic');
            
            const learningGoal = learningGoalEl ? learningGoalEl.value.trim() : '';
            const subject = subjectEl ? subjectEl.value.trim() : '';
            const topic = topicEl ? topicEl.value.trim() : '';
            
            try {
                const data = await api("{{ route('student.session.start') }}", {
                    learning_goal: learningGoal || null,
                    subject: subject || null,
                    topic: topic || null,
                });
                
                if (data.session_id) {
                    currentSessionId = data.session_id;
                    sessionStartTime = new Date();
                    const starter = qs('session-starter');
                    const active = qs('active-session');
                    if (starter) starter.classList.add('hidden');
                    if (active) active.classList.remove('hidden');
                    
                    // Start breathing phase
                    startBreathingPhase();
                } else {
                    throw new Error('Session ID not returned');
                }
                
            } catch(e) {
                console.error('Failed to start session:', e);
                alert('Failed to start session: ' + (e.message || 'Unknown error'));
            }
        });
    }
    
    // Update overall progress
    function updateProgress() {
        if(!sessionStartTime) return;
        const elapsed = Math.floor((new Date() - sessionStartTime) / 1000);
        const total = 40 * 60; // 40 minutes
        const progress = Math.min(100, (elapsed / total) * 100);
        const minutes = Math.floor(elapsed / 60);
        const seconds = elapsed % 60;
        
        const progressBar = qs('progress-bar');
        const progressPercent = qs('progress-percent');
        const elapsedTime = qs('elapsed-time');
        
        if (progressBar) progressBar.style.width = progress + '%';
        if (progressPercent) progressPercent.textContent = Math.round(progress) + '%';
        if (elapsedTime) elapsedTime.textContent = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
    }
    
    // Start Breathing Phase (2 minutes)
    async function startBreathingPhase() {
        currentPhase = 'breathing';
        const template = qs('template-breathing');
        if (!template) {
            console.error('Breathing template not found');
            return;
        }
        
        const phaseContainer = qs('phase-container');
        if (!phaseContainer) {
            console.error('Phase container not found');
            return;
        }
        
        const clonedTemplate = template.cloneNode(true);
        clonedTemplate.id = 'current-phase';
        clonedTemplate.classList.remove('hidden');
        phaseContainer.innerHTML = '';
        phaseContainer.appendChild(clonedTemplate);
        
        try {
            await api("{{ route('student.session.start-breathing') }}");
            startPhaseTimer(120, 'breathing-timer', () => {
                const btn = qs('complete-breathing-btn');
                if (btn) btn.classList.remove('hidden');
            });
            startBreathingAnimation();
        } catch(e) {
            console.error('Failed to start breathing:', e);
            // If it's already started, just continue with UI
            if (e.message && (e.message.includes('already') || e.message.includes('active'))) {
                startPhaseTimer(120, 'breathing-timer', () => {
                    const btn = qs('complete-breathing-btn');
                    if (btn) btn.classList.remove('hidden');
                });
                startBreathingAnimation();
            } else {
                alert('Failed to start breathing phase: ' + (e.message || 'Unknown error'));
            }
        }
        
        // Update progress every second
        if (sessionInterval) clearInterval(sessionInterval);
        sessionInterval = setInterval(updateProgress, 1000);
    }
    
    // Breathing animation
    function startBreathingAnimation() {
        const circle = qs('breathing-circle');
        const text = qs('breathing-text');
        if (!circle || !text) {
            console.warn('Breathing animation elements not found');
            return;
        }
        
        let cycle = 0;
        let animationTimeout = null;
        
        function animate() {
            if(currentPhase !== 'breathing') {
                if (animationTimeout) clearTimeout(animationTimeout);
                return;
            }
            
            // Inhale (4 seconds)
            text.textContent = 'Breathe In';
            circle.style.transition = 'transform 4s ease-in-out';
            circle.style.transform = 'scale(1.3)';
            
            animationTimeout = setTimeout(() => {
                if(currentPhase !== 'breathing') return;
                // Hold (4 seconds)
                text.textContent = 'Hold';
                circle.style.transition = 'transform 0.1s';
                
                animationTimeout = setTimeout(() => {
                    if(currentPhase !== 'breathing') return;
                    // Exhale (6 seconds)
                    text.textContent = 'Breathe Out';
                    circle.style.transition = 'transform 6s ease-in-out';
                    circle.style.transform = 'scale(1)';
                    
                    animationTimeout = setTimeout(() => {
                        cycle++;
                        if(cycle < 10 && currentPhase === 'breathing') {
                            animate();
                        }
                    }, 6000);
                }, 4000);
            }, 4000);
        }
        animate();
    }
    
    // Event delegation for phase completion buttons
    document.addEventListener('click', async function(e){
        if(e.target.id === 'complete-breathing-btn' || e.target.closest('#complete-breathing-btn')) {
            e.preventDefault();
            e.stopPropagation();
            try {
                await api("{{ route('student.session.complete-breathing') }}");
                startLearningPhase();
            } catch(err) {
                console.error('Error completing breathing:', err);
                alert('Error: ' + (err.message || 'Failed to complete breathing phase'));
            }
        } else if(e.target.id === 'complete-learning-btn' || e.target.closest('#complete-learning-btn')) {
            e.preventDefault();
            e.stopPropagation();
            try {
                await api("{{ route('student.session.complete-learning') }}");
                startCognitivePhase();
            } catch(err) {
                console.error('Error completing learning:', err);
                alert('Error: ' + (err.message || 'Failed to complete learning phase'));
            }
        } else if(e.target.id === 'complete-cognitive-btn' || e.target.closest('#complete-cognitive-btn')) {
            e.preventDefault();
            e.stopPropagation();
            try {
                await api("{{ route('student.session.complete-cognitive') }}");
                startLifeSkillsPhase();
            } catch(err) {
                console.error('Error completing cognitive:', err);
                alert('Error: ' + (err.message || 'Failed to complete cognitive phase'));
            }
        } else if(e.target.id === 'complete-life-skills-btn' || e.target.closest('#complete-life-skills-btn')) {
            e.preventDefault();
            e.stopPropagation();
            try {
                await api("{{ route('student.session.complete-life-skills') }}");
                completeSession();
            } catch(err) {
                console.error('Error completing life skills:', err);
                alert('Error: ' + (err.message || 'Failed to complete life skills phase'));
            }
        }
    });
    
    // Start Learning Phase (20 minutes)
    async function startLearningPhase() {
        currentPhase = 'learning';
        const template = qs('template-learning');
        if (!template) {
            console.error('Learning template not found');
            return;
        }
        
        const phaseContainer = qs('phase-container');
        if (!phaseContainer) {
            console.error('Phase container not found');
            return;
        }
        
        const clonedTemplate = template.cloneNode(true);
        clonedTemplate.id = 'current-phase';
        clonedTemplate.classList.remove('hidden');
        phaseContainer.innerHTML = '';
        phaseContainer.appendChild(clonedTemplate);
        
        // Set subject/topic from session start
        const subjectEl = qs('session-subject');
        const topicEl = qs('session-topic');
        const subject = subjectEl ? subjectEl.value : '';
        const topic = topicEl ? topicEl.value : '';
        
        const learningSubject = qs('learning-subject');
        const learningTopic = qs('learning-topic');
        if(subject && learningSubject) learningSubject.value = subject;
        if(topic && learningTopic) learningTopic.value = topic;
        
        try {
            await api("{{ route('student.session.start-learning') }}");
            startPhaseTimer(1200, 'learning-timer', () => {
                const btn = qs('complete-learning-btn');
                if (btn) btn.classList.remove('hidden');
            });
        } catch(e) {
            console.error('Failed to start learning:', e);
            alert('Failed to start learning phase: ' + (e.message || 'Unknown error'));
        }
        
        // Learning content handlers (using event delegation - already set up globally)
    }
    
    // Learning content handlers (using event delegation)
    document.addEventListener('click', async function(e){
        if(e.target.id === 'get-explanation-btn' || e.target.closest('#get-explanation-btn')) {
            e.preventDefault();
            e.stopPropagation();
            const subjectEl = qs('learning-subject');
            const topicEl = qs('learning-topic');
            const subject = subjectEl ? subjectEl.value : 'General';
            const topic = topicEl ? topicEl.value : 'Study Topic';
            const contentEl = qs('learning-content');
            if(contentEl) {
                contentEl.innerHTML = '<div class="text-center py-4">Loading explanation...</div>';
                try {
                    const data = await api("{{ route('student.learning.explain') }}", {
                        subject, topic, format: 'text'
                    });
                    contentEl.innerHTML = '<div class="whitespace-pre-wrap text-gray-700">' + (data.content || data.explanation || 'No content available') + '</div>';
                } catch(e) {
                    console.error('Error loading explanation:', e);
                    contentEl.innerHTML = '<div class="text-red-600">Failed to load explanation: ' + (e.message || 'Unknown error') + '</div>';
                }
            }
        } else if(e.target.id === 'generate-quiz-btn' || e.target.closest('#generate-quiz-btn')) {
            e.preventDefault();
            e.stopPropagation();
            const topicEl = qs('learning-topic');
            const topic = topicEl ? topicEl.value : 'Study Topic';
            const contentEl = qs('learning-content');
            if(contentEl) {
                contentEl.innerHTML = '<div class="text-center py-4">Generating quiz...</div>';
                try {
                    const data = await api("{{ route('student.learning.quiz') }}", { topic, num: 1 });
                    const items = data.quiz?.items || data.items || [];
                    if (items.length === 0) {
                        contentEl.innerHTML = '<div class="text-gray-600">No quiz questions generated. Try again.</div>';
                        return;
                    }
                    let html = '';
                    items.forEach((q,i)=>{
                        html += `<div class="mb-4 p-4 bg-white border rounded-lg">
                            <p class="font-medium mb-2">${q.question || 'Question ' + (i+1)}</p>
                            ${(q.options||[]).map((opt,idx)=>`
                                <label class="flex items-center space-x-2 mb-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                    <input type="radio" name="quiz-q${i}" value="${idx}" class="text-blue-600">
                                    <span>${opt}</span>
                                </label>
                            `).join('')}
                            <button class="mt-2 px-4 py-2 bg-blue-600 text-white rounded check-answer" data-ans="${q.answerIndex || q.correct_answer || 0}" data-options='${JSON.stringify(q.options || [])}'>Check Answer</button>
                        </div>`;
                    });
                    contentEl.innerHTML = html;
                } catch(e) {
                    console.error('Error generating quiz:', e);
                    contentEl.innerHTML = '<div class="text-red-600">Failed to generate quiz: ' + (e.message || 'Unknown error') + '</div>';
                }
            }
        } else if(e.target.classList.contains('check-answer')) {
            e.preventDefault();
            e.stopPropagation();
            const parent = e.target.closest('div');
            if (!parent) return;
            const selected = parent.querySelector('input[type=radio]:checked');
            const ans = parseInt(e.target.getAttribute('data-ans'));
            const optionsJson = e.target.getAttribute('data-options');
            const options = optionsJson ? JSON.parse(optionsJson) : [];
            
            if(!selected){ 
                alert('Please select an answer first');
                return; 
            }
            const correct = parseInt(selected.value) === ans;
            const correctAnswer = options[ans] || 'N/A';
            alert(correct ? 'Correct! Well done!' : 'Incorrect. The correct answer is: ' + correctAnswer);
            e.target.disabled = true;
            e.target.textContent = correct ? '✓ Correct!' : '✗ Incorrect';
            e.target.className = correct ? 'mt-2 px-4 py-2 bg-green-600 text-white rounded' : 'mt-2 px-4 py-2 bg-red-600 text-white rounded';
        }
    });
    
    // Start Cognitive Phase (8 minutes)
    async function startCognitivePhase() {
        currentPhase = 'cognitive';
        const template = qs('template-cognitive');
        if (!template) {
            console.error('Cognitive template not found');
            return;
        }
        
        const phaseContainer = qs('phase-container');
        if (!phaseContainer) {
            console.error('Phase container not found');
            return;
        }
        
        const clonedTemplate = template.cloneNode(true);
        clonedTemplate.id = 'current-phase';
        clonedTemplate.classList.remove('hidden');
        phaseContainer.innerHTML = '';
        phaseContainer.appendChild(clonedTemplate);
        
        // Generate random sequence
        const sequence = Array.from({length: 5}, () => Math.floor(Math.random() * 9) + 1);
        const sequenceDisplay = qs('sequence-display');
        if (sequenceDisplay) {
            sequenceDisplay.textContent = sequence.join(' - ');
            sequenceDisplay.setAttribute('data-sequence', sequence.join(''));
        }
        
        setTimeout(() => {
            const display = qs('sequence-display');
            const input = qs('sequence-input');
            if (display) display.classList.add('hidden');
            if (input) input.classList.remove('hidden');
        }, 5000);
        
        try {
            await api("{{ route('student.session.start-cognitive') }}");
            startPhaseTimer(480, 'cognitive-timer', () => {
                const btn = qs('complete-cognitive-btn');
                if (btn) btn.classList.remove('hidden');
            });
        } catch(e) {
            console.error('Failed to start cognitive:', e);
            alert('Failed to start cognitive phase: ' + (e.message || 'Unknown error'));
        }
    }
    
    
    // Start Life Skills Phase (10 minutes)
    async function startLifeSkillsPhase() {
        currentPhase = 'life_skills';
        const template = qs('template-life-skills');
        if (!template) {
            console.error('Life skills template not found');
            return;
        }
        
        const phaseContainer = qs('phase-container');
        if (!phaseContainer) {
            console.error('Phase container not found');
            return;
        }
        
        const clonedTemplate = template.cloneNode(true);
        clonedTemplate.id = 'current-phase';
        clonedTemplate.classList.remove('hidden');
        phaseContainer.innerHTML = '';
        phaseContainer.appendChild(clonedTemplate);
        
        try {
            await api("{{ route('student.session.start-life-skills') }}");
            startPhaseTimer(600, 'life-skills-timer', () => {
                const btn = qs('complete-life-skills-btn');
                if (btn) btn.classList.remove('hidden');
            });
        } catch(e) {
            console.error('Failed to start life skills:', e);
            alert('Failed to start life skills phase: ' + (e.message || 'Unknown error'));
        }
    }
    
    
    // Complete Session
    async function completeSession() {
        try {
            await api("{{ route('student.session.complete') }}");
            
            currentPhase = 'completed';
            const template = qs('template-completed');
            if (template) {
                const phaseContainer = qs('phase-container');
                if (phaseContainer) {
                    const clonedTemplate = template.cloneNode(true);
                    clonedTemplate.id = 'current-phase';
                    clonedTemplate.classList.remove('hidden');
                    phaseContainer.innerHTML = '';
                    phaseContainer.appendChild(clonedTemplate);
                }
            }
            
            if(sessionInterval) {
                clearInterval(sessionInterval);
                sessionInterval = null;
            }
            if(phaseTimer) {
                clearInterval(phaseTimer);
                phaseTimer = null;
            }
        } catch(e) {
            console.error('Error completing session:', e);
            alert('Error completing session: ' + (e.message || 'Unknown error'));
        }
    }
    
    // Phase timer function
    function startPhaseTimer(durationSeconds, timerElementId, onComplete) {
        let remaining = durationSeconds;
        const timerEl = qs(timerElementId);
        if(!timerEl) {
            console.warn('Timer element not found:', timerElementId);
            return;
        }
        
        if(phaseTimer) clearInterval(phaseTimer);
        
        phaseTimer = setInterval(() => {
            remaining--;
            const minutes = Math.floor(remaining / 60);
            const seconds = remaining % 60;
            timerEl.textContent = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
            
            if(remaining <= 0) {
                clearInterval(phaseTimer);
                phaseTimer = null;
                if(onComplete) onComplete();
            }
        }, 1000);
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        checkExistingSession();
    });
})();
</script>
@endpush

