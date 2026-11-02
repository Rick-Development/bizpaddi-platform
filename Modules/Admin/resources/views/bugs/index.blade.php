@extends('admin::layouts.app')
@section('title', 'Bug Tracker & Quality Assurance')

@section('content')
<div class="space-y-6">

    {{-- Header & Stats --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Bug Reports & QA Tracker</h1>
            <p class="text-sm text-gray-400 mt-1">Review, assign, and track issues reported across all active client projects</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.bugs.create') }}" class="inline-flex items-center gap-2 bg-brand-primary hover:bg-brand-primary/90 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Log New Bug
            </a>
        </div>
    </div>

    {{-- Overview Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 p-4 shadow-sm">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Reported</p>
            <p class="text-2xl font-black text-white mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-[#10231C]/60 rounded-2xl border border-blue-100 p-4 shadow-sm bg-blue-50/20">
            <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Open</p>
            <p class="text-2xl font-black text-blue-700 mt-1">{{ $stats['open'] }}</p>
        </div>
        <div class="bg-[#10231C]/60 rounded-2xl border border-amber-100 p-4 shadow-sm bg-amber-50/20">
            <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">In Progress</p>
            <p class="text-2xl font-black text-amber-700 mt-1">{{ $stats['in_progress'] }}</p>
        </div>
        <div class="bg-[#10231C]/60 rounded-2xl border border-emerald-100 p-4 shadow-sm bg-emerald-50/20">
            <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Resolved</p>
            <p class="text-2xl font-black text-emerald-700 mt-1">{{ $stats['resolved'] }}</p>
        </div>
        <div class="bg-[#10231C]/60 rounded-2xl border border-red-100 p-4 shadow-sm bg-red-50/20 col-span-2 sm:col-span-1">
            <p class="text-xs font-semibold text-red-600 uppercase tracking-wider">Critical (Unfixed)</p>
            <p class="text-2xl font-black text-red-700 mt-1">{{ $stats['critical'] }}</p>
        </div>
    </div>

    {{-- Filter Bar --}}
    <form method="GET" class="bg-[#10231C]/60 rounded-2xl border border-white/10 p-4 shadow-sm">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3 items-center">
            <div class="md:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title, description or steps..."
                    class="w-full rounded-xl border border-white/10 px-3.5 py-2 text-sm focus:ring-brand-primary focus:border-brand-primary placeholder:text-gray-400 bg-[#0a1612] text-white border-white/10">
            </div>

            <div>
                <select name="status" class="w-full rounded-xl border border-white/10 px-3 py-2 text-sm bg-[#10231C]/60 focus:ring-brand-primary focus:border-brand-primary bg-[#0a1612] text-white border-white/10">
                    <option value="">All Statuses</option>
                    <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                    <option value="wont_fix" {{ request('status') === 'wont_fix' ? 'selected' : '' }}>Won't Fix</option>
                </select>
            </div>

            <div>
                <select name="project_id" class="w-full rounded-xl border border-white/10 px-3 py-2 text-sm bg-[#10231C]/60 focus:ring-brand-primary focus:border-brand-primary bg-[#0a1612] text-white border-white/10">
                    <option value="">All Projects</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 bg-gray-900 hover:bg-black text-white text-sm font-semibold px-4 py-2 rounded-xl transition">
                    Filter
                </button>
                @if(request()->hasAny(['search','status','severity','priority','project_id']))
                <a href="{{ route('admin.bugs.index') }}" class="text-xs text-gray-400 hover:text-white px-2 py-2">Reset</a>
                @endif
            </div>
        </div>
    </form>

    {{-- Bug Reports Table --}}
    <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-white/5">
            <thead class="bg-white/[0.03]/75">
                <tr>
                    <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Bug Details</th>
                    <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Project & Task</th>
                    <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Severity</th>
                    <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Priority</th>
                    <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Reporter / Assignee</th>
                    <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($bugs as $bug)
                <tr class="hover:bg-white/[0.03]/80 transition">
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.bugs.show', $bug) }}" class="font-semibold text-white hover:text-brand-primary transition text-sm">
                            {{ $bug->title }}
                        </a>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs text-gray-400">#{{ $bug->id }} • {{ $bug->created_at->diffForHumans() }}</span>
                            @if($bug->environment)
                            <span class="inline-flex items-center text-[10px] font-medium bg-white/[0.06] text-gray-400 px-2 py-0.5 rounded-full">{{ $bug->environment }}</span>
                            @endif
                            @if($bug->due_date)
                            <span class="inline-flex items-center gap-1 text-[11px] {{ $bug->is_overdue ? 'text-red-600 font-bold' : 'text-gray-400' }}" title="Deadline: {{ $bug->due_date->format('M j, Y • g:i A') }}">
                                <svg class="w-3 h-3 {{ $bug->is_overdue ? 'text-red-500' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ $bug->due_date->format('M d, g:i A') }}
                                @if($bug->is_overdue)
                                <span class="text-[9px] bg-red-100 text-red-700 px-1 rounded font-bold uppercase">Overdue</span>
                                @endif
                            </span>
                            @endif
                        </div>
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-400">
                        <div class="font-medium text-white">{{ $bug->project?->name ?? '—' }}</div>
                        @if($bug->task)
                        <div class="text-xs text-gray-400 truncate max-w-[200px]" title="{{ $bug->task->title }}">Task: {{ $bug->task->title }}</div>
                        @endif
                    </td>

                    <td class="px-4 py-4">
                        <span class="inline-block text-xs font-bold px-2.5 py-1 rounded-full
                            @if($bug->severity === 'critical') bg-red-100 text-red-700
                            @elseif($bug->severity === 'high') bg-orange-100 text-orange-700
                            @elseif($bug->severity === 'medium') bg-yellow-100 text-yellow-700
                            @else bg-green-100 text-green-700 @endif">
                            {{ ucfirst($bug->severity) }}
                        </span>
                    </td>

                    <td class="px-4 py-4">
                        <span class="inline-block text-xs font-medium px-2 py-0.5 rounded-md
                            @if($bug->priority === 'urgent') bg-red-50 text-red-700 border border-red-200
                            @elseif($bug->priority === 'high') bg-orange-50 text-orange-700 border border-orange-200
                            @elseif($bug->priority === 'medium') bg-yellow-50 text-yellow-700 border border-yellow-200
                            @else bg-white/[0.03] text-gray-400 border border-white/10 @endif">
                            {{ ucfirst($bug->priority) }}
                        </span>
                    </td>

                    <td class="px-4 py-4">
                        <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-full border
                            @if($bug->status === 'open') border-blue-200 bg-blue-50 text-blue-700
                            @elseif($bug->status === 'in_progress') border-amber-200 bg-amber-50 text-amber-700
                            @elseif($bug->status === 'resolved') border-emerald-200 bg-emerald-50 text-emerald-700
                            @elseif($bug->status === 'closed') border-white/10 bg-white/[0.03] text-gray-400
                            @else border-red-200 bg-red-50 text-red-700 @endif">
                            {{ $bug->status_label }}
                        </span>
                    </td>

                    <td class="px-4 py-4 text-xs text-gray-400">
                        <div><span class="text-gray-400">By:</span> {{ $bug->reporter?->name ?? 'System' }}</div>
                        <div class="mt-0.5"><span class="text-gray-400">Assigned:</span> {{ $bug->assignee?->name ?? 'Unassigned' }}</div>
                    </td>

                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.bugs.show', $bug) }}" class="p-1.5 text-gray-400 hover:text-brand-primary rounded-lg hover:bg-white/[0.06] transition" title="View Details">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </a>
                            <a href="{{ route('admin.bugs.edit', $bug) }}" class="p-1.5 text-gray-400 hover:text-white rounded-lg hover:bg-white/[0.06] transition" title="Edit">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                            </a>
                            <form action="{{ route('admin.bugs.destroy', $bug) }}" method="POST" onsubmit="return confirm('Delete this bug report?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition" title="Delete">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center text-gray-400">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 12.75c1.148 0 2.278.08 3.383.237 1.037.146 1.866.966 1.866 2.013 0 3.728-2.35 6.75-5.25 6.75S6.75 18.728 6.75 15c0-1.046.83-1.867 1.866-2.013A24.204 24.204 0 0112 12.75z"/>
                        </svg>
                        <p class="font-medium">No bug reports match your filter criteria.</p>
                        <a href="{{ route('admin.bugs.create') }}" class="inline-block mt-2 text-sm text-brand-primary font-semibold hover:underline">Log a bug report →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($bugs->hasPages())
        <div class="px-6 py-4 border-t border-white/10 bg-white/[0.03]/50">
            {{ $bugs->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
