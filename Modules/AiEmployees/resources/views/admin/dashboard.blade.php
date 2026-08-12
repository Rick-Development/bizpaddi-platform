@extends('admin::layouts.app')

@section('title', 'Executive Command Center')

@section('content')
<!-- Header Section -->
<div class="relative overflow-hidden rounded-2xl bg-white px-6 py-10 shadow-sm border border-gray-200 sm:px-12 sm:py-16 mb-8">
    <div class="absolute inset-0">
        <div class="absolute inset-y-0 left-0 w-1/2 bg-gradient-to-r from-gray-50 to-transparent"></div>
    </div>
    
    <div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="inline-flex items-center rounded-full bg-brand-primary/10 px-3 py-1 text-sm font-medium text-brand-primary ring-1 ring-inset ring-brand-primary/20">
                    AI Workforce
                </span>
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">System Online</span>
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl mb-2">Executive Command Center</h1>
            <p class="text-lg leading-8 text-gray-600 max-w-2xl">
                Real-time overview of AI autonomous operations, tasks, and system health.
            </p>
        </div>
        
        <div class="flex items-center gap-3">
            <form action="{{ route('admin.aiemployees.run-routines') }}" method="POST" class="inline-block form-run-routines">
                @csrf
                <button type="submit" class="inline-flex items-center justify-center rounded-lg border border-emerald-300 bg-emerald-50 text-emerald-700 px-5 py-3 text-sm font-semibold hover:bg-emerald-100 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2 text-emerald-600 animate-spin" style="animation-duration: 4s;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Run Routines Now
                </button>
            </form>
            <a href="{{ route('admin.aiemployees.tasks.index') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                Task Assignments
            </a>
            <a href="{{ route('admin.aiemployees.employees.create') }}" class="inline-flex items-center justify-center rounded-lg bg-brand-primary px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-brand-primary/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary transition-all duration-200 transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Deploy New Agent
            </a>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
    <!-- Active Agents -->
    <div class="bg-white overflow-hidden rounded-2xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-50 rounded-xl p-3">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Agents Active</dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-bold text-gray-900">{{ $stats['active_agents'] }}</div>
                            <div class="ml-2 flex items-baseline text-sm font-semibold text-gray-500">
                                / {{ $stats['total_agents'] }} deployed
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Tasks Processed -->
    <div class="bg-white overflow-hidden rounded-2xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-50 rounded-xl p-3">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Tasks Processed</dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-bold text-gray-900">{{ $stats['tasks_processed'] }}</div>
                            <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                <svg class="self-center flex-shrink-0 h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                12%
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Rate -->
    <div class="bg-white overflow-hidden rounded-2xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-brand-primary/10 rounded-xl p-3">
                    <svg class="h-6 w-6 text-brand-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Task Success Rate</dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-bold text-gray-900">{{ $stats['success_rate'] }}%</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pending Reviews -->
    <div class="bg-white overflow-hidden rounded-2xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-amber-50 rounded-xl p-3">
                    <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Human Reviews Req.</dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-bold text-gray-900">{{ $stats['pending_reviews'] ?? 0 }}</div>
                            <div class="ml-2 flex items-baseline text-sm font-semibold text-red-600">
                                Pending Action
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Department Overview -->
<h2 class="text-xl font-bold tracking-tight text-gray-900 mb-4">Department Overviews</h2>
<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
    <!-- Operations Panel -->
    <div class="bg-white overflow-hidden rounded-2xl shadow-sm border border-gray-200">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h3 class="text-base font-semibold leading-6 text-gray-900">Operations & GM</h3>
        </div>
        <div class="px-6 py-5">
            <ul class="divide-y divide-gray-100">
                <li class="py-3 flex justify-between items-center">
                    <span class="text-sm text-gray-600">Company Health Scans</span>
                    <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700">Healthy</span>
                </li>
                <li class="py-3 flex justify-between items-center">
                    <span class="text-sm text-gray-600">Active Projects Monitored</span>
                    <span class="text-sm font-medium text-gray-900">12</span>
                </li>
                <li class="py-3 flex justify-between items-center">
                    <span class="text-sm text-gray-600">Incidents Detected (24h)</span>
                    <span class="text-sm font-medium text-gray-900">0</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Sales & Marketing Panel -->
    <div class="bg-white overflow-hidden rounded-2xl shadow-sm border border-gray-200">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h3 class="text-base font-semibold leading-6 text-gray-900">Sales & Marketing</h3>
        </div>
        <div class="px-6 py-5">
            <ul class="divide-y divide-gray-100">
                <li class="py-3 flex justify-between items-center">
                    <span class="text-sm text-gray-600">Prospects Qualified Today</span>
                    <span class="text-sm font-medium text-gray-900">45</span>
                </li>
                <li class="py-3 flex justify-between items-center">
                    <span class="text-sm text-gray-600">Automated Follow-ups Sent</span>
                    <span class="text-sm font-medium text-gray-900">128</span>
                </li>
                <li class="py-3 flex justify-between items-center">
                    <span class="text-sm text-gray-600">Proposals Generated</span>
                    <span class="text-sm font-medium text-gray-900">3</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Engineering Panel -->
    <div class="bg-white overflow-hidden rounded-2xl shadow-sm border border-gray-200">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h3 class="text-base font-semibold leading-6 text-gray-900">Engineering & Design</h3>
        </div>
        <div class="px-6 py-5">
            <ul class="divide-y divide-gray-100">
                <li class="py-3 flex justify-between items-center">
                    <span class="text-sm text-gray-600">PRs Reviewed</span>
                    <span class="text-sm font-medium text-gray-900">8</span>
                </li>
                <li class="py-3 flex justify-between items-center">
                    <span class="text-sm text-gray-600">Bugs Auto-Triage</span>
                    <span class="text-sm font-medium text-gray-900">15</span>
                </li>
                <li class="py-3 flex justify-between items-center">
                    <span class="text-sm text-gray-600">Design Concepts Generated</span>
                    <span class="text-sm font-medium text-gray-900">2</span>
                </li>
            </ul>
        </div>
    </div>
    
    <!-- Social & Growth Panel -->
    <div class="bg-white overflow-hidden rounded-2xl shadow-sm border border-gray-200">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h3 class="text-base font-semibold leading-6 text-gray-900">Social Media & Growth</h3>
        </div>
        <div class="px-6 py-5">
            <ul class="divide-y divide-gray-100">
                <li class="py-3 flex justify-between items-center">
                    <span class="text-sm text-gray-600">Posts Published (24h)</span>
                    <span class="text-sm font-medium text-gray-900">6</span>
                </li>
                <li class="py-3 flex justify-between items-center">
                    <span class="text-sm text-gray-600">Comments Moderated</span>
                    <span class="text-sm font-medium text-gray-900">84</span>
                </li>
                <li class="py-3 flex justify-between items-center">
                    <span class="text-sm text-gray-600">Engagement Score</span>
                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700">Excellent</span>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Pending Approvals Queue -->
@if(count($pendingActions) > 0)
<div class="mt-8 bg-white overflow-hidden rounded-2xl shadow-sm border border-amber-200">
    <div class="border-b border-amber-200 bg-amber-50 px-6 py-4 flex items-center justify-between">
        <h3 class="text-base font-semibold leading-6 text-amber-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            Pending Actions Requiring Approval
        </h3>
        <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800">
            {{ count($pendingActions) }} pending
        </span>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action / Tool</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Arguments</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Decision</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($pendingActions as $action)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8 bg-brand-primary/10 rounded-full flex items-center justify-center">
                                <span class="text-brand-primary font-bold text-xs">{{ substr($action->employee->name, 0, 1) }}</span>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">{{ $action->employee->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                            {{ $action->tool_name }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500" x-data="{ showModal: false }">
                        <button @click="showModal = true" type="button" class="inline-flex items-center gap-1.5 rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            View Data
                        </button>
                        
                        <!-- Modal -->
                        <div x-show="showModal" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
                            <div x-show="showModal" 
                                 x-transition:enter="ease-out duration-300" 
                                 x-transition:enter-start="opacity-0" 
                                 x-transition:enter-end="opacity-100" 
                                 x-transition:leave="ease-in duration-200" 
                                 x-transition:leave-start="opacity-100" 
                                 x-transition:leave-end="opacity-0" 
                                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                            
                            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                    <div x-show="showModal" 
                                         x-transition:enter="ease-out duration-300" 
                                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                                         x-transition:leave="ease-in duration-200" 
                                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                         @click.away="showModal = false" 
                                         class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">
                                        
                                        <div class="absolute right-0 top-0 hidden pr-4 pt-4 sm:block">
                                            <button @click="showModal = false" type="button" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2">
                                                <span class="sr-only">Close</span>
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <div>
                                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-brand-primary/10">
                                                <svg class="h-6 w-6 text-brand-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />
                                                </svg>
                                            </div>
                                            <div class="mt-3 text-center sm:mt-5">
                                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Action Data Payload</h3>
                                                <div class="mt-4 text-left">
                                                    <div class="overflow-hidden rounded-xl border border-slate-200 shadow-sm bg-white">
                                                        <table class="min-w-full divide-y divide-slate-200">
                                                            <thead class="bg-slate-50">
                                                                <tr>
                                                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-1/4">Parameter</th>
                                                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Value</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-y divide-slate-100 bg-white">
                                                                @foreach($action->tool_arguments as $key => $val)
                                                                    @php
                                                                        $displayVal = $val;
                                                                        if (is_string($displayVal)) {
                                                                            // Try decoding if it looks like a JSON string to show it cleanly
                                                                            if (str_starts_with($displayVal, '{') || str_starts_with($displayVal, '[')) {
                                                                                $decoded = json_decode($displayVal, true);
                                                                                if (json_last_error() === JSON_ERROR_NONE) {
                                                                                    $displayVal = $decoded;
                                                                                }
                                                                            }
                                                                        }
                                                                        
                                                                        if (is_array($displayVal) || is_object($displayVal)) {
                                                                            $displayVal = json_encode($displayVal, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                                                                        }
                                                                    @endphp
                                                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                                                        <td class="px-4 py-3 whitespace-nowrap text-xs font-bold text-slate-700 capitalize font-mono">{{ str_replace('_', ' ', $key) }}</td>
                                                                        <td class="px-4 py-3 text-sm text-slate-600 whitespace-pre-wrap font-medium">
                                                                            @if($key === 'content' || $key === 'description' || $key === 'context')
                                                                                <div class="bg-slate-50 rounded-lg p-3 border border-slate-100 font-sans leading-relaxed text-slate-800">
                                                                                    {!! nl2br(e($displayVal)) !!}
                                                                                </div>
                                                                            @else
                                                                                {{ $displayVal }}
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-5 sm:mt-6">
                                            <button @click="showModal = false" type="button" class="inline-flex w-full justify-center rounded-md bg-brand-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-primary/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">Got it, thanks!</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $action->created_at->diffForHumans() }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <form action="{{ route('admin.aiemployees.dashboard.actions.approve', $action->id) }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-900 mr-3">Approve</button>
                        </form>
                        <form action="{{ route('admin.aiemployees.dashboard.actions.reject', $action->id) }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-900">Reject</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Task Delegation Terminal -->
<div class="mt-8 bg-white overflow-hidden rounded-2xl shadow-sm border border-gray-200">
    <div class="border-b border-gray-200 bg-slate-900 px-6 py-4 flex items-center justify-between">
        <h3 class="text-base font-semibold leading-6 text-white flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Task Delegation Terminal
        </h3>
        <span class="text-xs font-medium text-slate-400">Direct AI Interface</span>
    </div>
    <div class="px-6 py-6 bg-slate-50">
        <form id="taskDelegationForm" action="{{ route('admin.aiemployees.dashboard.task') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Input Area -->
                <div class="md:col-span-1 space-y-4">
                    <div>
                        <label for="employee_id" class="block text-sm font-medium text-gray-700">Assign To Agent</label>
                        <select id="employee_id" name="employee_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-brand-primary focus:border-brand-primary sm:text-sm rounded-md shadow-sm bg-white">
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->name }} ({{ str_replace('_', ' ', Str::title($agent->role)) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="task" class="block text-sm font-medium text-gray-700">Task / Question</label>
                        <textarea id="task" name="task" rows="6" class="mt-1 shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border border-gray-300 rounded-md p-3" placeholder="E.g., Review my latest PR, write a post about AI..."></textarea>
                    </div>
                    <button type="submit" class="w-full flex justify-center items-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-brand-primary hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary transition-all">
                        Execute Task
                    </button>
                </div>
                
                <!-- Output Area -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">AI Output Log</label>
                    <div id="aiOutputContainer" class="bg-slate-900 rounded-md p-4 h-full min-h-[250px] shadow-inner relative overflow-y-auto">
                        <div id="aiOutputPlaceholder" class="absolute inset-0 flex flex-col items-center justify-center text-slate-500">
                            <svg class="w-12 h-12 mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <p class="text-sm">Awaiting task assignment...</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Agent Training & Configuration -->
<div class="mt-8 bg-white overflow-hidden rounded-2xl shadow-sm border border-gray-200">
    <div class="border-b border-gray-200 bg-white px-6 py-4 flex items-center justify-between">
        <h3 class="text-base font-semibold leading-6 text-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
            </svg>
            Agent Training & Configuration
        </h3>
        <span class="text-xs font-medium text-slate-500">Fine-tune AI prompts and core directives</span>
    </div>
    <div class="px-6 py-6 bg-slate-50">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($agents as $agent)
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-r from-brand-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="h-10 w-10 bg-brand-primary/10 rounded-full flex items-center justify-center text-brand-primary font-bold text-lg">
                                {{ substr($agent->name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 group-hover:text-brand-primary transition-colors">{{ $agent->name }}</h4>
                                <span class="text-xs text-gray-500 capitalize">{{ str_replace('_', ' ', $agent->role) }}</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-600 line-clamp-3 mb-5 leading-relaxed">{{ strip_tags($agent->system_prompt) }}</p>
                    </div>
                    <a href="{{ route('admin.aiemployees.employees.edit', $agent->id) }}" class="relative inline-flex w-full justify-center items-center gap-2 rounded-lg bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-brand-primary hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Train Agent
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        const form = $('#taskDelegationForm');
        const container = $('#aiOutputContainer');
        const placeholder = $('#aiOutputPlaceholder');
        const submitBtn = form.find('button[type="submit"]');

        let pollInterval;

        function formatOutput(result) {
            let html = '';
            
            // Format Agent Name & Model
            html += `<div class="mb-4 text-xs font-medium text-slate-500 uppercase tracking-widest border-b border-slate-800 pb-2 flex justify-between">
                <span>Model: ${result.model || 'Unknown'} | Provider: ${result.provider || 'Unknown'}</span>
                <span class="text-emerald-400">Status: Complete</span>
            </div>`;
            
            // Format the main text response
            if (result.result) {
                // Convert markdown-style bullet points to HTML
                let formattedText = result.result
                    .replace(/\n\n/g, '<br><br>')
                    .replace(/- \*\*(.*?)\*\*(.*?)(<br>|$)/g, '<li class="ml-4"><strong class="text-brand-primary">$1</strong>$2</li>')
                    .replace(/- (.*?)(<br>|$)/g, '<li class="ml-4 text-slate-300">$1</li>');
                
                // Wrap li elements in ul
                if (formattedText.includes('<li')) {
                    formattedText = formattedText.replace(/(<li.*?>.*?<\/li>)/g, '<ul class="list-disc mb-4 space-y-1 mt-2">$1</ul>');
                    // clean up multiple adjacent uls
                    formattedText = formattedText.replace(/<\/ul>\s*<ul class="list-disc mb-4 space-y-1 mt-2">/g, '');
                }

                html += `<div class="text-sm text-slate-200 leading-relaxed mb-6 font-sans">${formattedText}</div>`;
            }
            
            // Format Tool Calls
            if (result.toolCalls && result.toolCalls.length > 0) {
                html += `<div class="mt-6 pt-4 border-t border-slate-800">
                    <h4 class="text-xs font-semibold text-brand-primary uppercase tracking-widest mb-3">System Actions Executed</h4>`;
                
                result.toolCalls.forEach(tool => {
                    html += `<div class="bg-slate-800 rounded px-4 py-3 mb-2 border border-slate-700">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="font-mono text-sm text-emerald-400">${tool.name}</span>
                        </div>
                        <div class="bg-slate-900 rounded p-3 overflow-x-auto">
                            <pre class="text-xs text-slate-400 font-mono m-0">${JSON.stringify(tool.arguments, null, 2)}</pre>
                        </div>
                    </div>`;
                });
                
                html += `</div>`;
            }

            return html;
        }

        function pollTask(taskId) {
            $.ajax({
                url: `/admin/aiemployees/dashboard/task/${taskId}/poll`,
                type: 'GET',
                success: function(response) {
                    if (response.status === 'completed') {
                        clearInterval(pollInterval);
                        placeholder.hide();
                        $('#pollingIndicator').remove();
                        
                        const newEntry = $('<div class="mb-8 opacity-0 translate-y-4 transition-all duration-500"></div>');
                        newEntry.html(formatOutput(response.result));
                        
                        container.append(newEntry);
                        
                        requestAnimationFrame(() => {
                            newEntry.removeClass('opacity-0 translate-y-4');
                        });
                        
                        container.scrollTop(container[0].scrollHeight);
                        
                        submitBtn.prop('disabled', false)
                            .removeClass('opacity-50 cursor-not-allowed')
                            .html('Execute Task');

                    } else if (response.status === 'failed') {
                        clearInterval(pollInterval);
                        placeholder.hide();
                        $('#pollingIndicator').remove();
                        
                        const newEntry = $('<div class="mb-8 opacity-0 transition-all duration-500"></div>');
                        newEntry.html(`
                            <div class="bg-red-900/50 border border-red-500 text-red-200 rounded p-4 text-sm">
                                <strong class="font-bold">Execution Failed</strong><br>
                                ${response.result && response.result.text ? response.result.text : 'An unknown error occurred during AI processing.'}
                            </div>
                        `);
                        
                        container.append(newEntry);
                        requestAnimationFrame(() => newEntry.removeClass('opacity-0'));
                        
                        submitBtn.prop('disabled', false)
                            .removeClass('opacity-50 cursor-not-allowed')
                            .html('Execute Task');
                    }
                },
                error: function() {
                    clearInterval(pollInterval);
                    $('#pollingIndicator').remove();
                    submitBtn.prop('disabled', false)
                        .removeClass('opacity-50 cursor-not-allowed')
                        .html('Execute Task');
                    alert('Lost connection while polling task status.');
                }
            });
        }

        form.on('submit', function(e) {
            e.preventDefault();
            
            const taskInput = $('#task');
            
            if (!taskInput.val().trim()) {
                alert('Please enter a task.');
                return;
            }

            submitBtn.prop('disabled', true)
                .addClass('opacity-50 cursor-not-allowed')
                .html(`
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Dispatching...
                `);
                
            placeholder.hide();
            const loadingHtml = $(`
                <div id="loadingIndicator" class="flex flex-col items-center justify-center h-full text-slate-400 py-10">
                    <svg class="animate-spin h-10 w-10 text-brand-primary mb-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm font-medium">Processing Task Asynchronously...</span>
                </div>
            `);
            container.append(loadingHtml);
            container.scrollTop(container[0].scrollHeight);

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    if (response.task_id) {
                        $('#loadingIndicator').remove();
                        
                        const pollingHtml = $(`
                            <div id="pollingIndicator" class="flex flex-col items-center justify-center h-full text-brand-primary py-10">
                                <div class="flex space-x-2 mb-4">
                                  <div class="w-3 h-3 bg-brand-primary rounded-full animate-bounce" style="animation-delay: -0.3s"></div>
                                  <div class="w-3 h-3 bg-brand-primary rounded-full animate-bounce" style="animation-delay: -0.15s"></div>
                                  <div class="w-3 h-3 bg-brand-primary rounded-full animate-bounce"></div>
                                </div>
                                <span class="text-sm font-medium">AI is thinking and executing tools...</span>
                            </div>
                        `);
                        container.append(pollingHtml);
                        container.scrollTop(container[0].scrollHeight);
                        
                        submitBtn.html(`
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Waiting...
                        `);

                        pollInterval = setInterval(function() {
                            pollTask(response.task_id);
                        }, 2000); // poll every 2 seconds
                        
                        taskInput.val('');
                    } else {
                        $('#loadingIndicator').remove();
                        placeholder.show();
                        submitBtn.prop('disabled', false)
                            .removeClass('opacity-50 cursor-not-allowed')
                            .html('Execute Task');
                        alert('Error dispatching task.');
                    }
                },
                error: function(xhr) {
                    $('#loadingIndicator').remove();
                    placeholder.show();
                    submitBtn.prop('disabled', false)
                        .removeClass('opacity-50 cursor-not-allowed')
                        .html('Execute Task');
                        
                    let errorMsg = 'Failed to dispatch task.';
                    if (xhr.responseJSON && xhr.responseJSON.text) {
                        errorMsg = xhr.responseJSON.text;
                    }
                    alert(errorMsg);
                }
            });
        });
    });
</script>

@endpush
@endsection
