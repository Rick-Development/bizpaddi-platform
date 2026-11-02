@extends('admin::layouts.app')

@section('title', 'Team Member: ' . $teamMember->name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="border-b border-white/10 pb-5 sm:flex sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    Team Profile
                </span>
                <span class="text-xs text-gray-400">{{ $teamMember->department ?? 'General' }}</span>
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight mt-1">{{ $teamMember->name }}</h1>
            <p class="text-xs sm:text-sm text-gray-400">{{ $teamMember->email }}</p>
        </div>
        <div class="mt-4 sm:mt-0 flex flex-wrap items-center gap-2">
             <a href="{{ route('admin.team-members.impersonate', $teamMember) }}" class="px-3.5 py-2 bg-[#0E5E4A] hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition shadow-sm flex items-center gap-1.5">
                 <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                 </svg>
                 <span>Login to Account</span>
             </a>
             <a href="{{ route('admin.team-members.edit', $teamMember) }}" class="px-3.5 py-2 border border-white/10 rounded-xl text-xs font-bold text-gray-200 bg-white/5 hover:bg-white/10 transition">Edit Member</a>
             <a href="{{ route('admin.team-members.index') }}" class="px-3.5 py-2 bg-white/10 border border-white/10 rounded-xl text-xs font-bold text-gray-300 hover:bg-white/15 transition">Back to List</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Stats & Performance -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Overall Performance Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gradient-to-br from-[#0E5E4A] to-emerald-800 rounded-2xl border border-emerald-500/30 p-5 text-white shadow-lg">
                    <p class="text-xs font-extrabold uppercase tracking-widest text-emerald-200/90 mb-1">Average Score</p>
                    <h2 class="text-3xl font-black">{{ number_format($averageScore, 1) }}%</h2>
                    <p class="mt-1 text-xs text-emerald-200/75">Across {{ $totalMonths }} {{ Str::plural('month', $totalMonths) }}</p>
                </div>
                <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 p-5">
                    <p class="text-xs font-extrabold uppercase tracking-widest text-gray-400 mb-1">Total Tasks</p>
                    <h2 class="text-3xl font-black text-white">{{ $totalTasksCompleted }}</h2>
                    <p class="mt-1 text-xs text-gray-500">Completed successfully</p>
                </div>
                <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 p-5">
                    <p class="text-xs font-extrabold uppercase tracking-widest text-gray-400 mb-1">On-Time Rate</p>
                    <h2 class="text-3xl font-black text-emerald-400">{{ number_format($onTimePercentage, 1) }}%</h2>
                    <p class="mt-1 text-xs text-gray-500">Tasks on schedule</p>
                </div>
            </div>

            <!-- Current Month Performance -->
            <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-widest text-emerald-400 mb-1">Current Month Performance</p>
                        <h2 class="text-4xl font-black text-white">{{ $currentPerformance ? number_format($currentPerformance->score, 1) : '100.0' }}%</h2>
                        <p class="mt-1 text-xs text-gray-400 font-medium capitalize">{{ now()->format('F Y') }}</p>
                    </div>
                    <div class="text-center">
                        <div class="h-20 w-20 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center">
                            <span class="text-3xl">{{ ($currentPerformance->score ?? 100) >= 100 ? '🚀' : '📈' }}</span>
                        </div>
                    </div>
                </div>

                @if($currentPerformance)
                <div class="grid grid-cols-3 gap-3 pt-5 border-t border-white/10">
                    <div class="text-center p-3.5 bg-emerald-500/10 rounded-xl border border-emerald-500/20">
                        <p class="text-xs font-extrabold uppercase tracking-wider text-emerald-400 mb-1">Completed</p>
                        <h4 class="text-2xl font-black text-white">{{ $currentPerformance->tasks_completed ?? 0 }}</h4>
                    </div>
                    <div class="text-center p-3.5 bg-blue-500/10 rounded-xl border border-blue-500/20">
                        <p class="text-xs font-extrabold uppercase tracking-wider text-blue-400 mb-1">Bonus</p>
                        <h4 class="text-2xl font-black text-white">+{{ $currentPerformance->bonus_points ?? 0.0 }}</h4>
                    </div>
                    <div class="text-center p-3.5 bg-rose-500/10 rounded-xl border border-rose-500/20">
                        <p class="text-xs font-extrabold uppercase tracking-wider text-rose-400 mb-1">Penalty</p>
                        <h4 class="text-2xl font-black text-white">-{{ $currentPerformance->penalty_points ?? 0.0 }}</h4>
                    </div>
                </div>
                @endif
            </div>

            <!-- Monthly Performance History -->
            <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-white/10 bg-white/5 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">Monthly Performance History</h3>
                    <span class="text-xs text-gray-400 font-medium">Since {{ $teamMember->created_at ? $teamMember->created_at->format('M Y') : 'Start' }}</span>
                </div>
                
                @if($performanceHistory->isEmpty())
                <div class="p-10 text-center">
                    <div class="mx-auto h-12 w-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center mb-3">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-white">No Performance Data Yet</h3>
                    <p class="mt-1 text-xs text-gray-400">Performance metrics will automatically appear once assigned tasks are completed.</p>
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10 text-sm">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-4 py-2.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Month</th>
                                <th class="px-4 py-2.5 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Score</th>
                                <th class="px-4 py-2.5 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Completed</th>
                                <th class="px-4 py-2.5 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Early</th>
                                <th class="px-4 py-2.5 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Late</th>
                                <th class="px-4 py-2.5 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Bonus</th>
                                <th class="px-4 py-2.5 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Penalty</th>
                                <th class="px-4 py-2.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @foreach($performanceHistory as $perf)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-xs font-bold text-white">{{ \Carbon\Carbon::create($perf->year, $perf->month, 1)->format('F Y') }}</div>
                                    <div class="text-[10px] text-gray-500">{{ \Carbon\Carbon::create($perf->year, $perf->month, 1)->diffForHumans() }}</div>
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
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Performance Summary -->
                <div class="px-6 py-4 bg-white/5 border-t border-white/10">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center">
                            <p class="text-[10px] text-gray-400 font-extrabold uppercase tracking-wider mb-1">Total Months</p>
                            <p class="text-base font-black text-white">{{ $totalMonths }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-[10px] text-gray-400 font-extrabold uppercase tracking-wider mb-1">Avg Score</p>
                            <p class="text-base font-black text-white">{{ number_format($averageScore, 1) }}%</p>
                        </div>
                        <div class="text-center">
                            <p class="text-[10px] text-gray-400 font-extrabold uppercase tracking-wider mb-1">Best Month</p>
                            <p class="text-base font-black text-emerald-400">{{ $performanceHistory->max('score') ? number_format($performanceHistory->max('score'), 1) : '0.0' }}%</p>
                        </div>
                        <div class="text-center">
                            <p class="text-[10px] text-gray-400 font-extrabold uppercase tracking-wider mb-1">Total Tasks</p>
                            <p class="text-base font-black text-white">{{ $totalTasksCompleted }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Details -->
        <div class="space-y-6">
            <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 p-6 shadow-sm">
                 <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Member Details</h3>
                 <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-extrabold uppercase tracking-wider text-gray-400 mb-1">Status</label>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold capitalize 
                            @if($teamMember->status === 'active') bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 @else bg-white/5 text-gray-400 border border-white/10 @endif">
                            {{ $teamMember->status }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold uppercase tracking-wider text-gray-400 mb-1">Role</label>
                        <span class="text-sm font-bold text-white">{{ $teamMember->getRoleNames()->first() ?? 'Member' }}</span>
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold uppercase tracking-wider text-gray-400 mb-1">Joined</label>
                        <span class="text-sm font-bold text-white">{{ $teamMember->created_at ? $teamMember->created_at->format('M d, Y') : 'Recently' }}</span>
                    </div>
                 </div>
            </div>

            <div class="bg-[#10231C]/90 rounded-2xl border border-white/10 p-6 text-white shadow-sm">
                 <h4 class="text-sm font-bold uppercase tracking-wider mb-2 text-white">Performance Engine</h4>
                 <p class="text-gray-400 text-xs leading-relaxed font-medium mb-4">The performance engine automatically adjusts scores based on Task Status updates and delivery milestones.</p>
                 <a href="{{ route('admin.tasks.index', ['assigned_to' => $teamMember->id]) }}" class="block w-full text-center py-2.5 bg-white/10 border border-white/10 rounded-xl text-xs font-bold text-white hover:bg-white/15 transition-all">View All Assigned Tasks</a>
            </div>
        </div>
    </div>

    <!-- Assigned Work Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Assigned Projects -->
        <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-white/10 bg-white/5">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">Assigned Projects</h3>
            </div>
            <div class="p-5">
                @forelse($assignedProjects as $project)
                <div class="flex items-center justify-between p-3.5 mb-3 bg-[#0a1612] rounded-xl border border-white/10 hover:border-emerald-500/30 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">{{ $project->name }}</h4>
                            <p class="text-[10px] text-gray-400 font-medium capitalize">{{ $project->status }}</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.pm-projects.show', $project) }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300">View Project &rarr;</a>
                </div>
                @empty
                <p class="text-center text-xs text-gray-500 py-6">No projects currently assigned via tasks.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Assigned Tasks -->
        <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-white/10 bg-white/5">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">Recent Tasks</h3>
            </div>
            <div class="p-5">
                @forelse($assignedTasks->take(5) as $task)
                <div class="flex items-center justify-between p-3.5 mb-3 bg-[#0a1612] rounded-xl border border-white/10 hover:border-emerald-500/30 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">{{ $task->title }}</h4>
                            <p class="text-[10px] text-gray-400 font-medium">{{ $task->project->name ?? 'No Project' }} &bull; <span class="capitalize text-emerald-400">{{ $task->status }}</span></p>
                        </div>
                    </div>
                    <a href="{{ route('admin.tasks.show', $task) }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300">View Task &rarr;</a>
                </div>
                @empty
                <p class="text-center text-xs text-gray-500 py-6">No tasks currently assigned.</p>
                @endforelse
                
                @if($assignedTasks->count() > 5)
                <div class="mt-3 text-center">
                    <a href="{{ route('admin.tasks.index', ['assigned_to' => $teamMember->id]) }}" class="text-xs font-bold text-gray-400 hover:text-emerald-400 transition">See all tasks ({{ $assignedTasks->count() }})</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
