@extends('teamportal::layouts.app')
@section('title', 'Test Case: ' . $task->title)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Back + Actions --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('team.qa.test-cases.index') }}" class="text-sm text-gray-500 hover:text-gray-800 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Back to Test Cases
        </a>
        <a href="{{ route('team.qa.bugs.create') }}?task_id={{ $task->id }}" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-3 py-2 rounded-xl transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Report Bug for This Task
        </a>
    </div>

    {{-- Task Header --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div class="flex-1">
                <p class="text-xs font-semibold text-gray-400 mb-1">{{ $task->project?->name ?? 'No Project' }}</p>
                <h1 class="text-xl font-bold text-gray-900">{{ $task->title }}</h1>
                <div class="flex flex-wrap items-center gap-2 mt-3">
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                        @if($task->status === 'review') bg-purple-100 text-purple-700
                        @elseif($task->status === 'in_progress') bg-blue-100 text-blue-700
                        @elseif($task->status === 'done') bg-emerald-100 text-emerald-700
                        @else bg-gray-100 text-gray-600 @endif">
                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                    </span>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                        @if($task->priority === 'urgent') bg-red-100 text-red-700
                        @elseif($task->priority === 'high') bg-orange-100 text-orange-700
                        @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-700
                        @else bg-green-100 text-green-700 @endif">
                        {{ ucfirst($task->priority) }} Priority
                    </span>
                    @if($task->due_date)
                    <span class="text-xs font-medium text-gray-500 @if($task->due_date->isPast() && $task->status !== 'done') text-red-600 @endif">
                        Due {{ $task->due_date->format('M j, Y') }}
                    </span>
                    @endif
                </div>
            </div>

            {{-- Update Status form --}}
            @if($task->status !== 'done')
            <form action="{{ route('team.qa.test-cases.update-status', $task) }}" method="POST" class="flex items-center gap-2">
                @csrf @method('PATCH')
                <select name="status" class="rounded-xl border border-gray-300 px-3 py-2 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                    <option value="todo" {{ $task->status === 'todo' ? 'selected' : '' }}>To Do</option>
                    <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="review" {{ $task->status === 'review' ? 'selected' : '' }}>Submit for Review</option>
                </select>
                <button type="submit" class="bg-gray-900 hover:bg-black text-white text-sm font-semibold px-3 py-2 rounded-xl transition">Update</button>
            </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Description / Attachments --}}
        <div class="lg:col-span-2 space-y-5">
            @if($task->description)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-3">Description</h3>
                <div class="prose prose-sm max-w-none text-gray-700">{!! nl2br(e($task->description)) !!}</div>
            </div>
            @endif

            {{-- Attachments --}}
            @if($task->getMedia('attachments')->count())
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-4">Attachments</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($task->getMedia('attachments') as $media)
                    <a href="{{ $media->getUrl() }}" target="_blank" class="flex items-center gap-2 p-3 border border-gray-200 rounded-xl hover:bg-gray-50 transition text-sm text-gray-700">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13"/></svg>
                        <span class="truncate text-xs">{{ $media->file_name }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Comments --}}
            @if($task->comments->count())
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-4">Comments ({{ $task->comments->count() }})</h3>
                <div class="space-y-4">
                    @foreach($task->comments as $comment)
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 bg-gray-50 rounded-xl p-3">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-semibold text-gray-700">{{ $comment->user->name }}</span>
                                <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-700">{{ $comment->body }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Meta Sidebar --}}
        <div class="space-y-4">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-3">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wide">Details</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Project</span>
                        <span class="font-medium text-gray-800">{{ $task->project?->name ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Created</span>
                        <span class="font-medium text-gray-800">{{ $task->created_at->format('M j, Y') }}</span>
                    </div>
                    @if($task->due_date)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Due Date</span>
                        <span class="font-medium @if($task->due_date->isPast() && $task->status !== 'done') text-red-600 @else text-gray-800 @endif">
                            {{ $task->due_date->format('M j, Y') }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Quick Link to report bug --}}
            <a href="{{ route('team.qa.bugs.create') }}?task_id={{ $task->id }}" class="block bg-red-50 hover:bg-red-100 border border-red-200 rounded-2xl p-4 transition">
                <p class="text-sm font-semibold text-red-700">🐛 Found a bug?</p>
                <p class="text-xs text-red-500 mt-1">Click to report a bug for this task</p>
            </a>
        </div>

    </div>
</div>
@endsection
