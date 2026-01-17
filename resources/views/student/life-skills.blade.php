@extends('student.layouts.app')

@section('title', 'Life Skills - EduFocus')

@section('content')
<div class="space-y-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
            <span class="text-blue-600 mr-3">⚙️</span>
            Life Skills
        </h1>
        <p class="text-gray-600 mt-2">Develop essential life skills for success</p>
    </div>

    <!-- Time Management Banner -->
    <div class="bg-blue-600 text-white rounded-lg p-6 mb-8">
        <div class="flex items-center">
            <span class="text-2xl mr-3">⏰</span>
            <h2 class="text-2xl font-semibold">Time Management</h2>
        </div>
    </div>

    <!-- Time Management Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Weekly Schedule -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Weekly Schedule</h3>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-2 font-medium text-gray-700">Time</th>
                                <th class="text-left py-2 font-medium text-gray-700">Mon</th>
                                <th class="text-left py-2 font-medium text-gray-700">Tue</th>
                                <th class="text-left py-2 font-medium text-gray-700">Wed</th>
                            </tr>
                        </thead>
                        <tbody id="schedule-body">
                            <!-- Schedule data will be loaded dynamically -->
                        </tbody>
                    </table>
                </div>
                <button onclick="openAddEventModal()" class="mt-4 w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Add Event
                </button>
            </div>
        </div>

        <!-- Daily Routine Builder -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Daily Routine Builder</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Activity</label>
                        <input type="text" id="activity-input" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="e.g. Homework, Exercise">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Time</label>
                        <input type="time" id="time-input" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
                        <select id="duration-select" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="15">15 min</option>
                            <option value="30">30 min</option>
                            <option value="45">45 min</option>
                            <option value="60">1 hour</option>
                            <option value="90">1.5 hours</option>
                            <option value="120">2 hours</option>
                        </select>
                    </div>
                    <button onclick="addToRoutine()" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Add to Routine
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Life Skills Modules -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Budget Basics -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-3 bg-green-600 text-white rounded-t-lg font-semibold flex items-center">
                <span class="mr-2">💰</span>
                Budget Basics
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Monthly Allowance</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">$</span>
                            <input type="number" id="monthly-allowance" class="w-full border border-gray-300 rounded-lg pl-8 pr-3 py-2" placeholder="0">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Savings Goal</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">$</span>
                            <input type="number" id="savings-goal" class="w-full border border-gray-300 rounded-lg pl-8 pr-3 py-2" placeholder="0">
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600">Progress</span>
                            <span class="text-sm font-medium text-green-600" id="budget-progress">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 bg-green-500 rounded-full transition-all duration-500" id="budget-progress-bar" style="width: 0%"></div>
                        </div>
                    </div>
                    <button onclick="trackExpenses()" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                        Track Expenses
                    </button>
                </div>
            </div>
        </div>

        <!-- Savings Simulator -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-3 bg-yellow-600 text-white rounded-t-lg font-semibold flex items-center">
                <span class="mr-2">🏦</span>
                Savings Simulator
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Weekly Savings Amount</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">$</span>
                            <input type="number" id="weekly-savings" class="w-full border border-gray-300 rounded-lg pl-8 pr-3 py-2" placeholder="10" value="10">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">1 month:</span>
                            <span class="font-medium" id="monthly-savings">$40</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">6 months:</span>
                            <span class="font-medium" id="six-month-savings">$240</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">1 year:</span>
                            <span class="font-medium" id="yearly-savings">$520</span>
                        </div>
                    </div>
                    <button onclick="updateSavingsSimulator()" class="w-full bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors">
                        Try Different Amounts
                    </button>
                </div>
            </div>
        </div>

        <!-- Communication Scenarios -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-3 bg-blue-600 text-white rounded-t-lg font-semibold flex items-center">
                <span class="mr-2">💬</span>
                Communication Scenarios
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <span class="text-lg mr-2">👥</span>
                            <h4 class="font-medium text-gray-900">Group Project</h4>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">Practice dividing tasks fairly</p>
                        <button onclick="startCommunicationScenario('group-project')" class="w-full bg-blue-600 text-white px-3 py-2 rounded text-sm hover:bg-blue-700 transition-colors">
                            Start Simulation
                        </button>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <span class="text-lg mr-2">👨‍🏫</span>
                            <h4 class="font-medium text-gray-900">Teacher Meeting</h4>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">Ask for help with a difficult topic</p>
                        <button onclick="startCommunicationScenario('teacher-meeting')" class="w-full bg-blue-600 text-white px-3 py-2 rounded text-sm hover:bg-blue-700 transition-colors">
                            Start Simulation
                        </button>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <span class="text-lg mr-2">🏠</span>
                            <h4 class="font-medium text-gray-900">Parent Conversation</h4>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">Discuss extending curfew</p>
                        <button onclick="startCommunicationScenario('parent-conversation')" class="w-full bg-blue-600 text-white px-3 py-2 rounded text-sm hover:bg-blue-700 transition-colors">
                            Start Simulation
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Event Modal -->
<div id="add-event-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <h3 class="text-xl font-semibold mb-4">Add Event to Schedule</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Event Name</label>
                    <input type="text" id="event-name" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="e.g. Math Study">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Day</label>
                    <select id="event-day" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="monday">Monday</option>
                        <option value="tuesday">Tuesday</option>
                        <option value="wednesday">Wednesday</option>
                        <option value="thursday">Thursday</option>
                        <option value="friday">Friday</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Time</label>
                    <input type="time" id="event-time" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
                    <select id="event-duration" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="30">30 minutes</option>
                        <option value="60">1 hour</option>
                        <option value="90">1.5 hours</option>
                        <option value="120">2 hours</option>
                    </select>
                </div>
            </div>
            <div class="flex space-x-3 mt-6">
                <button onclick="closeAddEventModal()" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                    Cancel
                </button>
                <button onclick="saveEvent()" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Save Event
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Communication Scenario Modal -->
<div id="scenario-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full p-6">
            <h3 class="text-xl font-semibold mb-4" id="scenario-title">Communication Scenario</h3>
            <div id="scenario-content" class="space-y-4">
                <!-- Scenario content will be loaded dynamically -->
            </div>
            <div class="flex justify-end mt-6">
                <button onclick="closeScenarioModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Load initial data
document.addEventListener('DOMContentLoaded', function() {
    loadSchedule();
    loadBudgetData();
    updateSavingsSimulator();
});

// Schedule Management
function loadSchedule() {
    fetch('/student/life-skills/schedule')
        .then(response => response.json())
        .then(data => {
            displaySchedule(data);
        })
        .catch(error => {
            console.error('Error loading schedule:', error);
        });
}

function displaySchedule(scheduleData) {
    const tbody = document.getElementById('schedule-body');
    tbody.innerHTML = '';
    
    // Group events by time
    const timeSlots = {};
    scheduleData.forEach(event => {
        const time = event.start_time;
        if (!timeSlots[time]) {
            timeSlots[time] = { monday: '', tuesday: '', wednesday: '' };
        }
        timeSlots[time][event.day] = event.name;
    });
    
    // Display time slots
    Object.keys(timeSlots).sort().forEach(time => {
        const row = document.createElement('tr');
        row.className = 'border-b border-gray-200';
        row.innerHTML = `
            <td class="py-2 text-gray-600">${time}</td>
            <td class="py-2">${timeSlots[time].monday || '-'}</td>
            <td class="py-2">${timeSlots[time].tuesday || '-'}</td>
            <td class="py-2">${timeSlots[time].wednesday || '-'}</td>
        `;
        tbody.appendChild(row);
    });
}

function openAddEventModal() {
    document.getElementById('add-event-modal').classList.remove('hidden');
}

function closeAddEventModal() {
    document.getElementById('add-event-modal').classList.add('hidden');
}

function saveEvent() {
    const eventData = {
        name: document.getElementById('event-name').value,
        day: document.getElementById('event-day').value,
        start_time: document.getElementById('event-time').value,
        duration: document.getElementById('event-duration').value
    };
    
    if (!eventData.name || !eventData.start_time) {
        if (window.showToast) {
            window.showToast('Please fill in all required fields', 'error');
        } else {
            alert('Please fill in all required fields');
        }
        return;
    }
    
    fetch('/student/life-skills/schedule', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(eventData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeAddEventModal();
            loadSchedule();
            if (window.showToast) {
                window.showToast('Event added successfully!', 'success');
            } else {
                alert('Event added successfully!');
            }
        }
    })
    .catch(error => {
        console.error('Error saving event:', error);
    });
}

// Routine Management
function addToRoutine() {
    const routineData = {
        activity: document.getElementById('activity-input').value,
        time: document.getElementById('time-input').value,
        duration: document.getElementById('duration-select').value
    };
    
    if (!routineData.activity || !routineData.time) {
        if (window.showToast) {
            window.showToast('Please fill in all required fields', 'error');
        } else {
            alert('Please fill in all required fields');
        }
        return;
    }
    
    fetch('/student/life-skills/routine', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(routineData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('activity-input').value = '';
            document.getElementById('time-input').value = '';
            if (window.showToast) {
                window.showToast('Activity added to routine!', 'success');
            } else {
                alert('Activity added to routine!');
            }
        }
    })
    .catch(error => {
        console.error('Error adding to routine:', error);
    });
}

// Budget Management
function loadBudgetData() {
    fetch('/student/life-skills/budget')
        .then(response => response.json())
        .then(data => {
            document.getElementById('monthly-allowance').value = data.monthly_allowance || 0;
            document.getElementById('savings-goal').value = data.savings_goal || 0;
            updateBudgetProgress();
        })
        .catch(error => {
            console.error('Error loading budget data:', error);
        });
}

function updateBudgetProgress() {
    const allowance = parseFloat(document.getElementById('monthly-allowance').value) || 0;
    const goal = parseFloat(document.getElementById('savings-goal').value) || 0;
    
    if (goal > 0) {
        const progress = Math.min(100, (allowance / goal) * 100);
        document.getElementById('budget-progress').textContent = Math.round(progress) + '%';
        document.getElementById('budget-progress-bar').style.width = progress + '%';
    } else {
        document.getElementById('budget-progress').textContent = '0%';
        document.getElementById('budget-progress-bar').style.width = '0%';
    }
}

function trackExpenses() {
    const allowance = parseFloat(document.getElementById('monthly-allowance').value) || 0;
    const goal = parseFloat(document.getElementById('savings-goal').value) || 0;
    
    if (allowance <= 0 || goal <= 0) {
        if (window.showToast) {
            window.showToast('Please set your allowance and savings goal first', 'error');
        } else {
            alert('Please set your allowance and savings goal first');
        }
        return;
    }
    
    // Save budget data
    fetch('/student/life-skills/budget', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            monthly_allowance: allowance,
            savings_goal: goal
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (window.showToast) {
                window.showToast('Budget data saved!', 'success');
            } else {
                alert('Budget data saved!');
            }
        }
    })
    .catch(error => {
        console.error('Error saving budget data:', error);
    });
}

// Savings Simulator
function updateSavingsSimulator() {
    const weeklyAmount = parseFloat(document.getElementById('weekly-savings').value) || 0;
    
    const monthly = weeklyAmount * 4;
    const sixMonth = weeklyAmount * 26;
    const yearly = weeklyAmount * 52;
    
    document.getElementById('monthly-savings').textContent = '$' + monthly;
    document.getElementById('six-month-savings').textContent = '$' + sixMonth;
    document.getElementById('yearly-savings').textContent = '$' + yearly;
}

// Communication Scenarios
function startCommunicationScenario(scenarioType) {
    const scenarios = {
        'group-project': {
            title: 'Group Project Communication',
            content: `
                <div class="space-y-4">
                    <p class="text-gray-700">You're working on a group project with three other students. One member isn't contributing equally.</p>
                    <div class="space-y-3">
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <h4 class="font-medium text-blue-900">Scenario:</h4>
                            <p class="text-sm text-blue-800">The project is due in 2 weeks, but Sarah hasn't completed her assigned research section.</p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-lg">
                            <h4 class="font-medium text-green-900">Your Task:</h4>
                            <p class="text-sm text-green-800">Practice having a constructive conversation with Sarah about her responsibilities.</p>
                        </div>
                        <div class="p-3 bg-yellow-50 rounded-lg">
                            <h4 class="font-medium text-yellow-900">Key Points:</h4>
                            <ul class="text-sm text-yellow-800 list-disc list-inside">
                                <li>Be specific about what's needed</li>
                                <li>Offer help and support</li>
                                <li>Set clear deadlines</li>
                                <li>Maintain a positive tone</li>
                            </ul>
                        </div>
                    </div>
                </div>
            `
        },
        'teacher-meeting': {
            title: 'Teacher Meeting Communication',
            content: `
                <div class="space-y-4">
                    <p class="text-gray-700">You're struggling with a math concept and need to ask your teacher for help.</p>
                    <div class="space-y-3">
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <h4 class="font-medium text-blue-900">Scenario:</h4>
                            <p class="text-sm text-blue-800">You've been trying to understand quadratic equations for a week but still can't solve the problems.</p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-lg">
                            <h4 class="font-medium text-green-900">Your Task:</h4>
                            <p class="text-sm text-green-800">Practice asking your teacher for help in a clear and respectful way.</p>
                        </div>
                        <div class="p-3 bg-yellow-50 rounded-lg">
                            <h4 class="font-medium text-yellow-900">Key Points:</h4>
                            <ul class="text-sm text-yellow-800 list-disc list-inside">
                                <li>Be specific about what you don't understand</li>
                                <li>Show that you've tried to figure it out</li>
                                <li>Ask for examples or different explanations</li>
                                <li>Thank them for their time</li>
                            </ul>
                        </div>
                    </div>
                </div>
            `
        },
        'parent-conversation': {
            title: 'Parent Conversation Communication',
            content: `
                <div class="space-y-4">
                    <p class="text-gray-700">You want to discuss extending your curfew for a special event this weekend.</p>
                    <div class="space-y-3">
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <h4 class="font-medium text-blue-900">Scenario:</h4>
                            <p class="text-sm text-blue-800">Your friend is having a birthday party that ends at 11 PM, but your curfew is 10 PM.</p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-lg">
                            <h4 class="font-medium text-green-900">Your Task:</h4>
                            <p class="text-sm text-green-800">Practice having a mature conversation with your parents about extending your curfew.</p>
                        </div>
                        <div class="p-3 bg-yellow-50 rounded-lg">
                            <h4 class="font-medium text-yellow-900">Key Points:</h4>
                            <ul class="text-sm text-yellow-800 list-disc list-inside">
                                <li>Explain the situation clearly</li>
                                <li>Show responsibility and maturity</li>
                                <li>Offer compromises or solutions</li>
                                <li>Respect their decision</li>
                            </ul>
                        </div>
                    </div>
                </div>
            `
        }
    };
    
    const scenario = scenarios[scenarioType];
    document.getElementById('scenario-title').textContent = scenario.title;
    document.getElementById('scenario-content').innerHTML = scenario.content;
    document.getElementById('scenario-modal').classList.remove('hidden');
}

function closeScenarioModal() {
    document.getElementById('scenario-modal').classList.add('hidden');
}

// Event listeners for real-time updates
document.getElementById('monthly-allowance').addEventListener('input', updateBudgetProgress);
document.getElementById('savings-goal').addEventListener('input', updateBudgetProgress);
document.getElementById('weekly-savings').addEventListener('input', updateSavingsSimulator);
</script>
@endpush
