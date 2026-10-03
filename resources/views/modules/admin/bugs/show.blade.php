@extends('admin::layouts.app')
@section('title', 'Bug #' . $bug->id . ': ' . $bug->title)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- Breadcrumb & Actions --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.bugs.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 flex items-center gap-1.5 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Back to Bug Reports
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.bugs.edit', $bug) }}" class="inline-flex items-center gap-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-3.5 py-2 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                Edit Bug
            </a>
            <form action="{{ route('admin.bugs.destroy', $bug) }}" method="POST" onsubmit="return confirm('Delete this bug report completely?')">
                @csrf @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-semibold px-3.5 py-2 rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                    Delete
                </button>
            </form>
        </div>
    </div>

    {{-- Main Header Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs font-bold text-gray-400">#{{ $bug->id }}</span>
                    <span class="text-xs font-semibold text-brand-primary uppercase tracking-wider">{{ $bug->project?->name ?? 'No Project Assigned' }}</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">{{ $bug->title }}</h1>
                <div class="flex flex-wrap items-center gap-2 mt-3">
                    <span class="text-xs font-bold px-3 py-1 rounded-full
                        @if($bug->severity === 'critical') bg-red-100 text-red-700
                        @elseif($bug->severity === 'high') bg-orange-100 text-orange-700
                        @elseif($bug->severity === 'medium') bg-yellow-100 text-yellow-700
                        @else bg-green-100 text-green-700 @endif">
                        {{ ucfirst($bug->severity) }} Severity
                    </span>
                    <span class="text-xs font-medium px-3 py-1 rounded-full bg-gray-100 text-gray-700">
                        {{ ucfirst($bug->priority) }} Priority
                    </span>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full border
                        @if($bug->status === 'open') border-blue-200 bg-blue-50 text-blue-700
                        @elseif($bug->status === 'in_progress') border-amber-200 bg-amber-50 text-amber-700
                        @elseif($bug->status === 'resolved') border-emerald-200 bg-emerald-50 text-emerald-700
                        @elseif($bug->status === 'closed') border-gray-200 bg-gray-50 text-gray-600
                        @else border-red-200 bg-red-50 text-red-700 @endif">
                        {{ $bug->status_label }}
                    </span>
                    @if($bug->due_date)
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full border {{ $bug->is_overdue ? 'border-red-300 bg-red-50 text-red-700' : 'border-purple-200 bg-purple-50 text-purple-700' }}">
                        <svg class="w-3.5 h-3.5 {{ $bug->is_overdue ? 'text-red-500' : 'text-purple-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Deadline: {{ $bug->due_date->format('M j, Y • g:i A') }}
                        @if($bug->is_overdue)
                        <span class="text-[10px] bg-red-200 text-red-800 font-bold px-1.5 py-0.2 rounded ml-0.5 uppercase">Overdue</span>
                        @endif
                    </span>
                    @endif
                </div>
            </div>
            <div class="text-right text-xs text-gray-400">
                <p>Reported {{ $bug->created_at->format('M j, Y • g:i A') }}</p>
                @if($bug->resolved_at)
                <p class="text-emerald-600 font-medium mt-1">Resolved {{ $bug->resolved_at->format('M j, Y • g:i A') }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left 2 Columns: Issue Description, Steps, Results --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Description --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Issue Description</h3>
                <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed">
                    @if(strip_tags($bug->description) !== $bug->description)
                        {!! $bug->description !!}
                    @else
                        {!! nl2br(e($bug->description)) !!}
                    @endif
                </div>
            </div>

            {{-- Steps to Reproduce --}}
            @if($bug->steps_to_reproduce)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Steps to Reproduce</h3>
                <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed font-mono bg-gray-50 p-4 rounded-xl border border-gray-100">{{ $bug->steps_to_reproduce }}</p>
            </div>
            @endif

            {{-- Expected vs Actual --}}
            @if($bug->expected_result || $bug->actual_result)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                @if($bug->expected_result)
                <div>
                    <h3 class="text-xs font-bold text-emerald-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Expected Behaviour
                    </h3>
                    <p class="text-sm text-gray-700 whitespace-pre-line bg-emerald-50/50 p-3 rounded-xl border border-emerald-100">{{ $bug->expected_result }}</p>
                </div>
                @endif
                @if($bug->actual_result)
                <div>
                    <h3 class="text-xs font-bold text-red-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Actual Behaviour
                    </h3>
                    <p class="text-sm text-gray-700 whitespace-pre-line bg-red-50/50 p-3 rounded-xl border border-red-100">{{ $bug->actual_result }}</p>
                </div>
                @endif
            </div>
            @endif

            {{-- Resolution Notes --}}
            @if($bug->resolution_notes)
            <div class="bg-emerald-50 rounded-2xl border border-emerald-200 p-6">
                <h3 class="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-2">Resolution / Fix Notes</h3>
                <p class="text-sm text-emerald-800 whitespace-pre-line">{{ $bug->resolution_notes }}</p>
            </div>
            @endif

            {{-- Screenshots & Media --}}
            @if($bug->getMedia('screenshots')->count())
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Screenshots ({{ $bug->getMedia('screenshots')->count() }})</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($bug->getMedia('screenshots') as $media)
                    <a href="{{ $media->getUrl() }}" target="_blank" class="block rounded-xl overflow-hidden border border-gray-200 hover:shadow-md transition">
                        <img src="{{ $media->getUrl() }}" alt="Screenshot" class="w-full h-36 object-cover hover:scale-105 transition-transform duration-200">
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- Right Sidebar: Status & Assignment Controls, Details --}}
        <div class="space-y-6">

            {{-- Admin Control Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-4">
                <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Manage Status & Assignment</h3>
                <form action="{{ route('admin.bugs.update-status', $bug) }}" method="POST" class="space-y-3">
                    @csrf @method('PATCH')

                    <div>
                        <label for="status" class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                        <select name="status" id="status" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                            <option value="open" {{ $bug->status === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ $bug->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ $bug->status === 'resolved' ? 'selected' : '' }}>Resolved (Fixed)</option>
                            <option value="closed" {{ $bug->status === 'closed' ? 'selected' : '' }}>Closed (Verified)</option>
                            <option value="wont_fix" {{ $bug->status === 'wont_fix' ? 'selected' : '' }}>Won't Fix</option>
                        </select>
                    </div>

                    <div>
                        <label for="assigned_to" class="block text-xs font-medium text-gray-600 mb-1">Assign Developer / Team Member</label>
                        <select name="assigned_to" id="assigned_to" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                            <option value="">Unassigned</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $bug->assigned_to == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ ucfirst($user->role ?? 'Team') }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="due_date" class="block text-xs font-medium text-gray-600 mb-1">Deadline / Timeframe</label>
                        <input type="datetime-local" name="due_date" id="due_date"
                            value="{{ old('due_date', $bug->due_date ? $bug->due_date->format('Y-m-d\TH:i') : '') }}"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                    </div>

                    <div>
                        <label for="resolution_notes" class="block text-xs font-medium text-gray-600 mb-1">Resolution Notes</label>
                        <textarea name="resolution_notes" id="resolution_notes" rows="3" placeholder="Enter fix details or internal notes..."
                            class="w-full rounded-xl border border-gray-200 p-2.5 text-xs focus:ring-brand-primary focus:border-brand-primary">{{ old('resolution_notes', $bug->resolution_notes) }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white text-sm font-semibold py-2.5 px-4 rounded-xl transition shadow-sm">
                        Update Status & Assignee
                    </button>
                </form>
            </div>

            {{-- Bug Metadata --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-3">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Meta Details</h3>
                <div class="space-y-2.5 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Project</span>
                        <span class="font-medium text-gray-800">{{ $bug->project?->name ?? '—' }}</span>
                    </div>
                    @if($bug->task)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Linked Task</span>
                        <span class="font-medium text-gray-800 text-right max-w-[60%] truncate" title="{{ $bug->task->title }}">{{ $bug->task->title }}</span>
                    </div>
                    @endif
                    @if($bug->environment)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Environment</span>
                        <span class="font-medium text-gray-800">{{ $bug->environment }}</span>
                    </div>
                    @endif
                    @if($bug->browser)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Browser</span>
                        <span class="font-medium text-gray-800">{{ $bug->browser }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-500">Reported By</span>
                        <span class="font-medium text-gray-800">{{ $bug->reporter?->name ?? 'Admin' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Assigned To</span>
                        <span class="font-medium text-gray-800">{{ $bug->assignee?->name ?? 'Unassigned' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Report Date</span>
                        <span class="font-medium text-gray-800">{{ $bug->created_at->format('M j, Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-1 border-t border-gray-50">
                        <span class="text-gray-500">Deadline</span>
                        @if($bug->due_date)
                        <span class="font-medium text-xs text-right {{ $bug->is_overdue ? 'text-red-600 font-bold' : 'text-gray-800' }}">
                            {{ $bug->due_date->format('M j, Y \a\t g:i A') }}
                            @if($bug->is_overdue)
                            <span class="block text-[10px] text-red-500 font-bold uppercase">Overdue</span>
                            @endif
                        </span>
                        @else
                        <span class="font-medium text-gray-400 italic">None set</span>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
