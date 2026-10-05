@extends('admin::layouts.app')

@section('title', 'Team Performance')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-6" x-data="{ 
    emailModalOpen: false,
    selectedTarget: 'all',
    selectedTargetName: 'All Active Team Members',
    selectedWeek: '{{ now()->format('Y-m-d') }}',
    customNote: '',
    openEmailModal(targetId, targetName) {
        this.selectedTarget = targetId;
        this.selectedTargetName = targetName;
        this.emailModalOpen = true;
    }
}">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Team Performance & Assessments</h1>
                <p class="mt-2 text-sm text-gray-500">Track team KPIs, individual performance assessments, and weekly reviews</p>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                <a href="{{ route('admin.team-members.index') }}" class="px-4 py-2 bg-gray-900 text-white rounded-xl text-sm font-bold hover:bg-black transition-all shadow-sm">
                    Back to Team
                </a>

                <!-- Send Weekly Review Emails Button -->
                <button @click="openEmailModal('all', 'All Active Team Members')" type="button" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-brand-primary to-rose-600 text-white text-sm font-bold rounded-xl hover:opacity-95 transition-all shadow-sm shadow-brand-primary/20 hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span>Send Weekly Reviews</span>
                </button>

                <!-- Export Dropdown -->
                <div x-data="{ open: false }" class="relative inline-block text-left">
                    <button @click="open = !open" type="button" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-bold rounded-xl hover:bg-gray-50 transition-colors shadow-sm">
                        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Export Report
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
    
                    <div x-show="open" @click.away="open = false" x-cloak class="origin-top-right absolute right-0 mt-2 w-60 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-50">
                        <div class="py-1">
                            <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Download PDF</div>
                            <a href="{{ route('admin.team-performance.export-pdf', array_merge(request()->all(), ['period' => 'daily'])) }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                📅 Export Daily Report
                            </a>
                            <a href="{{ route('admin.team-performance.export-pdf', array_merge(request()->all(), ['period' => 'weekly'])) }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                🗓️ Export Weekly Report
                            </a>
                            <a href="{{ route('admin.team-performance.export-pdf', array_merge(request()->all(), ['period' => 'monthly'])) }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                📆 Export Monthly Report
                            </a>
                            <a href="{{ route('admin.team-performance.export-pdf', array_merge(request()->all(), ['period' => 'yearly'])) }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                📊 Export Yearly Report
                            </a>
                            <a href="{{ route('admin.team-performance.export-pdf', array_merge(request()->all(), ['period' => 'custom', 'preview' => '1'])) }}" target="_blank" class="group flex items-center px-4 py-2 text-sm text-brand-primary font-medium hover:bg-brand-primary/10">
                                👁️ Preview Custom Range
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6" x-data="{ period: '{{ $period ?? 'monthly' }}' }">
        <form method="GET" action="{{ route('admin.team-performance.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Period</label>
                <select name="period" x-model="period" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                    <option value="custom">Custom Range</option>
                </select>
            </div>
            <div x-show="period !== 'custom'">
                <label class="block text-sm font-medium text-gray-700 mb-2">Target Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                <p class="text-[10px] text-gray-500 mt-1">Leave blank for current {{ request('period', 'monthly') }}</p>
            </div>
            <div x-show="period === 'custom'" x-cloak>
                <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
            </div>
            <div x-show="period === 'custom'" x-cloak>
                <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
            </div>
            
            <div :class="period === 'custom' ? '' : 'md:col-span-2'">
                <label class="block text-sm font-medium text-gray-700 mb-2">Target Audience / Member</label>
                <select name="team_member" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                    <optgroup label="Groups">
                        <option value="group_active" {{ request('team_member') == 'group_active' ? 'selected' : '' }}>All Active Team</option>
                        <option value="group_suspended" {{ request('team_member') == 'group_suspended' ? 'selected' : '' }}>All Suspended Team</option>
                        <option value="group_inactive" {{ request('team_member') == 'group_inactive' ? 'selected' : '' }}>All Inactive Team</option>
                    </optgroup>
                    <optgroup label="Individual Team Members (View Detailed Assessment)">
                        @foreach($teamMembers as $member)
                            <option value="{{ $member->id }}" {{ request('team_member') == $member->id ? 'selected' : '' }}>
                                {{ $member->name }} ({{ $member->department ?? 'General' }})
                            </option>
                        @endforeach
                    </optgroup>
                </select>
            </div>
            <div class="flex items-start mt-7">
                <button type="submit" class="w-full px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors font-medium">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-brand-primary to-purple-600 rounded-2xl shadow-lg p-6 text-white">
            <p class="text-xs font-bold uppercase tracking-widest opacity-90 mb-2">Team Members</p>
            <h2 class="text-4xl font-black">{{ $totalMembers }}</h2>
            <p class="mt-2 text-xs opacity-75">In current filter</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Average Score</p>
            <h2 class="text-4xl font-black text-gray-900">{{ number_format($averageScore, 1) }}%</h2>
            <p class="mt-2 text-xs text-gray-500">Overall index</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Total Tasks Completed</p>
            <h2 class="text-4xl font-black text-gray-900">{{ $totalTasksCompleted }}</h2>
            <p class="mt-2 text-xs text-gray-500">In period {{ $periodLabel }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Top Performer</p>
            @if($topPerformer)
                <h2 class="text-lg font-black text-gray-900 truncate">{{ $topPerformer->user->name ?? 'N/A' }}</h2>
                <p class="mt-2 text-xs text-green-600 font-bold">{{ number_format($topPerformer->score, 1) }}% Score</p>
            @else
                <p class="text-sm text-gray-500">No data</p>
            @endif
        </div>
    </div>

    <!-- Detailed Individual Member Assessment Block (When an individual user is selected) -->
    @if(!empty($memberAssessment))
    <div class="bg-white rounded-3xl shadow-sm border-2 border-brand-primary/20 p-6 lg:p-8 mb-8 relative overflow-hidden">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 pb-6 border-b border-gray-100">
            <div>
                <div class="flex items-center gap-3 flex-wrap">
                    <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-brand-primary/10 text-brand-primary border border-brand-primary/20">
                        Individual Assessment Report
                    </span>
                    <span class="text-xs font-bold text-gray-500">{{ $periodLabel }}</span>
                </div>
                <h2 class="text-2xl lg:text-3xl font-black text-gray-900 mt-2">{{ $memberAssessment['user']->name }}</h2>
                <p class="text-xs text-gray-500 mt-1">{{ $memberAssessment['user']->email }} &bull; {{ $memberAssessment['user']->department ?? 'General' }}</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.team-performance.assessment', ['user' => $memberAssessment['user']->id, 'period' => $period, 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="px-4 py-2 bg-gray-900 text-white rounded-xl text-xs font-bold hover:bg-black transition-all shadow-sm">
                    Open Dedicated Assessment Page →
                </a>
                <button @click="openEmailModal('{{ $memberAssessment['user']->id }}', '{{ addslashes($memberAssessment['user']->name) }}')" type="button" class="px-4 py-2 bg-brand-primary text-white rounded-xl text-xs font-bold hover:bg-brand-primary/90 transition-all shadow-sm">
                    📧 Email Review
                </button>
            </div>
        </div>

        <!-- 4 Quick Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 py-6 border-b border-gray-100">
            <div class="bg-emerald-50 rounded-2xl p-4 border border-emerald-100">
                <span class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider">Completed Tasks</span>
                <div class="text-2xl font-black text-emerald-700 mt-1">{{ count($memberAssessment['completedTasks']) }}</div>
                <span class="text-[11px] text-emerald-600 font-semibold">{{ $memberAssessment['onTimeRate'] }}% on-time</span>
            </div>
            <div class="bg-amber-50 rounded-2xl p-4 border border-amber-100">
                <span class="text-[11px] font-bold text-amber-800 uppercase tracking-wider">Pending / Unfinished</span>
                <div class="text-2xl font-black text-amber-700 mt-1">{{ count($memberAssessment['pendingTasks']) }}</div>
                <span class="text-[11px] text-amber-600 font-semibold">In Progress</span>
            </div>
            <div class="bg-rose-50 rounded-2xl p-4 border border-rose-100">
                <span class="text-[11px] font-bold text-rose-800 uppercase tracking-wider">Overdue Tasks</span>
                <div class="text-2xl font-black text-rose-700 mt-1">{{ count($memberAssessment['overdueTasks']) }}</div>
                <span class="text-[11px] text-rose-600 font-semibold">Immediate attention</span>
            </div>
            <div class="bg-blue-50 rounded-2xl p-4 border border-blue-100">
                <span class="text-[11px] font-bold text-blue-800 uppercase tracking-wider">Logged Hours</span>
                <div class="text-2xl font-black text-blue-700 mt-1">{{ $memberAssessment['totalHours'] }}h</div>
                <span class="text-[11px] text-blue-600 font-semibold">{{ $memberAssessment['daysPresent'] }} active days</span>
            </div>
        </div>

        <!-- Tasks Lists Breakdown Preview -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pt-6">
            <!-- Completed Tasks -->
            <div>
                <h4 class="text-xs font-black uppercase tracking-wider text-emerald-700 mb-3 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    What They Completed This Period ({{ count($memberAssessment['completedTasks']) }})
                </h4>
                @if(count($memberAssessment['completedTasks']) > 0)
                <ul class="space-y-2">
                    @foreach($memberAssessment['completedTasks']->take(5) as $task)
                    <li class="bg-gray-50 border border-gray-100 rounded-xl p-3 text-xs flex items-center justify-between">
                        <span class="font-bold text-gray-900 truncate max-w-xs">{{ $task->title }}</span>
                        <span class="text-[11px] text-gray-500">{{ $task->completed_at ? $task->completed_at->format('M d') : 'Done' }}</span>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-xs text-gray-400 italic">No tasks completed in this period.</p>
                @endif
            </div>

            <!-- Pending / Unfinished -->
            <div>
                <h4 class="text-xs font-black uppercase tracking-wider text-amber-700 mb-3 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    Unfinished & Ongoing Tasks ({{ count($memberAssessment['pendingTasks']) }})
                </h4>
                @if(count($memberAssessment['pendingTasks']) > 0)
                <ul class="space-y-2">
                    @foreach($memberAssessment['pendingTasks']->take(5) as $task)
                    <li class="bg-gray-50 border border-gray-100 rounded-xl p-3 text-xs flex items-center justify-between {{ $task->is_overdue ? 'bg-rose-50/50 border-rose-200' : '' }}">
                        <span class="font-bold text-gray-900 truncate max-w-xs">{{ $task->title }}</span>
                        <span class="text-[11px] font-bold {{ $task->is_overdue ? 'text-rose-600' : 'text-gray-500' }}">
                            {{ $task->is_overdue ? "Overdue ({$task->days_overdue}d)" : ucfirst($task->status) }}
                        </span>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-xs text-gray-400 italic">No pending tasks.</p>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Performance Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between flex-wrap gap-4">
            <div>
                <h3 class="text-lg font-bold text-gray-900">
                    Performance Summary for {{ $periodLabel }}
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">Click "Assessment" on any member for full detailed audit and task breakdown</p>
            </div>
        </div>

        @if($performances->isEmpty())
        <div class="p-12 text-center">
            <div class="mx-auto h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">No Performance Data</h3>
            <p class="mt-2 text-sm text-gray-500">No performance records found for the selected period.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Rank</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Team Member</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Score</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Tasks Done</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Early</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Late</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Bonus</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Penalty</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Rating</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($performances as $index => $perf)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($index === 0)
                                <span class="text-2xl">🥇</span>
                            @elseif($index === 1)
                                <span class="text-2xl">🥈</span>
                            @elseif($index === 2)
                                <span class="text-2xl">🥉</span>
                            @else
                                <span class="text-sm font-bold text-gray-500">#{{ $index + 1 }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-brand-primary to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-lg mr-3">
                                    {{ strtoupper(substr($perf->user->name ?? 'N/A', 0, 2)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900">{{ $perf->user->name ?? 'Unknown' }}</div>
                                    <div class="text-xs text-gray-500">{{ $perf->user->department ?? 'General' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold
                                @if($perf->score >= 95) bg-green-100 text-green-800
                                @elseif($perf->score >= 80) bg-blue-100 text-blue-800
                                @elseif($perf->score >= 60) bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ number_format($perf->score, 1) }}%
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-gray-900">
                            {{ $perf->tasks_completed }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-green-600">
                            {{ $perf->tasks_early }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-red-600">
                            {{ $perf->tasks_late }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-blue-600">
                            +{{ number_format($perf->bonus_points, 1) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-red-600">
                            -{{ number_format($perf->penalty_points, 1) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($perf->score >= 95)
                                <span class="text-xs font-bold text-green-600">🏆 Excellent</span>
                            @elseif($perf->score >= 80)
                                <span class="text-xs font-bold text-blue-600">✨ Good</span>
                            @elseif($perf->score >= 60)
                                <span class="text-xs font-bold text-yellow-600">⚠️ Fair</span>
                            @else
                                <span class="text-xs font-bold text-red-600">📉 Needs Improvement</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="inline-flex items-center gap-2">
                                <!-- Assessment -->
                                <a href="{{ route('admin.team-performance.assessment', array_merge(['user' => $perf->user->id], request()->all())) }}" class="text-xs font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 px-2.5 py-1.5 rounded-lg transition-colors inline-flex items-center gap-1 shadow-sm" title="Detailed Assessment Report">
                                    <svg class="w-3.5 h-3.5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    <span>Assessment</span>
                                </a>

                                <!-- Email Review -->
                                <button @click="openEmailModal('{{ $perf->user->id }}', '{{ addslashes($perf->user->name) }}')" type="button" class="text-xs font-bold text-brand-primary bg-brand-primary/10 hover:bg-brand-primary/20 px-2.5 py-1.5 rounded-lg transition-colors inline-flex items-center gap-1" title="Email Weekly Review to {{ $perf->user->name }}">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span>Email</span>
                                </button>

                                <!-- Login -->
                                <a href="{{ route('admin.team-members.impersonate', $perf->user) }}" class="text-xs font-bold text-white bg-brand-primary hover:bg-brand-primary/90 px-2.5 py-1.5 rounded-lg transition-colors inline-flex items-center gap-1 shadow-sm" title="Login to account">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                    </svg>
                                    Login
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Performance Distribution -->
        <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
            <h4 class="text-sm font-bold text-gray-900 mb-4 uppercase tracking-widest">Performance Distribution</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-green-50 rounded-xl border border-green-100">
                    <p class="text-xs text-green-600 font-medium mb-1">Excellent (≥95%)</p>
                    <p class="text-2xl font-black text-green-700">{{ $performances->where('score', '>=', 95)->count() }}</p>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-xl border border-blue-100">
                    <p class="text-xs text-blue-600 font-medium mb-1">Good (80-94%)</p>
                    <p class="text-2xl font-black text-blue-700">{{ $performances->whereBetween('score', [80, 94.9])->count() }}</p>
                </div>
                <div class="text-center p-4 bg-yellow-50 rounded-xl border border-yellow-100">
                    <p class="text-xs text-yellow-600 font-medium mb-1">Fair (60-79%)</p>
                    <p class="text-2xl font-black text-yellow-700">{{ $performances->whereBetween('score', [60, 79.9])->count() }}</p>
                </div>
                <div class="text-center p-4 bg-red-50 rounded-xl border border-red-100">
                    <p class="text-xs text-red-600 font-medium mb-1">Needs Improvement (<60%)</p>
                    <p class="text-2xl font-black text-red-700">{{ $performances->where('score', '<', 60)->count() }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Modal: Send Weekly Review Email -->
    <div x-show="emailModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="emailModalOpen = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form method="POST" action="{{ route('admin.team-performance.send-weekly-review') }}">
                    @csrf
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <span class="p-2 rounded-xl bg-brand-primary/10 text-brand-primary">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                Send Weekly Performance Review
                            </h3>
                            <button @click="emailModalOpen = false" type="button" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <p class="text-xs text-gray-500 mb-4 leading-relaxed">
                            Recipient(s) will receive an automated email summarizing their weekly performance score, completed achievements, pending tasks, overdue alerts, and work hours.
                        </p>

                        <div class="space-y-4">
                            <!-- Recipient Target -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Target Recipient(s)</label>
                                <select name="target" x-model="selectedTarget" class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                                    <option value="all">🌟 All Active Team Members (Bulk Dispatch)</option>
                                    @foreach($teamMembers as $m)
                                        <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->email }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Evaluation Week -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Evaluation Week</label>
                                <input type="date" name="week" x-model="selectedWeek" class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                                <p class="text-[11px] text-gray-400 mt-1">Calculates for the week corresponding to this date.</p>
                            </div>

                            <!-- Optional Custom Note -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Custom Management Note (Optional)</label>
                                <textarea name="custom_note" x-model="customNote" rows="3" placeholder="Add custom guidance, motivation, or feedback..." class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:border-transparent"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-100">
                        <template x-if="selectedTarget !== 'all'">
                            <a :href="'/admin/team-performance/preview-review-email/' + selectedTarget + '?week=' + selectedWeek + '&custom_note=' + encodeURIComponent(customNote)" target="_blank" class="text-xs font-bold text-brand-primary hover:underline">
                                👁️ Preview Member Email
                            </a>
                        </template>
                        <template x-if="selectedTarget === 'all'">
                            <span class="text-xs text-gray-400 font-medium">Bulk dispatch to all active members</span>
                        </template>

                        <div class="flex items-center gap-2">
                            <button @click="emailModalOpen = false" type="button" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-bold hover:bg-gray-50 transition-all">
                                Cancel
                            </button>
                            <button type="submit" class="px-5 py-2 bg-brand-primary text-white rounded-xl text-sm font-bold hover:bg-brand-primary/90 transition-all shadow-md shadow-brand-primary/20">
                                Send Review(s)
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
