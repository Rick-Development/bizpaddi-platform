@extends('teamportal::layouts.app')
@section('title', 'Bug Reports')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Bug Reports</h1>
            <p class="text-sm text-gray-400 mt-1">Track and resolve project issues and bug reports</p>
        </div>
        @if(auth()->user()->isAdmin() || auth()->user()->isProjectManager() || auth()->user()->isQaTester())
        <a href="{{ route('team.qa.bugs.create') }}" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-500 text-white text-sm font-bold px-5 py-2.5 rounded-xl shadow-lg shadow-red-950/50 transition-all active:scale-95">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Report Bug
        </a>
        @endif
    </div>

    {{-- Filters --}}
    <form method="GET" class="bg-[#10231C]/60 backdrop-blur-sm rounded-2xl border border-white/10 p-5 shadow-xl">
        <div class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search bugs…"
                    class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white placeholder-gray-500 px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 shadow-inner">
            </div>
            <select name="status" class="rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 shadow-inner">
                <option value="" class="bg-[#10231C] text-white">All Statuses</option>
                <option value="open" {{ request('status') === 'open' ? 'selected' : '' }} class="bg-[#10231C] text-white">Open</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }} class="bg-[#10231C] text-white">In Progress</option>
                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }} class="bg-[#10231C] text-white">Resolved</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }} class="bg-[#10231C] text-white">Closed</option>
                <option value="wont_fix" {{ request('status') === 'wont_fix' ? 'selected' : '' }} class="bg-[#10231C] text-white">Won't Fix</option>
            </select>
            <select name="severity" class="rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 shadow-inner">
                <option value="" class="bg-[#10231C] text-white">All Severities</option>
                <option value="critical" {{ request('severity') === 'critical' ? 'selected' : '' }} class="bg-[#10231C] text-white">Critical</option>
                <option value="high" {{ request('severity') === 'high' ? 'selected' : '' }} class="bg-[#10231C] text-white">High</option>
                <option value="medium" {{ request('severity') === 'medium' ? 'selected' : '' }} class="bg-[#10231C] text-white">Medium</option>
                <option value="low" {{ request('severity') === 'low' ? 'selected' : '' }} class="bg-[#10231C] text-white">Low</option>
            </select>
            <select name="project_id" class="rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 shadow-inner">
                <option value="" class="bg-[#10231C] text-white">All Projects</option>
                @foreach($projects as $proj)
                <option value="{{ $proj->id }}" {{ request('project_id') == $proj->id ? 'selected' : '' }} class="bg-[#10231C] text-white">{{ $proj->name }}</option>
                @endforeach
            </select>
            @if(!empty($isPmOrAdmin) && isset($teamMembers) && $teamMembers->isNotEmpty())
            <select name="assigned_to" class="rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 shadow-inner">
                <option value="" class="bg-[#10231C] text-white">All Assignees</option>
                <option value="unassigned" {{ request('assigned_to') === 'unassigned' ? 'selected' : '' }} class="bg-[#10231C] text-white">Unassigned</option>
                @foreach($teamMembers as $m)
                <option value="{{ $m->id }}" {{ request('assigned_to') == $m->id ? 'selected' : '' }} class="bg-[#10231C] text-white">{{ $m->name }}</option>
                @endforeach
            </select>
            @endif
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition shadow-lg shadow-emerald-950/50">Filter</button>
            @if(request()->hasAny(['search','status','severity','project_id','assigned_to']))
            <a href="{{ route('team.qa.bugs.index') }}" class="text-sm text-gray-400 hover:text-white px-3 py-2 transition">Reset</a>
            @endif
        </div>
    </form>

    {{-- Bug Table --}}
    <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-2xl border border-white/10 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/5">
                <thead class="bg-white/5 border-b border-white/10">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Title</th>
                        <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Project</th>
                        <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Assigned To</th>
                        <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Severity</th>
                        <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Priority</th>
                        <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Reported</th>
                        <th class="px-4 py-3.5"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($bugs as $bug)
                    <tr class="hover:bg-white/[0.03] transition">
                        <td class="px-6 py-4">
                            <a href="{{ route('team.qa.bugs.show', $bug) }}" class="font-medium text-white hover:text-emerald-400 transition text-sm">{{ $bug->title }}</a>
                            <div class="flex items-center gap-2 mt-1">
                                @if($bug->assigned_to === auth()->id())
                                <span class="text-[10px] bg-blue-500/20 text-blue-300 border border-blue-500/30 px-2 py-0.5 rounded font-semibold">Assigned to you</span>
                                @elseif($bug->reported_by === auth()->id())
                                <span class="text-[10px] bg-purple-500/20 text-purple-300 border border-purple-500/30 px-2 py-0.5 rounded font-semibold">Reported by you</span>
                                @endif
                                @if($bug->environment)
                                <span class="text-[10px] bg-white/5 text-gray-400 border border-white/10 px-2 py-0.5 rounded">{{ $bug->environment }}</span>
                                @endif
                                @if($bug->due_date)
                                <span class="inline-flex items-center gap-1 text-[11px] {{ $bug->is_overdue ? 'text-red-400 font-bold' : 'text-gray-400' }}" title="Deadline: {{ $bug->due_date->format('M j, Y • g:i A') }}">
                                    <svg class="w-3 h-3 {{ $bug->is_overdue ? 'text-red-400' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    {{ $bug->due_date->format('M d, g:i A') }}
                                    @if($bug->is_overdue)
                                    <span class="text-[9px] bg-red-500/20 text-red-300 border border-red-500/30 px-1 rounded font-bold uppercase">Overdue</span>
                                    @endif
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-400">{{ $bug->project?->name ?? '—' }}</td>
                        <td class="px-4 py-4 text-sm">
                            @if($bug->assignee)
                                <div class="flex items-center gap-1.5">
                                    <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-xs font-bold">
                                        {{ substr($bug->assignee->name, 0, 1) }}
                                    </span>
                                    <span class="text-xs font-medium {{ $bug->assigned_to == auth()->id() ? 'text-emerald-400 font-bold' : 'text-gray-300' }}">
                                        {{ $bug->assigned_to == auth()->id() ? 'You' : $bug->assignee->name }}
                                    </span>
                                </div>
                            @else
                                <span class="text-xs text-gray-500 italic">Unassigned</span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-block text-xs font-bold px-2.5 py-1 rounded-full border
                                @if($bug->severity === 'critical') border-red-500/30 bg-red-500/20 text-red-300
                                @elseif($bug->severity === 'high') border-amber-500/30 bg-amber-500/20 text-amber-300
                                @elseif($bug->severity === 'medium') border-yellow-500/30 bg-yellow-500/20 text-yellow-300
                                @else border-emerald-500/30 bg-emerald-500/20 text-emerald-300 @endif">
                                {{ ucfirst($bug->severity) }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-block text-xs font-bold px-2.5 py-1 rounded-full border
                                @if($bug->priority === 'urgent') border-red-500/30 bg-red-500/20 text-red-300
                                @elseif($bug->priority === 'high') border-amber-500/30 bg-amber-500/20 text-amber-300
                                @elseif($bug->priority === 'medium') border-yellow-500/30 bg-yellow-500/20 text-yellow-300
                                @else border-emerald-500/30 bg-emerald-500/20 text-emerald-300 @endif">
                                {{ ucfirst($bug->priority) }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-block text-xs font-bold px-2.5 py-1 rounded-full border
                                @if($bug->status === 'open') border-blue-500/30 bg-blue-500/20 text-blue-300
                                @elseif($bug->status === 'in_progress') border-amber-500/30 bg-amber-500/20 text-amber-300
                                @elseif($bug->status === 'resolved') border-emerald-500/30 bg-emerald-500/20 text-emerald-300
                                @elseif($bug->status === 'closed') border-white/10 bg-white/5 text-gray-400
                                @else border-red-500/30 bg-red-500/20 text-red-300 @endif">
                                {{ $bug->status_label }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-xs text-gray-400">{{ $bug->created_at->diffForHumans() }}</td>
                        <td class="px-4 py-4 text-right">
                            <a href="{{ route('team.qa.bugs.show', $bug) }}" class="inline-flex items-center gap-1 text-xs font-bold text-emerald-400 hover:text-emerald-300 transition">
                                View Details
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-16 text-center">
                            <svg class="w-12 h-12 text-gray-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 12.75c1.148 0 2.278.08 3.383.237 1.037.146 1.866.966 1.866 2.013 0 3.728-2.35 6.75-5.25 6.75S6.75 18.728 6.75 15c0-1.046.83-1.867 1.866-2.013A24.204 24.204 0 0112 12.75z"/>
                            </svg>
                            <p class="text-sm text-gray-400">
                                @if(auth()->user()->isAdmin() || auth()->user()->isProjectManager())
                                    No bug reports found.
                                @else
                                    No bug reports assigned to you.
                                @endif
                            </p>
                            @if(auth()->user()->isAdmin() || auth()->user()->isProjectManager() || auth()->user()->isQaTester())
                            <a href="{{ route('team.qa.bugs.create') }}" class="mt-2 inline-block text-sm text-emerald-400 font-semibold hover:underline">Report a bug →</a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bugs->hasPages())
        <div class="px-6 py-4 border-t border-white/5">{{ $bugs->links() }}</div>
        @endif
    </div>

</div>
@endsection
