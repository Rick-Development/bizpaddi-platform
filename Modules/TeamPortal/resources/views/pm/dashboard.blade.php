@extends('teamportal::layouts.app')

@section('title', 'Project Manager Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Project Manager Dashboard</h1>
    <p class="mt-2 text-sm text-gray-600">Manage your projects, team members, and track progress</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-8">
    <!-- Active Projects -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium uppercase tracking-wider">Active Projects</p>
                <p class="mt-2 text-4xl font-bold">{{ $stats['active_projects'] }}</p>
                <p class="mt-1 text-sm text-blue-100">of {{ $stats['total_projects'] }} total</p>
            </div>
            <div class="bg-white/20 rounded-full p-4">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Team Members -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium uppercase tracking-wider">Team Members</p>
                <p class="mt-2 text-4xl font-bold">{{ $stats['total_team_members'] }}</p>
                <p class="mt-1 text-sm text-purple-100">across all projects</p>
            </div>
            <div class="bg-white/20 rounded-full p-4">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Pending Tasks -->
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm font-medium uppercase tracking-wider">Pending Tasks</p>
                <p class="mt-2 text-4xl font-bold">{{ $stats['pending_tasks'] }}</p>
                @if($stats['overdue_tasks'] > 0)
                <p class="mt-1 text-sm text-orange-100 font-semibold">⚠️ {{ $stats['overdue_tasks'] }} overdue</p>
                @else
                <p class="mt-1 text-sm text-orange-100">All on track</p>
                @endif
            </div>
            <div class="bg-white/20 rounded-full p-4">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Project Health -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Project Health
                </h2>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($projectHealth as $item)
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $item['project']->name }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $item['project']->client->name ?? 'No client' }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($item['health'] === 'critical')
                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">Critical</span>
                            @elseif($item['health'] === 'warning')
                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold">Warning</span>
                            @else
                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">Good</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex-1">
                            <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                                <span>Progress</span>
                                <span class="font-semibold">{{ $item['completion'] }}%</span>
                            </div>
                            <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-brand-primary transition-all duration-500" style="width: {{ $item['completion'] }}%"></div>
                            </div>
                        </div>
                        @if($item['overdue_tasks'] > 0)
                        <span class="text-xs text-red-600 font-semibold">{{ $item['overdue_tasks'] }} overdue</span>
                        @endif
                        <a href="{{ route('team.pm.projects.show', $item['project']) }}" class="text-brand-primary hover:text-brand-primary/80 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center">
                    <p class="text-gray-500 italic">No projects assigned yet</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-900">Recent Activity</h2>
            </div>
            <div class="divide-y divide-gray-100 max-h-[600px] overflow-y-auto">
                @forelse($recentActivity as $task)
                <div class="p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-2 h-2 rounded-full {{ $task->status === 'done' ? 'bg-green-500' : 'bg-blue-500' }}"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $task->title }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $task->project->name }}</p>
                            <div class="flex items-center gap-2 mt-2">
                                @if($task->assignee)
                                <span class="text-xs text-gray-600">{{ $task->assignee->name }}</span>
                                @endif
                                <span class="text-xs text-gray-400">{{ $task->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center">
                    <p class="text-sm text-gray-500 italic">No recent activity</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-gradient-to-br from-gray-900 to-black rounded-2xl shadow-lg p-6 text-white">
            <h3 class="text-lg font-bold mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('team.pm.projects.index') }}" class="flex items-center gap-3 p-3 bg-white/10 hover:bg-white/20 rounded-xl transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="text-sm font-semibold">View All Projects</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
