@extends('teamportal::layouts.app')
@section('title', 'Test Case: ' . $task->title)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- Back + Actions --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('team.qa.test-cases.index') }}" class="text-sm text-gray-400 hover:text-white flex items-center gap-1.5 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Back to Test Cases
        </a>
        <a href="{{ route('team.qa.bugs.create') }}?task_id={{ $task->id }}" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-500 text-white text-sm font-bold px-4 py-2 rounded-xl shadow-lg shadow-red-950/50 transition active:scale-95">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Report Bug for This Task
        </a>
    </div>

    {{-- Task Header --}}
    <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-2xl border border-white/10 shadow-xl p-6">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div class="flex-1">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">{{ $task->project?->name ?? 'No Project' }}</p>
                <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">{{ $task->title }}</h1>
                <div class="flex flex-wrap items-center gap-2 mt-3">
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full border
                        @if($task->status === 'review') border-purple-500/30 bg-purple-500/20 text-purple-300
                        @elseif($task->status === 'in_progress') border-blue-500/30 bg-blue-500/20 text-blue-300
                        @elseif($task->status === 'done') border-emerald-500/30 bg-emerald-500/20 text-emerald-300
                        @else border-white/10 bg-white/5 text-gray-300 @endif">
                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                    </span>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full border
                        @if($task->priority === 'urgent') border-red-500/30 bg-red-500/20 text-red-300
                        @elseif($task->priority === 'high') border-amber-500/30 bg-amber-500/20 text-amber-300
                        @elseif($task->priority === 'medium') border-yellow-500/30 bg-yellow-500/20 text-yellow-300
                        @else border-emerald-500/30 bg-emerald-500/20 text-emerald-300 @endif">
                        {{ ucfirst($task->priority) }} Priority
                    </span>
                    @if($task->due_date)
                    <span class="text-xs font-medium text-gray-400 @if($task->due_date->isPast() && $task->status !== 'done') text-red-400 font-bold @endif">
                        Due {{ $task->due_date->format('M j, Y') }}
                    </span>
                    @endif
                </div>
            </div>

            {{-- Update Status form --}}
            @if($task->status !== 'done')
            <form action="{{ route('team.qa.test-cases.update-status', $task) }}" method="POST" class="flex items-center gap-2">
                @csrf @method('PATCH')
                <select name="status" class="rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2 text-sm focus:ring-2 focus:ring-emerald-500">
                    <option value="todo" {{ $task->status === 'todo' ? 'selected' : '' }} class="bg-[#10231C] text-white">To Do</option>
                    <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }} class="bg-[#10231C] text-white">In Progress</option>
                    <option value="review" {{ $task->status === 'review' ? 'selected' : '' }} class="bg-[#10231C] text-white">Submit for Review</option>
                </select>
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold px-4 py-2 rounded-xl transition shadow-lg shadow-emerald-950/50">Update</button>
            </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Description / Attachments --}}
        <div class="lg:col-span-2 space-y-5">
            @if($task->description)
            <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-2xl border border-white/10 shadow-xl p-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Description</h3>
                <div class="prose prose-sm prose-invert max-w-none text-gray-200">{!! $task->description !!}</div>
            </div>
            @endif

            {{-- Attachments --}}
            @if($task->getMedia('attachments')->count())
            <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-2xl border border-white/10 shadow-xl p-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Attachments</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($task->getMedia('attachments') as $media)
                    <a href="{{ $media->getUrl() }}" target="_blank" class="flex items-center gap-2 p-3 border border-white/10 bg-white/5 rounded-xl hover:border-emerald-500/40 hover:bg-white/10 transition text-sm text-gray-200">
                        <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13"/></svg>
                        <span class="truncate text-xs font-medium">{{ $media->file_name }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Comments --}}
            @if($task->comments->count())
            <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-2xl border border-white/10 shadow-xl p-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Comments ({{ $task->comments->count() }})</h3>
                <div class="space-y-4">
                    @foreach($task->comments as $comment)
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center text-xs font-bold text-emerald-400">
                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 bg-white/5 border border-white/10 rounded-xl p-3">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-bold text-white">{{ $comment->user->name }}</span>
                                <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-200">{{ $comment->body }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Meta Sidebar --}}
        <div class="space-y-4">
            <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-2xl border border-white/10 shadow-xl p-5 space-y-3.5">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Details</h3>
                <div class="space-y-2.5 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Project</span>
                        <span class="font-semibold text-white">{{ $task->project?->name ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Created</span>
                        <span class="font-semibold text-white">{{ $task->created_at->format('M j, Y') }}</span>
                    </div>
                    @if($task->due_date)
                    <div class="flex justify-between">
                        <span class="text-gray-400">Due Date</span>
                        <span class="font-semibold @if($task->due_date->isPast() && $task->status !== 'done') text-red-400 @else text-white @endif">
                            {{ $task->due_date->format('M j, Y') }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Quick Link to report bug --}}
            <a href="{{ route('team.qa.bugs.create') }}?task_id={{ $task->id }}" class="block bg-red-500/10 hover:bg-red-500/20 border border-red-500/30 rounded-2xl p-4 transition">
                <p class="text-sm font-bold text-red-300">🐛 Found a bug?</p>
                <p class="text-xs text-red-400 mt-1">Click to report a bug for this task</p>
            </a>
        </div>

    </div>
</div>
@endsection
