@extends('teamportal::layouts.app')
@section('title', 'Bug Report: ' . $bug->title)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- Back + Actions --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('team.qa.bugs.index') }}" class="text-sm text-gray-400 hover:text-white flex items-center gap-1.5 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Back to Bug Reports
        </a>
        @if($canManage)
        <div class="flex gap-2">
            <a href="{{ route('team.qa.bugs.edit', $bug) }}" class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/15 text-white border border-white/10 text-sm font-semibold px-4 py-2 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                Edit
            </a>
            <form action="{{ route('team.qa.bugs.destroy', $bug) }}" method="POST" onsubmit="return confirm('Delete this bug report?')">
                @csrf @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-1.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/30 text-sm font-semibold px-4 py-2 rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                    Delete
                </button>
            </form>
        </div>
        @endif
    </div>

    {{-- Bug Header Card --}}
    <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-2xl border border-white/10 shadow-xl p-6">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div class="flex-1">
                <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">{{ $bug->title }}</h1>
                <div class="flex flex-wrap items-center gap-2 mt-3">
                    {{-- Severity --}}
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full border
                        @if($bug->severity === 'critical') border-red-500/30 bg-red-500/20 text-red-300
                        @elseif($bug->severity === 'high') border-amber-500/30 bg-amber-500/20 text-amber-300
                        @elseif($bug->severity === 'medium') border-yellow-500/30 bg-yellow-500/20 text-yellow-300
                        @else border-emerald-500/30 bg-emerald-500/20 text-emerald-300 @endif">
                        {{ ucfirst($bug->severity) }} Severity
                    </span>
                    {{-- Priority --}}
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full border
                        @if($bug->priority === 'urgent') border-red-500/30 bg-red-500/20 text-red-300
                        @elseif($bug->priority === 'high') border-amber-500/30 bg-amber-500/20 text-amber-300
                        @elseif($bug->priority === 'medium') border-yellow-500/30 bg-yellow-500/20 text-yellow-300
                        @else border-white/10 bg-white/5 text-gray-300 @endif">
                        {{ ucfirst($bug->priority) }} Priority
                    </span>
                    {{-- Status --}}
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full border
                        @if($bug->status === 'open') border-blue-500/30 bg-blue-500/20 text-blue-300
                        @elseif($bug->status === 'in_progress') border-amber-500/30 bg-amber-500/20 text-amber-300
                        @elseif($bug->status === 'resolved') border-emerald-500/30 bg-emerald-500/20 text-emerald-300
                        @elseif($bug->status === 'closed') border-white/10 bg-white/5 text-gray-400
                        @else border-red-500/30 bg-red-500/20 text-red-300 @endif">
                        {{ $bug->status_label }}
                    </span>
                    @if($bug->due_date)
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full border {{ $bug->is_overdue ? 'border-red-500/30 bg-red-500/20 text-red-300' : 'border-purple-500/30 bg-purple-500/20 text-purple-300' }}">
                        <svg class="w-3.5 h-3.5 {{ $bug->is_overdue ? 'text-red-400' : 'text-purple-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Deadline: {{ $bug->due_date->format('M j, Y • g:i A') }}
                        @if($bug->is_overdue)
                        <span class="text-[10px] bg-red-500/40 text-red-200 font-extrabold px-1.5 py-0.2 rounded ml-0.5 uppercase">Overdue</span>
                        @endif
                    </span>
                    @endif
                </div>
            </div>
            <div class="text-right text-xs text-gray-400">
                <p>Reported {{ $bug->created_at->diffForHumans() }}</p>
                @if($bug->resolved_at)
                <p class="text-emerald-400 font-semibold mt-1">Resolved {{ $bug->resolved_at->diffForHumans() }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Description --}}
            <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-2xl border border-white/10 shadow-xl p-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Description</h3>
                <p class="text-sm text-gray-200 whitespace-pre-line leading-relaxed">{{ $bug->description }}</p>
            </div>

            {{-- Steps to Reproduce --}}
            @if($bug->steps_to_reproduce)
            <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-2xl border border-white/10 shadow-xl p-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Steps to Reproduce</h3>
                <p class="text-sm text-gray-200 whitespace-pre-line leading-relaxed">{{ $bug->steps_to_reproduce }}</p>
            </div>
            @endif

            {{-- Expected vs Actual --}}
            @if($bug->expected_result || $bug->actual_result)
            <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-2xl border border-white/10 shadow-xl p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                @if($bug->expected_result)
                <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20">
                    <h3 class="text-xs font-bold text-emerald-400 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Expected Result
                    </h3>
                    <p class="text-sm text-gray-200 whitespace-pre-line">{{ $bug->expected_result }}</p>
                </div>
                @endif
                @if($bug->actual_result)
                <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/20">
                    <h3 class="text-xs font-bold text-red-400 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Actual Result
                    </h3>
                    <p class="text-sm text-gray-200 whitespace-pre-line">{{ $bug->actual_result }}</p>
                </div>
                @endif
            </div>
            @endif

            {{-- Resolution Notes --}}
            @if($bug->resolution_notes)
            <div class="bg-emerald-950/40 rounded-2xl border border-emerald-500/40 p-6">
                <h3 class="text-xs font-bold text-emerald-400 uppercase tracking-wider mb-2">Resolution Notes</h3>
                <p class="text-sm text-emerald-200 whitespace-pre-line">{{ $bug->resolution_notes }}</p>
            </div>
            @endif

            {{-- Screenshots --}}
            @if($bug->getMedia('screenshots')->count())
            <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-2xl border border-white/10 shadow-xl p-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Screenshots ({{ $bug->getMedia('screenshots')->count() }})</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($bug->getMedia('screenshots') as $media)
                    <a href="{{ $media->getUrl() }}" target="_blank" class="block rounded-xl overflow-hidden border border-white/10 hover:border-emerald-500/40 hover:opacity-90 transition">
                        <img src="{{ $media->getUrl() }}" alt="Screenshot" class="w-full h-32 object-cover">
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- Sidebar Details & Quick Status Update --}}
        <div class="space-y-5">

            {{-- Status Update Card --}}
            <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-2xl border border-white/10 shadow-xl p-5 space-y-4">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Update Status</h3>
                <form action="{{ route('team.qa.bugs.update-status', $bug) }}" method="POST" class="space-y-4">
                    @csrf @method('PATCH')
                    <div>
                        <label for="status" class="block text-xs font-semibold text-gray-300 mb-1.5">Status</label>
                        <select name="status" id="status" class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2 text-sm focus:ring-2 focus:ring-emerald-500">
                            <option value="open" {{ $bug->status === 'open' ? 'selected' : '' }} class="bg-[#10231C] text-white">Open</option>
                            <option value="in_progress" {{ $bug->status === 'in_progress' ? 'selected' : '' }} class="bg-[#10231C] text-white">In Progress (Fixing)</option>
                            <option value="resolved" {{ $bug->status === 'resolved' ? 'selected' : '' }} class="bg-[#10231C] text-white">Resolved (Fixed / Done)</option>
                            @if($canManage)
                            <option value="closed" {{ $bug->status === 'closed' ? 'selected' : '' }} class="bg-[#10231C] text-white">Closed (Verified)</option>
                            <option value="wont_fix" {{ $bug->status === 'wont_fix' ? 'selected' : '' }} class="bg-[#10231C] text-white">Won't Fix</option>
                            @endif
                        </select>
                    </div>

                    @if(!empty($canAssign) || auth()->user()->isAdmin() || auth()->user()->isProjectManager())
                    <div>
                        <label for="assigned_to" class="block text-xs font-semibold text-gray-300 mb-1.5">Assign Developer</label>
                        <select name="assigned_to" id="assigned_to" class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2 text-sm focus:ring-2 focus:ring-emerald-500">
                            <option value="" class="bg-[#10231C] text-white">Unassigned</option>
                            @foreach($assignableUsers as $u)
                            <option value="{{ $u->id }}" {{ old('assigned_to', $bug->assigned_to) == $u->id ? 'selected' : '' }} class="bg-[#10231C] text-white">
                                {{ $u->name }} ({{ ucfirst(str_replace('_', ' ', $u->role ?? 'Team')) }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="due_date" class="block text-xs font-semibold text-gray-300 mb-1.5">Deadline / Timeframe</label>
                        <input type="datetime-local" name="due_date" id="due_date"
                            value="{{ old('due_date', $bug->due_date ? $bug->due_date->format('Y-m-d\TH:i') : '') }}"
                            class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2 text-sm focus:ring-2 focus:ring-emerald-500">
                    </div>
                    @endif

                    <div>
                        <label for="resolution_notes" class="block text-xs font-semibold text-gray-300 mb-1.5">Resolution / Fix Notes</label>
                        <textarea name="resolution_notes" id="resolution_notes" rows="3" placeholder="Briefly describe what was fixed..." class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white p-3 text-xs focus:ring-2 focus:ring-emerald-500 placeholder-gray-500">{{ old('resolution_notes', $bug->resolution_notes) }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold py-2.5 px-4 rounded-xl transition shadow-lg shadow-emerald-950/50 active:scale-95">
                        Update Status
                    </button>
                </form>
            </div>

            <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-2xl border border-white/10 shadow-xl p-5 space-y-4">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Details</h3>
                <div class="space-y-3.5 text-sm">
                    <div>
                        <span class="block text-xs font-medium text-gray-400">Project</span>
                        <p class="font-semibold text-white mt-0.5">{{ $bug->project?->name ?? 'None' }}</p>
                    </div>
                    @if($bug->task)
                    <div>
                        <span class="block text-xs font-medium text-gray-400">Related Task</span>
                        <a href="{{ route('team.tasks.show', $bug->task) }}" class="font-semibold text-emerald-400 hover:underline mt-0.5 block truncate">{{ $bug->task->title }}</a>
                    </div>
                    @endif
                    <div>
                        <span class="block text-xs font-medium text-gray-400">Reported By</span>
                        <p class="font-semibold text-white mt-0.5">{{ $bug->reporter?->name ?? 'Unknown' }}</p>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-400">Assigned To</span>
                        <p class="font-semibold text-white mt-0.5">{{ $bug->assignee?->name ?? 'Unassigned' }}</p>
                    </div>
                    @if($bug->environment)
                    <div>
                        <span class="block text-xs font-medium text-gray-400">Environment</span>
                        <p class="font-semibold text-white mt-0.5">{{ $bug->environment }}</p>
                    </div>
                    @endif
                    @if($bug->browser)
                    <div>
                        <span class="block text-xs font-medium text-gray-400">Browser / Platform</span>
                        <p class="font-semibold text-white mt-0.5">{{ $bug->browser }}</p>
                    </div>
                    @endif
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
