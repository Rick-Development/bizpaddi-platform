@extends('teamportal::layouts.app')

@section('title', 'Team Dashboard')

@section('content')
<div class="dashboard-wrapper space-y-8 animate-fade-in">
    <!-- Blinking Overdue Tasks Reminder Banner -->
    <div id="overdue-tasks-reminder" class="hidden">
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-2xl shadow-md flex items-center justify-between animate-background-flash">
            <div class="flex items-center gap-3">
                <span class="relative flex h-3.5 w-3.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-red-600 animate-intense-blink"></span>
                </span>
                <div class="text-sm text-red-700">
                    <span class="font-extrabold uppercase tracking-wide text-xs bg-red-100 px-2 py-0.5 rounded-md mr-2">Action Required</span>
                    You have <span id="overdue-count-badge" class="font-extrabold underline">0</span> overdue task(s). Please review and complete them!
                </div>
            </div>
            <a href="{{ route('team.tasks.index') }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-bold transition-all shadow-md active:scale-95">
                View Tasks &rarr;
            </a>
        </div>
    </div>

    <!-- Blinking Upcoming Tasks Reminder Banner (Due within 48h) -->
    <div id="upcoming-tasks-reminder" class="hidden">
        <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-2xl shadow-md flex items-center justify-between animate-yellow-flash">
            <div class="flex items-center gap-3">
                <span class="relative flex h-3.5 w-3.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-amber-600 animate-intense-blink"></span>
                </span>
                <div class="text-sm text-amber-700">
                    <span class="font-extrabold uppercase tracking-wide text-xs bg-amber-100 px-2 py-0.5 rounded-md mr-2">Warning</span>
                    You have <span id="upcoming-count-badge" class="font-extrabold underline">0</span> task(s) due within the next 48 hours. Please plan accordingly!
                </div>
            </div>
            <a href="{{ route('team.tasks.index') }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold transition-all shadow-md active:scale-95">
                View Tasks &rarr;
            </a>
        </div>
    </div>
    <!-- Welcome Header -->
    <div class="welcome-banner relative overflow-hidden rounded-3xl bg-brand-secondary p-8 text-white shadow-2xl flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div class="relative z-10">
            <h1 class="text-4xl font-extrabold tracking-tight">Focus on Project Delivery, {{ auth()->user()->name }}!</h1>
            <p class="mt-2 text-brand-primary font-bold uppercase tracking-widest text-sm">Team Sentinel Dashboard</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 relative z-10">
            <!-- Overdue Badge inside Welcome Card -->
            <div id="card-overdue-badge" class="hidden shrink-0">
                <div class="flex items-center gap-2 bg-red-50 border border-red-500/50 rounded-2xl px-4 py-2 shadow-lg backdrop-blur-sm animate-background-flash">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600 animate-intense-blink"></span>
                    </span>
                    <span class="text-xs font-extrabold uppercase tracking-wider text-red-700">
                        <span id="card-overdue-count" class="text-red-950 text-sm font-black underline mr-1">0</span> Overdue
                    </span>
                </div>
            </div>
            
            <!-- Upcoming Badge inside Welcome Card -->
            <div id="card-upcoming-badge" class="hidden shrink-0">
                <div class="flex items-center gap-2 bg-amber-50 border border-amber-500/50 rounded-2xl px-4 py-2 shadow-lg backdrop-blur-sm animate-yellow-flash">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500 animate-intense-blink"></span>
                    </span>
                    <span class="text-xs font-extrabold uppercase tracking-wider text-amber-700">
                        <span id="card-upcoming-count" class="text-amber-950 text-sm font-black underline mr-1">0</span> Due Soon
                    </span>
                </div>
            </div>
        </div>
        <div class="absolute -right-10 -top-10 h-64 w-64 rounded-full bg-brand-primary/10 blur-3xl"></div>
        <div class="absolute -left-10 -bottom-10 h-48 w-48 rounded-full bg-white/5 blur-2xl"></div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <div class="stat-card group relative overflow-hidden rounded-3xl bg-white p-6 shadow-sm shadow-brand-secondary/5 transition-all hover:-translate-y-1 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-wider">Pending Tasks</p>
                    <h3 class="mt-1 text-3xl font-black text-brand-secondary">{{ $stats['pending_tasks'] }}</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-primary/10 text-brand-primary group-hover:bg-brand-primary group-hover:text-white transition-all">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <div class="mt-4 h-1.5 w-full rounded-full bg-gray-100">
                <div class="h-full rounded-full bg-brand-primary" style="width: {{ $stats['total_tasks'] > 0 ? ($stats['pending_tasks'] / $stats['total_tasks'] * 100) : 0 }}%"></div>
            </div>
        </div>

        <div class="stat-card group relative overflow-hidden rounded-3xl bg-white p-6 shadow-sm shadow-brand-secondary/5 transition-all hover:-translate-y-1 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-wider">Completed</p>
                    <h3 class="mt-1 text-3xl font-black text-green-600">{{ $stats['completed_tasks'] }}</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-green-100 text-green-600 group-hover:bg-green-600 group-hover:text-white transition-all">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <div class="mt-4 h-1.5 w-full rounded-full bg-gray-100">
                <div class="h-full rounded-full bg-green-500" style="width: {{ $stats['total_tasks'] > 0 ? ($stats['completed_tasks'] / $stats['total_tasks'] * 100) : 0 }}%"></div>
            </div>
        </div>

        <div class="stat-card group relative overflow-hidden rounded-3xl bg-white p-6 shadow-sm shadow-brand-secondary/5 transition-all hover:-translate-y-1 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-wider">Total Output</p>
                    <h3 class="mt-1 text-3xl font-black text-brand-secondary">{{ $stats['total_tasks'] }}</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-secondary/10 text-brand-secondary group-hover:bg-brand-secondary group-hover:text-white transition-all">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <span class="inline-flex items-center rounded-md bg-brand-secondary/5 px-2 py-1 text-xs font-bold text-brand-secondary">Core Efficiency</span>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="chart-container rounded-3xl bg-white p-8 shadow-sm border border-gray-100">
            <div class="mb-6 flex items-center justify-between">
                <h3 class="text-lg font-bold text-brand-secondary">Task Allocation</h3>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-tighter">By Status</span>
            </div>
            <div class="relative h-[300px]">
                <canvas id="taskDistributionChart"></canvas>
            </div>
        </div>

        <div class="chart-container rounded-3xl bg-white p-8 shadow-sm border border-gray-100">
            <div class="mb-6 flex items-center justify-between">
                <h3 class="text-lg font-bold text-brand-secondary">Performance Velocity</h3>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-tighter">7-Day Completion Trend</span>
            </div>
            <div class="relative h-[300px]">
                <canvas id="completionTrendChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="overflow-hidden rounded-3xl bg-white shadow-sm border border-gray-100">
        <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50/50 px-8 py-6">
            <div>
                <h3 class="text-xl font-black text-brand-secondary">Mission Pipeline</h3>
                <p class="text-sm font-bold text-gray-400">Next critical actions</p>
            </div>
            <a href="{{ route('team.tasks.index') }}" class="group flex items-center gap-2 rounded-xl bg-brand-primary px-4 py-2 text-sm font-bold text-white transition-all hover:bg-brand-secondary shadow-lg shadow-brand-primary/20">
                Operational Log
                <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </a>
        </div>
        <div class="px-8 pb-8 pt-4">
            <div class="space-y-4">
                @forelse($recentTasks as $task)
                <div class="task-row group flex flex-col items-start gap-4 rounded-2xl border border-gray-50 bg-gray-50/30 p-4 transition-all hover:border-brand-primary/20 hover:bg-white hover:shadow-md sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white font-bold text-brand-secondary shadow-sm">
                            {{ substr($task->title, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-brand-secondary group-hover:text-brand-primary transition-colors">{{ $task->title }}</h4>
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-bold text-gray-400">{{ $task->project->name ?? 'Direct Mission' }}</span>
                                @if($task->due_date)
                                <span class="flex items-center gap-1 text-[10px] font-black uppercase text-brand-primary {{ $task->due_date->isPast() ? 'text-red-600' : '' }}">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    {{ $task->due_date->format('M d, Y \a\t g:i A') }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 self-end sm:self-auto">
                        @php
                            $statusLabels = [
                                'todo' => 'To Do',
                                'in_progress' => 'In Progress',
                                'review' => 'Done',
                                'done' => 'Completed',
                            ];
                        @endphp
                        <span class="inline-flex rounded-lg px-3 py-1 text-[10px] font-black uppercase tracking-widest
                            {{ $task->status === 'done' ? 'bg-green-100 text-green-700' : 
                               ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : 
                               ($task->status === 'review' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600')) }}">
                            {{ $statusLabels[$task->status] ?? str_replace('_', ' ', $task->status) }}
                        </span>
                        <a href="{{ route('team.tasks.show', $task) }}" class="rounded-lg p-2 text-gray-400 hover:bg-brand-primary/10 hover:text-brand-primary transition-all">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="mb-4 rounded-full bg-gray-50 p-6">
                        <svg class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                    </div>
                    <h5 class="text-lg font-bold text-gray-400">Clear Skies! No active tasks found.</h5>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes backgroundFlash {
        0%, 100% { background-color: #FEF2F2; border-color: #FCA5A5; box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.1); }
        50% { background-color: #FEE2E2; border-color: #EF4444; box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.25); }
    }
    @keyframes intenseBlink {
        0%, 100% { opacity: 0.15; transform: scale(0.85); }
        50% { opacity: 1; transform: scale(1.15); }
    }
    @keyframes yellowFlash {
        0%, 100% { background-color: #FEF3C7; border-color: #FCD34D; box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.1); }
        50% { background-color: #FFFBEB; border-color: #F59E0B; box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.25); }
    }
    .animate-fade-in {
        animation: fadeIn 0.6s ease-out forwards;
    }
    .animate-background-flash {
        animation: backgroundFlash 1s infinite ease-in-out;
    }
    .animate-yellow-flash {
        animation: yellowFlash 1.5s infinite ease-in-out;
    }
    .animate-intense-blink {
        animation: intenseBlink 0.6s infinite ease-in-out;
    }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Task Distribution Chart
    const distCtx = document.getElementById('taskDistributionChart').getContext('2d');
    new Chart(distCtx, {
        type: 'doughnut',
        data: {
            labels: @json($taskDistribution['labels']),
            datasets: [{
                data: @json($taskDistribution['data']),
                backgroundColor: ['#94a3b8', '#3b82f6', '#f59e0b', '#22c55e'],
                borderWidth: 0,
                hoverOffset: 20
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: { weight: 'bold', size: 11 }
                    }
                }
            }
        }
    });

    // Completion Trend Chart
    const trendCtx = document.getElementById('completionTrendChart').getContext('2d');
    const gradient = trendCtx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(251, 76, 75, 0.4)');
    gradient.addColorStop(1, 'rgba(251, 76, 75, 0)');

    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: @json($completionTrend['labels']),
            datasets: [{
                label: 'Tasks Completed',
                data: @json($completionTrend['data']),
                borderColor: '#0E5E4A',
                backgroundColor: gradient,
                borderWidth: 4,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#0E5E4A',
                pointBorderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f8fafc' },
                    ticks: { font: { weight: 'bold' } }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { weight: 'bold' } }
                }
            }
        }
    });

    // Fetch overdue and upcoming tasks count via AJAX
    fetch('{{ route("team.api.overdue-tasks-count") }}')
        .then(response => response.json())
        .then(data => {
            if (data.overdue_count > 0) {
                document.getElementById('overdue-count-badge').textContent = data.overdue_count;
                document.getElementById('overdue-tasks-reminder').classList.remove('hidden');
                
                // Show inside Welcome Header Card
                document.getElementById('card-overdue-count').textContent = data.overdue_count;
                document.getElementById('card-overdue-badge').classList.remove('hidden');
            }
            if (data.upcoming_count > 0) {
                document.getElementById('upcoming-count-badge').textContent = data.upcoming_count;
                document.getElementById('upcoming-tasks-reminder').classList.remove('hidden');
                
                // Show inside Welcome Header Card
                document.getElementById('card-upcoming-count').textContent = data.upcoming_count;
                document.getElementById('card-upcoming-badge').classList.remove('hidden');
            }
        })
        .catch(error => console.error('Error fetching overdue tasks:', error));
});
</script>
@endpush
@endsection
