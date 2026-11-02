@extends('admin::layouts.app')

@section('title', 'Performance Assessment: ' . ($user->name ?? 'Team Member'))

@section('content')
<div class="space-y-6" x-data="{ 
    emailModalOpen: false, 
    previewModalOpen: false,
    activeTab: 'all',
    selectedWeek: '{{ now()->format('Y-m-d') }}',
    customNote: ''
}">

    <!-- Top Navigation & Breadcrumbs -->
    <div class="border-b border-white/10 pb-5 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-2 text-xs text-gray-400">
            <a href="{{ route('admin.team-performance.index') }}" class="hover:text-emerald-400 font-semibold transition-colors">Team Performance</a>
            <span>/</span>
            <span class="text-white font-bold">Individual Assessment</span>
            <span>/</span>
            <span class="text-emerald-400 font-bold">{{ $user->name }}</span>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.team-performance.index', ['team_member' => $user->id, 'period' => $period]) }}" class="px-3.5 py-2 bg-white/5 border border-white/10 text-gray-200 rounded-xl text-xs font-bold hover:bg-white/10 transition-all shadow-sm">
                ← Back to Overview
            </a>

            <!-- Send Review Email Button -->
            <button @click="emailModalOpen = true" type="button" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#0E5E4A] hover:bg-emerald-600 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span>Send Review Email</span>
            </button>

            <!-- Impersonate Button -->
            <a href="{{ route('admin.team-members.impersonate', $user) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white/10 border border-white/10 text-white text-xs font-bold rounded-xl hover:bg-white/15 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                <span>Login</span>
            </a>

            <!-- Print / PDF -->
            <button onclick="window.print()" type="button" class="p-2 bg-white/5 border border-white/10 text-gray-400 hover:text-white rounded-xl transition-all shadow-sm" title="Print Assessment">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Member Profile & Score Header Banner -->
    <div class="bg-[#10231C]/60 rounded-2xl shadow-sm border border-white/10 p-6 lg:p-7 relative overflow-hidden">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 relative z-10">
            <!-- Left: Avatar & Info -->
            <div class="flex items-center gap-5">
                <div class="relative">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#0E5E4A] to-emerald-700 flex items-center justify-center text-white font-black text-xl shadow-lg border border-white/10">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <span class="absolute -bottom-1 -right-1 px-1.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider text-white shadow-sm {{ $user->status === 'active' ? 'bg-emerald-500' : 'bg-gray-600' }}">
                        {{ $user->status }}
                    </span>
                </div>
                <div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-2xl font-black text-white tracking-tight">{{ $user->name }}</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-white/5 text-gray-300 border border-white/10">
                            {{ ucfirst(str_replace('_', ' ', $user->role ?? 'Team Member')) }}
                        </span>
                    </div>
                    <div class="mt-1.5 flex items-center gap-4 text-xs text-gray-400 flex-wrap">
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ $user->email }}
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            {{ $user->department ?? 'General Operations' }}
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Evaluation: <strong class="text-white">{{ $assessment['periodLabel'] }}</strong>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right: Performance Score Gauge Card -->
            <div class="flex items-center gap-4 bg-[#0a1612] border border-white/10 rounded-2xl p-4 self-stretch lg:self-auto justify-between lg:justify-start">
                <div class="text-right">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-gray-400 block mb-1">Rating</span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold tracking-wide
                        @if($assessment['score'] >= 95) bg-emerald-500/10 text-emerald-400 border border-emerald-500/20
                        @elseif($assessment['score'] >= 80) bg-blue-500/10 text-blue-400 border border-blue-500/20
                        @elseif($assessment['score'] >= 60) bg-amber-500/10 text-amber-400 border border-amber-500/20
                        @else bg-rose-500/10 text-rose-400 border border-rose-500/20
                        @endif">
                        {{ $assessment['grade']['badge'] }}
                    </span>
                    <p class="text-[10px] text-gray-400 mt-1 font-medium">Grade {{ $assessment['grade']['letter'] }}</p>
                </div>
                <div class="h-10 w-px bg-white/10"></div>
                <div class="text-center min-w-[80px]">
                    <span class="text-3xl font-black text-white tracking-tight leading-none">{{ number_format($assessment['score'], 1) }}%</span>
                    <span class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider block mt-1">Score Index</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar (Period switch & Member Switch) -->
    <div class="bg-[#10231C]/60 rounded-2xl shadow-sm border border-white/10 p-5" x-data="{ filterPeriod: '{{ $period }}' }">
        <form method="GET" action="{{ route('admin.team-performance.assessment', $user) }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <!-- Switch Member -->
            <div>
                <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Switch Member</label>
                <select onchange="window.location.href='/admin/team-performance/assessment/' + this.value + '?period={{ $period }}'" class="w-full px-3.5 py-2 bg-[#0a1612] border border-white/15 rounded-xl text-sm font-medium text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    @foreach($teamMembers as $m)
                        <option value="{{ $m->id }}" class="bg-[#08110e]" {{ $m->id == $user->id ? 'selected' : '' }}>
                            {{ $m->name }} ({{ $m->department ?? 'General' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Period -->
            <div>
                <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Evaluation Scope</label>
                <select name="period" x-model="filterPeriod" class="w-full px-3.5 py-2 bg-[#0a1612] border border-white/15 rounded-xl text-sm font-medium text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="weekly" class="bg-[#08110e]">Weekly Assessment</option>
                    <option value="monthly" class="bg-[#08110e]">Monthly Assessment</option>
                    <option value="custom" class="bg-[#08110e]">Custom Date Range</option>
                </select>
            </div>

            <!-- Single Date (when not custom) -->
            <div x-show="filterPeriod !== 'custom'">
                <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Target Week / Month</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-3.5 py-2 bg-[#0a1612] border border-white/15 rounded-xl text-sm font-medium text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <!-- Start Date (custom) -->
            <div x-show="filterPeriod === 'custom'" x-cloak>
                <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">From Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-3.5 py-2 bg-[#0a1612] border border-white/15 rounded-xl text-sm font-medium text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <!-- End Date (custom) -->
            <div x-show="filterPeriod === 'custom'" x-cloak>
                <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">To Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-3.5 py-2 bg-[#0a1612] border border-white/15 rounded-xl text-sm font-medium text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <div>
                <button type="submit" class="w-full px-4 py-2 bg-[#0E5E4A] hover:bg-emerald-600 text-white rounded-xl text-sm font-bold transition-all shadow-sm">
                    Apply Filter
                </button>
            </div>
        </form>
    </div>

    <!-- 4 KPI Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Completed Tasks -->
        <div class="bg-[#10231C]/60 rounded-2xl shadow-sm border border-emerald-500/20 p-5 relative overflow-hidden">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-extrabold uppercase tracking-wider text-emerald-400">Finished Tasks</span>
                <span class="p-1.5 rounded-lg bg-emerald-500/10 text-emerald-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-white">{{ count($assessment['completedTasks']) }}</span>
                <span class="text-xs font-bold text-emerald-400">{{ $assessment['onTimeRate'] }}% on-time</span>
            </div>
            <p class="text-[10px] text-gray-400 mt-1 font-medium">Completed in timeframe</p>
        </div>

        <!-- Pending / Unfinished Tasks -->
        <div class="bg-[#10231C]/60 rounded-2xl shadow-sm border border-amber-500/20 p-5 relative overflow-hidden">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-extrabold uppercase tracking-wider text-amber-400">Pending Tasks</span>
                <span class="p-1.5 rounded-lg bg-amber-500/10 text-amber-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-white">{{ count($assessment['pendingTasks']) }}</span>
                <span class="text-xs font-bold text-amber-400">Active</span>
            </div>
            <p class="text-[10px] text-gray-400 mt-1 font-medium">Currently carrying over</p>
        </div>

        <!-- Overdue Tasks -->
        <div class="bg-[#10231C]/60 rounded-2xl shadow-sm border border-rose-500/20 p-5 relative overflow-hidden">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-extrabold uppercase tracking-wider text-rose-400">Overdue Tasks</span>
                <span class="p-1.5 rounded-lg bg-rose-500/10 text-rose-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black {{ count($assessment['overdueTasks']) > 0 ? 'text-rose-400' : 'text-white' }}">
                    {{ count($assessment['overdueTasks']) }}
                </span>
                @if(count($assessment['overdueTasks']) > 0)
                    <span class="text-xs font-bold text-rose-400 uppercase tracking-wider">Attention</span>
                @else
                    <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider">Clean</span>
                @endif
            </div>
            <p class="text-[10px] text-gray-400 mt-1 font-medium">Missed initial deadline</p>
        </div>

        <!-- Work Log Hours -->
        <div class="bg-[#10231C]/60 rounded-2xl shadow-sm border border-blue-500/20 p-5 relative overflow-hidden">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-extrabold uppercase tracking-wider text-blue-400">Logged Hours</span>
                <span class="p-1.5 rounded-lg bg-blue-500/10 text-blue-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-white">{{ $assessment['totalHours'] }}h</span>
                <span class="text-xs font-bold text-blue-400">{{ $assessment['daysPresent'] }} days active</span>
            </div>
            <p class="text-[10px] text-gray-400 mt-1 font-medium">From verified work logs</p>
        </div>
    </div>

    <!-- Content Tabs -->
    <div class="bg-[#10231C]/60 rounded-2xl shadow-sm border border-white/10 overflow-hidden">
        <!-- Tab Bar -->
        <div class="border-b border-white/10 px-6 pt-4 flex items-center gap-6 overflow-x-auto bg-white/5">
            <button @click="activeTab = 'all'" :class="activeTab === 'all' ? 'text-emerald-400 border-emerald-400' : 'text-gray-400 border-transparent hover:text-white'" class="pb-3 border-b-2 font-bold text-xs uppercase tracking-wider transition-colors whitespace-nowrap">
                Overview & All Tasks ({{ count($assessment['completedTasks']) + count($assessment['pendingTasks']) }})
            </button>
            <button @click="activeTab = 'completed'" :class="activeTab === 'completed' ? 'text-emerald-400 border-emerald-400' : 'text-gray-400 border-transparent hover:text-white'" class="pb-3 border-b-2 font-bold text-xs uppercase tracking-wider transition-colors whitespace-nowrap">
                Completed ({{ count($assessment['completedTasks']) }})
            </button>
            <button @click="activeTab = 'pending'" :class="activeTab === 'pending' ? 'text-emerald-400 border-emerald-400' : 'text-gray-400 border-transparent hover:text-white'" class="pb-3 border-b-2 font-bold text-xs uppercase tracking-wider transition-colors whitespace-nowrap">
                Pending ({{ count($assessment['pendingTasks']) }})
            </button>
            <button @click="activeTab = 'worklogs'" :class="activeTab === 'worklogs' ? 'text-emerald-400 border-emerald-400' : 'text-gray-400 border-transparent hover:text-white'" class="pb-3 border-b-2 font-bold text-xs uppercase tracking-wider transition-colors whitespace-nowrap">
                Work Logs ({{ count($assessment['workLogs']) }})
            </button>
            <button @click="activeTab = 'audit'" :class="activeTab === 'audit' ? 'text-emerald-400 border-emerald-400' : 'text-gray-400 border-transparent hover:text-white'" class="pb-3 border-b-2 font-bold text-xs uppercase tracking-wider transition-colors whitespace-nowrap">
                Score Audit
            </button>
        </div>

        <!-- Tab 1: Completed Tasks Table -->
        <div x-show="activeTab === 'all' || activeTab === 'completed'" class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    Tasks Completed During This Period ({{ count($assessment['completedTasks']) }})
                </h4>
            </div>

            @if(count($assessment['completedTasks']) > 0)
            <div class="overflow-x-auto rounded-xl border border-white/10">
                <table class="min-w-full divide-y divide-white/10 text-xs">
                    <thead class="bg-white/5 text-[10px] font-bold uppercase tracking-wider text-gray-400">
                        <tr>
                            <th class="px-4 py-2.5 text-left">Task</th>
                            <th class="px-4 py-2.5 text-left">Project</th>
                            <th class="px-4 py-2.5 text-center">Due Date</th>
                            <th class="px-4 py-2.5 text-center">Completed At</th>
                            <th class="px-4 py-2.5 text-center">Timeliness</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @foreach($assessment['completedTasks'] as $task)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-4 py-3 font-bold text-white">
                                {{ $task->title }}
                            </td>
                            <td class="px-4 py-3 text-gray-400">
                                <span class="px-2 py-0.5 bg-white/5 rounded-lg text-gray-300 font-medium">
                                    {{ $task->project->name ?? 'General' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center text-gray-400 font-medium">
                                {{ $task->due_date ? $task->due_date->format('M d, Y') : '—' }}
                            </td>
                            <td class="px-4 py-3 text-center text-gray-300 font-medium">
                                {{ $task->completed_at ? $task->completed_at->format('M d, H:i') : ($task->updated_at ? $task->updated_at->format('M d, H:i') : '—') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($task->timing === 'early')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-500/10 text-emerald-400 font-bold rounded-full text-[10px]">
                                        ✓ Early Delivery (+2 pts)
                                    </span>
                                @elseif($task->timing === 'late')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-rose-500/10 text-rose-400 font-bold rounded-full text-[10px]">
                                        ⚠️ Late Delivery (-5 pts)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-500/10 text-blue-400 font-bold rounded-full text-[10px]">
                                        On Schedule
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8 bg-white/5 rounded-xl border border-dashed border-white/10">
                <p class="text-xs text-gray-400 font-medium">No tasks were recorded as completed in this timeframe.</p>
            </div>
            @endif
        </div>

        <!-- Tab 2: Pending / Unfinished Tasks Table -->
        <div x-show="activeTab === 'all' || activeTab === 'pending'" class="p-6 border-t border-white/10">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    Unfinished & In-Progress Tasks ({{ count($assessment['pendingTasks']) }})
                </h4>
            </div>

            @if(count($assessment['pendingTasks']) > 0)
            <div class="overflow-x-auto rounded-xl border border-white/10">
                <table class="min-w-full divide-y divide-white/10 text-xs">
                    <thead class="bg-white/5 text-[10px] font-bold uppercase tracking-wider text-gray-400">
                        <tr>
                            <th class="px-4 py-2.5 text-left">Task</th>
                            <th class="px-4 py-2.5 text-left">Project</th>
                            <th class="px-4 py-2.5 text-center">Status</th>
                            <th class="px-4 py-2.5 text-center">Priority</th>
                            <th class="px-4 py-2.5 text-center">Due Date</th>
                            <th class="px-4 py-2.5 text-center">Timeline</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @foreach($assessment['pendingTasks'] as $task)
                        <tr class="hover:bg-white/5 transition-colors {{ $task->is_overdue ? 'bg-rose-500/5' : '' }}">
                            <td class="px-4 py-3 font-bold text-white">
                                {{ $task->title }}
                            </td>
                            <td class="px-4 py-3 text-gray-400">
                                <span class="px-2 py-0.5 bg-white/5 rounded-lg text-gray-300 font-medium">
                                    {{ $task->project->name ?? 'General' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    @if($task->status === 'in_progress') bg-blue-500/10 text-blue-400 border border-blue-500/20
                                    @elseif($task->status === 'review') bg-purple-500/10 text-purple-400 border border-purple-500/20
                                    @elseif($task->status === 'testing') bg-amber-500/10 text-amber-400 border border-amber-500/20
                                    @else bg-white/5 text-gray-400 border border-white/10
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center font-bold">
                                <span class="{{ $task->priority === 'urgent' || $task->priority === 'high' ? 'text-rose-400' : 'text-gray-400' }}">
                                    {{ ucfirst($task->priority ?? 'Medium') }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center font-medium text-gray-400">
                                {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No Due Date' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($task->is_overdue)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-rose-500/10 text-rose-400 font-bold rounded-full text-[10px] uppercase">
                                        ⚠️ {{ $task->days_overdue }}d Overdue
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-500/10 text-emerald-400 font-bold rounded-full text-[10px]">
                                        On Track
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8 bg-white/5 rounded-xl border border-dashed border-white/10">
                <p class="text-xs text-emerald-400 font-bold">All caught up! Zero pending or unfinished tasks carried over.</p>
            </div>
            @endif
        </div>

        <!-- Tab 3: Work Sessions & Time Logs -->
        <div x-show="activeTab === 'worklogs'" class="p-6 border-t border-white/10">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                    Verified Work Sessions & Logs ({{ count($assessment['workLogs']) }})
                </h4>
                <span class="text-xs font-bold text-gray-400">
                    Total: {{ $assessment['totalHours'] }} Hours across {{ $assessment['daysPresent'] }} Days
                </span>
            </div>

            @if(count($assessment['workLogs']) > 0)
            <div class="overflow-x-auto rounded-xl border border-white/10">
                <table class="min-w-full divide-y divide-white/10 text-xs">
                    <thead class="bg-white/5 text-[10px] font-bold uppercase tracking-wider text-gray-400">
                        <tr>
                            <th class="px-4 py-2.5 text-left">Date</th>
                            <th class="px-4 py-2.5 text-center">Check-In</th>
                            <th class="px-4 py-2.5 text-center">Check-Out</th>
                            <th class="px-4 py-2.5 text-center">Duration</th>
                            <th class="px-4 py-2.5 text-left">Tasks / Notes</th>
                            <th class="px-4 py-2.5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @foreach($assessment['workLogs'] as $log)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-4 py-3 font-bold text-white whitespace-nowrap">
                                {{ $log->start_time ? $log->start_time->format('D, M d, Y') : '—' }}
                            </td>
                            <td class="px-4 py-3 text-center font-medium text-gray-400">
                                {{ $log->start_time ? $log->start_time->format('H:i') : '—' }}
                            </td>
                            <td class="px-4 py-3 text-center font-medium text-gray-400">
                                {{ $log->end_time ? $log->end_time->format('H:i') : 'In Progress' }}
                            </td>
                            <td class="px-4 py-3 text-center font-bold text-blue-400">
                                {{ round($log->duration_minutes / 60, 1) }}h ({{ $log->duration_minutes }}m)
                            </td>
                            <td class="px-4 py-3 text-gray-300 max-w-xs truncate" title="{{ $log->actual_tasks ?? $log->session_goal ?? $log->planned_tasks }}">
                                {{ $log->actual_tasks ?? $log->session_goal ?? $log->planned_tasks ?? 'Routine session' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                    {{ $log->status === 'completed' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-amber-500/10 text-amber-400' }}">
                                    {{ $log->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8 bg-white/5 rounded-xl border border-dashed border-white/10">
                <p class="text-xs text-gray-400 font-medium">No work log entries recorded for this evaluation period.</p>
            </div>
            @endif
        </div>

        <!-- Tab 4: Score Audit Breakdown -->
        <div x-show="activeTab === 'audit'" class="p-6 border-t border-white/10">
            <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-purple-400"></span>
                KPI Algorithm Audit Breakdown
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Base Score -->
                <div class="bg-[#0a1612] rounded-xl p-5 border border-white/10">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Base Metric</span>
                    <div class="text-3xl font-black text-white mt-1">100.0</div>
                    <p class="text-xs text-gray-500 mt-1">Starting baseline index</p>
                </div>

                <!-- Bonuses -->
                <div class="bg-emerald-500/10 rounded-xl p-5 border border-emerald-500/20">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-400">+ Bonus Points</span>
                    <div class="text-3xl font-black text-emerald-400 mt-1">+{{ number_format($assessment['metrics']['total_bonus'] ?? 0, 1) }}</div>
                    <ul class="text-xs text-emerald-300 mt-2 space-y-1">
                        <li>• Early task deliveries: {{ $assessment['metrics']['tasks']['early'] ?? 0 }} (+2 pts each)</li>
                    </ul>
                </div>

                <!-- Penalties -->
                <div class="bg-rose-500/10 rounded-xl p-5 border border-rose-500/20">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-rose-400">- Penalties Incurred</span>
                    <div class="text-3xl font-black text-rose-400 mt-1">-{{ number_format($assessment['metrics']['total_penalty'] ?? 0, 1) }}</div>
                    <ul class="text-xs text-rose-300 mt-2 space-y-1">
                        <li>• Overdue tasks: {{ $assessment['metrics']['tasks']['late'] ?? 0 }} (-5 pts each)</li>
                    </ul>
                </div>
            </div>

            <div class="mt-5 p-4 rounded-xl bg-white/5 border border-white/10 text-white flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-gray-300">Final Evaluated Score:</span>
                <span class="text-xl font-black text-emerald-400">{{ number_format($assessment['score'], 1) }}% ({{ $assessment['grade']['label'] }})</span>
            </div>
        </div>
    </div>

    <!-- Modal: Send Weekly Review Email -->
    <div x-show="emailModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" @click="emailModalOpen = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div class="inline-block align-bottom bg-[#0e1d17] border border-white/15 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form method="POST" action="{{ route('admin.team-performance.send-weekly-review') }}">
                    @csrf
                    <input type="hidden" name="target" value="{{ $user->id }}">

                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-bold text-white flex items-center gap-2">
                                <span class="p-2 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                Send Weekly Review to {{ $user->name }}
                            </h3>
                            <button @click="emailModalOpen = false" type="button" class="text-gray-400 hover:text-white">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <p class="text-xs text-gray-400 mb-4 leading-relaxed">
                            This will dispatch a branded performance report email directly to <strong>{{ $user->email }}</strong> containing their weekly score ({{ number_format($assessment['score'], 1) }}%), completed tasks ({{ count($assessment['completedTasks']) }}), pending items ({{ count($assessment['pendingTasks']) }}), and overdue tasks ({{ count($assessment['overdueTasks']) }}).
                        </p>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1">Evaluation Week</label>
                                <input type="date" name="week" x-model="selectedWeek" class="w-full px-3.5 py-2 bg-[#0a1612] border border-white/15 rounded-xl text-sm text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>

                            <div>
                                <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1">Optional Manager Note</label>
                                <textarea name="custom_note" x-model="customNote" rows="3" placeholder="Add custom feedback, recognition, or instructions..." class="w-full px-3.5 py-2 bg-[#0a1612] border border-white/15 rounded-xl text-sm text-white placeholder-gray-500 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-black/30 px-6 py-4 flex items-center justify-between border-t border-white/10">
                        <a :href="'/admin/team-performance/preview-review-email/{{ $user->id }}?week=' + selectedWeek + '&custom_note=' + encodeURIComponent(customNote)" target="_blank" class="text-xs font-bold text-emerald-400 hover:underline">
                            👁️ Preview Email
                        </a>

                        <div class="flex items-center gap-2">
                            <button @click="emailModalOpen = false" type="button" class="px-4 py-2 bg-white/5 border border-white/10 text-gray-300 rounded-xl text-xs font-bold hover:bg-white/10 transition">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-[#0E5E4A] hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition shadow-sm">
                                Send Review
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
