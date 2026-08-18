@extends('teamportal::layouts.app')

@section('title', 'Session Detail')

@push('styles')
<style>
    :root {
        --brand: #FB4C4B;
        --brand-dark: #D93837;
        --navy: #191C47;
    }

    .detail-hero {
        background: linear-gradient(135deg, var(--navy) 0%, #12153a 60%, #1e1048 100%);
        border-radius: 20px;
        position: relative;
        overflow: hidden;
    }
    .detail-hero::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 260px; height: 260px;
        background: radial-gradient(circle, rgba(251,76,75,0.15) 0%, transparent 70%);
        pointer-events: none;
    }

    .detail-card {
        background: #fff;
        border: 1px solid #e8eaf0;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 24px rgba(25,28,71,0.04);
    }
    .detail-card-header {
        background: #f8f9fc;
        border-bottom: 1.5px solid #e8eaf0;
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
        color: #64748b;
        margin: 0;
    }

    .section-label {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.09em;
        color: #94a3b8;
        display: block;
        margin-bottom: 4px;
    }

    .pill { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 999px; font-size: 10px; font-weight: 700; }
    .pill-red   { background:#fff0f0; color:var(--brand); border:1px solid #ffd5d5; }
    .pill-green { background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; }
    .pill-blue  { background:#dbeafe; color:#1e40af; border:1px solid #bfdbfe; }
    .pill-amber { background:#fffbeb; color:#92400e; border:1px solid #fde68a; }
    .pill-slate { background:#f1f5f9; color:#475569; border:1px solid #e2e8f0; }
    .pill-navy  { background:rgba(25,28,71,0.06); color:var(--navy); border:1px solid rgba(25,28,71,0.12); }
    .pill-purple { background:#faf5ff; color:#7e22ce; border:1px solid #e9d5ff; }

    .task-result-card {
        background: #fafbfc;
        border: 1.5px solid #e8eaf0;
        border-radius: 14px;
        overflow: hidden;
        transition: box-shadow 0.2s;
    }
    .task-result-card:hover { box-shadow: 0 4px 20px rgba(25,28,71,0.08); }
    .task-result-card .card-top {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }
    .task-result-card .card-body { padding: 16px 20px; }

    .result-completed { border-color: #bbf7d0; background: #f0fdf4; }
    .result-partial   { border-color: #bfdbfe; background: #eff6ff; }
    .result-blocked   { border-color: #fecaca; background: #fff5f5; }
    .result-assigned  { border-color: #e9d5ff; background: #faf5ff; }

    .stat-box {
        background: #fff;
        border: 1.5px solid #e8eaf0;
        border-radius: 12px;
        padding: 14px 18px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .stat-box .stat-val {
        font-size: 22px;
        font-weight: 900;
        color: #0f172a;
        line-height: 1;
    }
    .stat-box .stat-lbl {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: #94a3b8;
    }

    .output-tag {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 11px;
        border-radius: 999px;
        font-size: 11px; font-weight: 700;
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .evidence-box {
        background: #f8f9fc;
        border: 1.5px solid #e8eaf0;
        border-radius: 12px;
        padding: 14px 18px;
        font-size: 13px;
        color: #334155;
        line-height: 1.6;
    }

    .blocker-badge {
        background: #fff5f5;
        border: 1.5px solid #fecaca;
        border-radius: 12px;
        padding: 12px 16px;
    }

    .back-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 16px;
        background: white;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 13px; font-weight: 600;
        color: #475569;
        text-decoration: none;
        transition: background 0.2s, border-color 0.2s;
    }
    .back-btn:hover { background: #f8fafc; border-color: #cbd5e1; }

    .prose-content { font-size: 13px; color: #334155; line-height: 1.7; }
    .prose-content p { margin: 0 0 8px; }
    .prose-content ul, .prose-content ol { margin: 0 0 8px 16px; }
    .prose-content strong { font-weight: 700; }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto space-y-7 pb-12">

    {{-- Back + title --}}
    <div class="flex items-center gap-4 pt-2">
        <a href="{{ route('team.work-logs.index') }}" class="back-btn">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            Back to Work Logs
        </a>
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 leading-tight">Session Detail</h1>
            <p class="text-xs text-slate-400 mt-0.5">{{ $workLog->start_time->format('l, F d, Y') }}</p>
        </div>
    </div>

    {{-- Hero --}}
    <div class="detail-hero p-7 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div class="space-y-2 relative z-10">
            <span class="pill pill-red">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Completed Session
            </span>
            <h2 class="text-2xl font-extrabold text-white">{{ $workLog->start_time->format('M d, Y') }}</h2>
            <p class="text-sm text-white/50">{{ $workLog->start_time->format('H:i') }} → {{ $workLog->end_time?->format('H:i') ?? '—' }}</p>
        </div>
        <div class="grid grid-cols-2 gap-3 relative z-10">
            <div class="stat-box">
                <span class="stat-val">{{ $workLog->duration_minutes }}<span class="text-sm font-semibold text-slate-400 ml-1">min</span></span>
                <span class="stat-lbl">Duration</span>
            </div>
            <div class="stat-box">
                <span class="stat-val">{{ $workLog->tasks_data ? count($workLog->tasks_data) : '—' }}</span>
                <span class="stat-lbl">Tasks Logged</span>
            </div>
        </div>
    </div>

    {{-- Session Overview --}}
    <div class="detail-card">
        <div class="detail-card-header">
            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <h3>Session Overview</h3>
        </div>
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <span class="section-label">Planned Focus</span>
                <div class="text-sm font-semibold text-slate-800 leading-relaxed mt-1 prose-sm">{!! $workLog->planned_tasks ?? '—' !!}</div>
            </div>
            @if($workLog->session_goal)
            <div>
                <span class="section-label">Session Goal</span>
                <div class="text-sm font-semibold text-slate-800 leading-relaxed mt-1 prose-sm">{!! $workLog->session_goal !!}</div>
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
            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
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
            <h2 class="text-lg font-extrabold text-slate-900">Task Breakdown</h2>
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
                    <div class="space-y-0.5">
                        <span class="pill pill-slate text-[10px]">Task #{{ $task['task_id'] ?? '?' }}</span>
                        <h4 class="text-sm font-extrabold text-slate-900">{{ $taskModel->title ?? ('Task ID: ' . ($task['task_id'] ?? '?')) }}</h4>
                        @if($taskModel?->project)
                            <p class="text-[11px] text-slate-400">{{ $taskModel->project->name }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <span class="pill {{ $pillClass }}">{{ $resultIcon }} {{ $result }}</span>
                        @if(isset($task['progress_percent']))
                            <span class="pill pill-navy">{{ $task['progress_percent'] }}%</span>
                        @endif
                        @if(isset($task['time_spent_minutes']) && $task['time_spent_minutes'] > 0)
                            <span class="pill pill-slate">{{ $task['time_spent_minutes'] }} min</span>
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
                        <span class="section-label">Evidence / Reference <span class="text-[10px] text-slate-400 normal-case font-normal">({{ $task['evidence_type'] ?? 'N/A' }})</span></span>
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
                                    <svg class="w-3 h-3 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
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
                        <p class="text-xs font-extrabold text-red-700 uppercase tracking-wider">Blocker Details</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @if(!empty($task['blocker_details']['blocked_by']))
                            <div>
                                <span class="section-label">Blocked By</span>
                                <p class="text-sm font-semibold text-slate-800">{{ $task['blocker_details']['blocked_by'] }}</p>
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
                                <p class="text-sm font-semibold text-slate-800">{{ \Carbon\Carbon::parse($task['blocker_details']['expected_resolution'])->format('M d, Y') }}</p>
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
            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <h3>KPI Metrics</h3>
        </div>
        <div class="p-6 grid grid-cols-2 sm:grid-cols-4 gap-4">
            @if(isset($workLog->kpi_metrics['tasks_assigned']))
            <div class="stat-box">
                <span class="stat-val">{{ $workLog->kpi_metrics['tasks_assigned'] }}</span>
                <span class="stat-lbl">Tasks Assigned</span>
            </div>
            @endif
            @if(isset($workLog->kpi_metrics['tasks_closed']))
            <div class="stat-box">
                <span class="stat-val">{{ $workLog->kpi_metrics['tasks_closed'] }}</span>
                <span class="stat-lbl">Tasks Closed</span>
            </div>
            @endif
            @if(isset($workLog->kpi_metrics['requires_mgmt_attention']))
            <div class="stat-box sm:col-span-2">
                <span class="stat-val text-sm font-bold {{ $workLog->kpi_metrics['requires_mgmt_attention'] ? 'text-red-500' : 'text-green-600' }}">
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
