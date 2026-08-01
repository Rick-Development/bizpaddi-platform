@extends('admin::layouts.app')

@section('title', 'Jobs')

@section('content')
<!-- Header -->
<div class="mb-8">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Job Postings</h1>
            <p class="mt-2 text-sm text-gray-500">Manage career opportunities and job listings</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.jobs.create') }}" class="inline-flex items-center px-5 py-2.5 bg-brand-primary text-white text-sm font-medium rounded-xl hover:bg-brand-primary/90 shadow-lg shadow-brand-primary/30 transition-all hover:-translate-y-0.5">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Post Job
            </a>
        </div>
    </div>
</div>

@if($jobs->isEmpty())
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
    <div class="mx-auto h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" />
        </svg>
    </div>
    <h3 class="text-lg font-semibold text-gray-900">No job postings yet</h3>
    <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto">Start attracting top talent by posting your first job opening.</p>
    <div class="mt-6">
        <a href="{{ route('admin.jobs.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-primary text-white text-sm font-medium rounded-lg hover:bg-brand-primary/90 transition-colors">
            Post Your First Job
        </a>
    </div>
</div>
@else
<!-- Job Cards Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    @foreach($jobs as $job)
    <div class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden">
        <div class="p-6">
            <!-- Header -->
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-brand-primary transition-colors">
                        {{ $job->title }}
                    </h3>
                    <p class="text-sm text-gray-500">{{ $job->department }}</p>
                </div>
                
                @if($job->is_active)
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-100">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                    Active
                </span>
                @else
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600 border border-red-100">
                    Inactive
                </span>
                @endif
            </div>

            <!-- Job Details -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="h-4 w-4 mr-2 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="truncate">{{ $job->location }}</span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="h-4 w-4 mr-2 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="capitalize">{{ $job->type }}</span>
                </div>
            </div>

            <!-- Applications Count -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <a href="{{ route('admin.applications.index', ['job_id' => $job->id]) }}" class="flex items-center text-sm font-medium text-brand-primary hover:text-brand-primary/80 transition-colors">
                    <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    {{ $job->applications_count }} {{ Str::plural('Application', $job->applications_count) }}
                </a>
                
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.jobs.edit', $job) }}" class="p-1.5 text-gray-400 hover:text-brand-primary hover:bg-brand-primary/5 rounded-lg transition-colors" title="Edit">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                    <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this job posting?');" class="inline-flex">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
