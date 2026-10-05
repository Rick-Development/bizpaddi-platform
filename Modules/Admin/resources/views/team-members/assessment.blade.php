@extends('admin::layouts.app')

@section('title', 'Performance Assessment: ' . ($user->name ?? 'Team Member'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-slate-50 to-gray-100 p-4 sm:p-6 lg:p-8" x-data="{ 
    emailModalOpen: false, 
    previewModalOpen: false,
    activeTab: 'all',
    selectedWeek: '{{ now()->format('Y-m-d') }}',
    customNote: ''
}">

    <!-- Top Navigation & Breadcrumbs -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('admin.team-performance.index') }}" class="hover:text-brand-primary font-medium transition-colors">Team Performance</a>
            <span>/</span>
            <span class="text-gray-900 font-semibold">Individual Assessment</span>
            <span>/</span>
            <span class="text-brand-primary font-bold">{{ $user->name }}</span>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.team-performance.index', ['team_member' => $user->id, 'period' => $period]) }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-bold hover:bg-gray-50 transition-all shadow-sm">
                ← Back to Overview
            </a>

            <!-- Send Review Email Button -->
            <button @click="emailModalOpen = true" type="button" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-primary text-white text-sm font-bold rounded-xl hover:bg-brand-primary/90 transition-all shadow-sm shadow-brand-primary/20 hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span>Send Review Email</span>
            </button>

            <!-- Impersonate Button -->
            <a href="{{ route('admin.team-members.impersonate', $user) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-bold rounded-xl hover:bg-black transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                <span>Login as {{ explode(' ', $user->name)[0] }}</span>
            </a>

            <!-- Print / PDF -->
            <button onclick="window.print()" type="button" class="p-2 bg-white border border-gray-200 text-gray-600 rounded-xl hover:text-gray-900 hover:bg-gray-50 transition-all shadow-sm" title="Print Assessment">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Member Profile & Score Header Banner -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 lg:p-8 mb-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-brand-primary/5 via-purple-500/5 to-transparent rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 relative z-10">
            <!-- Left: Avatar & Info -->
            <div class="flex items-center gap-5">
                <div class="relative">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-brand-primary to-purple-700 flex items-center justify-center text-white font-extrabold text-2xl shadow-lg shadow-brand-primary/20">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <span class="absolute -bottom-1 -right-1 px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider text-white shadow-sm {{ $user->status === 'active' ? 'bg-emerald-500' : 'bg-gray-500' }}">
                        {{ $user->status }}
                    </span>
                </div>
                <div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-2xl lg:text-3xl font-black text-gray-900 tracking-tight">{{ $user->name }}</h1>
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-gray-100 text-gray-700 border border-gray-200">
                            {{ ucfirst(str_replace('_', ' ', $user->role ?? 'Team Member')) }}
                        </span>
                    </div>
                    <div class="mt-1.5 flex items-center gap-4 text-xs sm:text-sm text-gray-500 flex-wrap">
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ $user->email }}
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            {{ $user->department ?? 'General Operations' }}
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Evaluation: <strong class="text-gray-900">{{ $assessment['periodLabel'] }}</strong>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right: Performance Score Gauge Card -->
            <div class="flex items-center gap-4 bg-gray-50 border border-gray-100 rounded-2xl p-4 self-stretch lg:self-auto justify-between lg:justify-start">
                <div class="text-right">
                    <span class="text-[11px] font-black uppercase tracking-widest text-gray-400 block mb-1">Performance Rating</span>
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-black tracking-wide
                        @if($assessment['score'] >= 95) bg-emerald-100 text-emerald-800
                        @elseif($assessment['score'] >= 80) bg-blue-100 text-blue-800
                        @elseif($assessment['score'] >= 60) bg-amber-100 text-amber-800
                        @else bg-rose-100 text-rose-800
                        @endif">
                        {{ $assessment['grade']['badge'] }}
                    </span>
                    <p class="text-[11px] text-gray-500 mt-1 font-medium">Grade {{ $assessment['grade']['letter'] }}</p>
                </div>
                <div class="h-14 w-px bg-gray-200"></div>
                <div class="text-center min-w-[90px]">
                    <span class="text-4xl font-black text-gray-900 tracking-tight leading-none">{{ number_format($assessment['score'], 1) }}%</span>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mt-1">Score Index</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar (Period switch & Member Switch) -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-8" x-data="{ filterPeriod: '{{ $period }}' }">
        <form method="GET" action="{{ route('admin.team-performance.assessment', $user) }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <!-- Switch Member -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5">Switch Team Member</label>
                <select onchange="window.location.href='/admin/team-performance/assessment/' + this.value + '?period={{ $period }}'" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                    @foreach($teamMembers as $m)
                        <option value="{{ $m->id }}" {{ $m->id == $user->id ? 'selected' : '' }}>
                            {{ $m->name }} ({{ $m->department ?? 'General' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Period -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5">Evaluation Scope</label>
                <select name="period" x-model="filterPeriod" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                    <option value="weekly">Weekly Assessment</option>
                    <option value="monthly">Monthly Assessment</option>
                    <option value="custom">Custom Date Range</option>
                </select>
            </div>

            <!-- Single Date (when not custom) -->
            <div x-show="filterPeriod !== 'custom'">
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5">Target Week / Month</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-brand-primary focus:border-transparent">
            </div>

            <!-- Start Date (custom) -->
            <div x-show="filterPeriod === 'custom'" x-cloak>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5">From Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-brand-primary focus:border-transparent">
            </div>

            <!-- End Date (custom) -->
            <div x-show="filterPeriod === 'custom'" x-cloak>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5">To Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-brand-primary focus:border-transparent">
            </div>

            <div>
                <button type="submit" class="w-full px-4 py-2.5 bg-gray-900 hover:bg-black text-white rounded-xl text-sm font-bold transition-all shadow-sm">
                    Apply Filter
                </button>
            </div>
        </form>
    </div>

    <!-- 4 KPI Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        <!-- Completed Tasks -->
        <div class="bg-white rounded-2xl shadow-sm border border-emerald-100 p-5 relative overflow-hidden group hover:border-emerald-300 transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">Finished Tasks</span>
                <span class="p-2 rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl sm:text-4xl font-black text-gray-900">{{ count($assessment['completedTasks']) }}</span>
                <span class="text-xs font-bold text-emerald-600">{{ $assessment['onTimeRate'] }}% on-time</span>
            </div>
            <p class="text-xs text-gray-400 mt-2 font-medium">Completed in this timeframe</p>
        </div>

        <!-- Pending / Unfinished Tasks -->
        <div class="bg-white rounded-2xl shadow-sm border border-amber-100 p-5 relative overflow-hidden group hover:border-amber-300 transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-amber-700">Pending / In-Progress</span>
                <span class="p-2 rounded-xl bg-amber-50 text-amber-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl sm:text-4xl font-black text-gray-900">{{ count($assessment['pendingTasks']) }}</span>
                <span class="text-xs font-bold text-amber-600">Active</span>
            </div>
            <p class="text-xs text-gray-400 mt-2 font-medium">Currently carrying over</p>
        </div>

        <!-- Overdue Tasks -->
        <div class="bg-white rounded-2xl shadow-sm border border-rose-100 p-5 relative overflow-hidden group hover:border-rose-300 transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-rose-700">Overdue Tasks</span>
                <span class="p-2 rounded-xl bg-rose-50 text-rose-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl sm:text-4xl font-black {{ count($assessment['overdueTasks']) > 0 ? 'text-rose-600' : 'text-gray-900' }}">
                    {{ count($assessment['overdueTasks']) }}
                </span>
                @if(count($assessment['overdueTasks']) > 0)
                    <span class="text-xs font-bold text-rose-600 uppercase tracking-wider">Immediate Focus</span>
                @else
                    <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Clean</span>
                @endif
            </div>
            <p class="text-xs text-gray-400 mt-2 font-medium">Missed initial deadline</p>
        </div>

        <!-- Work Log Hours -->
        <div class="bg-white rounded-2xl shadow-sm border border-blue-100 p-5 relative overflow-hidden group hover:border-blue-300 transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-700">Logged Hours</span>
                <span class="p-2 rounded-xl bg-blue-50 text-blue-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl sm:text-4xl font-black text-gray-900">{{ $assessment['totalHours'] }}h</span>
                <span class="text-xs font-bold text-blue-600">{{ $assessment['daysPresent'] }} days active</span>
            </div>
            <p class="text-xs text-gray-400 mt-2 font-medium">From verified work logs</p>
        </div>
    </div>

    <!-- Manager Observations / AI Highlights -->
    @if(!empty($assessment['summaryPoints']))
    <div class="bg-gradient-to-r from-gray-900 via-indigo-950 to-gray-900 text-white rounded-2xl p-6 mb-8 shadow-md">
        <div class="flex items-center gap-3 mb-3">
            <span class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-amber-300">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </span>
            <h3 class="text-sm font-extrabold uppercase tracking-wider text-white">Assessment Summary & Highlights</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
            @foreach($assessment['summaryPoints'] as $point)
            <div class="bg-white/5 border border-white/10 rounded-xl p-3.5 text-xs text-gray-200 leading-relaxed font-medium">
                {{ $point }}
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Overdue Alert Notice (if any) -->
    @if(count($assessment['overdueTasks']) > 0)
    <div class="bg-rose-50 border-l-4 border-rose-500 rounded-r-2xl p-5 mb-8 shadow-sm">
        <div class="flex items-start gap-4">
            <div class="p-2 rounded-xl bg-rose-100 text-rose-700">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h4 class="text-sm font-bold text-rose-900 uppercase tracking-wide">
                    Action Required: {{ count($assessment['overdueTasks']) }} Overdue Task(s) Detected
                </h4>
                <p class="text-xs text-rose-700 mt-1 leading-relaxed">
                    These assignments have exceeded their target delivery date. They impact the member's punctuality score and may cause delays in dependent project milestones.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Content Tabs -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <!-- Tab Bar -->
        <div class="border-b border-gray-100 px-6 pt-4 flex items-center gap-6 overflow-x-auto">
            <button @click="activeTab = 'all'" :class="activeTab === 'all' ? 'text-brand-primary border-brand-primary' : 'text-gray-500 border-transparent hover:text-gray-900'" class="pb-4 border-b-2 font-bold text-sm tracking-wide transition-colors whitespace-nowrap">
                Overview & All Tasks ({{ count($assessment['completedTasks']) + count($assessment['pendingTasks']) }})
            </button>
            <button @click="activeTab = 'completed'" :class="activeTab === 'completed' ? 'text-brand-primary border-brand-primary' : 'text-gray-500 border-transparent hover:text-gray-900'" class="pb-4 border-b-2 font-bold text-sm tracking-wide transition-colors whitespace-nowrap">
                Completed ({{ count($assessment['completedTasks']) }})
            </button>
            <button @click="activeTab = 'pending'" :class="activeTab === 'pending' ? 'text-brand-primary border-brand-primary' : 'text-gray-500 border-transparent hover:text-gray-900'" class="pb-4 border-b-2 font-bold text-sm tracking-wide transition-colors whitespace-nowrap">
                Unfinished & Pending ({{ count($assessment['pendingTasks']) }})
            </button>
            <button @click="activeTab = 'worklogs'" :class="activeTab === 'worklogs' ? 'text-brand-primary border-brand-primary' : 'text-gray-500 border-transparent hover:text-gray-900'" class="pb-4 border-b-2 font-bold text-sm tracking-wide transition-colors whitespace-nowrap">
                Work Sessions & Logs ({{ count($assessment['workLogs']) }})
            </button>
            <button @click="activeTab = 'audit'" :class="activeTab === 'audit' ? 'text-brand-primary border-brand-primary' : 'text-gray-500 border-transparent hover:text-gray-900'" class="pb-4 border-b-2 font-bold text-sm tracking-wide transition-colors whitespace-nowrap">
                Score Audit Breakdown
            </button>
        </div>

        <!-- Tab 1: Completed Tasks Table -->
        <div x-show="activeTab === 'all' || activeTab === 'completed'" class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-sm font-extrabold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    Tasks Completed During This Period ({{ count($assessment['completedTasks']) }})
                </h4>
            </div>

            @if(count($assessment['completedTasks']) > 0)
            <div class="overflow-x-auto rounded-xl border border-gray-100">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-4 py-3 text-left">Task</th>
                            <th class="px-4 py-3 text-left">Project</th>
                            <th class="px-4 py-3 text-center">Due Date</th>
                            <th class="px-4 py-3 text-center">Completed At</th>
                            <th class="px-4 py-3 text-center">Timeliness</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-xs">
                        @foreach($assessment['completedTasks'] as $task)
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="px-4 py-3.5 font-bold text-gray-900">
                                {{ $task->title }}
                            </td>
                            <td class="px-4 py-3.5 text-gray-600">
                                <span class="px-2.5 py-1 bg-gray-100 rounded-lg text-gray-700 font-medium">
                                    {{ $task->project->name ?? 'General' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-center text-gray-500 font-medium">
                                {{ $task->due_date ? $task->due_date->format('M d, Y') : '—' }}
                            </td>
                            <td class="px-4 py-3.5 text-center text-gray-700 font-medium">
                                {{ $task->completed_at ? $task->completed_at->format('M d, H:i') : ($task->updated_at ? $task->updated_at->format('M d, H:i') : '—') }}
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                @if($task->timing === 'early')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 font-bold rounded-full text-[11px]">
                                        ✓ Early Delivery (+2 pts)
                                    </span>
                                @elseif($task->timing === 'late')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 text-rose-700 font-bold rounded-full text-[11px]">
                                        ⚠️ Late Delivery (-5 pts)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 font-bold rounded-full text-[11px]">
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
            <div class="text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                <p class="text-xs text-gray-500 font-medium">No tasks were recorded as completed in this timeframe.</p>
            </div>
            @endif
        </div>

        <!-- Tab 2: Pending / Unfinished Tasks Table -->
        <div x-show="activeTab === 'all' || activeTab === 'pending'" class="p-6 border-t border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-sm font-extrabold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                    Unfinished & In-Progress Tasks ({{ count($assessment['pendingTasks']) }})
                </h4>
            </div>

            @if(count($assessment['pendingTasks']) > 0)
            <div class="overflow-x-auto rounded-xl border border-gray-100">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-4 py-3 text-left">Task</th>
                            <th class="px-4 py-3 text-left">Project</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Priority</th>
                            <th class="px-4 py-3 text-center">Due Date</th>
                            <th class="px-4 py-3 text-center">Timeline Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-xs">
                        @foreach($assessment['pendingTasks'] as $task)
                        <tr class="hover:bg-gray-50/60 transition-colors {{ $task->is_overdue ? 'bg-rose-50/30' : '' }}">
                            <td class="px-4 py-3.5 font-bold text-gray-900">
                                {{ $task->title }}
                            </td>
                            <td class="px-4 py-3.5 text-gray-600">
                                <span class="px-2.5 py-1 bg-gray-100 rounded-lg text-gray-700 font-medium">
                                    {{ $task->project->name ?? 'General' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider
                                    @if($task->status === 'in_progress') bg-blue-100 text-blue-800
                                    @elseif($task->status === 'review') bg-purple-100 text-purple-800
                                    @elseif($task->status === 'testing') bg-amber-100 text-amber-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-center font-bold">
                                <span class="text-xs {{ $task->priority === 'urgent' || $task->priority === 'high' ? 'text-rose-600' : 'text-gray-600' }}">
                                    {{ ucfirst($task->priority ?? 'Medium') }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-center font-medium text-gray-600">
                                {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No Due Date' }}
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                @if($task->is_overdue)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-100 text-rose-800 font-black rounded-full text-[10px] uppercase tracking-wide">
                                        ⚠️ {{ $task->days_overdue }} Days Overdue
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 font-bold rounded-full text-[11px]">
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
            <div class="text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                <p class="text-xs text-emerald-600 font-bold">All caught up! Zero pending or unfinished tasks carried over.</p>
            </div>
            @endif
        </div>

        <!-- Tab 3: Work Sessions & Time Logs -->
        <div x-show="activeTab === 'worklogs'" class="p-6 border-t border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-sm font-extrabold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                    Verified Work Sessions & Logs ({{ count($assessment['workLogs']) }})
                </h4>
                <span class="text-xs font-bold text-gray-500">
                    Total: {{ $assessment['totalHours'] }} Hours across {{ $assessment['daysPresent'] }} Days
                </span>
            </div>

            @if(count($assessment['workLogs']) > 0)
            <div class="overflow-x-auto rounded-xl border border-gray-100">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-4 py-3 text-left">Date</th>
                            <th class="px-4 py-3 text-center">Check-In</th>
                            <th class="px-4 py-3 text-center">Check-Out</th>
                            <th class="px-4 py-3 text-center">Duration</th>
                            <th class="px-4 py-3 text-left">Tasks / Notes</th>
                            <th class="px-4 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-xs">
                        @foreach($assessment['workLogs'] as $log)
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="px-4 py-3 font-bold text-gray-900 whitespace-nowrap">
                                {{ $log->start_time ? $log->start_time->format('D, M d, Y') : '—' }}
                            </td>
                            <td class="px-4 py-3 text-center font-medium text-gray-600">
                                {{ $log->start_time ? $log->start_time->format('H:i') : '—' }}
                            </td>
                            <td class="px-4 py-3 text-center font-medium text-gray-600">
                                {{ $log->end_time ? $log->end_time->format('H:i') : 'In Progress' }}
                            </td>
                            <td class="px-4 py-3 text-center font-bold text-blue-600">
                                {{ round($log->duration_minutes / 60, 1) }}h ({{ $log->duration_minutes }}m)
                            </td>
                            <td class="px-4 py-3 text-gray-700 max-w-xs truncate" title="{{ $log->actual_tasks ?? $log->session_goal ?? $log->planned_tasks }}">
                                {{ $log->actual_tasks ?? $log->session_goal ?? $log->planned_tasks ?? 'Routine session' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                    {{ $log->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $log->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                <p class="text-xs text-gray-500 font-medium">No work log entries recorded for this evaluation period.</p>
            </div>
            @endif
        </div>

        <!-- Tab 4: Score Audit Breakdown -->
        <div x-show="activeTab === 'audit'" class="p-6 border-t border-gray-100">
            <h4 class="text-sm font-extrabold text-gray-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                KPI Algorithm Audit Breakdown
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Base Score -->
                <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Base Metric</span>
                    <div class="text-3xl font-black text-gray-900 mt-2">100.0</div>
                    <p class="text-xs text-gray-500 mt-1">Starting baseline index for every evaluation period</p>
                </div>

                <!-- Bonuses -->
                <div class="bg-emerald-50 rounded-2xl p-5 border border-emerald-100">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">+ Bonus Points</span>
                    <div class="text-3xl font-black text-emerald-700 mt-2">+{{ number_format($assessment['metrics']['total_bonus'] ?? 0, 1) }}</div>
                    <ul class="text-xs text-emerald-800 mt-2 space-y-1">
                        <li>• Early task deliveries: {{ $assessment['metrics']['tasks']['early'] ?? 0 }} (+2 pts each)</li>
                    </ul>
                </div>

                <!-- Penalties -->
                <div class="bg-rose-50 rounded-2xl p-5 border border-rose-100">
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-700">- Penalties Incurred</span>
                    <div class="text-3xl font-black text-rose-700 mt-2">-{{ number_format($assessment['metrics']['total_penalty'] ?? 0, 1) }}</div>
                    <ul class="text-xs text-rose-800 mt-2 space-y-1">
                        <li>• Overdue / Late tasks: {{ $assessment['metrics']['tasks']['late'] ?? 0 }} (-5 pts each)</li>
                        <li>• Missed check-ins: {{ $assessment['metrics']['attendance']['missed_checkins'] ?? 0 }}</li>
                        <li>• Unrealistic / Force checkouts: {{ ($assessment['metrics']['violations']['unrealistic_count'] ?? 0) + ($assessment['metrics']['violations']['failed_checkout_count'] ?? 0) }}</li>
                    </ul>
                </div>
            </div>

            <div class="mt-6 p-4 rounded-xl bg-gray-900 text-white flex items-center justify-between">
                <span class="text-sm font-bold uppercase tracking-wide">Final Evaluated Score:</span>
                <span class="text-2xl font-black text-brand-primary">{{ number_format($assessment['score'], 1) }}% ({{ $assessment['grade']['label'] }})</span>
            </div>
        </div>
    </div>

    <!-- Modal: Send Weekly Review Email -->
    <div x-show="emailModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="emailModalOpen = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form method="POST" action="{{ route('admin.team-performance.send-weekly-review') }}">
                    @csrf
                    <input type="hidden" name="target" value="{{ $user->id }}">

                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <span class="p-2 rounded-xl bg-brand-primary/10 text-brand-primary">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                Send Weekly Review to {{ $user->name }}
                            </h3>
                            <button @click="emailModalOpen = false" type="button" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <p class="text-xs text-gray-500 mb-4 leading-relaxed">
                            This will dispatch a branded performance report email directly to <strong>{{ $user->email }}</strong> containing their weekly score ({{ number_format($assessment['score'], 1) }}%), completed tasks ({{ count($assessment['completedTasks']) }}), pending items ({{ count($assessment['pendingTasks']) }}), overdue tasks ({{ count($assessment['overdueTasks']) }}), and logged hours.
                        </p>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Evaluation Week</label>
                                <input type="date" name="week" x-model="selectedWeek" class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                                <p class="text-[11px] text-gray-400 mt-1">Defaults to the selected week date.</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Optional Manager Note / Message</label>
                                <textarea name="custom_note" x-model="customNote" rows="3" placeholder="Add custom feedback, recognition, or instructions for this week..." class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:border-transparent"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-100">
                        <a :href="'/admin/team-performance/preview-review-email/{{ $user->id }}?week=' + selectedWeek + '&custom_note=' + encodeURIComponent(customNote)" target="_blank" class="text-xs font-bold text-brand-primary hover:underline">
                            👁️ Preview Email in Browser
                        </a>

                        <div class="flex items-center gap-2">
                            <button @click="emailModalOpen = false" type="button" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-bold hover:bg-gray-50 transition-all">
                                Cancel
                            </button>
                            <button type="submit" class="px-5 py-2 bg-brand-primary text-white rounded-xl text-sm font-bold hover:bg-brand-primary/90 transition-all shadow-md shadow-brand-primary/20">
                                Send Review Email
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
