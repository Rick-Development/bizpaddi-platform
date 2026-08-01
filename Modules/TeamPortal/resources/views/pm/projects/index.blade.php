@extends('teamportal::layouts.app')

@section('title', 'My Projects - Project Manager')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">My Projects</h1>
        <p class="mt-2 text-sm text-gray-600">Manage and track all your assigned projects</p>
    </div>
    <a href="{{ route('team.pm.dashboard') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-semibold transition-colors">
        ← Back to Dashboard
    </a>
</div>

@if($projects->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($projects as $project)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow">
        <div class="p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $project->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $project->client->name ?? 'No client' }}</p>
                </div>
                <span class="px-2.5 py-1 rounded-full text-xs font-bold
                    {{ $project->status === 'active' ? 'bg-green-100 text-green-700' : '' }}
                    {{ $project->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                    {{ $project->status === 'on_hold' ? 'bg-orange-100 text-orange-700' : '' }}
                    {{ $project->status === 'completed' ? 'bg-blue-100 text-blue-700' : '' }}
                    {{ $project->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                </span>
            </div>

            @if($project->description)
            <p class="text-sm text-gray-600 line-clamp-2 mb-4">{!! Str::limit(strip_tags($project->description), 100) !!}</p>
            @endif

            <!-- Stats -->
            <div class="grid grid-cols-2 gap-4 mb-4 pt-4 border-t border-gray-100">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Team</p>
                    <p class="text-lg font-bold text-gray-900 mt-1">{{ $project->teamMembers->count() }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Tasks</p>
                    <p class="text-lg font-bold text-gray-900 mt-1">{{ $project->tasks->count() }}</p>
                </div>
            </div>

            <!-- Progress -->
            @php
                $totalTasks = $project->tasks->count();
                $completedTasks = $project->tasks->where('status', 'done')->count();
                $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
            @endphp
            <div class="mb-4">
                <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                    <span>Progress</span>
                    <span class="font-semibold">{{ $progress }}%</span>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-brand-primary transition-all duration-500" style="width: {{ $progress }}%"></div>
                </div>
            </div>

            <!-- Dates -->
            <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                @if($project->start_date)
                <span>Started: {{ $project->start_date->format('M d, Y') }}</span>
                @endif
                @if($project->due_date)
                <span class="{{ $project->due_date->isPast() && $project->status !== 'completed' ? 'text-red-600 font-semibold' : '' }}">
                    Due: {{ $project->due_date->format('M d, Y') }}
                </span>
                @endif
            </div>

            <!-- Actions -->
            <div class="flex gap-2">
                <a href="{{ route('team.pm.projects.show', $project) }}" class="flex-1 px-4 py-2 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-xl text-sm font-bold text-center transition-colors">
                    Manage Project
                </a>
                <a href="{{ route('team.pm.projects.team', $project) }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-colors" title="Manage Team">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="mt-8">
    {{ $projects->links() }}
</div>

@else
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
    </svg>
    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Projects Yet</h3>
    <p class="text-gray-600">You haven't been assigned any projects to manage yet.</p>
</div>
@endif
@endsection
