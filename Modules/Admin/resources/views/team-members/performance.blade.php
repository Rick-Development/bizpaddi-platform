@extends('admin::layouts.app')

@section('title', 'Team Performance')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Team Performance</h1>
                <p class="mt-2 text-sm text-gray-500">View monthly performance metrics and KPIs for your team</p>
            </div>
            <a href="{{ route('admin.team-members.index') }}" class="px-4 py-2 bg-gray-900 text-white rounded-xl text-sm font-bold hover:bg-black transition-all">
                Back to Team
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Month</label>
                <select name="month" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $m, 1)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                <select name="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Team Member</label>
                <select name="team_member" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                    <option value="">All Team Members</option>
                    @foreach($teamMembers as $member)
                        <option value="{{ $member->id }}" {{ $teamMemberId == $member->id ? 'selected' : '' }}>
                            {{ $member->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
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
            <p class="mt-2 text-xs text-gray-500">Completed this month</p>
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
                Performance for {{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}
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
