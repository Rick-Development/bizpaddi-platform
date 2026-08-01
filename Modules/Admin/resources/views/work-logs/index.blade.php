@extends('admin::layouts.app')

@section('title', 'Daily Work Logs')

@section('content')
<div class="mb-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl font-bold text-gray-900">Daily Work Logs</h1>
            <p class="mt-1 text-sm text-gray-600">View daily team check-ins and check-outs</p>
        </div>
        
        <!-- Filters -->
        <form action="{{ route('admin.work-logs.index') }}" method="GET" class="flex gap-2 items-center">
            <div>
                <select name="user_id" class="rounded-lg border-gray-300 text-sm focus:ring-brand-primary focus:border-brand-primary">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <input type="date" name="date" value="{{ request('date') }}" class="rounded-lg border-gray-300 text-sm focus:ring-brand-primary focus:border-brand-primary">
            </div>
            <button type="submit" class="px-4 py-2 bg-brand-secondary text-white rounded-lg hover:bg-brand-secondary/90 transition-colors text-sm font-medium">
                Filter
            </button>
            @if(request()->has('date') || request()->has('user_id'))
                <a href="{{ route('admin.work-logs.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-900 text-sm font-medium">
                    Clear
                </a>
            @endif
        </form>
    </div>
</div>

@if($workLogs->isEmpty())
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
        </svg>
        <h3 class="mt-4 text-lg font-semibold text-gray-900">No work logs found</h3>
        <p class="mt-2 text-sm text-gray-600">Try adjusting your filters or check back later.</p>
    </div>
@else
    <div class="space-y-4">
        <!-- Header Row -->
        <div class="bg-gray-50/50 rounded-xl p-3 grid grid-cols-12 gap-4 text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:grid">
            <div class="col-span-3 pl-4">User & Date</div>
            <div class="col-span-4">Planned Tasks</div>
            <div class="col-span-3">Check-in / Out</div>
            <div class="col-span-2 text-right pr-4">Status</div>
        </div>

        @foreach($workLogs as $log)
        <a href="{{ route('admin.work-logs.show', $log->id) }}" class="block group bg-white rounded-2xl p-5 shadow-sm hover:shadow-md border border-gray-100 transition-all duration-200 relative overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                
                <!-- User & Date -->
                <div class="col-span-12 md:col-span-3">
                    <div class="flex items-center gap-4 pl-2">
                        <div class="h-10 w-10 rounded-full bg-brand-primary/10 flex items-center justify-center text-brand-primary font-bold text-sm">
                            {{ substr($log->user->name ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">{{ $log->user->name ?? 'Unknown User' }}</h3>
                            <p class="text-xs text-gray-500">{{ $log->start_time->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Planned Tasks -->
                <div class="col-span-12 md:col-span-4">
                    <p class="text-sm text-gray-600 line-clamp-2" title="{{ $log->planned_tasks }}">
                        <span class="font-medium text-gray-900">Summary:</span> {{ $log->planned_tasks }}
                    </p>
                </div>

                <!-- Check-in / Out -->
                <div class="col-span-6 md:col-span-3">
                    <div class="flex flex-col">
                        <span class="text-sm font-bold text-gray-900">
                             In: {{ $log->start_time->format('H:i') }}
                        </span>
                        <span class="text-sm text-gray-500">
                             Out: 
                             @if($log->end_time)
                                {{ $log->end_time->format('H:i') }}
                                <span class="text-xs bg-gray-100 rounded px-1 ml-1" title="Duration">
                                    {{ floor($log->duration_minutes / 60) }}h {{ $log->duration_minutes % 60 }}m
                                </span>
                             @else
                                <span class="text-green-600 font-semibold italic">Working...</span>
                             @endif
                        </span>
                    </div>
                </div>

                <!-- Status -->
                <div class="col-span-6 md:col-span-2 text-right">
                    @if($log->status === 'completed')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Completed
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            In Progress
                        </span>
                    @endif
                </div>
            </div>
        </a>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $workLogs->links() }}
    </div>
@endif

@endsection
