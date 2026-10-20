@extends('teamportal::layouts.app')
@section('title', 'Bug Reports')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Bug Reports</h1>
            <p class="text-sm text-gray-500 mt-1">Track and resolve project issues and bug reports</p>
        </div>
        @if(auth()->user()->isAdmin() || auth()->user()->isProjectManager() || auth()->user()->isQaTester())
        <a href="{{ route('team.qa.bugs.create') }}" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 rounded-xl shadow transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Report Bug
        </a>
        @endif
    </div>

    {{-- Filters --}}
    <form method="GET" class="bg-white rounded-2xl border border-gray-200 p-4 shadow-sm">
        <div class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[180px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search bugs…"
                    class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm focus:ring-brand-primary focus:border-brand-primary">
            </div>
            <select name="status" class="rounded-xl border border-gray-300 px-3 py-2 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                <option value="">All Statuses</option>
                <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                <option value="wont_fix" {{ request('status') === 'wont_fix' ? 'selected' : '' }}>Won't Fix</option>
            </select>
            <select name="severity" class="rounded-xl border border-gray-300 px-3 py-2 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                <option value="">All Severities</option>
                <option value="critical" {{ request('severity') === 'critical' ? 'selected' : '' }}>Critical</option>
                <option value="high" {{ request('severity') === 'high' ? 'selected' : '' }}>High</option>
                <option value="medium" {{ request('severity') === 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="low" {{ request('severity') === 'low' ? 'selected' : '' }}>Low</option>
            </select>
            <select name="project_id" class="rounded-xl border border-gray-300 px-3 py-2 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                <option value="">All Projects</option>
                @foreach($projects as $proj)
                <option value="{{ $proj->id }}" {{ request('project_id') == $proj->id ? 'selected' : '' }}>{{ $proj->name }}</option>
                @endforeach
            </select>
            @if(!empty($isPmOrAdmin) && isset($teamMembers) && $teamMembers->isNotEmpty())
            <select name="assigned_to" class="rounded-xl border border-gray-300 px-3 py-2 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                <option value="">All Assignees</option>
                <option value="unassigned" {{ request('assigned_to') === 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                @foreach($teamMembers as $m)
                <option value="{{ $m->id }}" {{ request('assigned_to') == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                @endforeach
            </select>
            @endif
            <button type="submit" class="bg-gray-900 hover:bg-black text-white text-sm font-semibold px-4 py-2 rounded-xl transition">Filter</button>
            @if(request()->hasAny(['search','status','severity','project_id','assigned_to']))
            <a href="{{ route('team.qa.bugs.index') }}" class="text-sm text-gray-500 hover:text-gray-800 px-3 py-2">Reset</a>
            @endif
        </div>
    </form>

    {{-- Bug Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Project</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Assigned To</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Severity</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Priority</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Reported</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($bugs as $bug)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <a href="{{ route('team.qa.bugs.show', $bug) }}" class="font-medium text-gray-900 hover:text-brand-primary transition text-sm">{{ $bug->title }}</a>
                        <div class="flex items-center gap-2 mt-1">
                            @if($bug->assigned_to === auth()->id())
                            <span class="text-[10px] bg-blue-50 text-blue-700 border border-blue-200 px-1.5 py-0.5 rounded font-semibold">Assigned to you</span>
                            @elseif($bug->reported_by === auth()->id())
                            <span class="text-[10px] bg-purple-50 text-purple-700 border border-purple-200 px-1.5 py-0.5 rounded font-semibold">Reported by you</span>
                            @endif
                            @if($bug->environment)
                            <span class="text-[10px] bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded">{{ $bug->environment }}</span>
                            @endif
                            @if($bug->due_date)
                            <span class="inline-flex items-center gap-1 text-[11px] {{ $bug->is_overdue ? 'text-red-600 font-bold' : 'text-gray-500' }}" title="Deadline: {{ $bug->due_date->format('M j, Y • g:i A') }}">
                                <svg class="w-3 h-3 {{ $bug->is_overdue ? 'text-red-500' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ $bug->due_date->format('M d, g:i A') }}
                                @if($bug->is_overdue)
                                <span class="text-[9px] bg-red-100 text-red-700 px-1 rounded font-bold uppercase">Overdue</span>
                                @endif
                            </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-600">{{ $bug->project?->name ?? '—' }}</td>
                    <td class="px-4 py-4 text-sm">
                        @if($bug->assignee)
                            <div class="flex items-center gap-1.5">
                                <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                                    {{ substr($bug->assignee->name, 0, 1) }}
                                </span>
                                <span class="text-xs font-medium {{ $bug->assigned_to == auth()->id() ? 'text-brand-primary font-bold' : 'text-gray-900' }}">
                                    {{ $bug->assigned_to == auth()->id() ? 'You' : $bug->assignee->name }}
                                </span>
                            </div>
                        @else
                            <span class="text-xs text-gray-400 italic">Unassigned</span>
                        @endif
                    </td>
                    <td class="px-4 py-4">
                        <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-full
                            @if($bug->severity === 'critical') bg-red-100 text-red-700
                            @elseif($bug->severity === 'high') bg-orange-100 text-orange-700
                            @elseif($bug->severity === 'medium') bg-yellow-100 text-yellow-700
                            @else bg-green-100 text-green-700 @endif">
                            {{ ucfirst($bug->severity) }}
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-full
                            @if($bug->priority === 'urgent') bg-red-100 text-red-700
                            @elseif($bug->priority === 'high') bg-orange-100 text-orange-700
                            @elseif($bug->priority === 'medium') bg-yellow-100 text-yellow-700
                            @else bg-green-100 text-green-700 @endif">
                            {{ ucfirst($bug->priority) }}
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-full border
                            @if($bug->status === 'open') border-blue-200 bg-blue-50 text-blue-700
                            @elseif($bug->status === 'in_progress') border-amber-200 bg-amber-50 text-amber-700
                            @elseif($bug->status === 'resolved') border-emerald-200 bg-emerald-50 text-emerald-700
                            @elseif($bug->status === 'closed') border-gray-200 bg-gray-50 text-gray-600
                            @else border-red-200 bg-red-50 text-red-700 @endif">
                            {{ $bug->status_label }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-xs text-gray-400">{{ $bug->created_at->diffForHumans() }}</td>
                    <td class="px-4 py-4 text-right">
                        <a href="{{ route('team.qa.bugs.show', $bug) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-brand-primary hover:text-red-700 transition">
                            View Details
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-16 text-center">
                        <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 12.75c1.148 0 2.278.08 3.383.237 1.037.146 1.866.966 1.866 2.013 0 3.728-2.35 6.75-5.25 6.75S6.75 18.728 6.75 15c0-1.046.83-1.867 1.866-2.013A24.204 24.204 0 0112 12.75z"/>
                        </svg>
                        <p class="text-sm text-gray-400">
                            @if(auth()->user()->isAdmin() || auth()->user()->isProjectManager())
                                No bug reports found.
                            @else
                                No bug reports assigned to or reported by you.
                            @endif
                        </p>
                        @if(auth()->user()->isAdmin() || auth()->user()->isProjectManager() || auth()->user()->isQaTester())
                        <a href="{{ route('team.qa.bugs.create') }}" class="mt-2 inline-block text-sm text-brand-primary font-semibold hover:underline">Report a bug →</a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($bugs->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $bugs->links() }}</div>
        @endif
    </div>

</div>
@endsection
