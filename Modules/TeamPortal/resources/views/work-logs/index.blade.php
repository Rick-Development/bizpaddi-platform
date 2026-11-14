@extends('teamportal::layouts.app')

@section('content')
<div class="px-4 py-8 sm:px-6 lg:px-8 max-w-5xl mx-auto space-y-8 animate-fade-in">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h1 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight">Work Session Control</h1>
            <p class="mt-2 text-sm text-gray-400">Check in to start tracking session time, log per-task status details, and check out.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-2xl bg-red-950/40 p-4 border border-red-500/50 shadow-xl backdrop-blur-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-red-200">Please correct the following errors:</h3>
                    <ul role="list" class="mt-1 list-disc list-inside text-sm text-red-300 space-y-1">
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
        <div class="bg-[#10231C]/60 backdrop-blur-sm overflow-hidden shadow-2xl rounded-3xl border border-white/10 mb-10">
            <div class="bg-gradient-to-r from-emerald-600 via-[#0E5E4A] to-teal-700 px-6 py-6 text-white flex items-center justify-between">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-400/20 text-emerald-300 border border-emerald-400/30 uppercase tracking-wider mb-2">
                        <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Active Session
                    </span>
                    <h3 class="text-xl sm:text-2xl font-black">Currently Checked In</h3>
                </div>
                <div class="text-3xl sm:text-4xl font-mono font-black tracking-wider bg-black/40 px-5 py-2.5 rounded-2xl border border-white/10 shadow-inner" id="timer">00:00:00</div>
            </div>

            <div class="p-6 sm:p-8 space-y-6">
                <!-- Check-in summary -->
                <div class="bg-white/5 rounded-2xl p-5 border border-white/10 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Session Planned Focus</span>
                        <p class="text-sm text-gray-200 font-semibold mt-1">{{ $activeLog->planned_tasks }}</p>
                    </div>
                    @if($activeLog->resources_needed)
                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Resources Requested</span>
                            <p class="text-sm text-gray-200 font-semibold mt-1">{{ $activeLog->resources_needed }}</p>
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
                        <h4 class="text-lg font-bold text-white border-b border-white/10 pb-3 mb-6">Session Execution & Management Notes</h4>
                        <div class="space-y-6">
                            <div>
                                <label for="key_issues" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Key Issues / Decisions / Risks</label>
                                <textarea id="key_issues" name="key_issues" rows="3" class="mt-1 block w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white placeholder-gray-500 shadow-inner focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 sm:text-sm p-3.5" placeholder="Detail any critical risks or updates during this session..." required></textarea>
                            </div>

                            <div class="bg-amber-950/40 rounded-2xl p-5 border border-amber-500/40">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="requires_mgmt_attention" name="requires_mgmt_attention" type="checkbox" value="1" class="focus:ring-amber-500 h-5 w-5 text-amber-500 border-white/20 rounded bg-[#08110e]" onchange="toggleMgmtDetails(this.checked)">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="requires_mgmt_attention" class="font-bold text-amber-300">Requires Senior Management Attention?</label>
                                        <span class="block text-amber-200/80 text-xs mt-0.5">Check this if blockers require escalation to management.</span>
                                    </div>
                                </div>

                                <div id="mgmt_details_container" class="mt-4 hidden">
                                    <label for="mgmt_attention_details" class="block text-xs font-bold text-amber-300 uppercase tracking-wider mb-1.5">Management Attention Details</label>
                                    <textarea id="mgmt_attention_details" name="mgmt_attention_details" rows="2" class="mt-1 block w-full rounded-xl border border-amber-500/50 shadow-inner focus:border-amber-400 focus:ring-2 focus:ring-amber-400 sm:text-sm bg-[#08110e]/90 text-white p-3.5" placeholder="What needs immediate attention/approval?"></textarea>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- DEVELOPER CHECK OUT FORM -->
                        <h4 class="text-lg font-bold text-white border-b border-white/10 pb-3 mb-6">Session Task Outcomes & Evidence <span class="text-xs font-normal text-gray-400 font-sans ml-2">(Optional)</span></h4>
                        
                        <div class="space-y-6">
                            <!-- Loop through activeLog tasks -->
                            @php
                                $plannedTasksArray = array_filter(explode(', ', $activeLog->planned_tasks));
                            @endphp

                            @forelse($assignedTasks as $index => $task)
                                <div class="p-6 rounded-2xl border border-white/10 bg-[#08110e]/80 shadow-lg space-y-4">
                                    <div class="flex items-center justify-between border-b border-white/5 pb-3">
                                        <div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-500/20 text-blue-300 border border-blue-500/30 mb-1 uppercase tracking-wider">
                                                Task #{{ $task->id }}
                                            </span>
                                            <h5 class="text-base font-bold text-white">{{ $task->title }}</h5>
                                        </div>
                                        <div>
                                            <span class="text-xs font-semibold text-gray-400 bg-white/5 px-3 py-1 rounded-full border border-white/10">
                                                ⏱️ Duration automatically calculated on save
                                            </span>
                                        </div>
                                    </div>

                                    <input type="hidden" name="tasks_data[{{ $index }}][task_id]" value="{{ $task->id }}">

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Result Status</label>
                                            <select name="tasks_data[{{ $index }}][result]" onchange="toggleTaskResultDetails(this.value, {{ $index }}, {{ $task->id }})" class="mt-1 block w-full rounded-xl border border-white/10 bg-[#10231C] text-white shadow-inner focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 sm:text-sm px-3.5 py-2.5">
                                                <option value="" class="bg-[#10231C] text-white">Select (Optional)...</option>
                                                <option value="Completed" class="bg-[#10231C] text-white">Completed</option>
                                                <option value="Partial" class="bg-[#10231C] text-white">Partial</option>
                                                <option value="Blocked" class="bg-[#10231C] text-white">Blocked</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Progress %</label>
                                            <input type="number" name="tasks_data[{{ $index }}][progress_percent]" min="0" max="100" class="mt-1 block w-full rounded-xl border border-white/10 bg-[#10231C] text-white shadow-inner focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 sm:text-sm px-3.5 py-2.5">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">What Changed?</label>
                                            <input type="text" name="tasks_data[{{ $index }}][what_changed]" class="mt-1 block w-full rounded-xl border border-white/10 bg-[#10231C] text-white placeholder-gray-500 shadow-inner focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 sm:text-sm px-3.5 py-2.5" placeholder="Quick description of update">
                                        </div>
                                    </div>

                                    <!-- Blocker details container -->
                                    <div id="blocker_container_{{ $index }}" class="hidden p-5 rounded-2xl bg-red-950/40 border border-red-500/40 grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-red-300 uppercase mb-1.5">Blocked By</label>
                                            <select name="tasks_data[{{ $index }}][blocker_details][blocked_by]" class="mt-1 block w-full rounded-xl border border-red-500/40 bg-[#08110e] text-white sm:text-sm px-3.5 py-2">
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
                                            <label class="block text-xs font-bold text-red-300 uppercase mb-1.5">Blocking Item</label>
                                            <input type="text" name="tasks_data[{{ $index }}][blocker_details][blocking_item]" class="mt-1 block w-full rounded-xl border border-red-500/40 bg-[#08110e] text-white placeholder-gray-500 sm:text-sm px-3.5 py-2" placeholder="Specify blockers">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-red-300 uppercase mb-1.5">Expected Resolution Date</label>
                                            <input type="date" name="tasks_data[{{ $index }}][blocker_details][expected_resolution]" class="mt-1 block w-full rounded-xl border border-red-500/40 bg-[#08110e] text-white sm:text-sm px-3.5 py-2">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-red-300 uppercase mb-1.5">Blocker Owner</label>
                                            <input type="text" name="tasks_data[{{ $index }}][blocker_details][owner]" class="mt-1 block w-full rounded-xl border border-red-500/40 bg-[#08110e] text-white placeholder-gray-500 sm:text-sm px-3.5 py-2" placeholder="Person responsible">
                                        </div>
                                    </div>

                                    <!-- Evidence & Outputs -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Evidence Link / Reference</label>
                                            <div class="flex gap-2">
                                                <select name="tasks_data[{{ $index }}][evidence_type]" class="rounded-xl border border-white/10 bg-[#10231C] text-white shadow-inner focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 sm:text-sm px-3 py-2">
                                                    <option value="PR">PR</option>
                                                    <option value="Commit">Commit</option>
                                                    <option value="Staging URL">Staging URL</option>
                                                    <option value="Screenshot">Screenshot</option>
                                                    <option value="Video">Video</option>
                                                    <option value="Test result">Test Result</option>
                                                </select>
                                                <input type="text" id="evidence_{{ $index }}" name="tasks_data[{{ $index }}][evidence]" class="block w-full rounded-xl border border-white/10 bg-[#10231C] text-white placeholder-gray-500 shadow-inner focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 sm:text-sm px-3.5 py-2" placeholder="URL of PR / proof">
                                            </div>
                                            <!-- Notification for partial/consecutive checks -->
                                            <div id="evidence_warning_{{ $index }}" class="mt-1 text-xs text-red-400 font-semibold hidden">
                                                ⚠️ This task has been partial for 3+ consecutive sessions. Evidence upload is mandatory to submit.
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Output Produced</label>
                                            <div class="mt-2 grid grid-cols-2 gap-2 text-xs">
                                                <label class="flex items-center gap-1.5 text-gray-300"><input type="checkbox" name="tasks_data[{{ $index }}][outputs][bug_fix]" value="1" class="rounded border-white/20 bg-[#08110e] text-emerald-500 focus:ring-emerald-500"> Bug Fix</label>
                                                <label class="flex items-center gap-1.5 text-gray-300"><input type="checkbox" name="tasks_data[{{ $index }}][outputs][feature_code]" value="1" class="rounded border-white/20 bg-[#08110e] text-emerald-500 focus:ring-emerald-500"> Feature Code</label>
                                                <label class="flex items-center gap-1.5 text-gray-300"><input type="checkbox" name="tasks_data[{{ $index }}][outputs][api_integration]" value="1" class="rounded border-white/20 bg-[#08110e] text-emerald-500 focus:ring-emerald-500"> API Integration</label>
                                                <label class="flex items-center gap-1.5 text-gray-300"><input type="checkbox" name="tasks_data[{{ $index }}][outputs][documentation]" value="1" class="rounded border-white/20 bg-[#08110e] text-emerald-500 focus:ring-emerald-500"> Documentation</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-gray-400 bg-white/5 rounded-2xl border border-dashed border-white/10">
                                    No assigned tasks currently open. Custom check-out summaries apply.
                                </div>
                            @endforelse

                            <div class="space-y-4 pt-4 border-t border-white/10">
                                <div>
                                    <label for="actual_tasks" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">General Work Summary</label>
                                    <textarea id="actual_tasks" name="actual_tasks" rows="3" class="mt-1 block w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white placeholder-gray-500 shadow-inner focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 sm:text-sm p-3.5" required placeholder="What did you get done overall?"></textarea>
                                </div>

                                <div>
                                    <label for="challenges" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">General Session Challenges / Blockers (Optional)</label>
                                    <textarea id="challenges" name="challenges" rows="2" class="mt-1 block w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white placeholder-gray-500 shadow-inner focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 sm:text-sm p-3.5" placeholder="Any high-level blocker details..."></textarea>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mt-8 pt-6 border-t border-white/10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center">
                            <input id="declare" name="declare" type="checkbox" class="h-4 w-4 text-emerald-600 border-white/20 bg-[#08110e] rounded focus:ring-emerald-500" required>
                            <label for="declare" class="ml-2 block text-xs font-medium text-gray-300">
                                I confirm this report reflects the actual work performed, discloses any blocker, and includes available evidence.
                            </label>
                        </div>
                        <button type="submit" class="inline-flex items-center justify-center px-7 py-3 border border-transparent text-sm font-bold rounded-xl shadow-lg shadow-emerald-950/50 text-white bg-emerald-600 hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all active:scale-95 shrink-0">
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

                const hours = Math.floor(diff / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                const formatted = 
                    String(hours).padStart(2, '0') + ':' + 
                    String(minutes).padStart(2, '0') + ':' + 
                    String(seconds).padStart(2, '0');

                const timerElement = document.getElementById('timer');
                if (timerElement) {
                    timerElement.innerText = formatted;
                }
            }

            setInterval(updateTimer, 1000);
            updateTimer();

            checkoutForm?.addEventListener('submit', function() {
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
                    fetch('/dashboard/team/work-logs/check-partial-status?task_id=' + taskId)
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
        <div class="bg-[#10231C]/60 backdrop-blur-sm overflow-hidden shadow-2xl rounded-3xl border border-white/10">
            <div class="px-6 py-6 border-b border-white/10 bg-white/5 flex items-center justify-between">
                <div>
                    <h3 class="text-xl sm:text-2xl font-black text-white">Start Session Check-In</h3>
                    <p class="text-xs text-gray-400 mt-1">Select current focus targets before initializing session.</p>
                </div>
            </div>

            <form action="{{ route('team.work-logs.check-in') }}" method="POST" class="p-6 sm:p-8 space-y-6">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider">Project(s) Working On</label>
                    <span class="block text-xs text-gray-400 mb-3 mt-0.5">Select projects linked to today's session.</span>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($projects as $project)
                            <label class="flex items-center gap-2.5 p-3.5 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 cursor-pointer transition-colors text-xs font-semibold text-gray-200">
                                <input type="checkbox" name="planned_projects[]" value="{{ $project->id }}" class="rounded border-white/20 bg-[#08110e] text-emerald-500 focus:ring-emerald-500">
                                {{ $project->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

                @if(auth()->user()->isProjectManager())
                    <!-- PM CHECK IN SPECIFIC -->
                    <div>
                        <label for="session_goal" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">PM Session Goal</label>
                        <span class="block text-xs text-gray-400 mb-2">What will you close or move forward this session?</span>
                        <input type="text" id="session_goal" name="session_goal" class="mt-1 block w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white placeholder-gray-500 shadow-inner focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 sm:text-sm p-3.5" required placeholder="Define session goal">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Tasks to assign this session</label>
                        <span class="block text-xs text-gray-400 mb-2">Select tasks you plan to allocate to developers.</span>
                        <div class="space-y-2 max-h-48 overflow-y-auto p-4 bg-[#08110e]/80 rounded-xl border border-white/10 text-gray-200">
                            @forelse($assignedTasks as $task)
                                <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer">
                                    <input type="checkbox" name="planned_assignments[]" value="{{ $task->id }}" class="rounded border-white/20 bg-[#10231C] text-emerald-500 focus:ring-emerald-500">
                                    {{ $task->title }}
                                </label>
                            @empty
                                <div class="text-xs text-gray-400">No open tasks available.</div>
                            @endforelse
                        </div>
                        <div class="mt-3 flex items-center">
                            <input id="no_assignments_planned" name="no_assignments_planned" type="checkbox" value="1" class="h-4 w-4 rounded border-white/20 bg-[#08110e] text-emerald-500 focus:ring-emerald-500">
                            <label for="no_assignments_planned" class="ml-2 block text-xs font-semibold text-gray-400">
                                No task assignments planned this session.
                            </label>
                        </div>
                    </div>
                @else
                    <!-- DEVELOPER CHECK IN SPECIFIC -->
                    <div>
                        <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider">Focus Tasks Selected for this Session</label>
                        <span class="block text-xs text-gray-400 mb-3 mt-0.5">Confirm target items you're actively working on.</span>
                        <div class="space-y-2 max-h-48 overflow-y-auto p-4 bg-[#08110e]/80 rounded-xl border border-white/10 text-gray-200">
                            @forelse($assignedTasks as $task)
                                <label class="flex items-center gap-2.5 text-xs text-gray-300 hover:text-white cursor-pointer">
                                    <input type="checkbox" name="planned_tasks[]" value="{{ $task->title }}" class="rounded border-white/20 bg-[#10231C] text-emerald-500 focus:ring-emerald-500">
                                    {{ $task->title }}
                                </label>
                            @empty
                                <div class="text-xs text-gray-400">No tasks currently assigned to you. Define focus manually using resources option.</div>
                            @endforelse
                        </div>
                    </div>

                    <div>
                        <label for="resources_needed" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Resources Needed (Optional)</label>
                        <textarea id="resources_needed" name="resources_needed" rows="2" class="mt-1 block w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white placeholder-gray-500 shadow-inner focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 sm:text-sm p-3.5" placeholder="Any documents, accesses, or permissions required..."></textarea>
                    </div>
                @endif

                <div class="pt-4 border-t border-white/10 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-7 py-3 border border-transparent text-sm font-bold rounded-xl shadow-lg shadow-emerald-950/50 text-white bg-emerald-600 hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all active:scale-95">
                        Check In & Start Timer
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Completed Sessions History -->
    <div class="mt-12 flex flex-col">
        <h3 class="text-xl sm:text-2xl font-black text-white mb-4">Completed Work Sessions</h3>
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden shadow-xl rounded-2xl border border-white/10 bg-[#10231C]/60 backdrop-blur-sm">
                    <table class="min-w-full divide-y divide-white/5">
                        <thead class="bg-white/5 border-b border-white/10">
                            <tr>
                                <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Date & Time</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Duration</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Planned Goals</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Session Accomplishments</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($history as $log)
                            <tr class="hover:bg-white/[0.03] transition-colors">
                                <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-bold text-white">
                                    {{ $log->start_time->format('M d, Y') }}
                                    <div class="text-xs text-gray-400 font-mono mt-0.5">{{ $log->start_time->format('H:i') }} - {{ $log->end_time?->format('H:i') }}</div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-emerald-400 font-mono font-bold">
                                    {{ $log->duration_minutes }} mins
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-300 max-w-xs truncate">
                                    {{ $log->planned_tasks }}
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-300 max-w-xs truncate">
                                    {{ $log->actual_tasks }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-400">No completed sessions logged.</td>
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
