@extends('teamportal::layouts.app')

@section('title', 'My Projects')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold leading-6 text-gray-900">My Projects</h1>
            <p class="mt-2 text-sm text-gray-700">Projects you are actively working on.</p>
        </div>
    </div>
    
    <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($projects as $project)
        <div class="col-span-1 flex flex-col divide-y divide-gray-200 rounded-lg bg-white shadow hover:shadow-md transition-shadow relative group">
            <a href="{{ route('team.projects.show', $project) }}" class="flex flex-1 flex-col p-8 group">
                <div class="flex items-center gap-x-3 justify-between">
                    <span class="inline-flex flex-shrink-0 items-center rounded-full bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">{{ ucfirst($project->status) }}</span>
                    <span class="text-xs text-gray-400">Due: {{ $project->due_date ? $project->due_date->format('M j') : 'N/A' }}</span>
                </div>
                <h3 class="mt-4 text-sm font-semibold text-gray-900 group-hover:text-brand-primary transition-colors">{{ $project->name }}</h3>
                <dl class="mt-1 flex flex-grow flex-col justify-between">
                    <dt class="sr-only">Client</dt>
                    <dd class="text-sm text-gray-500">{{ $project->client->name }}</dd>
                    <dt class="sr-only">Description</dt>
                    <dd class="text-sm text-gray-500 mt-2 line-clamp-2">{{ Str::limit(strip_tags($project->description), 100) }}</dd>
                </dl>
            </a>
            <div>
                <div class="-mt-px flex divide-x divide-gray-200">
                    <div class="flex w-0 flex-1">
                        <a href="{{ route('team.projects.chat', $project) }}" class="relative -mr-px inline-flex w-0 flex-1 items-center justify-center gap-x-3 rounded-bl-lg border border-transparent py-4 text-sm font-semibold text-gray-900 hover:text-brand-primary">
                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 2c-2.236 0-4.43.18-6.57.524 1.193.73 2.374 1.212 3.516 1.442.92.185 1.838-.1 2.532-1.282C9.93 2.158 10.45 2 11 2c.55 0 1.07.158 1.522.684.694 1.182 1.612 1.467 2.532 1.282 1.142-.23 2.323-.712 3.516-1.442C16.43 2.18 14.236 2 12 2c-2.236 0-4.43.18-6.57.524l-.126.02c-.084.013-.168.026-.252.04-.43.07-.86.148-1.286.233-.865.172-1.724.364-2.576.578C.696 3.541 0 4.095 0 4.771v10.458c0 .676.696 1.23 1.188 1.396.852.214 1.71.406 2.576.578.426.085.856.163 1.286.233.084.014.168.027.252.04C6.43 17.636 8.218 17.817 10 17.915V13.5a1.5 1.5 0 011.5-1.5h3a1.5 1.5 0 011.5 1.5v4.415c1.782-.098 3.57-.279 5.342-.459.084-.013.168-.026.252-.04.43-.07.86-.148 1.286-.233.865-.172 1.724-.364 2.576-.578C19.304 16.459 20 15.905 20 15.229V4.77c0-.676-.696-1.23-1.188-1.396-.852-.214-1.71-.406-2.576-.578-.426-.085-.856-.163-1.286-.233-.084-.014-.168-.027-.252-.04L10 2ZM8.5 2c-.176 0-.35.003-.523.01-.157.006-.312.014-.467.022C8.01 2.018 8.42 2 8.5 2Zm3 0c.08 0 .49.018.99.032-.155-.008-.31-.016-.467-.022-.173-.007-.347-.01-.523-.01Z" clip-rule="evenodd" />
                            </svg>
                            Project Chat
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 bg-white rounded-lg border border-gray-200 border-dashed">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-gray-900">No projects found</h3>
            <p class="mt-1 text-sm text-gray-500">You haven't been assigned to any projects yet.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
