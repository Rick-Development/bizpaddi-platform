@extends('teamportal::layouts.app')
@section('title', 'Test Cases')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Test Cases</h1>
            <p class="text-sm text-gray-500 mt-1">Tasks assigned to you for testing and QA review</p>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" class="bg-white rounded-2xl border border-gray-200 p-4 shadow-sm">
        <div class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[180px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tasks…"
                    class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm focus:ring-brand-primary focus:border-brand-primary">
            </div>
            <select name="status" class="rounded-xl border border-gray-300 px-3 py-2 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                <option value="">All Statuses</option>
                <option value="todo" {{ request('status') === 'todo' ? 'selected' : '' }}>To Do</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="review" {{ request('status') === 'review' ? 'selected' : '' }}>In Review</option>
                <option value="done" {{ request('status') === 'done' ? 'selected' : '' }}>Done</option>
            </select>
            <select name="priority" class="rounded-xl border border-gray-300 px-3 py-2 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                <option value="">All Priorities</option>
                <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
            </select>
            <select name="project_id" class="rounded-xl border border-gray-300 px-3 py-2 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                <option value="">All Projects</option>
                @foreach($projects as $proj)
                <option value="{{ $proj->id }}" {{ request('project_id') == $proj->id ? 'selected' : '' }}>{{ $proj->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-gray-900 hover:bg-black text-white text-sm font-semibold px-4 py-2 rounded-xl transition">Filter</button>
            @if(request()->hasAny(['search','status','priority','project_id']))
            <a href="{{ route('team.qa.test-cases.index') }}" class="text-sm text-gray-500 hover:text-gray-800 px-3 py-2">Reset</a>
            @endif
        </div>
    </form>

    {{-- Tasks Grid --}}
    @if($tasks->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-16 text-center">
        <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12"/>
        </svg>
        <p class="text-sm text-gray-400">No tasks assigned to you.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($tasks as $task)
        <a href="{{ route('team.qa.test-cases.show', $task) }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-gray-300 transition group flex flex-col gap-3">

            {{-- Priority dot + Project --}}
            <div class="flex items-center justify-between">
                <span class="text-xs text-gray-400 font-medium">{{ $task->project?->name ?? 'No Project' }}</span>
                <span class="flex items-center gap-1 text-xs font-semibold
                    @if($task->priority === 'urgent') text-red-600
                    @elseif($task->priority === 'high') text-orange-500
                    @elseif($task->priority === 'medium') text-yellow-600
                    @else text-green-600 @endif">
                    <span class="w-2 h-2 rounded-full inline-block
                        @if($task->priority === 'urgent') bg-red-500
                        @elseif($task->priority === 'high') bg-orange-400
                        @elseif($task->priority === 'medium') bg-yellow-400
                        @else bg-green-400 @endif"></span>
                    {{ ucfirst($task->priority) }}
                </span>
            </div>

            {{-- Title --}}
            <h3 class="text-sm font-semibold text-gray-800 group-hover:text-brand-primary transition leading-snug line-clamp-2">{{ $task->title }}</h3>

            {{-- Description snippet --}}
            @if($task->description)
            <p class="text-xs text-gray-400 line-clamp-2">{{ strip_tags($task->description) }}</p>
            @endif

            {{-- Status + Due date --}}
            <div class="flex items-center justify-between mt-auto pt-2 border-t border-gray-50">
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                    @if($task->status === 'review') bg-purple-100 text-purple-700
                    @elseif($task->status === 'in_progress') bg-blue-100 text-blue-700
                    @elseif($task->status === 'done') bg-emerald-100 text-emerald-700
                    @else bg-gray-100 text-gray-600 @endif">
                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                </span>
                @if($task->due_date)
                <span class="text-xs text-gray-400 @if($task->due_date->isPast() && $task->status !== 'done') text-red-500 font-semibold @endif">
                    Due {{ $task->due_date->format('M j') }}
                </span>
                @endif
            </div>
        </a>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($tasks->hasPages())
    <div>{{ $tasks->withQueryString()->links() }}</div>
    @endif
    @endif

</div>
@endsection
