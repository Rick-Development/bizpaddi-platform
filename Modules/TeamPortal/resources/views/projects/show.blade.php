@extends('teamportal::layouts.app')

@section('title', 'Project: ' . $project->name)

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('team.projects.index') }}" class="p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $project->name }}</h1>
            <p class="mt-1 text-sm text-gray-600">{{ $project->client->name }} • {{ ucfirst($project->status) }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Description -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                </svg>
                Project Description
            </h2>
            <div class="prose prose-sm max-w-none text-gray-600 leading-relaxed">
                {!! $project->description ?: 'No description provided.' !!}
            </div>
        </div>

        <!-- My Tasks -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    My Tasks in this Project
                </h2>
                <span class="px-2.5 py-1 rounded-full bg-brand-primary/10 text-brand-primary text-xs font-bold">
                    {{ $tasks->count() }} Tasks
                </span>
            </div>
            
            <div class="divide-y divide-gray-50">
                @forelse($tasks as $task)
                <a href="{{ route('team.tasks.show', $task) }}" class="block p-4 hover:bg-gray-50 transition-colors group">
                    <div class="flex items-center justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <h3 class="text-sm font-semibold text-gray-900 group-hover:text-brand-primary transition-colors truncate">{{ $task->title }}</h3>
                            <div class="mt-1 flex items-center gap-3 text-xs text-gray-500">
                                <span class="flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full {{ $task->status === 'done' ? 'bg-green-500' : ($task->status === 'in_progress' ? 'bg-blue-500' : 'bg-gray-400') }}"></span>
                                    @php
                                        $statusLabels = [
                                            'todo' => 'To Do',
                                            'in_progress' => 'In Progress',
                                            'review' => 'Done',
                                            'done' => 'Completed',
                                        ];
                                    @endphp
                                    {{ $statusLabels[$task->status] ?? ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                                @if($task->due_date)
                                <span>Due: {{ $task->due_date->format('M d \a\t g:i A') }}</span>
                                @endif
                                <span class="capitalize px-1.5 py-0.5 rounded {{ $task->priority === 'high' ? 'bg-red-50 text-red-600 font-bold' : 'bg-gray-50' }}">
                                    {{ $task->priority }}
                                </span>
                            </div>
                        </div>
                        <svg class="h-5 w-5 text-gray-300 group-hover:text-gray-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </div>
                </a>
                @empty
                <div class="p-12 text-center">
                    <p class="text-sm text-gray-500 italic">No tasks assigned to you on this project.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sidebar Stats -->
    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Project Stats</h2>
            
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Start Date</p>
                        <p class="text-sm font-bold text-gray-900 mt-1">{{ $project->start_date ? $project->start_date->format('M d, Y \a\t g:i A') : 'N/A' }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Due Date</p>
                        <p class="text-sm font-bold text-gray-900 mt-1">{{ $project->due_date ? $project->due_date->format('M d, Y \a\t g:i A') : 'N/A' }}</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-50">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Completion</p>
                    @php
                        $totalTasks = $project->tasks->count();
                        $completedTasks = $project->tasks->where('status', 'done')->count();
                        $percent = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                    @endphp
                    <div class="flex items-center gap-3">
                        <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-brand-primary transition-all duration-500" style="width: {{ $percent }}%"></div>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $percent }}%</span>
                    </div>
                    <p class="mt-2 text-[10px] text-gray-500">{{ $completedTasks }} of {{ $totalTasks }} tasks completed</p>
                </div>

                <div class="pt-4 border-t border-gray-50">
                    <a href="{{ route('team.projects.chat', $project) }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-gray-900 hover:bg-black text-white rounded-xl text-sm font-bold shadow-lg shadow-gray-200 transition-all active:scale-95">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                        </svg>
                        Project Chat
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
