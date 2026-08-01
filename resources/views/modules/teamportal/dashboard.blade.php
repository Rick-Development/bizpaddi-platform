@extends('teamportal::layouts.app')

@section('title', 'Team Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Dashboard</h1>
            <p class="mt-1 text-sm text-gray-500">Welcome back, {{ auth()->user()->name }}! Here's what's happening today.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <span class="inline-flex items-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                {{ now()->format('l, F j, Y') }}
            </span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Attendance Metric -->
        <!-- Attendance Metric -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 hover:shadow-md transition-shadow">
            <dt>
                <div class="absolute rounded-xl bg-brand-primary/10 p-3">
                    <svg class="h-6 w-6 text-brand-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="ml-16 truncate text-sm font-medium text-gray-500">Attendance</p>
            </dt>
            <dd class="ml-16 flex items-baseline pb-1 sm:pb-7">
                @if($attendanceToday && $attendanceToday->clock_out)
                    <p class="text-2xl font-semibold text-gray-900">Done for Day</p>
                @elseif($attendanceToday)
                    <p class="text-2xl font-semibold text-green-600">Clocked In</p>
                    <p class="ml-2 text-xs text-gray-500">since {{ $attendanceToday->clock_in->format('H:i') }}</p>
                @else
                    <p class="text-2xl font-semibold text-gray-400">Not Checked In</p>
                @endif
            </dd>
            <div class="absolute inset-x-0 bottom-0 bg-gray-50 px-6 py-3">
                <div class="text-sm">
                    @if($attendanceToday && $attendanceToday->clock_out)
                        <span class="text-gray-500 font-medium">Have a great evening!</span>
                    @elseif($attendanceToday)
                        <form action="{{ route('team.attendance.clock-out') }}" method="POST">
                            @csrf
                            <button type="submit" class="font-medium text-red-600 hover:text-red-500 transition-colors">Clock Out Now <span aria-hidden="true">&rarr;</span></button>
                        </form>
                    @else
                        <form action="{{ route('team.attendance.clock-in') }}" method="POST">
                            @csrf
                            <button type="submit" class="font-medium text-brand-primary hover:text-brand-primary/80 transition-colors">Check In Now <span aria-hidden="true">&rarr;</span></button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pending Tasks Metric -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 hover:shadow-md transition-shadow">
            <dt>
                <div class="absolute rounded-xl bg-brand-secondary/10 p-3">
                    <svg class="h-6 w-6 text-brand-secondary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <p class="ml-16 truncate text-sm font-medium text-gray-500">Pending Tasks</p>
            </dt>
            <dd class="ml-16 flex items-baseline pb-1 sm:pb-7">
                <p class="text-2xl font-semibold text-gray-900">0</p>
                <p class="ml-2 flex items-baseline text-sm font-semibold text-gray-500">
                    tasks
                </p>
            </dd>
            <div class="absolute inset-x-0 bottom-0 bg-gray-50 px-6 py-3">
                <div class="text-sm">
                    <a href="{{ route('team.tasks.index') }}" class="font-medium text-brand-secondary hover:text-brand-secondary/80">View list <span aria-hidden="true">&rarr;</span></a>
                </div>
            </div>
        </div>

        <!-- Daily Reports Metric -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 hover:shadow-md transition-shadow">
            <dt>
                <div class="absolute rounded-xl bg-brand-secondary/10 p-3">
                    <svg class="h-6 w-6 text-brand-secondary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <p class="ml-16 truncate text-sm font-medium text-gray-500">Reports</p>
            </dt>
            <dd class="ml-16 flex items-baseline pb-1 sm:pb-7">
                <p class="text-2xl font-semibold text-gray-900">0</p>
                <p class="ml-2 flex items-baseline text-sm font-semibold text-gray-500">
                    this week
                </p>
            </dd>
            <div class="absolute inset-x-0 bottom-0 bg-gray-50 px-6 py-3">
                <div class="text-sm">
                    <a href="{{ route('team.reports.create') }}" class="font-medium text-brand-secondary hover:text-brand-secondary/80">Create new <span aria-hidden="true">&rarr;</span></a>
                </div>
            </div>
        </div>

        <!-- Bank Accounts Metric -->
         <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 hover:shadow-md transition-shadow">
            <dt>
                <div class="absolute rounded-xl bg-brand-primary/10 p-3">
                    <svg class="h-6 w-6 text-brand-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                    </svg>
                </div>
                <p class="ml-16 truncate text-sm font-medium text-gray-500">Bank Accounts</p>
            </dt>
             <dd class="ml-16 flex items-baseline pb-1 sm:pb-7">
                <p class="text-2xl font-semibold text-gray-900">{{ auth()->user()->bankAccounts()->count() }}</p>
                <p class="ml-2 flex items-baseline text-sm font-semibold text-gray-500">
                    linked
                </p>
            </dd>
            <div class="absolute inset-x-0 bottom-0 bg-gray-50 px-6 py-3">
                <div class="text-sm">
                    <a href="{{ route('team.bank-accounts.index') }}" class="font-medium text-brand-primary hover:text-brand-primary/80">Manage <span aria-hidden="true">&rarr;</span></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Grid -->
    <div>
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <a href="#" class="group relative flex items-center gap-x-6 rounded-2xl bg-white p-4 text-sm leading-6 hover:bg-gray-50 shadow-sm ring-1 ring-gray-900/5 transition-all hover:shadow-md">
                <div class="flex h-11 w-11 flex-none items-center justify-center rounded-xl bg-gray-50 group-hover:bg-white ring-1 ring-gray-200 group-hover:ring-brand-primary/20">
                    <svg class="h-6 w-6 text-gray-600 group-hover:text-brand-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-auto">
                    <span class="block font-semibold text-gray-900">Log Attendance</span>
                    <span class="mt-1 block text-gray-500">Check in or out for the day</span>
                </div>
            </a>

            <a href="{{ route('team.reports.create') }}" class="group relative flex items-center gap-x-6 rounded-2xl bg-white p-4 text-sm leading-6 hover:bg-gray-50 shadow-sm ring-1 ring-gray-900/5 transition-all hover:shadow-md">
                <div class="flex h-11 w-11 flex-none items-center justify-center rounded-xl bg-gray-50 group-hover:bg-white ring-1 ring-gray-200 group-hover:ring-brand-secondary/20">
                    <svg class="h-6 w-6 text-gray-600 group-hover:text-brand-secondary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <div class="flex-auto">
                    <span class="block font-semibold text-gray-900">Daily Report</span>
                    <span class="mt-1 block text-gray-500">Submit your work log</span>
                </div>
            </a>

             <a href="{{ route('team.bank-accounts.index') }}" class="group relative flex items-center gap-x-6 rounded-2xl bg-white p-4 text-sm leading-6 hover:bg-gray-50 shadow-sm ring-1 ring-gray-900/5 transition-all hover:shadow-md">
                <div class="flex h-11 w-11 flex-none items-center justify-center rounded-xl bg-gray-50 group-hover:bg-white ring-1 ring-gray-200 group-hover:ring-brand-primary/20">
                    <svg class="h-6 w-6 text-gray-600 group-hover:text-brand-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                    </svg>
                </div>
                <div class="flex-auto">
                    <span class="block font-semibold text-gray-900">Manage Banking</span>
                    <span class="mt-1 block text-gray-500">Update account details</span>
                </div>
            </a>
    <!-- Recent Activity Section -->
    <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Recent Tasks -->
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex items-center justify-between">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Recent Tasks</h3>
                <a href="{{ route('team.tasks.index') }}" class="text-sm font-medium text-brand-primary hover:text-brand-primary/80">View all</a>
            </div>
            <ul role="list" class="divide-y divide-gray-100">
                @forelse(auth()->user()->tasks()->latest()->take(5)->get() as $task)
                <li class="relative flex items-center justify-between gap-x-6 px-6 py-4 hover:bg-gray-50 transition-colors">
                    <div class="min-w-0">
                        <div class="flex items-start gap-x-3">
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{ $task->title }}</p>
                            @php
                                $statusClasses = [
                                    'pending' => 'text-yellow-700 bg-yellow-50 ring-yellow-600/20',
                                    'in_progress' => 'text-blue-700 bg-blue-50 ring-blue-700/10',
                                    'completed' => 'text-green-700 bg-green-50 ring-green-600/20',
                                ];
                            @endphp
                            <p class="rounded-md whitespace-nowrap mt-0.5 px-1.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $statusClasses[$task->status] ?? 'text-gray-600 bg-gray-50 ring-gray-500/10' }}">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </p>
                        </div>
                        <div class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                            <p class="truncate">Due {{ $task->due_date ? $task->due_date->format('M j') : 'No date' }}</p>
                            <svg viewBox="0 0 2 2" class="h-0.5 w-0.5 fill-current"><circle cx="1" cy="1" r="1" /></svg>
                            <p class="truncate">{{ $task->project->name ?? 'No Project' }}</p>
                        </div>
                    </div>
                </li>
                @empty
                <li class="px-6 py-8 text-center text-sm text-gray-500">No recent tasks found.</li>
                @endforelse
            </ul>
        </div>

        <!-- Recent Reports -->
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex items-center justify-between">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Recent Reports</h3>
                <a href="{{ route('team.reports.index') }}" class="text-sm font-medium text-brand-primary hover:text-brand-primary/80">View all</a>
            </div>
            <ul role="list" class="divide-y divide-gray-100">
                @forelse(auth()->user()->reports()->latest()->take(5)->get() as $report)
                <li class="relative flex gap-x-4 px-6 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex-auto min-w-0">
                        <div class="flex items-baseline justify-between gap-x-4">
                            <p class="text-sm font-semibold leading-6 text-gray-900">Daily Report</p>
                            <p class="flex-none text-xs text-gray-500">
                                <time datetime="{{ $report->created_at }}">{{ $report->created_at->diffForHumans() }}</time>
                            </p>
                        </div>
                        <p class="mt-1 line-clamp-2 text-sm leading-6 text-gray-600">{{ Str::limit($report->content ?? 'No content', 100) }}</p>
                    </div>
                </li>
                 @empty
                <li class="px-6 py-8 text-center text-sm text-gray-500">No reports submitted recently.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
