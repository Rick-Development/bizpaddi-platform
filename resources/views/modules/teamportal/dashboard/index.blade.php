@extends('teamportal::layouts.app')

@section('title', 'Team Dashboard')

@section('content')
<div class="dashboard-wrapper space-y-8 animate-fade-in">
    <!-- Blinking Overdue Tasks Reminder Banner -->
    <div id="overdue-tasks-reminder" class="hidden">
        <div class="bg-red-950/40 border border-red-500/50 p-4 rounded-2xl shadow-xl flex items-center justify-between backdrop-blur-md">
            <div class="flex items-center gap-3">
                <span class="relative flex h-3.5 w-3.5 shrink-0">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-red-500"></span>
                </span>
                <div class="text-sm text-red-200">
                    <span class="font-extrabold uppercase tracking-wide text-xs bg-red-500/20 text-red-300 border border-red-500/30 px-2.5 py-0.5 rounded-md mr-2">Action Required</span>
                    You have <span id="overdue-count-badge" class="font-black text-white underline">0</span> overdue task(s). Please review and complete them!
                </div>
            </div>
            <a href="{{ route('team.tasks.index') }}" class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white rounded-xl text-xs font-bold transition-all shadow-lg active:scale-95 shrink-0">
                View Tasks &rarr;
            </a>
        </div>
    </div>

    <!-- Blinking Upcoming Tasks Reminder Banner (Due within 48h) -->
    <div id="upcoming-tasks-reminder" class="hidden">
        <div class="bg-amber-950/40 border border-amber-500/50 p-4 rounded-2xl shadow-xl flex items-center justify-between backdrop-blur-md">
            <div class="flex items-center gap-3">
                <span class="relative flex h-3.5 w-3.5 shrink-0">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-amber-500"></span>
                </span>
                <div class="text-sm text-amber-200">
                    <span class="font-extrabold uppercase tracking-wide text-xs bg-amber-500/20 text-amber-300 border border-amber-500/30 px-2.5 py-0.5 rounded-md mr-2">Warning</span>
                    You have <span id="upcoming-count-badge" class="font-black text-white underline">0</span> task(s) due within the next 48 hours. Please plan accordingly!
                </div>
            </div>
            <a href="{{ route('team.tasks.index') }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white rounded-xl text-xs font-bold transition-all shadow-lg active:scale-95 shrink-0">
                View Tasks &rarr;
            </a>
        </div>
    </div>

    <!-- Welcome Header -->
    <div class="welcome-banner relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#10231C] via-[#0d221b] to-[#08110e] border border-white/10 p-6 sm:p-8 text-white shadow-2xl flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div class="relative z-10 max-w-2xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold mb-3">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Team Sentinel Dashboard
            </div>
            <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white">Focus on Project Delivery, {{ auth()->user()->name }}!</h1>
            <p class="mt-2 text-sm text-gray-300">Track your assigned milestones, monitor performance velocity, and complete deliverables seamlessly.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 relative z-10">
            <!-- Overdue Badge inside Welcome Card -->
            <div id="card-overdue-badge" class="hidden shrink-0">
                <div class="flex items-center gap-2 bg-red-500/10 border border-red-500/30 rounded-2xl px-4 py-2 shadow-lg backdrop-blur-sm">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                    </span>
                    <span class="text-xs font-bold uppercase tracking-wider text-red-300">
                        <span id="card-overdue-count" class="text-white text-sm font-black mr-1">0</span> Overdue
                    </span>
                </div>
            </div>
            
            <!-- Upcoming Badge inside Welcome Card -->
            <div id="card-upcoming-badge" class="hidden shrink-0">
                <div class="flex items-center gap-2 bg-amber-500/10 border border-amber-500/30 rounded-2xl px-4 py-2 shadow-lg backdrop-blur-sm">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
                    </span>
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-300">
                        <span id="card-upcoming-count" class="text-white text-sm font-black mr-1">0</span> Due Soon
                    </span>
                </div>
            </div>

            <!-- Quick Action button -->
            <a href="{{ route('team.tasks.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs uppercase tracking-wider transition-all shadow-lg shadow-emerald-950/50 hover:shadow-emerald-600/30 active:scale-95">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                My Tasks
            </a>
        </div>
        <div class="absolute -right-10 -top-10 h-64 w-64 rounded-full bg-emerald-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-10 -bottom-10 h-48 w-48 rounded-full bg-emerald-600/5 blur-2xl pointer-events-none"></div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <!-- Pending Tasks -->
        <div class="stat-card group relative overflow-hidden rounded-3xl bg-[#10231C]/60 backdrop-blur-sm border border-white/10 p-6 shadow-xl transition-all duration-300 hover:-translate-y-1 hover:border-emerald-500/30">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pending Tasks</p>
                    <h3 class="mt-2 text-3xl font-black text-white">{{ $stats['pending_tasks'] }}</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 group-hover:scale-110 transition-transform">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <div class="mt-5">
                <div class="flex items-center justify-between text-xs text-gray-400 mb-1.5">
                    <span>Work In Queue</span>
                    <span class="font-bold text-white">{{ $stats['total_tasks'] > 0 ? round(($stats['pending_tasks'] / $stats['total_tasks']) * 100) : 0 }}%</span>
                </div>
                <div class="h-1.5 w-full rounded-full bg-white/5 overflow-hidden">
                    <div class="h-full rounded-full bg-gradient-to-r from-amber-500 to-amber-400 transition-all duration-500" style="width: {{ $stats['total_tasks'] > 0 ? ($stats['pending_tasks'] / $stats['total_tasks'] * 100) : 0 }}%"></div>
                </div>
            </div>
        </div>

        <!-- Completed Tasks -->
        <div class="stat-card group relative overflow-hidden rounded-3xl bg-[#10231C]/60 backdrop-blur-sm border border-white/10 p-6 shadow-xl transition-all duration-300 hover:-translate-y-1 hover:border-emerald-500/30">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Completed</p>
                    <h3 class="mt-2 text-3xl font-black text-emerald-400">{{ $stats['completed_tasks'] }}</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 group-hover:scale-110 transition-transform">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <div class="mt-5">
                <div class="flex items-center justify-between text-xs text-gray-400 mb-1.5">
                    <span>Completion Rate</span>
                    <span class="font-bold text-emerald-400">{{ $stats['total_tasks'] > 0 ? round(($stats['completed_tasks'] / $stats['total_tasks']) * 100) : 0 }}%</span>
                </div>
                <div class="h-1.5 w-full rounded-full bg-white/5 overflow-hidden">
                    <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-emerald-400 transition-all duration-500" style="width: {{ $stats['total_tasks'] > 0 ? ($stats['completed_tasks'] / $stats['total_tasks'] * 100) : 0 }}%"></div>
                </div>
            </div>
        </div>

        <!-- Total Output -->
        <div class="stat-card group relative overflow-hidden rounded-3xl bg-[#10231C]/60 backdrop-blur-sm border border-white/10 p-6 shadow-xl transition-all duration-300 hover:-translate-y-1 hover:border-emerald-500/30">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Output</p>
                    <h3 class="mt-2 text-3xl font-black text-white">{{ $stats['total_tasks'] }}</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 group-hover:scale-110 transition-transform">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
            </div>
            <div class="mt-5 flex items-center justify-between">
                <span class="inline-flex items-center gap-1.5 rounded-lg bg-white/5 border border-white/10 px-2.5 py-1 text-xs font-medium text-gray-300">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    Core Efficiency
                </span>
                <span class="text-xs text-gray-400">All-time assignments</span>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Task Allocation -->
        <div class="chart-container rounded-3xl bg-[#10231C]/60 backdrop-blur-sm border border-white/10 p-6 lg:p-8 shadow-xl">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-white">Task Allocation</h3>
                    <p class="text-xs font-semibold text-emerald-400/80 uppercase tracking-wider">By Status</p>
                </div>
                <span class="text-xs px-2.5 py-1 rounded-full bg-white/5 border border-white/10 text-gray-400">
                    {{ array_sum($taskDistribution['data']) }} Total
                </span>
            </div>

            @if(array_sum($taskDistribution['data']) === 0)
                <div class="h-[280px] flex flex-col items-center justify-center text-center p-6 rounded-2xl bg-white/[0.02] border border-white/5">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 mb-3">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                    </div>
                    <h4 class="text-sm font-bold text-white">No Tasks In Pipeline</h4>
                    <p class="text-xs text-gray-400 mt-1 max-w-xs">You currently don't have tasks assigned. Active tasks will populate here by status.</p>
                </div>
            @else
                <div class="relative h-[280px]">
                    <canvas id="taskDistributionChart"></canvas>
                </div>
            @endif
        </div>

        <!-- Performance Velocity -->
        <div class="chart-container rounded-3xl bg-[#10231C]/60 backdrop-blur-sm border border-white/10 p-6 lg:p-8 shadow-xl">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-white">Performance Velocity</h3>
                    <p class="text-xs font-semibold text-emerald-400/80 uppercase tracking-wider">7-Day Completion Trend</p>
                </div>
                <span class="text-xs px-2.5 py-1 rounded-full bg-white/5 border border-white/10 text-gray-400">
                    {{ array_sum($completionTrend['data']) }} Completed
                </span>
            </div>
            <div class="relative h-[280px]">
                <canvas id="completionTrendChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Mission Pipeline (Recent Activity) -->
    <div class="overflow-hidden rounded-3xl bg-[#10231C]/60 backdrop-blur-sm border border-white/10 shadow-xl">
        <div class="flex items-center justify-between border-b border-white/10 bg-white/[0.02] px-6 lg:px-8 py-5">
            <div>
                <h3 class="text-lg lg:text-xl font-bold text-white">Mission Pipeline</h3>
                <p class="text-xs font-semibold text-emerald-400/80 uppercase tracking-wider">Next critical actions</p>
            </div>
            <a href="{{ route('team.tasks.index') }}" class="group flex items-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 px-4 py-2 text-xs font-bold text-white transition-all shadow-lg shadow-emerald-950/40">
                Operational Log
                <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </a>
        </div>
        <div class="px-6 lg:px-8 pb-6 pt-4">
            <div class="space-y-3">
                @forelse($recentTasks as $task)
                <div class="task-row group flex flex-col items-start gap-4 rounded-2xl border border-white/5 bg-white/[0.02] p-4 transition-all hover:border-emerald-500/30 hover:bg-white/[0.05] sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-500/10 border border-emerald-500/20 font-bold text-emerald-400">
                            {{ substr($task->title, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-white group-hover:text-emerald-400 transition-colors">{{ $task->title }}</h4>
                            <div class="flex items-center gap-3 mt-1">
                                <span class="text-xs text-gray-400">{{ $task->project->name ?? 'Direct Mission' }}</span>
                                @if($task->due_date)
                                <span class="flex items-center gap-1 text-[11px] font-semibold {{ $task->due_date->isPast() ? 'text-red-400' : 'text-emerald-400' }}">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    {{ $task->due_date->format('M d, Y \a\t g:i A') }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 self-end sm:self-auto">
                        @php
                            $statusLabels = [
                                'todo' => 'To Do',
                                'in_progress' => 'In Progress',
                                'review' => 'Done',
                                'done' => 'Completed',
                            ];
                        @endphp
                        <span class="inline-flex rounded-lg px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider
                            {{ $task->status === 'done' ? 'bg-emerald-500/10 border border-emerald-500/30 text-emerald-400' : 
                               ($task->status === 'in_progress' ? 'bg-blue-500/10 border border-blue-500/30 text-blue-400' : 
                               ($task->status === 'review' ? 'bg-amber-500/10 border border-amber-500/30 text-amber-400' : 'bg-white/5 border border-white/10 text-gray-400')) }}">
                            {{ $statusLabels[$task->status] ?? str_replace('_', ' ', $task->status) }}
                        </span>
                        <a href="{{ route('team.tasks.show', $task) }}" class="rounded-lg p-2 text-gray-400 hover:bg-emerald-500/10 hover:text-emerald-400 transition-all">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="mb-4 rounded-3xl bg-white/[0.02] border border-white/10 p-5">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 mx-auto">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                    </div>
                    <h5 class="text-base font-bold text-white">Clear Skies! No active tasks found.</h5>
                    <p class="text-xs text-gray-400 mt-1 max-w-sm">You are completely up to date with your assignments. Check back later or review your completed work.</p>
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
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Task Distribution Chart
    const distCanvas = document.getElementById('taskDistributionChart');
    if (distCanvas) {
        const distCtx = distCanvas.getContext('2d');
        new Chart(distCtx, {
            type: 'doughnut',
            data: {
                labels: @json($taskDistribution['labels']),
                datasets: [{
                    data: @json($taskDistribution['data']),
                    backgroundColor: ['#64748b', '#3b82f6', '#f59e0b', '#10b981'],
                    borderColor: '#10231C',
                    borderWidth: 2,
                    hoverOffset: 12
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            color: '#94a3b8',
                            font: { weight: '600', size: 12 }
                        }
                    }
                }
            }
        });
    }

    // Completion Trend Chart
    const trendCanvas = document.getElementById('completionTrendChart');
    if (trendCanvas) {
        const trendCtx = trendCanvas.getContext('2d');
        const gradient = trendCtx.createLinearGradient(0, 0, 0, 260);
        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.35)');
        gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: @json($completionTrend['labels']),
                datasets: [{
                    label: 'Tasks Completed',
                    data: @json($completionTrend['data']),
                    borderColor: '#10b981',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#08110e',
                    pointBorderColor: '#10b981',
                    pointBorderWidth: 2.5,
                    pointRadius: 4,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#10231C',
                        titleColor: '#fff',
                        bodyColor: '#34d399',
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: 5,
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { 
                            stepSize: 1, 
                            precision: 0, 
                            color: '#94a3b8',
                            font: { weight: '600', size: 11 } 
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { 
                            color: '#94a3b8', 
                            font: { weight: '600', size: 11 } 
                        }
                    }
                }
            }
        });
    }

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
