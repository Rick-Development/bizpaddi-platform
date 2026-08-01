@extends('admin::layouts.app')

@section('title', 'Work Log Details')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.work-logs.index') }}" class="p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Work Log Details</h1>
            <p class="mt-1 text-sm text-gray-600">ID: #{{ $workLog->id }} • {{ $workLog->start_time ? $workLog->start_time->format('M d, Y') : 'N/A' }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Planned Tasks -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                </svg>
                Planned Tasks (Check-in)
            </h2>
            <div class="bg-gray-50 rounded-xl p-4 text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $workLog->planned_tasks }}</div>
            
            @if($workLog->resources_needed)
            <div class="mt-4">
                <h4 class="text-sm font-semibold text-gray-900 mb-2">Resources Needed:</h4>
                <p class="text-sm text-gray-600 bg-yellow-50 p-3 rounded-lg border border-yellow-100">{{ $workLog->resources_needed }}</p>
            </div>
            @endif
        </div>

        <!-- Actual Tasks (Check-out) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden">
            @if(!$workLog->end_time)
                <div class="absolute inset-0 bg-gray-50/50 backdrop-blur-[1px] flex items-center justify-center z-10">
                    <span class="bg-white px-4 py-2 rounded-lg shadow-sm font-medium text-gray-500 border border-gray-200">
                        Session in progress...
                    </span>
                </div>
            @endif

            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 019 9v.375M10.125 2.25A3.375 3.375 0 0113.5 5.25v1.5c0 .668.275 1.39.8 1.907.24.24.521.434.82.576.4.19.855.286 1.33.286h4.5M19.5 7.5v1.5a2.25 2.25 0 00-2.25-2.25h-1.5m3 6v1.5a2.25 2.25 0 00-2.25-2.25h-1.5m3 6v1.5a2.25 2.25 0 00-2.25-2.25h-1.5" />
                </svg>
                Actual Accomplishments (Check-out)
            </h2>
            
            @if($workLog->actual_tasks)
                <div class="bg-green-50/50 rounded-xl p-4 text-gray-700 whitespace-pre-wrap leading-relaxed border border-green-100">{{ $workLog->actual_tasks }}</div>
            @else
                <p class="text-gray-400 italic">Not submitted yet.</p>
            @endif

            @if($workLog->challenges)
            <div class="mt-4">
                <h4 class="text-sm font-semibold text-red-700 mb-2">Challenges Faced:</h4>
                <p class="text-sm text-red-600 bg-red-50 p-3 rounded-lg border border-red-100">{{ $workLog->challenges }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Sidebar Stats -->
    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Session Info</h2>
            
            <div class="space-y-4">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Team Member</p>
                    <div class="flex items-center gap-3 mt-1">
                        <div class="h-8 w-8 rounded-full bg-brand-primary/10 flex items-center justify-center text-brand-primary font-bold text-xs">
                            {{ substr($workLog->user->name ?? 'U', 0, 1) }}
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $workLog->user->name ?? 'Unknown' }}</span>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100">
                     <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Duration</p>
                     @if($workLog->duration_minutes)
                        <p class="text-3xl font-bold text-brand-primary">{{ floor($workLog->duration_minutes / 60) }}h {{ $workLog->duration_minutes % 60 }}m</p>
                        <p class="text-xs text-gray-500 mt-1">Total worked time</p>
                     @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 animate-pulse">
                            Tracking...
                        </span>
                     @endif
                </div>

                <div class="pt-4 border-t border-gray-100 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Check-in</p>
                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $workLog->start_time ? $workLog->start_time->format('H:i') : 'N/A' }}</p>
                        <p class="text-xs text-gray-400">{{ $workLog->start_time ? $workLog->start_time->format('M d') : '' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Check-out</p>
                        @if($workLog->end_time)
                            <p class="text-sm font-semibold text-gray-900 mt-1">{{ $workLog->end_time->format('H:i') }}</p>
                            <p class="text-xs text-gray-400">{{ $workLog->end_time->format('M d') }}</p>
                        @else
                            <p class="text-sm font-medium text-gray-400 italic mt-1">Pending...</p>
                        @endif
                    </div>
                </div>
                
                @if($workLog->status === 'completed')
                <div class="pt-4 border-t border-gray-100">
                    <span class="block text-center w-full px-4 py-2 bg-green-50 text-green-700 rounded-lg text-sm font-medium border border-green-100">
                        Session Completed
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
