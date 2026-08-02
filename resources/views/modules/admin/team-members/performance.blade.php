@extends('admin::layouts.app')

@section('title', 'Team Performance')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Team Performance</h1>
                <p class="mt-2 text-sm text-gray-500">View performance metrics and KPIs based on tasks and attendance</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.team-members.index') }}" class="px-4 py-2 bg-gray-900 text-white rounded-xl text-sm font-bold hover:bg-black transition-all">
                    Back to Team
                </a>

                <!-- Export Dropdown -->
                <div x-data="{ open: false }" class="relative inline-block text-left">
                    <button @click="open = !open" type="button" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-primary text-white text-sm font-bold rounded-xl hover:bg-brand-primary/90 transition-colors shadow-sm">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Export Report
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
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
                <label class="block text-sm font-medium text-gray-700 mb-2">Target Audience</label>
                <select name="team_member" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                    <optgroup label="Groups">
                        <option value="group_active" {{ request('team_member') == 'group_active' ? 'selected' : '' }}>All Active Team</option>
                        <option value="group_suspended" {{ request('team_member') == 'group_suspended' ? 'selected' : '' }}>All Suspended Team</option>
                        <option value="group_inactive" {{ request('team_member') == 'group_inactive' ? 'selected' : '' }}>All Inactive Team</option>
                    </optgroup>
                    <optgroup label="Individual Team Members">
                        @foreach($teamMembers as $member)
                            <option value="{{ $member->id }}" {{ request('team_member') == $member->id ? 'selected' : '' }}>
                                {{ $member->name }}
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
            <p class="mt-2 text-xs opacity-75">With performance data</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Average Score</p>
            <h2 class="text-4xl font-black text-gray-900">{{ number_format($averageScore, 1) }}%</h2>
            <p class="mt-2 text-xs text-gray-500">Team performance</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Total Tasks</p>
            <h2 class="text-4xl font-black text-gray-900">{{ $totalTasksCompleted }}</h2>
            <p class="mt-2 text-xs text-gray-500">Completed in period</p>
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

    <!-- Performance Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-900">
                Performance for {{ $periodLabel }}
            </h3>
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
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Tasks Completed</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Early</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Late</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Bonus</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Penalty</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
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
                                    <div class="text-xs text-gray-500">{{ $perf->user->department ?? 'No Department' }}</div>
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
                            <a href="{{ route('admin.team-members.show', $perf->user) }}" class="text-brand-primary hover:text-brand-primary/80 font-medium text-sm">
                                View Profile →
                            </a>
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
</div>
@endsection
