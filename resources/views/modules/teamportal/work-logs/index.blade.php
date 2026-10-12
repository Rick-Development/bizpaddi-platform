@extends('teamportal::layouts.app')

@section('content')
<div class="px-4 py-8 sm:px-6 lg:px-8 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight sm:text-4xl">Work Session Control</h1>
            <p class="mt-2 text-sm text-gray-500">Check in to start tracking session time, log per-task status details, and check out.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-md bg-red-50 p-4 mb-6 border border-red-200 shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                    <ul role="list" class="mt-1 list-disc list-inside text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Active Session Form / Check Out -->
    @if($activeLog)
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-150 mb-10">
            <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-6 text-white flex items-center justify-between">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 uppercase tracking-wider mb-2">
                        <span class="h-2 w-2 rounded-full bg-emerald-600 animate-pulse"></span>
                        Active Session
                    </span>
                    <h3 class="text-xl font-bold">Currently Checked In</h3>
                </div>
                <div class="text-4xl font-mono font-extrabold tracking-wider bg-black/25 px-4 py-2 rounded-xl" id="timer">00:00:00</div>
            </div>

            <div class="p-6">
                <!-- Check-in summary -->
                <div class="bg-gray-50 rounded-xl p-4 mb-8 border border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Session Planned Focus</span>
                        <p class="text-sm text-gray-700 font-medium mt-1">{{ $activeLog->planned_tasks }}</p>
                    </div>
                    @if($activeLog->resources_needed)
                        <div>
                            <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Resources Requested</span>
                            <p class="text-sm text-gray-700 font-medium mt-1">{{ $activeLog->resources_needed }}</p>
                        </div>
                    @endif
                </div>

                <form action="{{ route('team.work-logs.check-out', $activeLog) }}" method="POST" id="checkout-form">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="actual_tasks_encoded" id="actual_tasks_encoded">
                    <input type="hidden" name="challenges_encoded" id="challenges_encoded">

                    @if(auth()->user()->isProjectManager())
                        <!-- PM CHECK OUT FORM -->
                        <h4 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3 mb-6">Session Execution & Management Notes</h4>
                        <div class="space-y-6">
                            <div>
                                <label for="key_issues" class="block text-sm font-semibold text-gray-700">Key Issues / Decisions / Risks</label>
                                <textarea id="key_issues" name="key_issues" rows="3" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm" placeholder="Detail any critical risks or updates during this session..." required></textarea>
                            </div>

                            <div class="bg-amber-50 rounded-xl p-5 border border-amber-200">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="requires_mgmt_attention" name="requires_mgmt_attention" type="checkbox" value="1" class="focus:ring-amber-500 h-5 w-5 text-amber-600 border-gray-300 rounded" onchange="toggleMgmtDetails(this.checked)">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="requires_mgmt_attention" class="font-bold text-amber-900">Requires Senior Management Attention?</label>
                                        <span class="block text-amber-700">Check this if blockers require escalation to management.</span>
                                    </div>
                                </div>

                                <div id="mgmt_details_container" class="mt-4 hidden">
                                    <label for="mgmt_attention_details" class="block text-sm font-semibold text-amber-900">Management Attention Details</label>
                                    <textarea id="mgmt_attention_details" name="mgmt_attention_details" rows="2" class="mt-1 block w-full rounded-xl border-amber-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm bg-white" placeholder="What needs immediate attention/approval?"></textarea>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- DEVELOPER CHECK OUT FORM -->
                        <h4 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3 mb-6">Session Task Outcomes & Evidence <span class="text-xs font-normal text-gray-400 font-sans ml-2">(Optional)</span></h4>
                        
                        <div class="space-y-8">
                            <!-- Loop through activeLog tasks -->
                            @php
                                $plannedTasksArray = array_filter(explode(', ', $activeLog->planned_tasks));
                            @endphp

                            @forelse($assignedTasks as $index => $task)
                                <div class="p-6 rounded-2xl border border-gray-200 shadow-sm bg-white space-y-4">
                                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                                        <div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mb-1">
                                                Task #{{ $task->id }}
                                            </span>
                                            <h5 class="text-md font-bold text-gray-800">{{ $task->title }}</h5>
                                        </div>
                                        <div>
                                            <!-- Dynamic calculation timer notice -->
                                            <span class="text-xs font-semibold text-gray-400 bg-gray-50 px-3 py-1 rounded-full border border-gray-150">
                                                ⏱️ Duration automatically calculated on save
                                            </span>
                                        </div>
                                    </div>

                                    <input type="hidden" name="tasks_data[{{ $index }}][task_id]" value="{{ $task->id }}">

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700">Result Status</label>
                                            <select name="tasks_data[{{ $index }}][result]" onchange="toggleTaskResultDetails(this.value, {{ $index }}, {{ $task->id }})" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                                                <option value="">Select (Optional)...</option>
                                                <option value="Completed">Completed</option>
                                                <option value="Partial">Partial</option>
                                                <option value="Blocked">Blocked</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700">Progress %</label>
                                            <input type="number" name="tasks_data[{{ $index }}][progress_percent]" min="0" max="100" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700">What Changed?</label>
                                            <input type="text" name="tasks_data[{{ $index }}][what_changed]" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm" placeholder="Quick description of update">
                                        </div>
                                    </div>

                                    <!-- Blocker details container (conditionally shown via JS) -->
                                    <div id="blocker_container_{{ $index }}" class="hidden p-4 rounded-xl bg-red-50 border border-red-200 grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-red-900 uppercase">Blocked By</label>
                                            <select name="tasks_data[{{ $index }}][blocker_details][blocked_by]" class="mt-1 block w-full rounded-lg border-gray-300 sm:text-sm">
                                                <option value="Client">Client</option>
                                                <option value="PM">PM</option>
                                                <option value="QA">QA</option>
                                                <option value="API Provider">API Provider</option>
                                                <option value="Third Party">Third Party</option>
                                                <option value="Infrastructure">Infrastructure</option>
                                                <option value="Requirement">Requirement</option>
                                                <option value="Internal Dependency">Internal Dependency</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-red-900 uppercase">Blocking Item</label>
                                            <input type="text" name="tasks_data[{{ $index }}][blocker_details][blocking_item]" class="mt-1 block w-full rounded-lg border-gray-300 sm:text-sm" placeholder="Specify blockers">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-red-900 uppercase">Expected Resolution Date</label>
                                            <input type="date" name="tasks_data[{{ $index }}][blocker_details][expected_resolution]" class="mt-1 block w-full rounded-lg border-gray-300 sm:text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-red-900 uppercase">Blocker Owner</label>
                                            <input type="text" name="tasks_data[{{ $index }}][blocker_details][owner]" class="mt-1 block w-full rounded-lg border-gray-300 sm:text-sm" placeholder="Person responsible">
                                        </div>
                                    </div>

                                    <!-- Evidence & Outputs -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700">Evidence Link / Reference</label>
                                            <div class="flex gap-2">
                                                <select name="tasks_data[{{ $index }}][evidence_type]" class="rounded-xl border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                                                    <option value="PR">PR</option>
                                                    <option value="Commit">Commit</option>
                                                    <option value="Staging URL">Staging URL</option>
                                                    <option value="Screenshot">Screenshot</option>
                                                    <option value="Video">Video</option>
                                                    <option value="Test result">Test Result</option>
                                                </select>
                                                <input type="text" id="evidence_{{ $index }}" name="tasks_data[{{ $index }}][evidence]" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm" placeholder="URL of PR / proof">
                                            </div>
                                            <!-- Notification for partial/consecutive checks -->
                                            <div id="evidence_warning_{{ $index }}" class="mt-1 text-xs text-red-500 hidden">
                                                ⚠️ This task has been partial for 3+ consecutive sessions. Evidence upload is mandatory to submit.
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700">Output Produced</label>
                                            <div class="mt-2 grid grid-cols-2 gap-2 text-xs">
                                                <label class="flex items-center gap-1.5"><input type="checkbox" name="tasks_data[{{ $index }}][outputs][bug_fix]" value="1" class="rounded"> Bug Fix</label>
                                                <label class="flex items-center gap-1.5"><input type="checkbox" name="tasks_data[{{ $index }}][outputs][feature_code]" value="1" class="rounded"> Feature Code</label>
                                                <label class="flex items-center gap-1.5"><input type="checkbox" name="tasks_data[{{ $index }}][outputs][api_integration]" value="1" class="rounded"> API Integration</label>
                                                <label class="flex items-center gap-1.5"><input type="checkbox" name="tasks_data[{{ $index }}][outputs][documentation]" value="1" class="rounded"> Documentation</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-gray-500 bg-gray-50 rounded-2xl border border-dashed border-gray-300">
                                    No assigned tasks currently open. Custom check-out summaries apply.
                                </div>
                            @endforelse

                            <div class="space-y-4 pt-4 border-t border-gray-100">
                                <div>
                                    <label for="actual_tasks" class="block text-sm font-semibold text-gray-700">General Work Summary</label>
                                    <textarea id="actual_tasks" name="actual_tasks" rows="3" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm" required placeholder="What did you get done overall?"></textarea>
                                </div>

                                <div>
                                    <label for="challenges" class="block text-sm font-semibold text-gray-700">General Session Challenges / Blockers (Optional)</label>
                                    <textarea id="challenges" name="challenges" rows="2" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm" placeholder="Any high-level blocker details..."></textarea>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="declare" name="declare" type="checkbox" class="h-4 w-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500" required>
                            <label for="declare" class="ml-2 block text-xs font-semibold text-gray-600">
                                I confirm this report reflects the actual work performed, discloses any blocker, and includes available evidence.
                            </label>
                        </div>
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-bold rounded-xl shadow-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                            Check Out & Finish Session
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            // Timer counter script
            const startTime = new Date("{{ $activeLog->start_time }}").getTime();
            const checkoutForm = document.getElementById('checkout-form');
            const actualTasksField = document.getElementById('actual_tasks');
            const challengesField = document.getElementById('challenges');
            const actualTasksEncodedField = document.getElementById('actual_tasks_encoded');
            const challengesEncodedField = document.getElementById('challenges_encoded');

            function updateTimer() {
                const now = new Date().getTime();
                const diff = now - startTime;
                if (diff < 0) return;
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const secs = Math.floor((diff % (1000 * 60)) / 1000);
                document.getElementById("timer").innerHTML = 
                    (hours < 10 ? "0" + hours : hours) + ":" + 
                    (mins < 10 ? "0" + mins : mins) + ":" + 
                    (secs < 10 ? "0" + secs : secs);
            }
            setInterval(updateTimer, 1000);
            updateTimer();

            // Encode content to handle safe transport to the backend
            checkoutForm?.addEventListener('submit', function () {
                if (actualTasksField && actualTasksEncodedField) {
                    actualTasksEncodedField.value = btoa(unescape(encodeURIComponent(actualTasksField.value)));
                    actualTasksField.value = '';
                }
                if (challengesField && challengesEncodedField) {
                    challengesEncodedField.value = btoa(unescape(encodeURIComponent(challengesField.value)));
                    challengesField.value = '';
                }
            });

            function toggleMgmtDetails(isChecked) {
                const detailsContainer = document.getElementById('mgmt_details_container');
                if (isChecked) {
                    detailsContainer.classList.remove('hidden');
                    document.getElementById('mgmt_attention_details').setAttribute('required', 'required');
                } else {
                    detailsContainer.classList.add('hidden');
                    document.getElementById('mgmt_attention_details').removeAttribute('required');
                }
            }

            function toggleTaskResultDetails(resultValue, index, taskId) {
                const blockerContainer = document.getElementById('blocker_container_' + index);
                const evidenceField = document.getElementById('evidence_' + index);
                
                // Reset required blocker attributes
                const blockedBySelect = blockerContainer?.querySelector('select');
                const blockingInput = blockerContainer?.querySelector('input[type="text"]');

                if (resultValue === 'Blocked') {
                    blockerContainer.classList.remove('hidden');
                    blockedBySelect?.setAttribute('required', 'required');
                    blockingInput?.setAttribute('required', 'required');
                } else {
                    blockerContainer.classList.add('hidden');
                    blockedBySelect?.removeAttribute('required');
                    blockingInput?.removeAttribute('required');
                }

                // Check for 3+ consecutive partial validation via API helper call triggers
                if (resultValue === 'Partial') {
                    fetch('/team/work-logs/check-partial-status?task_id=' + taskId)
                    .then(res => res.json())
                    .then(data => {
                        const warnDiv = document.getElementById('evidence_warning_' + index);
                        if (data.is_stale_partial) {
                            warnDiv.classList.remove('hidden');
                            evidenceField.setAttribute('required', 'required');
                        } else {
                            warnDiv.classList.add('hidden');
                            evidenceField.removeAttribute('required');
                        }
                    });
                } else if (resultValue === 'Completed') {
                    evidenceField.setAttribute('required', 'required');
                } else {
                    evidenceField.removeAttribute('required');
                }
            }
        </script>
    @else
        <!-- Check In Panel -->
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-150">
            <div class="px-6 py-6 border-b border-gray-150 bg-gray-50 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Start Session Check-In</h3>
                    <p class="text-xs text-gray-500 mt-1">Select current focus targets before initializing session.</p>
                </div>
            </div>

            <form action="{{ route('team.work-logs.check-in') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700">Project(s) Working On</label>
                    <span class="block text-xs text-gray-400 mb-2">Select projects linked to today's session.</span>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($projects as $project)
                            <label class="flex items-center gap-2 p-3 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors text-xs font-semibold text-gray-700">
                                <input type="checkbox" name="planned_projects[]" value="{{ $project->id }}" class="rounded text-brand-primary focus:ring-brand-primary">
                                {{ $project->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

                @if(auth()->user()->isProjectManager())
                    <!-- PM CHECK IN SPECIFIC -->
                    <div>
                        <label for="session_goal" class="block text-sm font-semibold text-gray-700">PM Session Goal</label>
                        <span class="block text-xs text-gray-400 mb-2">What will you close or move forward this session?</span>
                        <input type="text" id="session_goal" name="session_goal" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm" required placeholder="Define session goal">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Tasks to assign this session</label>
                        <span class="block text-xs text-gray-400 mb-2">Select tasks you plan to allocate to developers.</span>
                        <div class="space-y-2 max-h-48 overflow-y-auto p-3 bg-gray-50 rounded-xl border border-gray-200">
                            @forelse($assignedTasks as $task)
                                <label class="flex items-center gap-2 text-xs text-gray-700">
                                    <input type="checkbox" name="planned_assignments[]" value="{{ $task->id }}" class="rounded">
                                    {{ $task->title }}
                                </label>
                            @empty
                                <div class="text-xs text-gray-400">No open tasks available.</div>
                            @endforelse
                        </div>
                        <div class="mt-3 flex items-center">
                            <input id="no_assignments_planned" name="no_assignments_planned" type="checkbox" value="1" class="h-4 w-4 rounded text-brand-primary focus:ring-brand-primary">
                            <label for="no_assignments_planned" class="ml-2 block text-xs font-semibold text-gray-500">
                                No task assignments planned this session.
                            </label>
                        </div>
                    </div>
                @else
                    <!-- DEVELOPER CHECK IN SPECIFIC -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Focus Tasks Selected for this Session</label>
                        <span class="block text-xs text-gray-400 mb-2">Confirm target items you're actively working on.</span>
                        <div class="space-y-2 max-h-48 overflow-y-auto p-3 bg-gray-50 rounded-xl border border-gray-200">
                            @forelse($assignedTasks as $task)
                                <label class="flex items-center gap-2 text-xs text-gray-700">
                                    <input type="checkbox" name="planned_tasks[]" value="{{ $task->title }}" class="rounded">
                                    {{ $task->title }}
                                </label>
                            @empty
                                <div class="text-xs text-gray-400">No tasks currently assigned to you. Define focus manually using resources option.</div>
                            @endforelse
                        </div>
                    </div>

                    <div>
                        <label for="resources_needed" class="block text-sm font-semibold text-gray-700">Resources Needed (Optional)</label>
                        <textarea id="resources_needed" name="resources_needed" rows="2" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm" placeholder="Any documents, accesses, or permissions required..."></textarea>
                    </div>
                @endif

                <div class="pt-4 border-t border-gray-150 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-bold rounded-xl shadow-md text-white bg-brand-primary hover:bg-brand-primary/95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary transition-colors">
                        Check In & Start Timer
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Completed Sessions History -->
    <div class="mt-10 flex flex-col">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Completed Work Sessions</h3>
        <div class="overflow-x-auto -mx-4 sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow-sm ring-1 ring-black ring-opacity-5 md:rounded-2xl">
                    <table class="min-w-full divide-y divide-gray-200 bg-white">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date & Time</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Duration</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Planned Goals</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Session Accomplishments</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($history as $log)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-semibold text-gray-900">
                                    {{ $log->start_time->format('M d, Y') }}
                                    <div class="text-xs text-gray-400 font-medium">{{ $log->start_time->format('H:i') }} - {{ $log->end_time?->format('H:i') }}</div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600 font-medium">
                                    {{ $log->duration_minutes }} mins
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-600 max-w-xs truncate">
                                    {{ $log->planned_tasks }}
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-600 max-w-xs truncate">
                                    {{ $log->actual_tasks }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-400">No completed sessions logged.</td>
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
