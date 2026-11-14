@extends('teamportal::layouts.app')

@section('title', 'Project Manager Dashboard')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Project Manager Dashboard</h1>
            <p class="mt-1 text-xs sm:text-sm text-gray-400">Manage your projects, team members, and track delivery progress</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('team.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-semibold text-gray-300 transition-colors">
                &larr; Team Dashboard
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Active Projects -->
        <div class="bg-[#10231C]/60 backdrop-blur-sm border border-white/10 rounded-3xl p-6 text-white shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Active Projects</p>
                    <p class="mt-2 text-3xl sm:text-4xl font-black text-white">{{ $stats['active_projects'] }}</p>
                    <p class="mt-1 text-xs text-gray-400">of {{ $stats['total_projects'] }} total</p>
                </div>
                <div class="h-12 w-12 rounded-2xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Team Members -->
        <div class="bg-[#10231C]/60 backdrop-blur-sm border border-white/10 rounded-3xl p-6 text-white shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Team Members</p>
                    <p class="mt-2 text-3xl sm:text-4xl font-black text-white">{{ $stats['total_team_members'] }}</p>
                    <p class="mt-1 text-xs text-gray-400">across all projects</p>
                </div>
                <div class="h-12 w-12 rounded-2xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Tasks -->
        <div class="bg-[#10231C]/60 backdrop-blur-sm border border-white/10 rounded-3xl p-6 text-white shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Pending Tasks</p>
                    <p class="mt-2 text-3xl sm:text-4xl font-black text-white">{{ $stats['pending_tasks'] }}</p>
                    @if($stats['overdue_tasks'] > 0)
                    <p class="mt-1 text-xs text-red-400 font-semibold">⚠️ {{ $stats['overdue_tasks'] }} overdue</p>
                    @else
                    <p class="mt-1 text-xs text-emerald-400 font-semibold">All on track</p>
                    @endif
                </div>
                <div class="h-12 w-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Project Health -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-3xl border border-white/10 shadow-2xl overflow-hidden">
                <div class="p-6 border-b border-white/10">
                    <h2 class="text-base font-bold text-white uppercase tracking-wider flex items-center gap-2">
                        <svg class="h-5 w-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Project Health
                    </h2>
                </div>
                <div class="divide-y divide-white/5">
                    @forelse($projectHealth as $item)
                    <div class="p-6 hover:bg-white/[0.03] transition-colors">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1 min-w-0 pr-4">
                                <h3 class="font-bold text-white text-sm truncate">{{ $item['project']->name }}</h3>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $item['project']->client->name ?? 'Internal' }}
                                    @if($item['project']->due_date)
                                        &bull; <span class="text-gray-300">Due: {{ $item['project']->due_date->format('M d, Y') }}</span>
                                    @endif
                                </p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                @if($item['health'] === 'critical')
                                <span class="px-2.5 py-1 rounded-full bg-red-500/10 border border-red-500/30 text-red-400 text-[10px] font-bold uppercase tracking-wider">Critical</span>
                                @elseif($item['health'] === 'warning')
                                <span class="px-2.5 py-1 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-400 text-[10px] font-bold uppercase tracking-wider">Warning</span>
                                @else
                                <span class="px-2.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">Good</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="flex-1">
                                <div class="flex items-center justify-between text-[11px] text-gray-400 mb-1">
                                    <span>Progress</span>
                                    <span class="font-bold text-white">{{ $item['completion'] }}%</span>
                                </div>
                                <div class="h-2 bg-white/10 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 transition-all duration-500" style="width: {{ $item['completion'] }}%"></div>
                                </div>
                            </div>
                            @if($item['overdue_tasks'] > 0)
                            <span class="text-xs text-red-400 font-semibold shrink-0">{{ $item['overdue_tasks'] }} overdue</span>
                            @endif
                            <a href="{{ route('team.pm.projects.show', $item['project']) }}" class="text-emerald-400 hover:text-emerald-300 transition-colors shrink-0">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="p-12 text-center">
                        <p class="text-gray-400 italic text-xs sm:text-sm">No projects assigned yet</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Activity & Quick Actions -->
        <div class="space-y-6">
            <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-3xl border border-white/10 shadow-2xl overflow-hidden">
                <div class="p-6 border-b border-white/10">
                    <h2 class="text-base font-bold text-white uppercase tracking-wider">Recent Activity</h2>
                </div>
                <div class="divide-y divide-white/5 max-h-[480px] overflow-y-auto">
                    @forelse($recentActivity as $task)
                    <div class="p-4 hover:bg-white/[0.03] transition-colors">
                        <div class="flex items-start gap-3">
                            <div class="shrink-0 mt-1">
                                <div class="w-2 h-2 rounded-full {{ $task->status === 'done' ? 'bg-emerald-400' : 'bg-blue-400' }}"></div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm font-bold text-white truncate">{{ $task->title }}</p>
                                <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $task->project->name }}</p>
                                <div class="flex items-center gap-2 mt-2 text-[10px] text-gray-400">
                                    @if($task->assignee)
                                    <span class="text-gray-300 font-semibold">{{ $task->assignee->name }}</span>
                                    <span>&bull;</span>
                                    @endif
                                    <span title="{{ $task->updated_at->diffForHumans() }}">{{ $task->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center">
                        <p class="text-xs text-gray-400 italic">No recent activity</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-3xl border border-white/10 shadow-2xl p-6">
                <h3 class="text-xs font-bold text-gray-300 uppercase tracking-wider mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('team.pm.projects.index') }}" class="flex items-center gap-3 p-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-2xl text-white transition-colors text-xs font-bold">
                        <svg class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>View All Projects</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
