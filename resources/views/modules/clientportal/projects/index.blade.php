@extends('clientportal::layouts.app')

@section('title', 'My Projects')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">My Projects</h1>
        <p class="mt-2 text-sm text-gray-600">View and track your ongoing projects</p>
    </div>
</div>

@if($projects->isEmpty())
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
    <div class="mx-auto h-16 w-16 rounded-full bg-blue-50 flex items-center justify-center mb-4">
        <svg class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
    </div>
    <h3 class="text-lg font-semibold text-gray-900">No projects assignment</h3>
    <p class="mt-2 text-sm text-gray-500">You don't have any projects assigned to you yet.</p>
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($projects as $project)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-gray-900 line-clamp-1">{{ $project->name }}</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    {{ $project->status === 'completed' ? 'bg-green-100 text-green-800' : 
                       ($project->status === 'active' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                </span>
            </div>
            <a href="{{ route('client.projects.show', $project->id) }}" class="text-brand-primary hover:text-brand-primary/80">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </a>
        </div>
        
        <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ Str::limit(strip_tags($project->description), 100) }}</p>

        <div class="space-y-3">
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500">Start Date</span>
                <span class="font-medium text-gray-900">{{ $project->start_date ? $project->start_date->format('M d, Y') : 'N/A' }}</span>
            </div>
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500">Due Date</span>
                <span class="font-medium text-gray-900">{{ $project->due_date ? $project->due_date->format('M d, Y') : 'N/A' }}</span>
            </div>
            
            <div class="pt-3 border-t border-gray-100 mt-3">
                 <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Tasks</span>
                    <span class="font-medium text-gray-900">{{ $project->tasks->count() }}</span>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-6">
    {{ $projects->links() }}
</div>
@endif
@endsection
