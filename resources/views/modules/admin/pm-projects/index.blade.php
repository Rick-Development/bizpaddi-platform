@extends('admin::layouts.app')

@section('title', 'Projects')

@section('content')
<!-- Header -->
<div class="mb-8">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Projects</h1>
            <p class="mt-2 text-sm text-gray-600">Manage your projects and track progress</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.pm-projects.create') }}" class="inline-flex items-center px-5 py-2.5 bg-brand-primary text-white text-sm font-medium rounded-xl hover:bg-brand-primary/90 shadow-lg shadow-brand-primary/30 transition-all hover:-translate-y-0.5">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                New Project
            </a>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <form method="GET" action="{{ route('admin.pm-projects.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search projects..." class="pl-10 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent transition-all">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent transition-all">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full px-4 py-2.5 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-all duration-200 font-medium">
                Apply Filters
            </button>
        </div>
    </form>
</div>

<!-- Project Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($projects as $project)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200 flex flex-col h-full">
        <div class="p-6 flex-1">
            <div class="flex justify-between items-start mb-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    @if($project->status == 'active') bg-green-100 text-green-800
                    @elseif($project->status == 'completed') bg-blue-100 text-blue-800
                    @elseif($project->status == 'cancelled') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800 @endif">
                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                </span>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.pm-projects.edit', $project) }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                    <form action="{{ route('admin.pm-projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this project?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <a href="{{ route('admin.pm-projects.show', $project) }}" class="block group">
                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-[#FB4C4B] transition-colors mb-2">{{ $project->name }}</h3>
                <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ Str::limit(strip_tags($project->description), 100) }}</p>

                <div class="space-y-3">
                    @if($project->client)
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>{{ $project->client->name }}</span>
                    </div>
                    @endif
                    
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span>{{ $project->tasks_count ?? $project->tasks->count() }} Tasks</span>
                    </div>

                    @if($project->due_date)
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>Due {{ $project->due_date->format('M d, Y') }}</span>
                    </div>
                    @endif
                </div>
            </a>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center rounded-b-xl">
             <a href="{{ route('admin.tasks.index', ['project_id' => $project->id]) }}" class="text-sm font-medium text-[#FB4C4B] hover:text-[#D93837]">
                View Board &rarr;
            </a>
            <span class="text-xs text-gray-500">Updated {{ $project->updated_at->diffForHumans() }}</span>
        </div>
    </div>
    @empty
    <div class="col-span-full">
        <div class="text-center py-12 bg-white rounded-xl border border-dashed border-gray-300">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No projects found</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating a new project.</p>
            <div class="mt-6">
                <a href="{{ route('admin.pm-projects.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#FB4C4B] hover:bg-[#D93837] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FB4C4B]">
                    New Project
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>
<div class="mt-6">
    {{ $projects->links() }}
</div>
@endsection
