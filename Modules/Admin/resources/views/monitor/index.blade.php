@extends('admin::layouts.app')

@section('title', 'Team Monitoring')

@section('content')
<div class="mb-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl font-bold text-gray-900">Team Monitoring</h1>
            <p class="mt-1 text-sm text-gray-600">Track attendance, work duration, and team availability</p>
        </div>
        
        <!-- Filters -->
        <form action="{{ route('admin.team-monitoring.index') }}" method="GET" class="flex gap-2 items-center">
            <div>
                <select name="user_id" class="rounded-lg border-gray-300 text-sm focus:ring-brand-primary focus:border-brand-primary">
                    <option value="">All Team Members</option>
                    @foreach($allUsers as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="month" class="rounded-lg border-gray-300 text-sm focus:ring-brand-primary focus:border-brand-primary">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endfor
                </select>
            </div>
             <div>
                <select name="year" class="rounded-lg border-gray-300 text-sm focus:ring-brand-primary focus:border-brand-primary">
                    @foreach(range(date('Y'), date('Y') - 2) as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-brand-secondary text-white rounded-lg hover:bg-brand-secondary/90 transition-colors text-sm font-medium">
                View
            </button>
        </form>
    </div>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                   <div class="bg-blue-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                   </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Hours Logged</dt>
                        <dd>
                            <div class="text-lg font-medium text-gray-900">{{ number_format($totalHours, 1) }} hrs</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="bg-green-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Avg. Attendance Rate</dt>
                        <dd>
                            <div class="text-lg font-medium text-gray-900">{{ $avgAttendanceRate }}%</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    
     <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="bg-purple-100 rounded-md p-3">
                         <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Working Days (So Far)</dt>
                        <dd>
                            <div class="text-lg font-medium text-gray-900">{{ $potentialWorkDays }} days</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Table -->
<div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
    <table class="min-w-full divide-y divide-gray-300">
        <thead>
            <tr>
                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Team Member</th>
                <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900">Days Present</th>
                 <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900">Total Duration</th>
                <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900">Avg. Daily Hours</th>
                <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900">Days Absent</th>
                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                    <span class="sr-only">Actions</span>
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($monitoringData as $data)
            <tr>
                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                    <div class="flex items-center">
                        <div class="h-10 w-10 flex-shrink-0">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gray-500">
                                <span class="font-medium leading-none text-white">{{ substr($data['user']->name, 0, 1) }}</span>
                            </span>
                        </div>
                        <div class="ml-4">
                            <div class="font-medium text-gray-900">{{ $data['user']->name }}</div>
                            <div class="text-gray-500">{{ $data['user']->email }}</div>
                        </div>
                    </div>
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-center text-gray-500">
                    <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                        {{ $data['days_present'] }} days
                    </span>
                </td>
                 <td class="whitespace-nowrap px-3 py-4 text-sm text-center text-gray-500">
                     <span class="font-semibold text-gray-900">
                        {{ floor($data['total_duration_minutes'] / 60) }}h {{ $data['total_duration_minutes'] % 60 }}m
                     </span>
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-center text-gray-500">
                    {{ $data['avg_daily_hours'] }} hrs
                </td>
                 <td class="whitespace-nowrap px-3 py-4 text-sm text-center text-gray-500">
                     @if($data['days_absent'] > 0)
                        <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">
                            {{ $data['days_absent'] }} days
                        </span>
                     @else
                        <span class="text-gray-400">-</span>
                     @endif
                </td>
                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                    <a href="{{ route('admin.work-logs.index', ['user_id' => $data['user']->id, 'date' => date('Y-m-d')]) }}" class="text-brand-primary hover:text-brand-primary/80">
                        View Logs<span class="sr-only">, {{ $data['user']->name }}</span>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-3 py-8 text-center text-sm text-gray-500">
                    No data available for this period.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
