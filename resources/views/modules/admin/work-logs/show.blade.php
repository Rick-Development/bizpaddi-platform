@extends('admin::layouts.app')

@section('title', 'Work Log Details')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.work-logs.index') }}" class="p-2 rounded-lg text-gray-400 hover:text-gray-400 hover:bg-white/[0.06] transition-colors">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-white">Work Log Details</h1>
            <p class="mt-1 text-sm text-gray-400">ID: #{{ $workLog->id }} • {{ $workLog->start_time ? $workLog->start_time->format('M d, Y') : 'N/A' }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Check-in Target Setup -->
        <div class="bg-[#10231C]/60 rounded-2xl shadow-sm border border-gray-150 p-6 space-y-6">
            <h2 class="text-lg font-bold text-white border-b border-slate-100 pb-3 flex items-center gap-2">
                <svg class="h-5 w-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                Check-in Target Setup
            </h2>

            @php
                $plannedProjects = $workLog->planned_projects ?? [];
                if (is_string($plannedProjects)) {
                    $plannedProjects = json_decode($plannedProjects, true) ?: [];
                }
                $projects = \Modules\ProjectManagement\Models\Project::whereIn('id', $plannedProjects)->get();
                $isPM = $workLog->user && method_exists($workLog->user, 'isProjectManager') && $workLog->user->isProjectManager();
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Projects Selected -->
                <div class="space-y-2">
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Target Project(s):</span>
                    @if($projects->isNotEmpty())
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($projects as $project)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-semibold bg-red-50 text-emerald-400 border border-red-100/60 shadow-sm">
                                    {{ $project->name }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <span class="text-xs text-slate-450 italic">No projects selected at check-in.</span>
                    @endif
                </div>

                <!-- Resources Needed -->
                @if(!$isPM && $workLog->resources_needed)
                    <div class="space-y-2">
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Resources Requested:</span>
                        <div class="text-xs text-gray-400 bg-yellow-50/50 p-3 rounded-xl border border-yellow-100 leading-relaxed">
                            {!! $workLog->resources_needed !!}
                        </div>
                    </div>
                @endif
            </div>

            @if($isPM)
                {{-- PM Specific Check-in Information --}}
                <div class="grid grid-cols-1 gap-4 pt-4 border-t border-slate-100">
                    @if($workLog->session_goal)
                        <div class="bg-slate-50/70 rounded-xl p-4 border border-slate-100">
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Stated PM Session Goal:</span>
                            <div class="text-sm font-semibold text-slate-700 mt-1.5 leading-relaxed prose-sm">{!! $workLog->session_goal !!}</div>
                        </div>
                    @endif

                    @php
                        $plannedAssignments = $workLog->planned_assignments ?? [];
                        if (is_string($plannedAssignments)) {
                            $plannedAssignments = json_decode($plannedAssignments, true) ?: [];
                        }
                        $assignmentTasks = \Modules\ProjectManagement\Models\Task::whereIn('id', $plannedAssignments)->get();
                    @endphp

                    <div class="space-y-2">
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Planned Task Assignments ({{ count($plannedAssignments) }}):</span>
                        @if($assignmentTasks->isNotEmpty())
                            <div class="space-y-2 max-h-48 overflow-y-auto p-3 bg-slate-50/50 rounded-xl border border-slate-150/60">
                                @foreach($assignmentTasks as $task)
                                    @php
                                        // Check if assigned in session window
                                        $isAssigned = $task->assigned_to && ($task->updated_at >= $workLog->start_time);
                                        $assignee = $isAssigned ? \App\Models\User::find($task->assigned_to) : null;
                                    @endphp
                                    <div class="flex items-center justify-between text-xs p-2 bg-[#10231C]/60 rounded-lg border border-slate-100">
                                        <span class="font-semibold text-slate-700">#{{ $task->id }} - {{ $task->title }}</span>
                                        @if($isAssigned)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-100 font-bold">
                                                Assigned ({{ $assignee->name ?? 'N/A' }})
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-slate-500 border border-slate-200 font-medium">
                                                Pending
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <span class="text-xs text-slate-400 italic block pl-1">No tasks scheduled for assignment.</span>
                        @endif
                    </div>
                </div>
            @else
                {{-- Developer Focus Tasks checklist --}}
                <div class="space-y-2 pt-4 border-t border-slate-100">
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Focus Tasks Selected for Session:</span>
                    @if($workLog->planned_tasks)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach(explode(', ', $workLog->planned_tasks) as $t)
                                @if(trim($t))
                                    <div class="flex items-start gap-2 p-2.5 bg-slate-50/50 rounded-xl border border-slate-100 text-xs text-slate-700 font-medium">
                                        <svg class="h-4 w-4 text-emerald-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>{{ $t }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <span class="text-xs text-slate-400 italic block pl-1">No focus tasks selected.</span>
                    @endif
                </div>
            @endif
        </div>

        {{-- Session Outcomes & Accomplishments --}}
        @if($workLog->status === 'completed')
            
            {{-- PM Metrics & Management Escalations --}}
            @if($isPM)
                <div class="bg-[#10231C]/60 rounded-2xl shadow-sm border border-gray-150 p-6 space-y-6">
                    <h2 class="text-lg font-bold text-white border-b border-slate-100 pb-3 flex items-center gap-2">
                        <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        PM Management & Session Control
                    </h2>

                    @if(is_array($workLog->kpi_metrics))
                        <div class="grid grid-cols-2 gap-4 bg-slate-50/70 p-4 rounded-xl border border-slate-100">
                            <div>
                                <span class="block text-xs font-bold text-slate-400 uppercase">Tasks Assigned This Session:</span>
                                <span class="text-lg font-extrabold text-slate-800">{{ $workLog->kpi_metrics['tasks_assigned'] ?? 0 }}</span>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-slate-400 uppercase">Tasks Closed This Session:</span>
                                <span class="text-lg font-extrabold text-slate-800">{{ $workLog->kpi_metrics['tasks_closed'] ?? 0 }}</span>
                            </div>
                        </div>
                    @endif

                    @if(is_array($workLog->kpi_metrics) && !empty($workLog->kpi_metrics['requires_mgmt_attention']))
                        <div class="bg-amber-50 border border-amber-250 rounded-2xl p-5 space-y-2">
                            <div class="flex items-center gap-2 text-amber-800 font-bold text-sm">
                                <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <span>Escalated for Senior Management Attention</span>
                            </div>
                            <div class="text-sm text-amber-700 pl-7 leading-relaxed">
                                {!! $workLog->kpi_metrics['mgmt_attention_details'] ?? '' !!}
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Tasks outcomes & evidence (Developer) --}}
            @if(is_array($workLog->tasks_data) && count($workLog->tasks_data) > 0)
                <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl p-6">
                    <h2 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                        <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        Session Tasks Outcomes & Evidence
                    </h2>
                    
                    <div class="space-y-6">
                        @php
                            $tasksData = $workLog->tasks_data;
                            $taskIds = collect($tasksData)->pluck('task_id')->toArray();
                            $tasks = \Modules\ProjectManagement\Models\Task::whereIn('id', $taskIds)->get()->keyBy('id');
                            
                            // Load previous session logs for delta calculations
                            $prevLog = \Modules\TeamPortal\Models\WorkLog::where('user_id', $workLog->user_id)
                                ->where('id', '<', $workLog->id)
                                ->whereNotNull('tasks_data')
                                ->orderBy('id', 'desc')
                                ->first();
                        @endphp

                        @foreach($tasksData as $taskData)
                            @php
                                $taskModel = $tasks[$taskData['task_id'] ?? 0] ?? null;
                                $result = $taskData['result'] ?? 'N/A';
                                $progress = (int)($taskData['progress_percent'] ?? 0);
                                $whatChanged = $taskData['what_changed'] ?? '';
                                $evidenceType = $taskData['evidence_type'] ?? '';
                                $evidence = $taskData['evidence'] ?? '';
                                $outputs = $taskData['outputs'] ?? [];
                                $blockers = $taskData['blocker_details'] ?? [];
                                $timeSpentMins = $taskData['time_spent_minutes'] ?? 0;
                                $timeSpentStr = floor($timeSpentMins / 60) . 'h ' . ($timeSpentMins % 60) . 'm';
                                
                                // Resolve delta progress
                                $prevProgress = 0;
                                if ($prevLog && is_array($prevLog->tasks_data)) {
                                    foreach ($prevLog->tasks_data as $pt) {
                                        if (($pt['task_id'] ?? null) == ($taskData['task_id'] ?? 0)) {
                                            $prevProgress = (int)($pt['progress_percent'] ?? 0);
                                            break;
                                        }
                                    }
                                }
                                $delta = $progress - $prevProgress;

                                 $badgeColor = 'bg-slate-100 text-slate-700 border-slate-200';
                                 if ($result === 'Completed') $badgeColor = 'bg-emerald-50 text-emerald-700 border-emerald-100';
                                 elseif ($result === 'Partial') $badgeColor = 'bg-blue-50 text-blue-700 border-blue-100';
                                 elseif ($result === 'Blocked') $badgeColor = 'bg-rose-50 text-rose-700 border-rose-100';
                                 elseif ($result === 'Assigned') $badgeColor = 'bg-purple-50 text-purple-700 border-purple-100';
                             @endphp

                            <div class="border border-slate-150 rounded-2xl p-5 hover:border-slate-300 transition-colors">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3 mb-4">
                                    <div class="space-y-0.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-500 uppercase tracking-wide">
                                            Task #{{ $taskData['task_id'] ?? 'N/A' }}
                                        </span>
                                        <h4 class="text-sm font-bold text-slate-800">{{ $taskModel ? $taskModel->title : 'Unknown Task' }}</h4>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $badgeColor }}">
                                            {{ $result }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            Prev: {{ $prevProgress }}% → Current: {{ $progress }}% ({{ $delta >= 0 ? '+' : '' }}{{ $delta }}%)
                                        </span>
                                    </div>
                                </div>

                                <div class="space-y-3 text-sm">
                                    <div>
                                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">What Changed:</span>
                                        <div class="text-slate-700 mt-0.5 prose-sm">{!! $whatChanged !!}</div>
                                    </div>

                                    @if($evidence)
                                        <div>
                                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Evidence Delivered ({{ $evidenceType }}):</span>
                                            @php
                                                $cleanEvidence = trim(strip_tags($evidence));
                                                $isUrl = filter_var($cleanEvidence, FILTER_VALIDATE_URL) || preg_match('/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/', $cleanEvidence);
                                            @endphp
                                            @if($isUrl)
                                                @php
                                                    $url = $cleanEvidence;
                                                    if (!preg_match('~^(?:f|ht)tps?://~i', $url)) {
                                                        $url = 'http://' . $url;
                                                    }
                                                @endphp
                                                <a href="{{ $url }}" target="_blank" class="inline-flex items-center gap-1 text-emerald-400 hover:underline mt-0.5 font-medium">
                                                    <span>View Reference Proof ({{ $cleanEvidence }})</span>
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                </a>
                                            @else
                                                <div class="mt-1 p-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 text-xs leading-relaxed">
                                                    {!! $evidence !!}
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Time Spent:</span>
                                            <span class="text-slate-800 font-semibold">{{ $timeSpentStr }} (automatically tracked)</span>
                                        </div>
                                    </div>

                                    @if($result === 'Blocked' && count($blockers) > 0)
                                        <div class="bg-rose-50/50 border border-rose-100 rounded-xl p-4 space-y-2 mt-3">
                                            <span class="block text-xs font-bold text-rose-800 uppercase tracking-wider">Blocker Details:</span>
                                            <div class="grid grid-cols-2 gap-3 text-xs text-rose-700">
                                                <div>
                                                    <span class="font-semibold block">Blocked By:</span>
                                                    <span>{{ $blockers['blocked_by'] ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="font-semibold block">Blocking Item:</span>
                                                    <div>{!! $blockers['blocking_item'] ?? 'N/A' !!}</div>
                                                </div>
                                                @if(!empty($blockers['expected_resolution']))
                                                    <div>
                                                        <span class="font-semibold block">Expected Resolution:</span>
                                                        <span>{{ $blockers['expected_resolution'] }}</span>
                                                    </div>
                                                @endif
                                                @if(!empty($blockers['owner']))
                                                    <div>
                                                        <span class="font-semibold block">Blocker Owner:</span>
                                                        <div>{!! $blockers['owner'] !!}</div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    @if(count($outputs) > 0)
                                        <div>
                                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Outputs Logged:</span>
                                            <div class="flex flex-wrap gap-1.5 mt-1">
                                                @foreach($outputs as $key => $val)
                                                    @if($val)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-xs font-medium border border-slate-200">
                                                            {{ ucwords(str_replace('_', ' ', $key)) }}
                                                        </span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- General Accomplishments (Check-out) -->
            <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl p-6 space-y-4">
                <h2 class="text-lg font-bold text-white flex items-center gap-2 border-b border-slate-100 pb-3">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 019 9v.375M10.125 2.25A3.375 3.375 0 0113.5 5.25v1.5c0 .668.275 1.39.8 1.907.24.24.521.434.82.576.4.19.855.286 1.33.286h4.5" />
                    </svg>
                    General Work Summary
                </h2>
                
                @if($workLog->actual_tasks)
                    <div class="bg-slate-50/50 rounded-xl p-4 text-gray-300 leading-relaxed border border-slate-200">{!! $workLog->actual_tasks !!}</div>
                @else
                    <p class="text-gray-450 italic">No summary details submitted.</p>
                @endif

                @if($workLog->challenges)
                <div class="pt-4 border-t border-slate-100">
                    <h4 class="text-sm font-semibold text-red-700 mb-2">Challenges Faced:</h4>
                    <div class="text-sm text-red-650 bg-red-50 p-3 rounded-lg border border-red-100 leading-relaxed">{!! $workLog->challenges !!}</div>
                </div>
                @endif
            </div>

        @else
            <!-- Active Session Check-out Placeholder -->
            <div class="bg-[#10231C]/60 rounded-2xl shadow-sm border border-gray-150 p-6 relative overflow-hidden text-center py-10">
                <div class="flex flex-col items-center justify-center space-y-3">
                    <span class="w-12 h-12 rounded-full bg-amber-50 text-amber-500 border border-amber-100 flex items-center justify-center animate-pulse">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <h3 class="text-base font-bold text-slate-800">Session Checkout Pending</h3>
                    <p class="text-xs text-slate-400 max-w-xs mx-auto leading-relaxed">This team member is currently checked-in and active. Checkout accomplishments, task-specific status deltas, and evidence will appear here once submitted.</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Sidebar Stats -->
    <div class="space-y-6">
        <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl p-6">
            <h2 class="text-lg font-bold text-white mb-4">Session Info</h2>
            
            <div class="space-y-4">
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Team Member</p>
                    <div class="flex items-center gap-3 mt-1">
                        <div class="h-8 w-8 rounded-full bg-emerald-600/10 flex items-center justify-center text-emerald-400 font-bold text-xs">
                            {{ substr($workLog->user->name ?? 'U', 0, 1) }}
                        </div>
                        <span class="text-sm font-semibold text-white">{{ $workLog->user->name ?? 'Unknown' }}</span>
                    </div>
                </div>

                <div class="pt-4 border-t border-white/10">
                     <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Duration</p>
                     @if($workLog->duration_minutes)
                        <p class="text-3xl font-bold text-emerald-400">{{ floor($workLog->duration_minutes / 60) }}h {{ $workLog->duration_minutes % 60 }}m</p>
                        <p class="text-xs text-gray-400 mt-1">Total worked time</p>
                     @else
                        @php
                            $liveMins = $workLog->start_time ? $workLog->start_time->diffInMinutes(now()) : 0;
                        @endphp
                        <p class="text-3xl font-extrabold text-amber-500">{{ floor($liveMins / 60) }}h {{ $liveMins % 60 }}m</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100 animate-pulse mt-1.5">
                            Tracking Live...
                        </span>
                     @endif
                </div>

                <div class="pt-4 border-t border-white/10 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Check-in</p>
                        <p class="text-sm font-semibold text-white mt-1">{{ $workLog->start_time ? $workLog->start_time->format('H:i') : 'N/A' }}</p>
                        <p class="text-xs text-gray-400">{{ $workLog->start_time ? $workLog->start_time->format('M d') : '' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Check-out</p>
                        @if($workLog->end_time)
                            <p class="text-sm font-semibold text-white mt-1">{{ $workLog->end_time->format('H:i') }}</p>
                            <p class="text-xs text-gray-400">{{ $workLog->end_time->format('M d') }}</p>
                        @else
                            <p class="text-sm font-medium text-gray-400 italic mt-1">Pending...</p>
                        @endif
                    </div>
                </div>
                
                @if($workLog->status === 'completed')
                <div class="pt-4 border-t border-white/10">
                    <span class="block text-center w-full px-4 py-2 bg-emerald-50 text-emerald-700 rounded-lg text-sm font-medium border border-emerald-100 font-bold">
                        Session Completed
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
