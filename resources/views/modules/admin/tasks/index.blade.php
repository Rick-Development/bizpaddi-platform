@extends('admin::layouts.app')

@section('title', 'Project Board')

@section('content')
<div class="h-[calc(100vh-6rem)] flex flex-col bg-gray-50/50 -m-6 p-6">
    <!-- Board Header -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Task Board</h1>
            <p class="text-sm text-gray-500 mt-1">Manage and track project progress</p>
        </div>
        <div class="flex items-center flex-wrap gap-3">
            <!-- Project & Deadline Filter -->
            <form method="GET" class="flex items-center gap-2">
                <div class="relative">
                    <select name="project_id" onchange="this.form.submit()" class="appearance-none pl-4 pr-10 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary shadow-sm transition-all cursor-pointer hover:border-gray-300">
                        <option value="">All Projects</option>
                        @foreach(\Modules\ProjectManagement\Models\Project::all() as $p)
                            <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                <div class="relative">
                    <select name="status" onchange="this.form.submit()" class="appearance-none pl-4 pr-10 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary shadow-sm transition-all cursor-pointer hover:border-gray-300">
                        <option value="">All Deadlines / Status</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>🚨 Overdue Tasks</option>
                        <option value="due_soon" {{ request('status') == 'due_soon' ? 'selected' : '' }}>⏰ Due Soon (Next 48h)</option>
                        <option value="due_today" {{ request('status') == 'due_today' ? 'selected' : '' }}>📅 Due Today</option>
                        <option value="due_this_week" {{ request('status') == 'due_this_week' ? 'selected' : '' }}>📆 Due This Week</option>
                        <option value="todo" {{ request('status') == 'todo' ? 'selected' : '' }}>To Do</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>Review</option>
                        <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Done</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                @if(request('project_id') || request('status'))
                    <a href="{{ route('admin.tasks.index') }}" class="px-3 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs font-semibold transition-colors" title="Reset Filters">
                        ✕ Reset
                    </a>
                @endif
            </form>

            <!-- Export Task PDF & CSV Buttons -->
            <a href="{{ route('admin.tasks.export-pdf', array_merge(request()->all(), ['preview' => 1])) }}" target="_blank" class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 shadow-sm transition-all">
                <svg class="w-4 h-4 mr-2 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Export Task PDF
            </a>

            <a href="{{ route('admin.tasks.export-csv', request()->all()) }}" class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 shadow-sm transition-all">
                <svg class="w-4 h-4 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Export CSV
            </a>
            
            <a href="{{ route('admin.tasks.create', ['project_id' => request('project_id')]) }}" class="inline-flex items-center px-5 py-2.5 bg-brand-primary text-white text-sm font-medium rounded-xl hover:bg-brand-primary/90 shadow-lg shadow-brand-primary/30 transition-all hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Task
            </a>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="flex-1 overflow-x-auto overflow-y-hidden pb-4">
        <div class="flex h-full gap-6 min-w-max px-1">
            
            @foreach(['todo' => ['label' => 'To Do', 'color' => 'blue'], 'in_progress' => ['label' => 'In Progress', 'color' => 'orange'], 'review' => ['label' => 'Review', 'color' => 'purple'], 'done' => ['label' => 'Done', 'color' => 'green']] as $status => $config)
            <div class="w-80 flex flex-col h-full">
                <!-- Column Header -->
                <div class="mb-3 flex justify-between items-center px-1">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-{{ $config['color'] }}-500"></span>
                        <h3 class="font-bold text-gray-700 text-sm uppercase tracking-wider">{{ $config['label'] }}</h3>
                    </div>
                    <span class="bg-white border border-gray-100 text-gray-500 text-xs font-semibold px-2.5 py-1 rounded-lg shadow-sm">
                        {{ $tasks->where('status', $status)->count() }}
                    </span>
                </div>

                <!-- Column Body -->
                <div class="flex-1 bg-gray-100/50 rounded-2xl p-4 border border-gray-100/50 backdrop-blur-sm overflow-y-auto custom-scrollbar">
                    <div class="space-y-3">
                        @forelse($tasks->where('status', $status) as $task)
                        <div class="group relative bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:shadow-xl hover:border-{{ $config['color'] }}-200 transition-all duration-300 cursor-pointer">
                            
                            <!-- Card Header: Priority & Actions -->
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-medium bg-gray-50 text-gray-500 border border-gray-100">
                                        {{ $task->project->name ?? 'No Project' }}
                                    </span>
                                    
                                    @php
                                        $priorityColors = [
                                            'urgent' => 'bg-red-50 text-red-600 border-red-100',
                                            'high' => 'bg-orange-50 text-orange-600 border-orange-100',
                                            'medium' => 'bg-blue-50 text-blue-600 border-blue-100',
                                            'low' => 'bg-gray-50 text-gray-600 border-gray-100'
                                        ];
                                        $pColor = $priorityColors[$task->priority] ?? $priorityColors['low'];
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $pColor }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </div>

                                <!-- Always Visible Actions -->
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.tasks.edit', $task) }}" class="p-1 text-gray-400 hover:text-brand-primary hover:bg-brand-primary/5 rounded-md transition-colors" title="Edit Task">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </a>
                                    <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Delete task?')" class="inline-flex">
                                        @csrf @method('DELETE')
                                        <button class="p-1 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-md transition-colors" title="Delete Task">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Task Title -->
                            <div class="mb-3 group-hover:text-brand-primary transition-colors">
                                <h4 class="text-sm font-semibold text-gray-900 leading-snug">{{ $task->title }}</h4>
                            </div>
                            
                            <!-- Footer Info -->
                            <div class="flex justify-between items-center pt-3 border-t border-gray-50 mt-auto">
                                <div class="flex items-center gap-2">
                                    @if($task->assignee)
                                        <div title="{{ $task->assignee->name }}" class="h-6 w-6 rounded-full bg-gradient-to-br from-brand-primary to-purple-600 p-[1px] shadow-sm">
                                            <div class="bg-white h-full w-full rounded-full flex items-center justify-center">
                                                <span class="text-[9px] font-bold text-gray-700">{{ substr($task->assignee->name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="h-6 w-6 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-400" title="Unassigned">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                        </div>
                                    @endif
                                    
                                    @if($task->due_date)
                                    <div class="flex items-center gap-1 {{ $task->due_date->isPast() ? 'text-red-500' : 'text-gray-400' }}">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-[11px] font-medium">{{ $task->due_date->format('M d') }}</span>
                                    </div>
                                    @endif
                                </div>

                                <!-- Move Controls -->
                                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-all duration-200 translate-y-2 group-hover:translate-y-0">
                                    @if($status != 'todo')
                                    <form action="{{ route('admin.tasks.update-status', $task) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="{{ $status == 'in_progress' ? 'todo' : ($status == 'review' ? 'in_progress' : 'review') }}">
                                        <button class="p-1 hover:bg-gray-100 rounded text-gray-400 hover:text-gray-600" title="Move Back">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                                        </button>
                                    </form>
                                    @endif
                                    
                                    @if($status != 'done')
                                    <form action="{{ route('admin.tasks.update-status', $task) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="{{ $status == 'todo' ? 'in_progress' : ($status == 'in_progress' ? 'review' : 'done') }}">
                                        <button class="p-1 hover:bg-gray-100 rounded text-gray-400 hover:text-gray-600" title="Move Forward">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="h-32 border-2 border-dashed border-gray-200 rounded-xl flex flex-col items-center justify-center text-gray-400 gap-2 bg-gray-50/50">
                            <svg class="w-8 h-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span class="text-sm font-medium">No tasks yet</span>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</div>

<style>
/* Custom Scrollbar for columns */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: rgba(209, 213, 219, 0.5);
    border-radius: 20px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background-color: rgba(156, 163, 175, 0.8);
}
</style>
@endsection
