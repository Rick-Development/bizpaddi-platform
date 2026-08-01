@extends('admin::layouts.app')

@section('title', 'Team Member: ' . $teamMember->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $teamMember->name }}</h1>
            <p class="text-sm text-gray-500 font-medium lowercase tracking-wide mt-1">{{ $teamMember->email }} &bull; {{ $teamMember->department }}</p>
        </div>
        <div class="flex gap-3">
             <a href="{{ route('admin.team-members.edit', $teamMember) }}" class="px-4 py-2 border border-gray-200 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all">Edit Member</a>
             <a href="{{ route('admin.team-members.index') }}" class="px-4 py-2 bg-gray-900 text-white rounded-xl text-sm font-bold hover:bg-black transition-all">Back to List</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Stats & Performance -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Overall Performance Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-brand-primary to-purple-600 rounded-3xl shadow-xl p-6 text-white">
                    <p class="text-xs font-bold uppercase tracking-widest opacity-90 mb-2">Average Score</p>
                    <h2 class="text-4xl font-black">{{ number_format($averageScore, 1) }}%</h2>
                    <p class="mt-2 text-xs opacity-75">Across {{ $totalMonths }} {{ Str::plural('month', $totalMonths) }}</p>
                </div>
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Total Tasks</p>
                    <h2 class="text-4xl font-black text-gray-900">{{ $totalTasksCompleted }}</h2>
                    <p class="mt-2 text-xs text-gray-500">Completed successfully</p>
                </div>
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">On-Time Rate</p>
                    <h2 class="text-4xl font-black text-gray-900">{{ number_format($onTimePercentage, 1) }}%</h2>
                    <p class="mt-2 text-xs text-gray-500">Tasks on schedule</p>
                </div>
            </div>

            <!-- Current Month Performance -->
            <div class="bg-white rounded-3xl shadow-xl shadow-brand-primary/5 border border-brand-primary/10 p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-xs font-bold text-brand-primary uppercase tracking-widest mb-2">Current Month Performance</p>
                        <h2 class="text-5xl font-black text-gray-900">{{ $currentPerformance ? number_format($currentPerformance->score, 1) : '100.0' }}%</h2>
                        <p class="mt-2 text-sm text-gray-500 font-medium capitalize">{{ now()->format('F Y') }}</p>
                    </div>
                    <div class="text-center">
                        <div class="h-28 w-28 rounded-full border-[10px] border-brand-primary/10 flex items-center justify-center">
                            <span class="text-3xl">{{ ($currentPerformance->score ?? 100) >= 100 ? '🚀' : '📈' }}</span>
                        </div>
                    </div>
                </div>

                @if($currentPerformance)
                <div class="grid grid-cols-3 gap-4 pt-6 border-t border-gray-100">
                    <div class="text-center p-4 bg-green-50/50 rounded-xl">
                        <p class="text-xs font-bold text-green-600 uppercase tracking-widest mb-1">Completed</p>
                        <h4 class="text-2xl font-black text-gray-900">{{ $currentPerformance->tasks_completed ?? 0 }}</h4>
                    </div>
                    <div class="text-center p-4 bg-blue-50/50 rounded-xl">
                        <p class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-1">Bonus</p>
                        <h4 class="text-2xl font-black text-gray-900">+{{ $currentPerformance->bonus_points ?? 0.0 }}</h4>
                    </div>
                    <div class="text-center p-4 bg-red-50/50 rounded-xl">
                        <p class="text-xs font-bold text-red-600 uppercase tracking-widest mb-1">Penalty</p>
                        <h4 class="text-2xl font-black text-gray-900">-{{ $currentPerformance->penalty_points ?? 0.0 }}</h4>
                    </div>
                </div>
                @endif
            </div>

            <!-- Monthly Performance History -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">Monthly Performance History</h3>
                    <span class="text-xs text-gray-500 font-medium">Since {{ $teamMember->created_at->format('M Y') }}</span>
                </div>
                
                @if($performanceHistory->isEmpty())
                <div class="p-12 text-center">
                    <div class="mx-auto h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">No Performance Data Yet</h3>
                    <p class="mt-2 text-sm text-gray-500">Performance metrics will appear once tasks are completed.</p>
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Month</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Score</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Completed</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Early</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Late</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Bonus</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Penalty</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($performanceHistory as $perf)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::create($perf->year, $perf->month, 1)->format('F Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::create($perf->year, $perf->month, 1)->diffForHumans() }}</div>
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
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Performance Summary -->
                <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center">
                            <p class="text-xs text-gray-500 font-medium mb-1">Total Months</p>
                            <p class="text-lg font-black text-gray-900">{{ $totalMonths }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500 font-medium mb-1">Avg Score</p>
                            <p class="text-lg font-black text-gray-900">{{ number_format($averageScore, 1) }}%</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500 font-medium mb-1">Best Month</p>
                            <p class="text-lg font-black text-green-600">{{ $performanceHistory->max('score') ? number_format($performanceHistory->max('score'), 1) : '0.0' }}%</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500 font-medium mb-1">Total Tasks</p>
                            <p class="text-lg font-black text-gray-900">{{ $totalTasksCompleted }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Details -->
        <div class="space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8">
                 <h3 class="text-lg font-bold text-gray-900 mb-6">Member Details</h3>
                 <div class="space-y-5">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold capitalize 
                            @if($teamMember->status === 'active') bg-green-100 text-green-700 @else bg-gray-100 text-gray-700 @endif">
                            {{ $teamMember->status }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Role</label>
                        <span class="text-sm font-bold text-gray-900">{{ $teamMember->getRoleNames()->first() ?? 'No Role' }}</span>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Joined</label>
                        <span class="text-sm font-bold text-gray-900">{{ $teamMember->created_at->format('M d, Y') }}</span>
                    </div>
                 </div>
            </div>

            <div class="bg-gray-900 rounded-3xl p-8 text-white">
                 <h4 class="text-xl font-black mb-4">Performance Log</h4>
                 <p class="text-gray-400 text-xs leading-relaxed font-medium mb-6">System automatically adjusts scores based on Task Status updates. This month has seen <span class="text-white font-bold">{{ $performance->tasks_completed ?? 0 }}</span> qualifying events.</p>
                 <a href="{{ route('admin.tasks.index', ['assigned_to' => $teamMember->id]) }}" class="block w-full text-center py-3 bg-white/10 border border-white/10 rounded-2xl text-xs font-bold hover:bg-white/20 transition-all uppercase tracking-widest">View All Tasks</a>
            </div>
        </div>
    </div>

    <!-- Assigned Work Section -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Assigned Projects -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900">Assigned Projects</h3>
            </div>
            <div class="p-6">
                @forelse($assignedProjects as $project)
                <div class="flex items-center justify-between p-4 mb-4 bg-gray-50 rounded-2xl border border-gray-100 hover:border-brand-primary/30 transition-all">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 rounded-xl bg-brand-primary/10 flex items-center justify-center text-brand-primary">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-900">{{ $project->name }}</h4>
                            <p class="text-xs text-gray-500 font-medium capitalize">{{ $project->status }}</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.pm-projects.show', $project) }}" class="text-xs font-bold text-brand-primary hover:underline">View Project</a>
                </div>
                @empty
                <p class="text-center text-sm text-gray-500 py-8">No projects currently assigned via tasks.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Assigned Tasks -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900">Recent Tasks</h3>
            </div>
            <div class="p-6">
                @forelse($assignedTasks->take(5) as $task)
                <div class="flex items-center justify-between p-4 mb-4 bg-gray-50 rounded-2xl border border-gray-100 hover:border-brand-primary/30 transition-all">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-900">{{ $task->title }}</h4>
                            <p class="text-xs text-gray-500 font-medium">{{ $task->project->name ?? 'No Project' }} &bull; <span class="capitalize">{{ $task->status }}</span></p>
                        </div>
                    </div>
                    <a href="{{ route('admin.tasks.show', $task) }}" class="text-xs font-bold text-brand-primary hover:underline">View Task</a>
                </div>
                @empty
                <p class="text-center text-sm text-gray-500 py-8">No tasks currently assigned.</p>
                @endforelse
                
                @if($assignedTasks->count() > 5)
                <div class="mt-4 text-center">
                    <a href="{{ route('admin.tasks.index', ['assigned_to' => $teamMember->id]) }}" class="text-xs font-bold text-gray-500 hover:text-brand-primary uppercase tracking-widest">See all tasks ({{ $assignedTasks->count() }})</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
