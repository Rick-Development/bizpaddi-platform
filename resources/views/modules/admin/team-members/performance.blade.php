@extends('admin::layouts.app')

@section('title', 'Team Performance')

@section('content')
<div class="space-y-6" x-data="{ 
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
    <div class="border-b border-white/10 pb-5 sm:flex sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    Analytics & Reviews
                </span>
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight mt-1">Team Performance & Assessments</h1>
            <p class="text-xs sm:text-sm text-gray-400">Track team KPIs, individual performance assessments, and weekly reviews.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex items-center gap-3 flex-wrap">
            <a href="{{ route('admin.team-members.index') }}" class="px-3.5 py-2 bg-white/5 border border-white/10 text-gray-200 rounded-xl text-xs font-bold hover:bg-white/10 transition">
                Back to Team
            </a>

            <!-- Send Weekly Review Emails Button -->
            <button @click="openEmailModal('all', 'All Active Team Members')" type="button" class="inline-flex items-center gap-2 px-3.5 py-2 bg-[#0E5E4A] hover:bg-emerald-600 text-white text-xs font-bold rounded-xl transition shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span>Send Weekly Reviews</span>
            </button>

            <!-- Export Dropdown -->
            <div x-data="{ open: false }" class="relative inline-block text-left">
                <button @click="open = !open" type="button" class="inline-flex items-center gap-2 px-3.5 py-2 bg-white/5 border border-white/10 text-gray-200 text-xs font-bold rounded-xl hover:bg-white/10 transition">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Export Report
                    <svg class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                <div x-show="open" @click.away="open = false" x-cloak class="origin-top-right absolute right-0 mt-2 w-56 rounded-xl shadow-xl bg-[#0e1d17] border border-white/15 divide-y divide-white/10 focus:outline-none z-50">
                    <div class="py-1">
                        <div class="px-4 py-2 text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">Download PDF</div>
                        <a href="{{ route('admin.team-performance.export-pdf', array_merge(request()->all(), ['period' => 'daily'])) }}" class="group flex items-center px-4 py-2 text-xs text-gray-200 hover:bg-white/10">
                            📅 Export Daily Report
                        </a>
                        <a href="{{ route('admin.team-performance.export-pdf', array_merge(request()->all(), ['period' => 'weekly'])) }}" class="group flex items-center px-4 py-2 text-xs text-gray-200 hover:bg-white/10">
                            🗓️ Export Weekly Report
                        </a>
                        <a href="{{ route('admin.team-performance.export-pdf', array_merge(request()->all(), ['period' => 'monthly'])) }}" class="group flex items-center px-4 py-2 text-xs text-gray-200 hover:bg-white/10">
                            📆 Export Monthly Report
                        </a>
                        <a href="{{ route('admin.team-performance.export-pdf', array_merge(request()->all(), ['period' => 'yearly'])) }}" class="group flex items-center px-4 py-2 text-xs text-gray-200 hover:bg-white/10">
                            📊 Export Yearly Report
                        </a>
                        <a href="{{ route('admin.team-performance.export-pdf', array_merge(request()->all(), ['period' => 'custom', 'preview' => '1'])) }}" target="_blank" class="group flex items-center px-4 py-2 text-xs text-emerald-400 font-bold hover:bg-white/10">
                            👁️ Preview Custom Range
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 p-5 shadow-sm" x-data="{ period: '{{ $period ?? 'monthly' }}' }">
        <form method="GET" action="{{ route('admin.team-performance.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Period</label>
                <select name="period" x-model="period" class="w-full px-3.5 py-2 bg-[#0a1612] border border-white/15 rounded-xl text-white text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="daily" class="bg-[#08110e]">Daily</option>
                    <option value="weekly" class="bg-[#08110e]">Weekly</option>
                    <option value="monthly" class="bg-[#08110e]">Monthly</option>
                    <option value="yearly" class="bg-[#08110e]">Yearly</option>
                    <option value="custom" class="bg-[#08110e]">Custom Range</option>
                </select>
            </div>
            <div x-show="period !== 'custom'">
                <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Target Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-3.5 py-2 bg-[#0a1612] border border-white/15 rounded-xl text-white text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                <p class="text-[10px] text-gray-500 mt-1">Leave blank for current {{ request('period', 'monthly') }}</p>
            </div>
            <div x-show="period === 'custom'" x-cloak>
                <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-3.5 py-2 bg-[#0a1612] border border-white/15 rounded-xl text-white text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <div x-show="period === 'custom'" x-cloak>
                <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-3.5 py-2 bg-[#0a1612] border border-white/15 rounded-xl text-white text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            
            <div :class="period === 'custom' ? '' : 'md:col-span-2'">
                <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Target Member</label>
                <select name="team_member" class="w-full px-3.5 py-2 bg-[#0a1612] border border-white/15 rounded-xl text-white text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <optgroup label="Groups" class="bg-[#08110e]">
                        <option value="group_active" {{ request('team_member') == 'group_active' ? 'selected' : '' }}>All Active Team</option>
                        <option value="group_suspended" {{ request('team_member') == 'group_suspended' ? 'selected' : '' }}>All Suspended Team</option>
                        <option value="group_inactive" {{ request('team_member') == 'group_inactive' ? 'selected' : '' }}>All Inactive Team</option>
                    </optgroup>
                    <optgroup label="Individual Team Members" class="bg-[#08110e]">
                        @foreach($teamMembers as $member)
                            <option value="{{ $member->id }}" {{ request('team_member') == $member->id ? 'selected' : '' }}>
                                {{ $member->name }} ({{ $member->department ?? 'General' }})
                            </option>
                        @endforeach
                    </optgroup>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-[#0E5E4A] hover:bg-emerald-600 text-white font-bold text-sm rounded-xl transition">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        <div class="p-5 rounded-2xl bg-[#10231C]/60 border border-white/10">
            <p class="text-[10px] font-extrabold uppercase tracking-widest text-gray-400 mb-1">Team Members</p>
            <h2 class="text-3xl font-black text-white">{{ $totalMembers }}</h2>
            <p class="mt-1 text-xs text-gray-500">In current filter</p>
        </div>
        <div class="p-5 rounded-2xl bg-[#10231C]/60 border border-white/10">
            <p class="text-[10px] font-extrabold uppercase tracking-widest text-gray-400 mb-1">Average Score</p>
            <h2 class="text-3xl font-black text-emerald-400">{{ number_format($averageScore, 1) }}%</h2>
            <p class="mt-1 text-xs text-gray-500">Overall index</p>
        </div>
        <div class="p-5 rounded-2xl bg-[#10231C]/60 border border-white/10">
            <p class="text-[10px] font-extrabold uppercase tracking-widest text-gray-400 mb-1">Total Tasks Completed</p>
            <h2 class="text-3xl font-black text-white">{{ $totalTasksCompleted }}</h2>
            <p class="mt-1 text-xs text-gray-500">In period {{ $periodLabel }}</p>
        </div>
        <div class="p-5 rounded-2xl bg-[#10231C]/60 border border-white/10">
            <p class="text-[10px] font-extrabold uppercase tracking-widest text-gray-400 mb-1">Top Performer</p>
            @if($topPerformer)
                <h2 class="text-base font-black text-white truncate">{{ $topPerformer->user->name ?? 'N/A' }}</h2>
                <p class="mt-1 text-xs text-emerald-400 font-bold">{{ number_format($topPerformer->score, 1) }}% Score</p>
            @else
                <p class="text-sm text-gray-500">No data</p>
            @endif
        </div>
    </div>

    <!-- Detailed Individual Member Assessment Block -->
    @if(!empty($memberAssessment))
    <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 p-6 shadow-sm space-y-6">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 pb-5 border-b border-white/10">
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                        Individual Assessment Report
                    </span>
                    <span class="text-xs text-gray-400">{{ $periodLabel }}</span>
                </div>
                <h2 class="text-2xl font-black text-white mt-1">{{ $memberAssessment['user']->name }}</h2>
                <p class="text-xs text-gray-400">{{ $memberAssessment['user']->email }} &bull; {{ $memberAssessment['user']->department ?? 'General' }}</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.team-performance.assessment', ['user' => $memberAssessment['user']->id, 'period' => $period, 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="px-3.5 py-2 bg-white/10 hover:bg-white/15 text-white rounded-xl text-xs font-bold transition">
                    Open Dedicated Page &rarr;
                </a>
                <button @click="openEmailModal('{{ $memberAssessment['user']->id }}', '{{ addslashes($memberAssessment['user']->name) }}')" type="button" class="px-3.5 py-2 bg-[#0E5E4A] hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition">
                    📧 Email Review
                </button>
            </div>
        </div>

        <!-- 4 Quick Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 py-2 border-b border-white/10">
            <div class="bg-emerald-500/10 rounded-xl p-4 border border-emerald-500/20">
                <span class="text-[10px] font-extrabold text-emerald-400 uppercase tracking-wider">Completed Tasks</span>
                <div class="text-2xl font-black text-white mt-1">{{ count($memberAssessment['completedTasks']) }}</div>
                <span class="text-[10px] text-emerald-400 font-semibold">{{ $memberAssessment['onTimeRate'] }}% on-time</span>
            </div>
            <div class="bg-amber-500/10 rounded-xl p-4 border border-amber-500/20">
                <span class="text-[10px] font-extrabold text-amber-400 uppercase tracking-wider">Pending Tasks</span>
                <div class="text-2xl font-black text-white mt-1">{{ count($memberAssessment['pendingTasks']) }}</div>
                <span class="text-[10px] text-amber-400 font-semibold">In Progress</span>
            </div>
            <div class="bg-rose-500/10 rounded-xl p-4 border border-rose-500/20">
                <span class="text-[10px] font-extrabold text-rose-400 uppercase tracking-wider">Overdue Tasks</span>
                <div class="text-2xl font-black text-white mt-1">{{ count($memberAssessment['overdueTasks']) }}</div>
                <span class="text-[10px] text-rose-400 font-semibold">Needs Attention</span>
            </div>
            <div class="bg-blue-500/10 rounded-xl p-4 border border-blue-500/20">
                <span class="text-[10px] font-extrabold text-blue-400 uppercase tracking-wider">Logged Hours</span>
                <div class="text-2xl font-black text-white mt-1">{{ $memberAssessment['totalHours'] }}h</div>
                <span class="text-[10px] text-blue-400 font-semibold">{{ $memberAssessment['daysPresent'] }} active days</span>
            </div>
        </div>

        <!-- Tasks Lists Breakdown Preview -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <!-- Completed Tasks -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-400 mb-2 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    Completed ({{ count($memberAssessment['completedTasks']) }})
                </h4>
                @if(count($memberAssessment['completedTasks']) > 0)
                <ul class="space-y-2">
                    @foreach($memberAssessment['completedTasks']->take(5) as $task)
                    <li class="bg-[#0a1612] border border-white/10 rounded-xl p-3 text-xs flex items-center justify-between">
                        <span class="font-bold text-white truncate max-w-xs">{{ $task->title }}</span>
                        <span class="text-[10px] text-gray-400">{{ $task->completed_at ? $task->completed_at->format('M d') : 'Done' }}</span>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-xs text-gray-500 italic">No tasks completed in this period.</p>
                @endif
            </div>

            <!-- Pending / Unfinished -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-amber-400 mb-2 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    Ongoing & Pending ({{ count($memberAssessment['pendingTasks']) }})
                </h4>
                @if(count($memberAssessment['pendingTasks']) > 0)
                <ul class="space-y-2">
                    @foreach($memberAssessment['pendingTasks']->take(5) as $task)
                    <li class="bg-[#0a1612] border border-white/10 rounded-xl p-3 text-xs flex items-center justify-between {{ $task->is_overdue ? 'border-rose-500/30' : '' }}">
                        <span class="font-bold text-white truncate max-w-xs">{{ $task->title }}</span>
                        <span class="text-[10px] font-bold {{ $task->is_overdue ? 'text-rose-400' : 'text-gray-400' }}">
                            {{ $task->is_overdue ? "Overdue ({$task->days_overdue}d)" : ucfirst($task->status) }}
                        </span>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-xs text-gray-500 italic">No pending tasks.</p>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Performance Table -->
    <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-white/10 bg-white/5 flex items-center justify-between flex-wrap gap-4">
            <div>
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">
                    Performance Summary for {{ $periodLabel }}
                </h3>
            </div>
        </div>

        @if($performances->isEmpty())
        <div class="p-12 text-center">
            <div class="mx-auto h-12 w-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center mb-3">
                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-white">No Performance Data</h3>
            <p class="mt-1 text-xs text-gray-400">No performance records found for the selected period.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10 text-sm">
                <thead class="bg-white/5">
                    <tr>
                        <th class="px-4 py-2.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Rank</th>
                        <th class="px-4 py-2.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Team Member</th>
                        <th class="px-4 py-2.5 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Score</th>
                        <th class="px-4 py-2.5 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Tasks Done</th>
                        <th class="px-4 py-2.5 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Early</th>
                        <th class="px-4 py-2.5 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Late</th>
                        <th class="px-4 py-2.5 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Bonus</th>
                        <th class="px-4 py-2.5 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Penalty</th>
                        <th class="px-4 py-2.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Rating</th>
                        <th class="px-4 py-2.5 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @foreach($performances as $index => $perf)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if($index === 0)
                                <span class="text-xl">🥇</span>
                            @elseif($index === 1)
                                <span class="text-xl">🥈</span>
                            @elseif($index === 2)
                                <span class="text-xl">🥉</span>
                            @else
                                <span class="text-xs font-bold text-gray-500">#{{ $index + 1 }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-[#0E5E4A] to-emerald-700 flex items-center justify-center text-white font-black text-xs shadow-md mr-3 border border-white/10">
                                    {{ strtoupper(substr($perf->user->name ?? 'N/A', 0, 2)) }}
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-white">{{ $perf->user->name ?? 'Unknown' }}</div>
                                    <div class="text-[10px] text-gray-400">{{ $perf->user->department ?? 'General' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold
                                @if($perf->score >= 95) bg-emerald-500/10 text-emerald-400 border border-emerald-500/20
                                @elseif($perf->score >= 80) bg-blue-500/10 text-blue-400 border border-blue-500/20
                                @elseif($perf->score >= 60) bg-amber-500/10 text-amber-400 border border-amber-500/20
                                @else bg-rose-500/10 text-rose-400 border border-rose-500/20
                                @endif">
                                {{ number_format($perf->score, 1) }}%
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center text-xs font-semibold text-white">
                            {{ $perf->tasks_completed }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center text-xs font-semibold text-emerald-400">
                            {{ $perf->tasks_early }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center text-xs font-semibold text-rose-400">
                            {{ $perf->tasks_late }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center text-xs font-semibold text-blue-400">
                            +{{ number_format($perf->bonus_points, 1) }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center text-xs font-semibold text-rose-400">
                            -{{ number_format($perf->penalty_points, 1) }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-xs">
                            @if($perf->score >= 95)
                                <span class="font-bold text-emerald-400">🏆 Excellent</span>
                            @elseif($perf->score >= 80)
                                <span class="font-bold text-blue-400">✨ Good</span>
                            @elseif($perf->score >= 60)
                                <span class="font-bold text-amber-400">⚠️ Fair</span>
                            @else
                                <span class="font-bold text-rose-400">📉 Needs Work</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right">
                            <div class="inline-flex items-center gap-1.5">
                                <a href="{{ route('admin.team-performance.assessment', array_merge(['user' => $perf->user->id], request()->all())) }}" class="text-xs font-bold text-gray-300 bg-white/5 hover:bg-white/10 px-2 py-1 rounded-lg border border-white/10 transition" title="Detailed Assessment Report">
                                    Assessment
                                </a>

                                <button @click="openEmailModal('{{ $perf->user->id }}', '{{ addslashes($perf->user->name) }}')" type="button" class="text-xs font-bold text-emerald-400 bg-emerald-500/10 hover:bg-emerald-500/20 px-2 py-1 rounded-lg border border-emerald-500/20 transition" title="Email Weekly Review to {{ $perf->user->name }}">
                                    Email
                                </button>

                                <a href="{{ route('admin.team-members.impersonate', $perf->user) }}" class="text-xs font-bold text-white bg-[#0E5E4A] hover:bg-emerald-600 px-2 py-1 rounded-lg transition" title="Login to account">
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
        <div class="px-6 py-4 bg-white/5 border-t border-white/10">
            <h4 class="text-xs font-extrabold text-gray-400 mb-3 uppercase tracking-wider">Performance Distribution</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="text-center p-3 bg-emerald-500/10 rounded-xl border border-emerald-500/20">
                    <p class="text-[10px] text-emerald-400 font-extrabold uppercase mb-1">Excellent (≥95%)</p>
                    <p class="text-xl font-black text-white">{{ $performances->where('score', '>=', 95)->count() }}</p>
                </div>
                <div class="text-center p-3 bg-blue-500/10 rounded-xl border border-blue-500/20">
                    <p class="text-[10px] text-blue-400 font-extrabold uppercase mb-1">Good (80-94%)</p>
                    <p class="text-xl font-black text-white">{{ $performances->whereBetween('score', [80, 94.9])->count() }}</p>
                </div>
                <div class="text-center p-3 bg-amber-500/10 rounded-xl border border-amber-500/20">
                    <p class="text-[10px] text-amber-400 font-extrabold uppercase mb-1">Fair (60-79%)</p>
                    <p class="text-xl font-black text-white">{{ $performances->whereBetween('score', [60, 79.9])->count() }}</p>
                </div>
                <div class="text-center p-3 bg-rose-500/10 rounded-xl border border-rose-500/20">
                    <p class="text-[10px] text-rose-400 font-extrabold uppercase mb-1">Needs Work (<60%)</p>
                    <p class="text-xl font-black text-white">{{ $performances->where('score', '<', 60)->count() }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Modal: Send Weekly Review Email -->
    <div x-show="emailModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" @click="emailModalOpen = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div class="inline-block align-bottom bg-[#0e1d17] border border-white/15 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form method="POST" action="{{ route('admin.team-performance.send-weekly-review') }}">
                    @csrf
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-bold text-white flex items-center gap-2">
                                <span class="p-2 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                Send Weekly Performance Review
                            </h3>
                            <button @click="emailModalOpen = false" type="button" class="text-gray-400 hover:text-white">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <p class="text-xs text-gray-400 mb-4 leading-relaxed">
                            Recipient(s) will receive an automated email summarizing their weekly performance score, completed achievements, pending tasks, overdue alerts, and work hours.
                        </p>

                        <div class="space-y-4">
                            <!-- Recipient Target -->
                            <div>
                                <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1">Target Recipient(s)</label>
                                <select name="target" x-model="selectedTarget" class="w-full px-3.5 py-2 bg-[#0a1612] border border-white/15 rounded-xl text-sm text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="all" class="bg-[#08110e]">🌟 All Active Team Members (Bulk Dispatch)</option>
                                    @foreach($teamMembers as $m)
                                        <option value="{{ $m->id }}" class="bg-[#08110e]">{{ $m->name }} ({{ $m->email }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Evaluation Week -->
                            <div>
                                <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1">Evaluation Week</label>
                                <input type="date" name="week" x-model="selectedWeek" class="w-full px-3.5 py-2 bg-[#0a1612] border border-white/15 rounded-xl text-sm text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <p class="text-[10px] text-gray-500 mt-1">Calculates for the week corresponding to this date.</p>
                            </div>

                            <!-- Optional Custom Note -->
                            <div>
                                <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1">Custom Management Note (Optional)</label>
                                <textarea name="custom_note" x-model="customNote" rows="3" placeholder="Add custom guidance, motivation, or feedback..." class="w-full px-3.5 py-2 bg-[#0a1612] border border-white/15 rounded-xl text-sm text-white placeholder-gray-500 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-black/30 px-6 py-4 flex items-center justify-between border-t border-white/10">
                        <template x-if="selectedTarget !== 'all'">
                            <a :href="'/admin/team-performance/preview-review-email/' + selectedTarget + '?week=' + selectedWeek + '&custom_note=' + encodeURIComponent(customNote)" target="_blank" class="text-xs font-bold text-emerald-400 hover:underline">
                                👁️ Preview Member Email
                            </a>
                        </template>
                        <template x-if="selectedTarget === 'all'">
                            <span class="text-xs text-gray-400 font-medium">Bulk dispatch to all active members</span>
                        </template>

                        <div class="flex items-center gap-2">
                            <button @click="emailModalOpen = false" type="button" class="px-4 py-2 bg-white/5 border border-white/10 text-gray-300 rounded-xl text-xs font-bold hover:bg-white/10 transition">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-[#0E5E4A] hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition shadow-sm">
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
