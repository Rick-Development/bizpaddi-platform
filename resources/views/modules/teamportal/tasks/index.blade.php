@extends('teamportal::layouts.app')

@section('title', 'My Tasks')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">My Tasks</h1>
            <p class="mt-1 text-sm text-gray-500">Manage and track your assigned tasks</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <div class="flex gap-2">
                <!-- Future: Add filter/sort options -->
            </div>
        </div>
    </div>

    <!-- Tasks List - Card-based responsive layout -->
    <div class="space-y-4">
        @forelse($tasks as $task)
        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-xl overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <!-- Left: Task Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start gap-3">
                            <!-- Priority Badge -->
                            <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-semibold shrink-0
                                {{ $task->priority === 'urgent' ? 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20' : 
                                   ($task->priority === 'high' ? 'bg-orange-50 text-orange-700 ring-1 ring-inset ring-orange-600/20' : 
                                   'bg-gray-50 text-gray-600 ring-1 ring-inset ring-gray-500/20') }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                            
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('team.tasks.show', $task) }}" class="hover:text-gray-600 transition-colors">
                                    <h3 class="text-base font-semibold text-gray-900 mb-1">{{ $task->title }}</h3>
                                </a>
                                @if($task->description)
                                <p class="text-sm text-gray-500 line-clamp-2">{{ Str::limit(strip_tags($task->description), 100) }}</p>
                                @endif
                                
                                <!-- Meta Info -->
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-3 text-xs text-gray-500">
                                    @if($task->project)
                                    <div class="flex items-center gap-1.5">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                                        </svg>
                                        <span>{{ $task->project->name }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($task->due_date)
                                    <div class="flex items-center gap-1.5">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                        </svg>
                                        <span class="{{ $task->due_date->isPast() ? 'text-red-600 font-medium' : '' }}">
                                            Due {{ $task->due_date->format('M d, Y') }}
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
                                class="block w-full sm:w-40 rounded-lg border-0 py-2 pl-3 pr-10 text-sm font-medium shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary
                                {{ $task->status === 'done' ? 'text-green-700 bg-green-50' : 
                                   ($task->status === 'in_progress' ? 'text-blue-700 bg-blue-50' : 
                                   ($task->status === 'review' ? 'text-purple-700 bg-purple-50' : 'text-gray-700 bg-gray-50')) }}">
                                <option value="todo" {{ $task->status === 'todo' ? 'selected' : '' }}>📋 To Do</option>
                                <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>🚀 In Progress</option>
                                <option value="review" {{ $task->status === 'review' ? 'selected' : '' }}>👀 Review</option>
                                <option value="done" {{ $task->status === 'done' ? 'selected' : '' }}>✅ Done</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <!-- Empty State -->
        <div class="text-center py-12 bg-white rounded-xl shadow-sm ring-1 ring-gray-900/5">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-gray-900">No tasks assigned</h3>
            <p class="mt-1 text-sm text-gray-500">You're all caught up! No tasks assigned to you yet.</p>
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
