@extends('teamportal::layouts.app')
@section('title', 'Test Cases')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">My Test Cases</h1>
            <p class="text-sm text-gray-400 mt-1">Tasks assigned to you for testing and QA review</p>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" class="bg-[#10231C]/60 backdrop-blur-sm rounded-2xl border border-white/10 p-5 shadow-xl">
        <div class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tasks…"
                    class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white placeholder-gray-500 px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 shadow-inner">
            </div>
            <select name="status" class="rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 shadow-inner">
                <option value="" class="bg-[#10231C] text-white">All Statuses</option>
                <option value="todo" {{ request('status') === 'todo' ? 'selected' : '' }} class="bg-[#10231C] text-white">To Do</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }} class="bg-[#10231C] text-white">In Progress</option>
                <option value="review" {{ request('status') === 'review' ? 'selected' : '' }} class="bg-[#10231C] text-white">In Review</option>
                <option value="done" {{ request('status') === 'done' ? 'selected' : '' }} class="bg-[#10231C] text-white">Done</option>
            </select>
            <select name="priority" class="rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 shadow-inner">
                <option value="" class="bg-[#10231C] text-white">All Priorities</option>
                <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }} class="bg-[#10231C] text-white">Urgent</option>
                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }} class="bg-[#10231C] text-white">High</option>
                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }} class="bg-[#10231C] text-white">Medium</option>
                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }} class="bg-[#10231C] text-white">Low</option>
            </select>
            <select name="project_id" class="rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 shadow-inner">
                <option value="" class="bg-[#10231C] text-white">All Projects</option>
                @foreach($projects as $proj)
                <option value="{{ $proj->id }}" {{ request('project_id') == $proj->id ? 'selected' : '' }} class="bg-[#10231C] text-white">{{ $proj->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition shadow-lg shadow-emerald-950/50">Filter</button>
            @if(request()->hasAny(['search','status','priority','project_id']))
            <a href="{{ route('team.qa.test-cases.index') }}" class="text-sm text-gray-400 hover:text-white px-3 py-2 transition">Reset</a>
            @endif
        </div>
    </form>

    {{-- Tasks Grid --}}
    @if($tasks->isEmpty())
    <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-2xl border border-white/10 shadow-xl px-6 py-16 text-center">
        <svg class="w-12 h-12 text-gray-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12"/>
        </svg>
        <p class="text-sm text-gray-400">No tasks assigned to you.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($tasks as $task)
        <a href="{{ route('team.qa.test-cases.show', $task) }}" class="bg-[#10231C]/60 backdrop-blur-sm rounded-2xl border border-white/10 shadow-xl p-5 hover:border-emerald-500/40 hover:bg-[#10231C]/80 transition group flex flex-col gap-3">

            {{-- Priority dot + Project --}}
            <div class="flex items-center justify-between">
                <span class="text-xs text-gray-400 font-medium">{{ $task->project?->name ?? 'No Project' }}</span>
                <span class="flex items-center gap-1.5 text-xs font-bold
                    @if($task->priority === 'urgent') text-red-400
                    @elseif($task->priority === 'high') text-amber-400
                    @elseif($task->priority === 'medium') text-yellow-400
                    @else text-emerald-400 @endif">
                    <span class="w-2 h-2 rounded-full inline-block
                        @if($task->priority === 'urgent') bg-red-400
                        @elseif($task->priority === 'high') bg-amber-400
                        @elseif($task->priority === 'medium') bg-yellow-400
                        @else bg-emerald-400 @endif"></span>
                    {{ ucfirst($task->priority) }}
                </span>
            </div>

            {{-- Title --}}
            <h3 class="text-sm font-bold text-white group-hover:text-emerald-400 transition leading-snug line-clamp-2">{{ $task->title }}</h3>

            {{-- Description snippet --}}
            @if($task->description)
            <p class="text-xs text-gray-400 line-clamp-2">{{ strip_tags($task->description) }}</p>
            @endif

            {{-- Status + Due date --}}
            <div class="flex items-center justify-between mt-auto pt-3 border-t border-white/5">
                <span class="text-xs font-bold px-2.5 py-1 rounded-full border
                    @if($task->status === 'review') border-purple-500/30 bg-purple-500/20 text-purple-300
                    @elseif($task->status === 'in_progress') border-blue-500/30 bg-blue-500/20 text-blue-300
                    @elseif($task->status === 'done') border-emerald-500/30 bg-emerald-500/20 text-emerald-300
                    @else border-white/10 bg-white/5 text-gray-300 @endif">
                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                </span>
                @if($task->due_date)
                <span class="text-xs text-gray-400 @if($task->due_date->isPast() && $task->status !== 'done') text-red-400 font-bold @endif">
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
