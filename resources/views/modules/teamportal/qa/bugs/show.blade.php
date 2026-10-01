@extends('teamportal::layouts.app')
@section('title', 'Bug Report: ' . $bug->title)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Back + Actions --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('team.qa.bugs.index') }}" class="text-sm text-gray-500 hover:text-gray-800 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Back to Bug Reports
        </a>
        @if($canManage)
        <div class="flex gap-2">
            <a href="{{ route('team.qa.bugs.edit', $bug) }}" class="inline-flex items-center gap-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-3 py-2 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                Edit
            </a>
            <form action="{{ route('team.qa.bugs.destroy', $bug) }}" method="POST" onsubmit="return confirm('Delete this bug report?')">
                @csrf @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-semibold px-3 py-2 rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                    Delete
                </button>
            </form>
        </div>
        @endif
    </div>

    {{-- Bug Header Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div class="flex-1">
                <h1 class="text-xl font-bold text-gray-900">{{ $bug->title }}</h1>
                <div class="flex flex-wrap items-center gap-2 mt-2">
                    {{-- Severity --}}
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                        @if($bug->severity === 'critical') bg-red-100 text-red-700
                        @elseif($bug->severity === 'high') bg-orange-100 text-orange-700
                        @elseif($bug->severity === 'medium') bg-yellow-100 text-yellow-700
                        @else bg-green-100 text-green-700 @endif">
                        {{ ucfirst($bug->severity) }} Severity
                    </span>
                    {{-- Priority --}}
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-gray-100 text-gray-600">
                        {{ ucfirst($bug->priority) }} Priority
                    </span>
                    {{-- Status --}}
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full border
                        @if($bug->status === 'open') border-blue-200 bg-blue-50 text-blue-700
                        @elseif($bug->status === 'in_progress') border-amber-200 bg-amber-50 text-amber-700
                        @elseif($bug->status === 'resolved') border-emerald-200 bg-emerald-50 text-emerald-700
                        @elseif($bug->status === 'closed') border-gray-200 bg-gray-50 text-gray-600
                        @else border-red-200 bg-red-50 text-red-700 @endif">
                        {{ $bug->status_label }}
                    </span>
                </div>
            </div>
            <div class="text-right text-xs text-gray-400">
                <p>Reported {{ $bug->created_at->diffForHumans() }}</p>
                @if($bug->resolved_at)
                <p class="text-emerald-600 font-medium">Resolved {{ $bug->resolved_at->diffForHumans() }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Description --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-3">Description</h3>
                <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $bug->description }}</p>
            </div>

            {{-- Steps to Reproduce --}}
            @if($bug->steps_to_reproduce)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-3">Steps to Reproduce</h3>
                <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $bug->steps_to_reproduce }}</p>
            </div>
            @endif

            {{-- Expected vs Actual --}}
            @if($bug->expected_result || $bug->actual_result)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                @if($bug->expected_result)
                <div>
                    <h3 class="text-xs font-bold text-emerald-700 uppercase tracking-wide mb-2 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Expected Result
                    </h3>
                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $bug->expected_result }}</p>
                </div>
                @endif
                @if($bug->actual_result)
                <div>
                    <h3 class="text-xs font-bold text-red-700 uppercase tracking-wide mb-2 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Actual Result
                    </h3>
                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $bug->actual_result }}</p>
                </div>
                @endif
            </div>
            @endif

            {{-- Resolution Notes --}}
            @if($bug->resolution_notes)
            <div class="bg-emerald-50 rounded-2xl border border-emerald-200 p-6">
                <h3 class="text-sm font-bold text-emerald-800 uppercase tracking-wide mb-2">Resolution Notes</h3>
                <p class="text-sm text-emerald-700 whitespace-pre-line">{{ $bug->resolution_notes }}</p>
            </div>
            @endif

            {{-- Screenshots --}}
            @if($bug->getMedia('screenshots')->count())
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-4">Screenshots ({{ $bug->getMedia('screenshots')->count() }})</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($bug->getMedia('screenshots') as $media)
                    <a href="{{ $media->getUrl() }}" target="_blank" class="block rounded-xl overflow-hidden border border-gray-200 hover:opacity-90 transition">
                        <img src="{{ $media->getUrl() }}" alt="Screenshot" class="w-full h-32 object-cover">
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- Sidebar Details & Quick Status Update --}}
        <div class="space-y-4">

            {{-- Status Update Card (For Developers, Designers, QA, Admins) --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-3">
                <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wide">Update Status</h3>
                <form action="{{ route('team.qa.bugs.update-status', $bug) }}" method="POST" class="space-y-3">
                    @csrf @method('PATCH')
                    <div>
                        <label for="status" class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                        <select name="status" id="status" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                            <option value="open" {{ $bug->status === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ $bug->status === 'in_progress' ? 'selected' : '' }}>In Progress (Fixing)</option>
                            <option value="resolved" {{ $bug->status === 'resolved' ? 'selected' : '' }}>Resolved (Fixed / Done)</option>
                            @if($canManage)
                            <option value="closed" {{ $bug->status === 'closed' ? 'selected' : '' }}>Closed (Verified)</option>
                            <option value="wont_fix" {{ $bug->status === 'wont_fix' ? 'selected' : '' }}>Won't Fix</option>
                            @endif
                        </select>
                    </div>

                    <div>
                        <label for="resolution_notes" class="block text-xs font-medium text-gray-500 mb-1">Resolution / Fix Notes</label>
                        <textarea name="resolution_notes" id="resolution_notes" rows="3" placeholder="Briefly describe what was fixed..." class="w-full rounded-xl border border-gray-300 p-2.5 text-xs focus:ring-brand-primary focus:border-brand-primary">{{ old('resolution_notes', $bug->resolution_notes) }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-brand-primary hover:opacity-90 text-white text-sm font-semibold py-2 px-3 rounded-xl transition shadow-sm">
                        Update Status
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-4">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wide">Details</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Project</span>
                        <span class="font-medium text-gray-800">{{ $bug->project?->name ?? '—' }}</span>
                    </div>
                    @if($bug->task)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Task</span>
                        <span class="font-medium text-gray-800 text-right max-w-[60%] truncate">{{ $bug->task->title }}</span>
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
                    @if($bug->assignee)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Assigned To</span>
                        <span class="font-medium text-gray-800">{{ $bug->assignee->name }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-500">Reported By</span>
                        <span class="font-medium text-gray-800">{{ $bug->reporter->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Created</span>
                        <span class="font-medium text-gray-800">{{ $bug->created_at->format('M j, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
