@extends('teamportal::layouts.app')

@section('title', 'My Performance & KPIs')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Header Section -->
    <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight sm:truncate">My Performance</h1>
            <p class="mt-2 text-sm text-gray-500 font-medium">
                Track your achievements, kpis, and monthly performance rewards.
            </p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-4 flex gap-3">
             <form action="{{ route('team.performance.index') }}" method="GET" class="flex items-center gap-2">
                <select name="month" class="block w-full pl-3 pr-10 py-2 text-sm border-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary rounded-xl transition-all">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                    @endforeach
                </select>
                <select name="year" class="block w-full pl-3 pr-10 py-2 text-sm border-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary rounded-xl transition-all">
                    @foreach(range(date('Y')-2, date('Y')) as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-xs font-bold rounded-xl hover:bg-black transition-all">Go</button>
             </form>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Main Score Card -->
        <div class="md:col-span-2 bg-white rounded-3xl shadow-xl shadow-brand-primary/5 border border-brand-primary/10 p-8 flex items-center justify-between relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-xs font-bold text-brand-primary uppercase tracking-widest mb-2">Performance Score</p>
                <h2 class="text-6xl font-black text-gray-900">{{ number_format($performance->score, 1) }}%</h2>
                <p class="mt-4 text-sm text-gray-500 font-medium">
                    Started with <span class="text-gray-900 font-bold">100%</span> at month begin.
                </p>
            </div>
            <div class="relative z-10 text-center">
                 <div class="h-24 w-24 rounded-full border-8 border-brand-primary/10 flex items-center justify-center p-4">
                    <span class="text-2xl font-black text-brand-primary">{{ $performance->score >= 100 ? '🚀' : '📈' }}</span>
                 </div>
            </div>
            <!-- Background Decoration -->
            <div class="absolute -right-4 -bottom-4 h-32 w-32 bg-brand-primary/5 rounded-full blur-3xl group-hover:bg-brand-primary/10 transition-all"></div>
        </div>

        <!-- Completion Rate -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Task Completion</p>
            <div class="flex items-end justify-between">
                <div>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $performance->tasks_completed }}</h3>
                    <p class="text-xs text-gray-500 font-medium mt-1">Tasks finalized</p>
                </div>
                <div class="p-2.5 bg-green-50 rounded-2xl text-green-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Late Penalty -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Deadlines Missed</p>
            <div class="flex items-end justify-between">
                <div>
                    <h3 class="text-3xl font-bold text-red-600">{{ $performance->tasks_late }}</h3>
                    <p class="text-xs text-gray-500 font-medium mt-1">Penalty applied</p>
                </div>
                <div class="p-2.5 bg-red-50 rounded-2xl text-red-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <!-- Breakdown & KPI -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-900">Points Breakdown</h3>
                    <span class="text-xs font-bold text-gray-400 uppercase">This Month</span>
                </div>
                <div class="p-8">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">100</div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">Base Performance</p>
                                    <p class="text-xs text-gray-500 font-medium">Automatic reset every month begin</p>
                                </div>
                            </div>
                            <span class="text-sm font-bold text-gray-400">Fixed</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">Efficiency Bonus</p>
                                    <p class="text-xs text-gray-500 font-medium">{{ $performance->tasks_early }} tasks completed ahead of schedule (+2.0 points each)</p>
                                </div>
                            </div>
                            <span class="text-sm font-black text-green-600">+ {{ number_format($performance->bonus_points, 1) }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M13 17H5m0 0v-8m0 8l8-8 4 4 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">Punctuality Penalty</p>
                                    <p class="text-xs text-gray-500 font-medium">{{ $performance->tasks_late }} tasks completed after deadline (-5.0 points each)</p>
                                </div>
                            </div>
                            <span class="text-sm font-black text-red-600">- {{ number_format($performance->penalty_points, 1) }}</span>
                        </div>
                    </div>

                    <div class="mt-10 pt-8 border-t border-gray-50 flex justify-between items-center">
                         <p class="text-sm font-bold text-gray-400 uppercase">Final Month End Est.</p>
                         <h4 class="text-2xl font-black text-gray-900">{{ number_format($performance->score, 1) }}%</h4>
                    </div>
                </div>
            </div>

            <!-- Task Log -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-900">Recent Scoring Events</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Task</th>
                                <th class="px-4 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Status</th>
                                <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Impact</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($recentTasks as $task)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-4">
                                    <p class="text-sm font-bold text-gray-900 truncate max-w-xs">{{ $task->title }}</p>
                                    <p class="text-[10px] text-gray-400 font-medium font-mono uppercase mt-0.5">Closed: {{ $task->completed_at->format('M d, H:i') }}</p>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @php
                                        $isLate = $task->due_date && $task->completed_at > $task->due_date;
                                        $isEarly = $task->due_date && $task->completed_at->diffInDays($task->due_date) >= 1;
                                    @endphp
                                    @if($isLate)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700">LATE</span>
                                    @elseif($isEarly)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700">EARLY</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-700">ON TIME</span>
                                    @endif
                                </td>
                                <td class="px-8 py-4 text-right">
                                    @if($isLate)
                                        <span class="text-sm font-black text-red-600">- 5.0</span>
                                    @elseif($isEarly)
                                        <span class="text-sm font-black text-green-600">+ 2.0</span>
                                    @else
                                        <span class="text-sm font-black text-gray-400">0.0</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-8 py-12 text-center text-gray-400 italic text-sm">No task completion events recorded for this period yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar / KPIs -->
        <div class="space-y-6">
            <div class="bg-gray-900 rounded-3xl p-8 text-white relative overflow-hidden shadow-2xl">
                 <div class="relative z-10">
                    <h4 class="text-xl font-black mb-2">Team KPIs</h4>
                    <p class="text-gray-400 text-xs font-medium leading-relaxed">Your performance score directly impacts monthly bonuses and eligibility for "Performer of the Month".</p>
                    
                    <div class="mt-8 space-y-8">
                        @foreach($kpis as $kpi)
                        <div>
                            <div class="flex justify-between items-end mb-2.5">
                                <span class="text-xs font-black uppercase tracking-widest text-gray-400">{{ $kpi->name }}</span>
                                <span class="text-lg font-black">{{ $performance->score }}<span class="text-xs text-gray-500 font-bold">%</span></span>
                            </div>
                            <div class="w-full bg-white/10 rounded-full h-1.5">
                                <div class="bg-brand-primary h-1.5 rounded-full shadow-sm" style="width: {{ min(100, $performance->score) }}%"></div>
                            </div>
                            <p class="mt-2 text-[10px] text-gray-500 font-bold uppercase tracking-tighter">Target: {{ $kpi->target }}{{ $kpi->unit }}</p>
                        </div>
                        @endforeach

                        @if($kpis->isEmpty())
                        <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                             <p class="text-xs text-center text-gray-500 font-bold uppercase tracking-widest">Admin hasn't defined global KPIs yet.</p>
                        </div>
                        @endif
                    </div>
                 </div>
                 <!-- Decorative Art -->
                 <div class="absolute top-0 right-0 h-32 w-32 bg-brand-primary/10 rounded-full blur-[80px]"></div>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
                 <h4 class="text-sm font-bold text-gray-900 mb-4">Guidelines</h4>
                 <ul class="space-y-4">
                    <li class="flex gap-3">
                        <div class="h-5 w-5 bg-green-50 rounded-full flex items-center justify-center shrink-0">
                            <span class="text-[10px]">🟢</span>
                        </div>
                        <p class="text-xs text-gray-600 font-medium">Complete tasks at least <span class="font-bold text-gray-900">24h before</span> due date for +2.0 bonus points.</p>
                    </li>
                    <li class="flex gap-3">
                        <div class="h-5 w-5 bg-red-50 rounded-full flex items-center justify-center shrink-0">
                            <span class="text-[10px]">🔴</span>
                        </div>
                        <p class="text-xs text-gray-600 font-medium">Late submissions result in <span class="font-bold text-gray-900">-5.0 deduction</span> automatically.</p>
                    </li>
                    <li class="flex gap-3">
                        <div class="h-5 w-5 bg-blue-50 rounded-full flex items-center justify-center shrink-0">
                            <span class="text-[10px]">🔵</span>
                        </div>
                        <p class="text-xs text-gray-600 font-medium">Maintain above <span class="font-bold text-gray-900">95%</span> for the full month to stay eligible for reward bonuses.</p>
                    </li>
                 </ul>
            </div>
        </div>
    </div>
</div>
@endsection
