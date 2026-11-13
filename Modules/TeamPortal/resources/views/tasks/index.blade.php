@extends('teamportal::layouts.app')

@section('title', 'My Tasks')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-black text-white tracking-tight">My Tasks</h1>
            <p class="mt-1 text-sm text-gray-400">Manage and track your assigned deliverables</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route('team.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-semibold text-gray-300 transition-colors">
                &larr; Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Tasks List - Card-based responsive layout -->
    <div class="space-y-4">
        @forelse($tasks as $task)
        <div class="bg-[#10231C]/60 backdrop-blur-sm border border-white/10 rounded-2xl overflow-hidden hover:border-emerald-500/30 transition-all shadow-xl">
            <div class="p-5 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <!-- Left: Task Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start gap-3">
                            <!-- Priority Badge -->
                            <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider shrink-0
                                {{ $task->priority === 'urgent' ? 'bg-red-500/10 text-red-400 border border-red-500/30' : 
                                   ($task->priority === 'high' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/30' : 
                                   'bg-white/5 text-gray-300 border border-white/10') }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                            
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('team.tasks.show', $task) }}" class="hover:text-emerald-400 transition-colors">
                                    <h3 class="text-base font-bold text-white mb-1">{{ $task->title }}</h3>
                                </a>
                                @if($task->description)
                                <p class="text-xs text-gray-400 line-clamp-2">{{ Str::limit(strip_tags($task->description), 140) }}</p>
                                @endif
                                
                                <!-- Meta Info -->
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-3 text-xs text-gray-400">
                                    @if($task->project)
                                    <div class="flex items-center gap-1.5">
                                        <svg class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                                        </svg>
                                        <span>{{ $task->project->name }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($task->due_date)
                                    <div class="flex items-center gap-1.5">
                                        <svg class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                        </svg>
                                        <span class="{{ $task->due_date->isPast() && !in_array($task->status, ['done','completed']) ? 'text-red-400 font-semibold' : 'text-gray-300' }}">
                                            Due {{ $task->due_date->format('M d, Y') }} at {{ $task->due_date->format('g:i A') }}
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Status Selector -->
                    <div class="sm:ml-4 shrink-0 w-full sm:w-auto">
                        <form action="{{ route('team.tasks.update-status', $task) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="this.form.submit()" 
                                class="block w-full sm:w-44 rounded-xl border border-white/10 bg-[#08110e] py-2 pl-3 pr-10 text-xs font-semibold focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500
                                {{ $task->status === 'done' ? 'text-emerald-400' : 
                                   ($task->status === 'in_progress' ? 'text-blue-400' : 
                                   ($task->status === 'review' ? 'text-amber-400' : 'text-gray-300')) }}">
                                <option value="todo" {{ $task->status === 'todo' ? 'selected' : '' }} class="bg-[#10231C] text-gray-200">📋 To Do</option>
                                <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }} class="bg-[#10231C] text-blue-300">🚀 In Progress</option>
                                <option value="review" {{ $task->status === 'review' ? 'selected' : '' }} class="bg-[#10231C] text-amber-300">👀 Done</option>
                                @if(auth()->user()->isProjectManager() || $task->status === 'done')
                                    <option value="done" {{ $task->status === 'done' ? 'selected' : '' }} {{ !auth()->user()->isProjectManager() ? 'disabled' : '' }} class="bg-[#10231C] text-emerald-300">✅ Completed</option>
                                @endif
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <!-- Empty State -->
        <div class="text-center py-16 bg-[#10231C]/40 rounded-2xl border border-white/10 backdrop-blur-sm">
            <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 mx-auto mb-3">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="mt-2 text-base font-bold text-white">No tasks assigned</h3>
            <p class="mt-1 text-xs text-gray-400">You're all caught up! No tasks assigned to you yet.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($tasks->hasPages())
    <div class="mt-6">
        {{ $tasks->links() }}
    </div>
    @endif
</div>
@endsection
