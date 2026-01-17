@extends('parent.layouts.app')

@section('title', ($selectedStudent?->name ?? 'Student')."'s Progress")
@section('page_title', ($selectedStudent?->name ?? 'Student')."'s Progress")

@section('content')
<div class="space-y-6">
	<!-- Student Selector and Filters -->
	<div class="flex items-center justify-between flex-wrap gap-4">
		<div class="flex items-center gap-4">
			@if($students->count() > 1)
			<select id="student-selector" class="rounded-lg border border-gray-300 px-4 py-2 text-sm bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
				@foreach($students as $student)
				<option value="{{ $student->id }}" {{ $selectedStudent && $selectedStudent->id === $student->id ? 'selected' : '' }}>
					{{ $student->name }}
				</option>
				@endforeach
			</select>
			@elseif($selectedStudent)
			<h2 class="text-xl font-semibold text-gray-900">{{ $selectedStudent->name }}'s Progress</h2>
			@else
			<h2 class="text-xl font-semibold text-gray-900">No student linked</h2>
			@endif
		</div>
		
		<div class="flex items-center gap-2">
			<div class="flex items-center gap-2 border rounded-lg p-1">
				<button data-period="weekly" class="period-btn rounded-lg px-3 py-2 text-xs font-medium transition-colors bg-gray-800 text-white">Weekly</button>
				<button data-period="monthly" class="period-btn rounded-lg px-3 py-2 text-xs font-medium transition-colors text-gray-600 hover:bg-gray-100">Monthly</button>
				<button data-period="yearly" class="period-btn rounded-lg px-3 py-2 text-xs font-medium transition-colors text-gray-600 hover:bg-gray-100">Yearly</button>
			</div>
			<button id="export-btn" class="rounded-lg bg-blue-600 text-white px-3 py-2 text-xs font-medium hover:bg-blue-700 transition-colors">Export</button>
		</div>
	</div>
	
	<!-- Subject Filter -->
	@if(!empty($subjects))
	<div class="flex items-center gap-2">
		<button data-subject="all" class="subject-filter inline-flex items-center gap-2 rounded-lg border px-3 py-2 text-sm font-medium text-gray-700 bg-white border-blue-500 text-blue-600">
			<span>Overview</span>
		</button>
		@foreach($subjects as $subject)
		<button data-subject="{{ strtolower($subject) }}" class="subject-filter inline-flex items-center gap-2 rounded-lg border px-3 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50">
			<span>{{ $subject }}</span>
		</button>
		@endforeach
	</div>
	@endif

	<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
		<div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-gray-100">
			<h2 class="font-semibold mb-4 flex items-center gap-2"><span class="text-lg">Focus Time Trend</span></h2>
			<div class="relative h-56">
				<canvas id="focusChart" class="absolute inset-0 w-full h-full"></canvas>
			</div>
			<div class="mt-4 grid grid-cols-3 text-sm text-gray-700">
					<div>
						<div class="text-gray-500">Current Week</div>
						<div class="font-semibold">{{ number_format($currentWeekHours, 1) }}h</div>
					</div>
					<div>
						<div class="text-gray-500">Weekly Avg.</div>
						<div class="font-semibold">{{ number_format($weeklyAvgHours, 1) }}h</div>
					</div>
					<div>
						<div class="text-gray-500">Change</div>
						<div class="font-semibold {{ $changePercent >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $changePercent >= 0 ? '+' : '' }}{{ $changePercent }}%</div>
					</div>
				</div>
			</div>
		</div>
		<div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-gray-100">
			<h2 class="font-semibold mb-4 flex items-center gap-2"><span class="text-lg">Mood & Wellbeing</span></h2>
			<div class="relative h-56">
				<canvas id="moodChart" class="absolute inset-0 w-full h-full"></canvas>
			</div>
			<div class="mt-4 grid grid-cols-3 text-sm text-gray-700">
					<div>
						<div class="text-gray-500">Positive Days</div>
						<div class="font-semibold">{{ $positiveDays }}/30</div>
					</div>
					<div>
						<div class="text-gray-500">Common Mood</div>
						<div class="font-semibold">{{ $commonMood ?? 'N/A' }}</div>
					</div>
					<div>
						<div class="text-gray-500">Stress Level</div>
						<div class="font-semibold {{ $stressLevel === 'High' ? 'text-red-600' : ($stressLevel === 'Medium' ? 'text-amber-600' : 'text-green-600') }}">
							{{ $stressLevel ?? 'N/A' }}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>

<div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-gray-100">
	<h2 class="font-semibold mb-4 flex items-center gap-2 text-xl">Assignment Completion</h2>
	<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
		<div class="lg:col-span-2">
			<div class="overflow-hidden rounded-xl border border-gray-100">
				<table id="assignment-table" class="min-w-full divide-y divide-gray-100 text-sm">
					<thead class="bg-gray-50">
						<tr class="text-left text-gray-600">
							<th class="px-4 py-3 font-medium">Subject</th>
							<th class="px-4 py-3 font-medium">Completed</th>
							<th class="px-4 py-3 font-medium">Avg. Score</th>
							<th class="px-4 py-3 font-medium">Feedback</th>
							<th class="px-4 py-3 font-medium">Status</th>
						</tr>
					</thead>
					<tbody class="divide-y divide-gray-100 bg-white">
						@forelse(($assignmentRows ?? []) as $row)
						<tr>
							<td class="px-4 py-3 text-gray-800">{{ $row['subject'] }}</td>
							<td class="px-4 py-3">{{ $row['completed'] }}</td>
							<td class="px-4 py-3">{{ $row['avg'] }}%</td>
							<td class="px-4 py-3 text-gray-700">"{{ $row['feedback'] }}"</td>
							<td class="px-4 py-3">
								@php $status=$row['status']; @endphp
								<span class="px-3 py-1 rounded-full text-xs font-medium
								{{ $status==='Excelling' ? 'bg-emerald-100 text-emerald-700' : ($status==='On Track' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700') }}">{{ $status }}</span>
							</td>
						</tr>
						@empty
						<tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">No assignment data</td></tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
		<div class="flex items-center justify-center">
			<canvas id="assignmentPie" class="w-64 h-64"></canvas>
		</div>
	</div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function() {
    let focusChart, moodChart, pieChart;
    let currentPeriod = 'weekly';
    let currentSubject = 'all';
    
    // Store assignment data from server for export
    const assignmentData = @json($assignmentRows ?? []);
    
    // Initialize charts
    function initCharts() {
        const days = @json($days);
        const focus = @json($focusMinutesByDay);
        const moodLabels = Object.keys(@json($moodStats));
        const moodData = Object.values(@json($moodStats));
        const pieData = @json($assignmentPie ?? ['Completed'=>0,'Remaining'=>0,'Overdue'=>0]);

        // Focus Chart
        const focusCtx = document.getElementById('focusChart');
        if (focusCtx) {
            if (focusChart) focusChart.destroy();
            focusChart = new Chart(focusCtx, {
                type: 'line',
                data: {
                    labels: days,
                    datasets: [{
                        label: 'Minutes',
                        data: focus,
                        borderColor: '#6366F1',
                        backgroundColor: 'rgba(99,102,241,0.12)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true } },
                    plugins: { legend: { display: false } }
                }
            });
        }

        // Mood Chart
        const moodCtx = document.getElementById('moodChart');
        if (moodCtx) {
            if (moodChart) moodChart.destroy();
            moodChart = new Chart(moodCtx, {
                type: 'bar',
                data: {
                    labels: moodLabels,
                    datasets: [{
                        data: moodData,
                        backgroundColor: ['#10B981','#4F46E5','#F59E0B','#EF4444','#06B6D4']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            });
        }

        // Pie Chart
        const pieCtx = document.getElementById('assignmentPie');
        if (pieCtx) {
            if (pieChart) pieChart.destroy();
            pieChart = new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: Object.keys(pieData),
                    datasets: [{
                        data: Object.values(pieData),
                        backgroundColor: ['#3B82F6','#FBBF24','#F87171']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }
    }
    
    // Student selector
    const studentSelector = document.getElementById('student-selector');
    if (studentSelector) {
        studentSelector.addEventListener('change', function() {
            const studentId = this.value;
            window.location.href = '{{ route("parent.progress") }}?student_id=' + studentId;
        });
    }
    
    // Period buttons
    document.querySelectorAll('.period-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            currentPeriod = this.getAttribute('data-period');
            document.querySelectorAll('.period-btn').forEach(b => {
                b.classList.remove('bg-gray-800', 'text-white');
                b.classList.add('text-gray-600');
            });
            this.classList.add('bg-gray-800', 'text-white');
            this.classList.remove('text-gray-600');
            
            // Reload data for selected period (for now just show alert, can be enhanced with API)
            alert('Period filter: ' + currentPeriod + '. This will filter data by ' + currentPeriod + ' period.');
        });
    });
    
    // Subject filter buttons
    document.querySelectorAll('.subject-filter').forEach(btn => {
        btn.addEventListener('click', function() {
            currentSubject = this.getAttribute('data-subject');
            document.querySelectorAll('.subject-filter').forEach(b => {
                b.classList.remove('border-blue-500', 'text-blue-600', 'bg-white');
                b.classList.add('text-gray-500');
            });
            this.classList.add('border-blue-500', 'text-blue-600', 'bg-white');
            this.classList.remove('text-gray-500');
            
            // Filter assignment table
            filterAssignments(currentSubject);
        });
    });
    
    // Filter assignments by subject
    function filterAssignments(subject) {
        const rows = document.querySelectorAll('#assignment-table tbody tr');
        rows.forEach(row => {
            if (subject === 'all') {
                row.style.display = '';
            } else {
                const subjectCell = row.querySelector('td:first-child');
                if (subjectCell) {
                    const rowSubject = subjectCell.textContent.trim().toLowerCase();
                    row.style.display = rowSubject === subject ? '' : 'none';
                }
            }
        });
    }
    
    // Export button
    const exportBtn = document.getElementById('export-btn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            // Create CSV data from server-side data
            const csvData = [];
            csvData.push(['Subject', 'Completed', 'Avg Score', 'Feedback', 'Status']);
            
            // Filter data based on current subject filter
            let dataToExport = assignmentData;
            if (currentSubject !== 'all') {
                dataToExport = assignmentData.filter(row => 
                    row.subject.toLowerCase() === currentSubject
                );
            }
            
            // Add rows from server data
            dataToExport.forEach(row => {
                csvData.push([
                    row.subject || '',
                    row.completed || '0/0',
                    (row.avg || 0) + '%',
                    row.feedback || '',
                    row.status || ''
                ]);
            });
            
            // If no data, show a helpful message
            if (csvData.length === 1) {
                // Don't add empty row, just show headers - the user will see it's empty
                // This is better than showing "No assignment data available" in the export
            }
            
            // Convert to CSV string
            const csv = csvData.map(row => 
                row.map(cell => {
                    // Escape quotes and wrap in quotes if contains comma, quote, or newline
                    const cellStr = String(cell || '').replace(/"/g, '""');
                    return `"${cellStr}"`;
                }).join(',')
            ).join('\n');
            
            // Add BOM for Excel compatibility
            const BOM = '\uFEFF';
            const blob = new Blob([BOM + csv], { type: 'text/csv;charset=utf-8;' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'progress-export-' + new Date().toISOString().split('T')[0] + '.csv';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        });
    }
    
    // Initialize on load
    initCharts();
})();
</script>
@endpush
@endsection


