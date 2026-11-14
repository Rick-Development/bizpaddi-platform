@extends('teamportal::layouts.app')

@section('title', 'Session Detail')

@push('styles')
<style>
    :root {
        --brand: #0E5E4A;
        --brand-dark: #0A4335;
        --navy: #10231C;
    }

    .detail-hero {
        background: linear-gradient(135deg, #10231C 0%, #0d221b 60%, #08110e 100%);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.4);
    }
    .detail-hero::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 260px; height: 260px;
        background: radial-gradient(circle, rgba(16,185,129,0.15) 0%, transparent 70%);
        pointer-events: none;
    }

    .detail-card {
        background: rgba(16,35,28,0.6);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
    .detail-card-header {
        background: rgba(255,255,255,0.05);
        border-bottom: 1px solid rgba(255,255,255,0.1);
        padding: 16px 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .detail-card-header h3 {
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #9ca3af;
        margin: 0;
    }

    .section-label {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.09em;
        color: #9ca3af;
        display: block;
        margin-bottom: 4px;
    }

    .pill { display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 999px; font-size: 10px; font-weight: 800; text-transform: uppercase; tracking: 0.05em; }
    .pill-red   { background:rgba(239,68,68,0.2); color:#fca5a5; border:1px solid rgba(239,68,68,0.3); }
    .pill-green { background:rgba(16,185,129,0.2); color:#6ee7b7; border:1px solid rgba(16,185,129,0.3); }
    .pill-blue  { background:rgba(59,130,246,0.2); color:#93c5fd; border:1px solid rgba(59,130,246,0.3); }
    .pill-amber { background:rgba(245,158,11,0.2); color:#fde68a; border:1px solid rgba(245,158,11,0.3); }
    .pill-slate { background:rgba(255,255,255,0.08); color:#d1d5db; border:1px solid rgba(255,255,255,0.1); }
    .pill-navy  { background:rgba(16,35,28,0.8); color:#34d399; border:1px solid rgba(16,185,129,0.3); }
    .pill-purple { background:rgba(168,85,247,0.2); color:#d8b4fe; border:1px solid rgba(168,85,247,0.3); }

    .task-result-card {
        background: rgba(8,17,14,0.8);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 16px;
        overflow: hidden;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .task-result-card:hover { border-color: rgba(16,185,129,0.4); box-shadow: 0 4px 20px rgba(0,0,0,0.4); }
    .task-result-card .card-top {
        padding: 16px 20px;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }
    .task-result-card .card-body { padding: 16px 20px; }

    .result-completed { border-color: rgba(16,185,129,0.3); background: rgba(16,185,129,0.05); }
    .result-partial   { border-color: rgba(59,130,246,0.3); background: rgba(59,130,246,0.05); }
    .result-blocked   { border-color: rgba(239,68,68,0.3); background: rgba(239,68,68,0.05); }
    .result-assigned  { border-color: rgba(168,85,247,0.3); background: rgba(168,85,247,0.05); }

    .stat-box {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 14px;
        padding: 14px 18px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .stat-box .stat-val {
        font-size: 22px;
        font-weight: 900;
        color: #ffffff;
        line-height: 1;
    }
    .stat-box .stat-lbl {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #9ca3af;
    }

    .output-tag {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 11px;
        border-radius: 999px;
        font-size: 11px; font-weight: 700;
        background: rgba(255,255,255,0.06);
        color: #d1d5db;
        border: 1px solid rgba(255,255,255,0.1);
    }

    .evidence-box {
        background: rgba(0,0,0,0.3);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 12px;
        padding: 14px 18px;
        font-size: 13px;
        color: #e5e7eb;
        line-height: 1.6;
    }

    .blocker-badge {
        background: rgba(239,68,68,0.1);
        border: 1px solid rgba(239,68,68,0.3);
        border-radius: 14px;
        padding: 14px 18px;
    }

    .back-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 16px;
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 12px;
        font-size: 13px; font-weight: 600;
        color: #d1d5db;
        text-decoration: none;
        transition: background 0.2s, color 0.2s, border-color 0.2s;
    }
    .back-btn:hover { background: rgba(255,255,255,0.12); color: #fff; border-color: rgba(255,255,255,0.2); }

    .prose-content { font-size: 13px; color: #d1d5db; line-height: 1.7; }
    .prose-content p { margin: 0 0 8px; }
    .prose-content ul, .prose-content ol { margin: 0 0 8px 16px; }
    .prose-content strong { font-weight: 700; color: #fff; }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto space-y-7 pb-12 animate-fade-in">

    {{-- Back + title --}}
    <div class="flex items-center gap-4 pt-2">
        <a href="{{ route('team.work-logs.index') }}" class="back-btn">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            Back to Work Logs
        </a>
        <div>
            <h1 class="text-2xl font-black text-white leading-tight">Session Detail</h1>
            <p class="text-xs text-gray-400 mt-0.5">{{ $workLog->start_time->format('l, F d, Y') }}</p>
        </div>
    </div>

    {{-- Hero --}}
    <div class="detail-hero p-7 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div class="space-y-2 relative z-10">
            <span class="pill pill-green">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Completed Session
            </span>
            <h2 class="text-2xl sm:text-3xl font-black text-white">{{ $workLog->start_time->format('M d, Y') }}</h2>
            <p class="text-sm text-gray-300 font-mono">{{ $workLog->start_time->format('H:i') }} → {{ $workLog->end_time?->format('H:i') ?? '—' }}</p>
        </div>
        <div class="grid grid-cols-2 gap-3 relative z-10">
            <div class="stat-box">
                <span class="stat-val font-mono">{{ $workLog->duration_minutes }}<span class="text-sm font-semibold text-gray-400 ml-1">min</span></span>
                <span class="stat-lbl">Duration</span>
            </div>
            <div class="stat-box">
                <span class="stat-val font-mono">{{ $workLog->tasks_data ? count($workLog->tasks_data) : '—' }}</span>
                <span class="stat-lbl">Tasks Logged</span>
            </div>
        </div>
    </div>

    {{-- Session Overview --}}
    <div class="detail-card">
        <div class="detail-card-header">
            <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <h3>Session Overview</h3>
        </div>
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <span class="section-label">Planned Focus</span>
                <div class="text-sm font-semibold text-white leading-relaxed mt-1 prose-sm">{!! $workLog->planned_tasks ?? '—' !!}</div>
            </div>
            @if($workLog->session_goal)
            <div>
                <span class="section-label">Session Goal</span>
                <div class="text-sm font-semibold text-white leading-relaxed mt-1 prose-sm">{!! $workLog->session_goal !!}</div>
            </div>
            @endif
            @if($workLog->resources_needed)
            <div class="sm:col-span-2">
                <span class="section-label">Resources Requested</span>
                <div class="prose-content mt-1">{!! $workLog->resources_needed !!}</div>
            </div>
            @endif
        </div>
    </div>

    {{-- General Work Summary --}}
    @if($workLog->actual_tasks)
    <div class="detail-card">
        <div class="detail-card-header">
            <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            <h3>General Work Summary</h3>
        </div>
        <div class="p-6">
            <div class="prose-content">{!! $workLog->actual_tasks !!}</div>
        </div>
    </div>
    @endif

    {{-- Challenges --}}
    @if($workLog->challenges)
    <div class="detail-card">
        <div class="detail-card-header">
            <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <h3>Challenges / Blockers Noted</h3>
        </div>
        <div class="p-6">
            <div class="prose-content">{!! $workLog->challenges !!}</div>
        </div>
    </div>
    @endif

    {{-- Per-Task Breakdown --}}
    @if($workLog->tasks_data && count($workLog->tasks_data) > 0)
    <div class="space-y-4">
        <div class="flex items-center gap-3">
            <h2 class="text-lg font-extrabold text-white">Task Breakdown</h2>
            <span class="pill pill-slate">{{ count($workLog->tasks_data) }} task(s)</span>
        </div>

        @foreach($workLog->tasks_data as $i => $task)
            @php
                $result = $task['result'] ?? 'Unknown';
                 $resultClass = match($result) {
                    'Completed' => 'result-completed',
                    'Partial'   => 'result-partial',
                    'Blocked'   => 'result-blocked',
                    'Assigned'  => 'result-assigned',
                    default     => ''
                };
                $pillClass = match($result) {
                    'Completed' => 'pill-green',
                    'Partial'   => 'pill-blue',
                    'Blocked'   => 'pill-red',
                    'Assigned'  => 'pill-purple',
                    default     => 'pill-slate'
                };
                $resultIcon = match($result) {
                    'Completed' => '✓',
                    'Partial'   => '⬡',
                    'Blocked'   => '✕',
                    'Assigned'  => '★',
                    default     => '?'
                };
                $taskModel = \Modules\ProjectManagement\Models\Task::find($task['task_id'] ?? 0);
            @endphp
            <div class="task-result-card {{ $resultClass }}">
                <div class="card-top">
                    <div class="space-y-1">
                        <span class="pill pill-slate text-[10px]">Task #{{ $task['task_id'] ?? '?' }}</span>
                        <h4 class="text-base font-extrabold text-white">{{ $taskModel->title ?? ('Task ID: ' . ($task['task_id'] ?? '?')) }}</h4>
                        @if($taskModel?->project)
                            <p class="text-xs text-gray-400">{{ $taskModel->project->name }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <span class="pill {{ $pillClass }}">{{ $resultIcon }} {{ $result }}</span>
                        @if(isset($task['progress_percent']))
                            <span class="pill pill-navy">{{ $task['progress_percent'] }}%</span>
                        @endif
                        @if(isset($task['time_spent_minutes']) && $task['time_spent_minutes'] > 0)
                            <span class="pill pill-slate font-mono">{{ $task['time_spent_minutes'] }} min</span>
                        @endif
                    </div>
                </div>

                <div class="card-body space-y-4">
                    {{-- What Changed --}}
                    @if(!empty($task['what_changed']))
                    <div>
                        <span class="section-label">What Changed</span>
                        <div class="prose-content mt-1">{!! $task['what_changed'] !!}</div>
                    </div>
                    @endif

                    {{-- Evidence --}}
                    @if(!empty($task['evidence']))
                    <div>
                        <span class="section-label">Evidence / Reference <span class="text-[10px] text-gray-400 normal-case font-normal">({{ $task['evidence_type'] ?? 'N/A' }})</span></span>
                        <div class="evidence-box mt-1 prose-content">{!! $task['evidence'] !!}</div>
                    </div>
                    @endif

                    {{-- Output Produced --}}
                    @php
                        $outputs = array_filter([
                            'Bug Fix'         => $task['outputs']['bug_fix'] ?? false,
                            'Feature Code'    => $task['outputs']['feature_code'] ?? false,
                            'API Integration' => $task['outputs']['api_integration'] ?? false,
                            'Documentation'   => $task['outputs']['documentation'] ?? false,
                        ]);
                    @endphp
                    @if(!empty($outputs))
                    <div>
                        <span class="section-label">Output Produced</span>
                        <div class="flex flex-wrap gap-2 mt-1">
                            @foreach($outputs as $label => $val)
                                @if($val)
                                <span class="output-tag">
                                    <svg class="w-3 h-3 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    {{ $label }}
                                </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Blocker Details --}}
                    @if($result === 'Blocked' && !empty($task['blocker_details']))
                    <div class="blocker-badge space-y-3">
                        <p class="text-xs font-extrabold text-red-300 uppercase tracking-wider">Blocker Details</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @if(!empty($task['blocker_details']['blocked_by']))
                            <div>
                                <span class="section-label">Blocked By</span>
                                <p class="text-sm font-semibold text-white">{{ $task['blocker_details']['blocked_by'] }}</p>
                            </div>
                            @endif
                            @if(!empty($task['blocker_details']['blocking_item']))
                            <div>
                                <span class="section-label">Blocking Item</span>
                                <div class="prose-content mt-1">{!! $task['blocker_details']['blocking_item'] !!}</div>
                            </div>
                            @endif
                            @if(!empty($task['blocker_details']['expected_resolution']))
                            <div>
                                <span class="section-label">Expected Resolution</span>
                                <p class="text-sm font-semibold text-white">{{ \Carbon\Carbon::parse($task['blocker_details']['expected_resolution'])->format('M d, Y') }}</p>
                            </div>
                            @endif
                            @if(!empty($task['blocker_details']['owner']))
                            <div>
                                <span class="section-label">Blocker Owner</span>
                                <div class="prose-content mt-1">{!! $task['blocker_details']['owner'] !!}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    @endif

    {{-- KPI Metrics (PM) --}}
    @if($workLog->kpi_metrics && count($workLog->kpi_metrics) > 0)
    <div class="detail-card">
        <div class="detail-card-header">
            <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <h3>KPI Metrics</h3>
        </div>
        <div class="p-6 grid grid-cols-2 sm:grid-cols-4 gap-4">
            @if(isset($workLog->kpi_metrics['tasks_assigned']))
            <div class="stat-box">
                <span class="stat-val font-mono">{{ $workLog->kpi_metrics['tasks_assigned'] }}</span>
                <span class="stat-lbl">Tasks Assigned</span>
            </div>
            @endif
            @if(isset($workLog->kpi_metrics['tasks_closed']))
            <div class="stat-box">
                <span class="stat-val font-mono">{{ $workLog->kpi_metrics['tasks_closed'] }}</span>
                <span class="stat-lbl">Tasks Closed</span>
            </div>
            @endif
            @if(isset($workLog->kpi_metrics['requires_mgmt_attention']))
            <div class="stat-box sm:col-span-2">
                <span class="stat-val text-sm font-bold {{ $workLog->kpi_metrics['requires_mgmt_attention'] ? 'text-red-400' : 'text-emerald-400' }}">
                    {{ $workLog->kpi_metrics['requires_mgmt_attention'] ? 'Yes — Escalated' : 'No Escalation' }}
                </span>
                <span class="stat-lbl">Management Attention</span>
            </div>
            @endif
        </div>
    </div>
    @endif

</div>
@endsection
