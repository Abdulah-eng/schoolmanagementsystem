@extends('student.layouts.app')

@section('title', 'Learning Center - EduFocus')

@section('content')
<div class="space-y-6">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">EduFocus More - Learning Center</h1>
            <p class="text-gray-600 mt-2">Explore curriculum with neuroscience-based learning intervals</p>
        </div>
        <!-- Session Timer (hidden initially) -->
        <div id="session-timer" class="hidden bg-blue-600 text-white px-6 py-3 rounded-lg shadow-lg">
            <div class="text-sm font-medium">Session Time</div>
            <div id="timer-display" class="text-2xl font-bold">00:00</div>
        </div>
    </div>

    <!-- Start Learning Session Card -->
    <div id="session-starter" class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
        <h2 class="text-2xl font-bold mb-4">Start Your Learning Session</h2>
        <p class="mb-6 text-blue-100">Begin studying with integrated neuroscience breaks for optimal learning</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium mb-2">Subject *</label>
                <input type="text" id="session-subject" placeholder="e.g., Mathematics" class="w-full px-4 py-2 rounded-lg text-gray-900" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Topic *</label>
                <input type="text" id="session-topic" placeholder="e.g., Quadratic Equations" class="w-full px-4 py-2 rounded-lg text-gray-900" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Duration (minutes) *</label>
                <select id="session-duration" class="w-full px-4 py-2 rounded-lg text-gray-900" required>
                    <option value="30">30 minutes</option>
                    <option value="45" selected>45 minutes</option>
                    <option value="60">60 minutes</option>
                    <option value="90">90 minutes</option>
                </select>
            </div>
        </div>
        <button id="start-session-btn" class="bg-white text-blue-600 font-semibold px-8 py-3 rounded-lg hover:bg-blue-50 transition-colors">
            Start Learning Session
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($courses as $course)
        <div class="bg-white rounded-2xl shadow p-6 text-center">
            <div class="w-12 h-12 mx-auto mb-3 text-blue-600">
                @if($course->course_name === 'Mathematics')
                    √x
                @elseif($course->course_name === 'Science')
                    ⚛
                @elseif($course->course_name === 'Languages')
                    A↔︎文
                @elseif($course->course_name === 'History')
                    📚
                @elseif($course->course_name === 'Arts')
                    🎨
                @else
                    📖
                @endif
            </div>
            <h3 class="text-xl font-semibold text-gray-900">{{ $course->course_name }}</h3>
            <p class="text-gray-600">{{ $course->description }}</p>
            <span class="inline-block mt-3 px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">{{ $course->course_code }}</span>
        </div>
        @empty
        <div class="col-span-3 text-center py-8 text-gray-500">
            No courses available at the moment.
        </div>
        @endforelse
    </div>

    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-3 bg-blue-600 text-white rounded-t-lg font-semibold">AI-Powered Explanation</div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <input id="lc-subject" class="border border-gray-300 rounded-lg px-3 py-2" placeholder="Subject (e.g., Mathematics)">
                <input id="lc-topic" class="border border-gray-300 rounded-lg px-3 py-2 md:col-span-2" placeholder="Topic (e.g., Quadratic Equations)">
            </div>
            <div class="flex flex-wrap gap-2">
                <button data-format="text" class="lc-format px-4 py-2 border-2 border-blue-500 bg-blue-50 text-blue-700 rounded-lg font-medium">Visual</button>
                <button data-format="audio" class="lc-format px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:border-blue-500 hover:bg-blue-50">Audio</button>
                <button data-format="video" class="lc-format px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:border-blue-500 hover:bg-blue-50">Video</button>
            </div>
            <div id="lc-output" class="bg-blue-50 border border-blue-200 rounded-lg p-4 min-h-[120px]"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Practice Quiz</h3>
            <div id="lc-quiz" class="space-y-4"></div>
            <button id="lc-quiz-generate" class="mt-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Generate Quiz</button>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Learning Targets</h3>
            <ul class="space-y-3">
                @forelse($learningTargets as $target)
                    <li class="flex items-center justify-between">
                        <span class="flex items-center">
                            @if($target['status'] === 'completed')
                                <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                            @else
                                <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                            @endif
                            {{ $target['title'] }}
                        </span>
                        <span class="text-xs px-2 py-1 rounded 
                            @if($target['status'] === 'completed') bg-green-100 text-green-700
                            @elseif($target['priority'] === 'high') bg-red-100 text-red-700
                            @elseif($target['priority'] === 'medium') bg-yellow-100 text-yellow-700
                            @else bg-blue-100 text-blue-700
                            @endif">
                            @if($target['status'] === 'completed')
                                Completed
                            @else
                                Due {{ $target['due'] }}
                            @endif
                        </span>
                    </li>
                @empty
                    <li class="text-gray-500 text-center py-4">No learning targets set yet</li>
                @endforelse
            </ul>
            <div class="mt-4">
                <div class="text-sm text-gray-600 mb-1">Weekly Progress</div>
                <div class="w-full h-2 bg-gray-200 rounded-full">
                    <div class="h-2 bg-blue-600 rounded-full transition-all duration-500" style="width:{{ $weeklyProgress }}%"></div>
                </div>
                <div class="text-xs text-gray-500 mt-1 text-right">{{ $weeklyProgress }}%</div>
            </div>
        </div>
    </div>
</div>

<!-- Break Modal -->
<div id="break-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <h2 id="break-title" class="text-2xl font-bold text-gray-900 mb-4"></h2>
            <div id="break-content" class="space-y-4">
                <div id="break-body"></div>
                <div id="break-quiz-container" class="hidden"></div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button id="break-complete-btn" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium">
                    Complete Break
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
    let sessionStartTime = null;
    let sessionInterval = null;
    let breakCheckInterval = null;
    let currentSessionId = null;
    
    function qs(id){return document.getElementById(id)}
    async function api(url, body, method = 'POST'){
        const headers = { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept':'application/json', 'Content-Type':'application/json' };
        const res = await fetch(url,{method, headers, body: body ? JSON.stringify(body) : null});
        if(!res.ok){ throw new Error(await res.text()) }
        return res.json();
    }
    
    // Start Learning Session
    qs('start-session-btn')?.addEventListener('click', async function(){
        const subject = qs('session-subject').value.trim();
        const topic = qs('session-topic').value.trim();
        const duration = parseInt(qs('session-duration').value);
        
        if(!subject || !topic) {
            alert('Please fill in both subject and topic');
            return;
        }
        
        try {
            const data = await api("{{ route('student.learning.session.start') }}", {
                subject, topic, duration_minutes: duration
            });
            
            currentSessionId = data.session_id;
            sessionStartTime = new Date();
            qs('session-starter').classList.add('hidden');
            qs('session-timer').classList.remove('hidden');
            
            // Start timer
            updateTimer();
            sessionInterval = setInterval(updateTimer, 1000);
            
            // Check for breaks every 30 seconds to catch 15-minute intervals
            breakCheckInterval = setInterval(checkForBreak, 30000);
            
            // Set topic in learning inputs
            qs('lc-subject').value = subject;
            qs('lc-topic').value = topic;
            
        } catch(e) {
            alert('Failed to start session: ' + e.message);
        }
    });
    
    function updateTimer() {
        if(!sessionStartTime) return;
        const elapsed = Math.floor((new Date() - sessionStartTime) / 1000);
        const minutes = Math.floor(elapsed / 60);
        const seconds = elapsed % 60;
        qs('timer-display').textContent = 
            String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
    }
    
    async function checkForBreak() {
        if(!currentSessionId || !sessionStartTime) return;
        
        const elapsedMinutes = Math.floor((new Date() - sessionStartTime) / 60000);
        
        // Trigger break every 15 minutes (15, 30, 45, 60, etc.)
        if(elapsedMinutes > 0 && elapsedMinutes % 15 === 0) {
            // Determine break type based on interval number
            const intervalNumber = Math.floor(elapsedMinutes / 15);
            const breakTypes = ['breathing', 'visualization', 'physical', 'quiz'];
            const breakType = breakTypes[(intervalNumber - 1) % breakTypes.length];
            
            // Only show if we haven't shown this break yet
            const lastBreakTime = sessionStorage.getItem('lastBreakTime');
            const currentBreakKey = `${elapsedMinutes}_${breakType}`;
            
            if(lastBreakTime !== currentBreakKey) {
                sessionStorage.setItem('lastBreakTime', currentBreakKey);
                showBreakModal(breakType);
                
                // Also notify server
                try {
                    await api("{{ route('student.learning.session.break') }}", {
                        break_type: breakType,
                        session_id: currentSessionId
                    });
                } catch(e) {
                    console.error('Break notification failed:', e);
                }
            }
        }
    }
    
    function showBreakModal(breakType) {
        const modal = document.getElementById('break-modal');
        const breakContent = document.getElementById('break-content');
        const breakTitle = document.getElementById('break-title');
        const breakBody = document.getElementById('break-body');
        const breakCompleteBtn = document.getElementById('break-complete-btn');
        
        // Stop timer during break
        if(sessionInterval) {
            clearInterval(sessionInterval);
        }
        
        const breakConfigs = {
            breathing: {
                title: '🌬️ Breathing Break',
                body: `
                    <div class="text-center space-y-4">
                        <p class="text-lg">Take a 2-minute breathing break to refresh your mind</p>
                        <div class="flex items-center justify-center space-x-4">
                            <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center animate-pulse">
                                <span class="text-4xl">🌬️</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <p class="font-medium">Follow this pattern:</p>
                            <p>Inhale for 4 seconds → Hold for 4 seconds → Exhale for 4 seconds</p>
                            <p class="text-sm text-gray-600">Repeat for 2 minutes</p>
                        </div>
                    </div>
                `
            },
            visualization: {
                title: '🧘 Visualization Exercise',
                body: `
                    <div class="text-center space-y-4">
                        <p class="text-lg">Take a moment to visualize what you just learned</p>
                        <div class="bg-purple-50 rounded-lg p-6 space-y-3">
                            <p class="font-medium">Close your eyes and imagine:</p>
                            <ul class="text-left space-y-2 text-gray-700">
                                <li>• Picture the concepts you just studied</li>
                                <li>• Visualize how they connect together</li>
                                <li>• Imagine applying them in real situations</li>
                            </ul>
                            <p class="text-sm text-gray-600 mt-4">Take 2 minutes for this visualization</p>
                        </div>
                    </div>
                `
            },
            physical: {
                title: '🏃 Quick Physical Activity',
                body: `
                    <div class="text-center space-y-4">
                        <p class="text-lg">Time for a 2-minute physical break!</p>
                        <div class="bg-green-50 rounded-lg p-6 space-y-3">
                            <p class="font-medium">Choose one activity:</p>
                            <ul class="text-left space-y-2 text-gray-700">
                                <li>• 10 jumping jacks</li>
                                <li>• 10 arm circles (each direction)</li>
                                <li>• 5 deep squats</li>
                                <li>• Stretch your arms and legs</li>
                                <li>• Walk around for 30 seconds</li>
                            </ul>
                            <p class="text-sm text-gray-600 mt-4">Get your blood flowing!</p>
                        </div>
                    </div>
                `
            },
            quiz: {
                title: '📝 Quick Quiz Check',
                body: `
                    <div class="text-center space-y-4">
                        <p class="text-lg">Test your understanding with a quick quiz</p>
                        <div class="bg-yellow-50 rounded-lg p-6">
                            <p class="text-gray-700 mb-4">A quick quiz will help reinforce what you just learned!</p>
                            <button id="start-quiz-btn" class="bg-yellow-600 text-white px-6 py-2 rounded-lg hover:bg-yellow-700">
                                Start Quick Quiz
                            </button>
                        </div>
                    </div>
                `
            }
        };
        
        const config = breakConfigs[breakType];
        if(!config) return;
        
        breakTitle.textContent = config.title;
        breakBody.innerHTML = config.body;
        breakCompleteBtn.setAttribute('data-break-type', breakType);
        
        modal.classList.remove('hidden');
        
        // Handle quiz button if it exists
        const quizBtn = document.getElementById('start-quiz-btn');
        if(quizBtn) {
            // Remove old listener and add new one
            const newQuizBtn = quizBtn.cloneNode(true);
            quizBtn.parentNode.replaceChild(newQuizBtn, quizBtn);
            newQuizBtn.addEventListener('click', function() {
                document.getElementById('break-quiz-container').classList.remove('hidden');
                generateQuickQuiz();
            });
        }
    }
    
    async function completeBreak(breakType) {
        document.getElementById('break-modal').classList.add('hidden');
        document.getElementById('break-quiz-container').classList.add('hidden');
        document.getElementById('break-quiz-container').innerHTML = '';
        
        // Resume timer
        if(sessionInterval) {
            clearInterval(sessionInterval);
        }
        sessionInterval = setInterval(updateTimer, 1000);
        
        if(window.showToast) {
            window.showToast('Break completed! Back to learning', 'success');
        }
    }
    
    document.getElementById('break-complete-btn')?.addEventListener('click', function() {
        const breakType = this.getAttribute('data-break-type');
        completeBreak(breakType);
    });
    
    async function generateQuickQuiz() {
        const topic = qs('lc-topic').value || 'Current Topic';
        const container = document.getElementById('break-quiz-container');
        container.innerHTML = 'Generating quiz...';
        
        try {
            const data = await api("{{ route('student.learning.quiz') }}", { topic, num: 1 });
            const items = Array.isArray(data.quiz) ? data.quiz : (data.quiz?.items || []);
            container.innerHTML = '';
            
            items.forEach((q,i)=>{
                const card = document.createElement('div');
                card.className='p-4 bg-white border rounded-lg mb-4';
                card.innerHTML = `<p class="font-medium mb-3">${q.question||'Question'}</p>` +
                    (q.options||[]).map((opt,idx)=>`<label class="flex items-center space-x-2 mb-2 cursor-pointer hover:bg-gray-50 p-2 rounded"><input type="radio" name="break-q${i}" value="${idx}" class="text-blue-600"> <span>${opt}</span></label>`).join('') +
                    `<button class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg break-answer-btn" data-a="${q.answerIndex}">Submit</button>`;
                container.appendChild(card);
            });
            
            container.addEventListener('click', function(e){
                if(e.target.classList.contains('break-answer-btn')){
                    const parent = e.target.closest('div');
                    const selected = parent.querySelector('input[type=radio]:checked');
                    const ans = parseInt(e.target.getAttribute('data-a'));
                    if(!selected){ 
                        alert('Please select an answer first');
                        return; 
                    }
                    const correct = parseInt(selected.value) === ans;
                    alert(correct ? 'Correct! Well done!' : 'Incorrect. The correct answer is: ' + (q.options[ans] || 'N/A'));
                    e.target.disabled = true;
                    e.target.textContent = correct ? '✓ Correct!' : '✗ Incorrect';
                    e.target.className = correct ? 'mt-3 px-4 py-2 bg-green-600 text-white rounded-lg' : 'mt-3 px-4 py-2 bg-red-600 text-white rounded-lg';
                }
            });
        } catch(e) {
            container.innerHTML = 'Failed to generate quiz.';
        }
    }
    document.querySelectorAll('.lc-format').forEach(btn=>{
        btn.addEventListener('click', async ()=>{
            document.querySelectorAll('.lc-format').forEach(b=>b.classList.remove('border-2','border-blue-500','bg-blue-50','text-blue-700'));
            btn.classList.add('border-2','border-blue-500','bg-blue-50','text-blue-700');
            const subject = qs('lc-subject').value || 'Mathematics';
            const topic = qs('lc-topic').value || 'Quadratic Equations';
            const format = btn.getAttribute('data-format');
            qs('lc-output').textContent = 'Loading...';
            try {
                const data = await api("{{ route('student.learning.explain') }}", {subject, topic, format});
                let content = data.content || 'No content';
                if (data.fallback) {
                    content += '\n\n⚠️ Using fallback content (AI service unavailable)';
                }
                qs('lc-output').textContent = content;
            } catch(e){ 
                qs('lc-output').textContent = 'Failed to load explanation. Please try again later.';
            }
        });
    });

    qs('lc-quiz-generate')?.addEventListener('click', async ()=>{
        const topic = qs('lc-topic').value || 'Quadratic Equations';
        const container = qs('lc-quiz');
        container.innerHTML = 'Generating quiz...';
        try {
            const data = await api("{{ route('student.learning.quiz') }}", { topic, num: 1 });
            const items = Array.isArray(data.quiz) ? data.quiz : (data.quiz?.items || []);
            container.innerHTML = '';
            
            // Show fallback notice if using fallback content
            if (data.fallback) {
                const notice = document.createElement('div');
                notice.className = 'mb-4 p-3 bg-yellow-100 border border-yellow-300 rounded-lg text-yellow-800 text-sm';
                notice.innerHTML = '';
                container.appendChild(notice);
            }
            
            items.forEach((q,i)=>{
                const card = document.createElement('div');
                card.className='p-4 border rounded-lg';
                card.innerHTML = `<p class="font-medium mb-2">${q.question||'Question'}</p>` +
                    (q.options||[]).map((opt,idx)=>`<label class=\"flex items-center space-x-2 mb-2\"><input type=\"radio\" name=\"q${i}\" value=\"${idx}\" class=\"text-blue-600\"> <span>${opt}</span></label>`).join('') +
                    `<button class=\"mt-2 px-3 py-1 bg-indigo-600 text-white rounded answer-btn\" data-a=\"${q.answerIndex}\">Submit Answer</button>`;
                container.appendChild(card);
            });
            
            // Add event listener for answer checking (without once: true)
            container.addEventListener('click', function(e){
                if(e.target.classList.contains('answer-btn')){
                    const parent = e.target.closest('div');
                    const selected = parent.querySelector('input[type=radio]:checked');
                    const ans = parseInt(e.target.getAttribute('data-a'));
                    if(!selected){ 
                        if(window.showToast) {
                            window.showToast('Please select an answer first', 'error', 1500);
                        } else {
                            alert('Please select an answer first');
                        }
                        return; 
                    }
                    const correct = parseInt(selected.value) === ans;
                    if(window.showToast) {
                        window.showToast(correct ? 'Correct! Well done!' : 'Incorrect. Try again!', correct ? 'success' : 'error');
                    } else {
                        alert(correct ? 'Correct! Well done!' : 'Incorrect. Try again!');
                    }
                    
                    // Disable the submit button after answering
                    e.target.disabled = true;
                    e.target.textContent = correct ? 'Correct!' : 'Incorrect';
                    e.target.className = correct ? 'mt-2 px-3 py-1 bg-green-600 text-white rounded answer-btn' : 'mt-2 px-3 py-1 bg-red-600 text-white rounded answer-btn';
                }
            });
        } catch(e){ 
            container.innerHTML = 'Failed to generate quiz. Please try again later.';
        }
    });
})();
</script>
@endpush
