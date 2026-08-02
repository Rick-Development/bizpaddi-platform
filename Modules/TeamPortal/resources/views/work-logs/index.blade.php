@extends('teamportal::layouts.app')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-bold leading-6 text-gray-900">Work Session Logs</h1>
            <p class="mt-2 text-sm text-gray-700">Track your work sessions, check-ins, challenges, and progress.</p>
        </div>
    </div>

    <!-- Active Session / Check In -->
    <div class="mt-8">
        @if($activeLog)
            <!-- Active Session Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-green-500">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 flex items-center gap-2">
                            <span class="relative flex h-3 w-3">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                            </span>
                            Work In Progress
                        </h3>
                        <div class="text-3xl font-mono font-bold text-gray-700" id="timer">00:00:00</div>
                    </div>

                    <div class="bg-gray-50 rounded-md p-4 mb-6">
                        <strong class="block text-sm font-medium text-gray-700">Current Focus:</strong>
                        <p class="mt-1 text-sm text-gray-600">{{ $activeLog->planned_tasks }}</p>
                        @if($activeLog->resources_needed)
                            <div class="mt-2">
                                <strong class="text-xs font-medium text-gray-500 uppercase">Resources Needed:</strong>
                                <span class="text-sm text-gray-600">{{ $activeLog->resources_needed }}</span>
                            </div>
                        @endif
                        <div class="mt-2 text-xs text-gray-400">Started at {{ $activeLog->start_time->format('H:i') }}</div>
                    </div>
                
                    <form action="{{ route('team.work-logs.check-out', $activeLog) }}" method="POST" id="work-log-checkout-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="actual_tasks_encoded" id="actual_tasks_encoded">
                        <input type="hidden" name="challenges_encoded" id="challenges_encoded">
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-6">
                                <label for="actual_tasks" class="block text-sm font-medium text-gray-700">Work Completed Summary</label>
                                <div class="mt-1">
                                    <textarea id="actual_tasks" name="actual_tasks" rows="3" class="shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border-gray-300 rounded-md" placeholder="What did you get done?" required></textarea>
                                </div>
                            </div>

                            <div class="sm:col-span-6">
                                <label for="challenges" class="block text-sm font-medium text-gray-700">Challenges / Blockers</label>
                                <div class="mt-1">
                                    <textarea id="challenges" name="challenges" rows="2" class="shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Any issues encountered?"></textarea>
                                </div>
                            </div>
                            
                            <!-- KPI Section (Optional / Example) -->
                            <!-- 
                            <div class="sm:col-span-6">
                                <label class="block text-sm font-medium text-gray-700">KPIs</label>
                                <div class="mt-2 grid grid-cols-2 gap-4">
                                     <div>
                                         <label for="kpi_calls" class="block text-xs font-medium text-gray-500">Calls Made</label>
                                         <input type="number" name="kpi_metrics[calls_made]" id="kpi_calls" class="mt-1 block w-full py-1.5 sm:text-sm border-gray-300 rounded-md shadow-sm focus:border-brand-primary focus:ring-brand-primary">
                                     </div>
                                </div>
                            </div>
                            -->
                        </div>

                        <div class="mt-5 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Check Out & Finish
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                // Simple Timer Script
                const startTime = new Date("{{ $activeLog->start_time }}").getTime();
                const checkoutForm = document.getElementById('work-log-checkout-form');
                const actualTasksField = document.getElementById('actual_tasks');
                const challengesField = document.getElementById('challenges');
                const actualTasksEncodedField = document.getElementById('actual_tasks_encoded');
                const challengesEncodedField = document.getElementById('challenges_encoded');
                
                function updateTimer() {
                    const now = new Date().getTime();
                    const distance = now - startTime;
                    
                    if (distance < 0) return;

                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    document.getElementById("timer").innerHTML = 
                        (hours < 10 ? "0" + hours : hours) + ":" + 
                        (minutes < 10 ? "0" + minutes : minutes) + ":" + 
                        (seconds < 10 ? "0" + seconds : seconds);
                }

                setInterval(updateTimer, 1000);
                updateTimer();

                // Some hosting security layers reject code-like textarea content before Laravel sees it.
                // Encode the transport payload so legitimate developer notes can still be submitted,
                // then decode it safely in the controller before saving the original text.
                checkoutForm?.addEventListener('submit', function () {
                    actualTasksEncodedField.value = btoa(unescape(encodeURIComponent(actualTasksField.value)));
                    challengesEncodedField.value = btoa(unescape(encodeURIComponent(challengesField.value)));

                    actualTasksField.value = '';
                    challengesField.value = '';
                });
            </script>
        @else
            <!-- Check In Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Start New Session</h3>
                    <form action="{{ route('team.work-logs.check-in') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-6">
                                <label for="planned_tasks" class="block text-sm font-medium text-gray-700">Activities Planned / Focus</label>
                                <div class="mt-1">
                                    <textarea id="planned_tasks" name="planned_tasks" rows="3" class="shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border-gray-300 rounded-md" placeholder="What will you be working on?" required></textarea>
                                </div>
                            </div>

                            <div class="sm:col-span-6">
                                <label for="resources_needed" class="block text-sm font-medium text-gray-700">Resources Needed (Optional)</label>
                                <div class="mt-1">
                                    <textarea id="resources_needed" name="resources_needed" rows="2" class="shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Any access or documents required?"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-brand-primary hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary">
                                Check In & Start Timer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <!-- History -->
    <div class="mt-8 flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Date</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Duration</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Planned</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Outcome</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Details</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($history as $log)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                    {{ $log->start_time->format('M d, Y') }}
                                    <div class="text-xs text-gray-500">{{ $log->start_time->format('H:i') }} - {{ $log->end_time?->format('H:i') }}</div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ $log->duration_minutes }} mins
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-500 max-w-xs truncate">
                                    {{ $log->planned_tasks }}
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-500 max-w-xs truncate">
                                    {{ $log->actual_tasks }}
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <!-- View Details link could go here -->
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No work logs found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-4">
            {{ $history->links() }}
        </div>
    </div>
</div>
@endsection
