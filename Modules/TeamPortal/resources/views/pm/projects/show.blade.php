@extends('teamportal::layouts.app')

@section('title', $project->name . ' - Project Management')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <div class="flex items-center gap-3">
            <h1 class="text-3xl font-bold text-gray-900">{{ $project->name }}</h1>
            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $project->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                {{ $project->status }}
            </span>
        </div>
        <p class="mt-2 text-sm text-gray-600">{{ $project->client->name ?? 'No Client' }} • Due {{ $project->due_date ? $project->due_date->format('M j, Y') : 'N/A' }}</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('team.pm.projects.team', $project) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all shadow-sm">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            Manage Team
        </a>
        <a href="{{ route('team.pm.tasks.create', $project) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-primary text-white rounded-xl text-sm font-semibold hover:bg-brand-primary/90 transition-all shadow-lg shadow-brand-primary/20">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            New Task
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
    @foreach(['todo' => 'To Do', 'in_progress' => 'In Progress', 'review' => 'Review', 'done' => 'Done'] as $status => $label)
    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between px-2">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                {{ $label }}
                <span class="bg-gray-200 text-gray-700 text-[10px] px-2 py-0.5 rounded-full">{{ $tasksByStatus[$status]->count() }}</span>
            </h3>
        </div>
        
        <div class="space-y-4">
            @forelse($tasksByStatus[$status] as $task)
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all cursor-pointer group relative">
                <div class="flex justify-between items-start mb-2">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">{{ $task->milestone->name ?? 'No Milestone' }}</span>
                    <div class="opacity-0 group-hover:opacity-100 transition-opacity flex gap-2">
                        <a href="{{ route('team.pm.tasks.edit', [$project, $task]) }}" class="text-blue-500 hover:text-blue-700">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </a>
                    </div>
                </div>
                <h4 class="text-sm font-semibold text-gray-900 group-hover:text-brand-primary transition-colors">{{ $task->title }}</h4>
                
                <div class="mt-4 flex items-center justify-between">
                    <div class="flex -space-x-2">
                        @if($task->assignee)
                        <div class="h-6 w-6 rounded-full bg-brand-primary flex items-center justify-center text-[10px] text-white font-bold ring-2 ring-white" title="{{ $task->assignee->name }}">
                            {{ substr($task->assignee->name, 0, 1) }}
                        </div>
                        @else
                        <div class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-[10px] text-gray-500 font-bold ring-2 ring-white">?</div>
                        @endif
                    </div>
                    <div class="flex items-center gap-1 text-[10px] text-gray-500">
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        {{ $task->due_date ? $task->due_date->format('M j') : 'No date' }}
                    </div>
                </div>
            </div>
            @empty
            <div class="border-2 border-dashed border-gray-100 rounded-2xl p-8 text-center">
                <p class="text-xs text-gray-400 italic">No tasks</p>
            </div>
            @endforelse
        </div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-12">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-900">Team Performance</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-widest">
                        <tr>
                            <th class="px-6 py-4">Team Member</th>
                            <th class="px-6 py-4">Tasks</th>
                            <th class="px-6 py-4">Progress</th>
                            <th class="px-6 py-4">Rate</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($teamPerformance as $perf)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-600">
                                        {{ substr($perf['member']->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $perf['member']->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $perf['member']->role }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-gray-900">{{ $perf['completed'] }}</span>
                                <span class="text-sm text-gray-400">/ {{ $perf['assigned'] }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="w-24">
                                    <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-brand-primary" style="width: {{ $perf['completion_rate'] }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-brand-primary">{{ $perf['completion_rate'] }}%</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Project Details</h2>
            <div class="space-y-4">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400 block mb-1">Description</span>
                    <div class="text-sm text-gray-600 prose prose-sm max-w-none">
                        {!! $project->description !!}
                    </div>
                </div>
                <div class="pt-4 border-t border-gray-100 grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400 block mb-1">Start Date</span>
                        <p class="text-sm font-semibold text-gray-900">{{ $project->start_date ? $project->start_date->format('M j, Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400 block mb-1">Due Date</span>
                        <p class="text-sm font-semibold text-gray-900">{{ $project->due_date ? $project->due_date->format('M j, Y') : 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
