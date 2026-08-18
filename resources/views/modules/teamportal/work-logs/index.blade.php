@extends('teamportal::layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    /* ─── Page Variables ─────────────────────────────── */
    :root {
        --brand: #FB4C4B;
        --brand-dark: #D93837;
        --navy: #191C47;
        --surface: #ffffff;
        --surface-subtle: #f8f9fc;
        --border: #e8eaf0;
    }

    /* ─── Hero Banner ─────────────────────────────────── */
    .wl-hero {
        background: linear-gradient(135deg, var(--navy) 0%, #12153a 60%, #1e1048 100%);
        border-radius: 20px;
        position: relative;
        overflow: hidden;
    }
    .wl-hero::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 260px; height: 260px;
        background: radial-gradient(circle, rgba(251,76,75,0.18) 0%, transparent 70%);
        pointer-events: none;
    }
    .wl-hero::after {
        content: '';
        position: absolute;
        bottom: -40px; left: -40px;
        width: 180px; height: 180px;
        background: radial-gradient(circle, rgba(251,76,75,0.10) 0%, transparent 70%);
        pointer-events: none;
    }

    /* ─── Timer ───────────────────────────────────────── */
    .wl-timer {
        font-variant-numeric: tabular-nums;
        letter-spacing: 0.04em;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.14);
        backdrop-filter: blur(8px);
        border-radius: 14px;
        padding: 10px 20px;
    }

    /* ─── Wizard Progress Bar ─────────────────────────── */
    .wl-steps-track {
        position: relative;
        display: flex;
        align-items: flex-start;
        gap: 0;
    }
    .wl-step-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 1;
    }
    .wl-step-wrapper:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 16px;
        left: 50%;
        right: -50%;
        height: 2px;
        background: #e2e8f0;
        z-index: -1;
    }
    .wl-step-wrapper.done:not(:last-child)::after {
        background: var(--brand);
    }
    .wl-step-dot {
        width: 34px; height: 34px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 800;
        border: 2px solid #e2e8f0;
        background: #f1f5f9;
        color: #94a3b8;
        transition: all 0.3s ease;
        position: relative;
        z-index: 2;
    }
    .wl-step-dot.active {
        background: var(--brand);
        border-color: var(--brand);
        color: white;
        box-shadow: 0 0 0 4px rgba(251,76,75,0.15);
    }
    .wl-step-dot.done {
        background: var(--brand);
        border-color: var(--brand-dark);
        color: white;
    }
    .wl-step-label {
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        margin-top: 6px;
        color: #94a3b8;
        text-align: center;
        max-width: 56px;
        line-height: 1.3;
    }
    .wl-step-label.active,
    .wl-step-label.done { color: var(--brand); }

    /* ─── Cards ───────────────────────────────────────── */
    .wl-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 24px rgba(25,28,71,0.04);
    }
    .wl-card-inner {
        background: var(--surface-subtle);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 20px;
    }

    /* ─── Pill Badges ─────────────────────────────────── */
    .pill { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 999px; font-size: 10px; font-weight: 700; }
    .pill-red { background: #fff0f0; color: var(--brand); border: 1px solid #ffd5d5; }
    .pill-navy { background: rgba(25,28,71,0.06); color: var(--navy); border: 1px solid rgba(25,28,71,0.12); }
    .pill-amber { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
    .pill-slate { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
    .pill-indigo { background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe; }

    /* ─── Form Fields ─────────────────────────────────── */
    .wl-input {
        display: block; width: 100%;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 0.875rem;
        color: #1e293b;
        background: #fafbfc;
        outline: none;
        transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
        font-family: inherit;
    }
    .wl-input:focus {
        border-color: var(--brand);
        background: white;
        box-shadow: 0 0 0 3px rgba(251,76,75,0.10);
    }

    /* ─── Buttons ─────────────────────────────────────── */
    .btn-primary {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 11px 24px;
        background: var(--brand);
        color: white;
        font-size: 0.875rem; font-weight: 700;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        transition: background 0.2s, transform 0.1s, box-shadow 0.2s;
        box-shadow: 0 4px 14px rgba(251,76,75,0.25);
    }
    .btn-primary:hover { background: var(--brand-dark); box-shadow: 0 6px 20px rgba(251,76,75,0.35); transform: translateY(-1px); }
    .btn-primary:active { transform: translateY(0); }
    .btn-secondary {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 11px 20px;
        background: white;
        color: #475569;
        font-size: 0.875rem; font-weight: 600;
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        cursor: pointer;
        transition: background 0.2s, border-color 0.2s;
    }
    .btn-secondary:hover { background: #f8fafc; border-color: #cbd5e1; }

    /* ─── Project Checkboxes ──────────────────────────── */
    .project-chip {
        display: flex; align-items: center; gap: 8px;
        padding: 10px 14px;
        border: 1.5px solid #e8eaf0;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        background: white;
        user-select: none;
    }
    .project-chip:hover { border-color: var(--brand); color: var(--brand); background: #fff8f8; }
    .project-chip input:checked ~ * { color: var(--brand); }
    .project-chip:has(input:checked) {
        border-color: var(--brand);
        background: #fff0f0;
        color: var(--brand);
    }

    /* ─── Task Check Chips ────────────────────────────── */
    .task-chip-item {
        display: flex; align-items: center; justify-content: space-between;
        padding: 10px 12px;
        border: 1.5px solid #e8eaf0;
        border-radius: 10px;
        background: white;
        transition: all 0.2s;
        cursor: pointer;
    }
    .task-chip-item:hover { border-color: #cbd5e1; }
    .task-chip-item:has(input:checked) { border-color: var(--brand); background: #fff8f8; }

    /* ─── Output Checkboxes ───────────────────────────── */
    .output-chip {
        display: flex; align-items: center; gap: 8px;
        padding: 9px 13px;
        border: 1.5px solid #e8eaf0;
        border-radius: 10px;
        cursor: pointer;
        font-size: 11.5px;
        font-weight: 600;
        color: #64748b;
        background: #fafbfc;
        transition: all 0.2s;
        user-select: none;
    }
    .output-chip:hover { border-color: #94a3b8; color: #334155; }
    .output-chip:has(input:checked) { border-color: var(--brand); background: #fff0f0; color: var(--brand); }

    /* ─── Blocker panel ───────────────────────────────── */
    .blocker-panel {
        background: linear-gradient(135deg, #fff5f5, #fff8f8);
        border: 1.5px solid #fecaca;
        border-radius: 14px;
        padding: 18px;
    }
    .blocker-panel .wl-input {
        background: #ffffff !important;
        border-color: #fca5a5;
    }
    .blocker-panel .wl-input:focus {
        border-color: var(--brand) !important;
        box-shadow: 0 0 0 3px rgba(251,76,75,0.10) !important;
        background: #ffffff !important;
    }

    /* ─── History Table ───────────────────────────────── */
    .wl-table th {
        background: #f8f9fc;
        padding: 12px 16px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: #94a3b8;
        border-bottom: 1.5px solid #e8eaf0;
    }
    .wl-table td {
        padding: 14px 16px;
        font-size: 13px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: top;
    }
    .wl-table tr:last-child td { border-bottom: none; }
    .wl-table tbody tr { transition: background 0.15s; }
    .wl-table tbody tr:hover td { background: #fafbfc; }

    /* ─── Summernote Overrides ────────────────────────── */
    .note-editor.note-frame {
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 12px !important;
        overflow: hidden;
        background: #fafbfc !important;
        box-shadow: none !important;
    }
    .note-editor.note-frame:focus-within {
        border-color: var(--brand) !important;
        box-shadow: 0 0 0 3px rgba(251,76,75,0.10) !important;
    }
    .note-editor .note-toolbar {
        background: #f1f5f9 !important;
        border-bottom: 1.5px solid #e2e8f0 !important;
        padding: 6px 10px !important;
    }
    .note-editable {
        background: #fafbfc !important;
        min-height: 110px !important;
        color: #334155 !important;
        font-family: 'Outfit', sans-serif !important;
        font-size: 0.875rem !important;
        padding: 10px 14px !important;
    }
    .note-editable:focus { background: #ffffff !important; }
    .note-btn {
        background: white !important;
        border: 1px solid #e2e8f0 !important;
        color: #475569 !important;
        border-radius: 6px !important;
    }
    .note-btn:hover { background: #f1f5f9 !important; color: #0f172a !important; }
    .note-btn.active { background: #fee2e2 !important; color: var(--brand) !important; }

    /* ─── Section Label ───────────────────────────────── */
    .section-label {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.09em;
        color: #94a3b8;
        margin-bottom: 8px;
        display: block;
    }
    .section-label .req { color: var(--brand); }

    /* ─── Pulse dot ───────────────────────────────────── */
    @keyframes pulse-ring {
        0%   { box-shadow: 0 0 0 0 rgba(251,76,75,0.5); }
        70%  { box-shadow: 0 0 0 8px rgba(251,76,75,0); }
        100% { box-shadow: 0 0 0 0 rgba(251,76,75,0); }
    }
    .pulse-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: var(--brand);
        animation: pulse-ring 1.4s ease infinite;
        display: inline-block;
    }

    /* ─── Mgmt attention card ─────────────────────────── */
    .mgmt-card {
        background: linear-gradient(135deg, #fffbeb, #fefce8);
        border: 1.5px solid #fde68a;
        border-radius: 14px;
        padding: 16px 20px;
    }

    /* ─── Check-in Start banner ───────────────────────── */
    .checkin-banner {
        background: linear-gradient(135deg, var(--navy) 0%, #252963 100%);
        border-radius: 20px;
        position: relative;
        overflow: hidden;
    }
    .checkin-banner::before {
        content: '';
        position: absolute;
        top: -80px; right: -60px;
        width: 240px; height: 240px;
        background: radial-gradient(circle, rgba(251,76,75,0.15) 0%, transparent 70%);
        pointer-events: none;
    }

    /* ─── Review summary card ─────────────────────────── */
    .review-item {
        display: flex; align-items: center; justify-content: space-between;
        padding: 12px 16px;
        background: white;
        border: 1.5px solid #e8eaf0;
        border-radius: 12px;
    }

    /* ─── Alert modal ─────────────────────────────────── */
    .alert-overlay {
        position: fixed; inset: 0; z-index: 9999;
        background: rgba(15,23,42,0.5);
        backdrop-filter: blur(4px);
        display: flex; align-items: center; justify-content: center;
    }
    .alert-box {
        background: white;
        border-radius: 20px;
        padding: 28px 24px;
        max-width: 360px; width: 100%;
        margin: 16px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        transform: scale(0.96);
        opacity: 0;
        transition: all 0.2s ease;
    }
    .alert-box.open { transform: scale(1); opacity: 1; }

    /* ─── Evidence / stale warning ────────────────────── */
    .stale-warn {
        background: #fff5f5;
        border: 1px solid #fecaca;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 11.5px;
        color: #b91c1c;
        display: flex; align-items: flex-start; gap: 8px;
    }

    /* ─── Scrollbar ───────────────────────────────────── */
    .thin-scroll::-webkit-scrollbar { width: 4px; }
    .thin-scroll::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 99px; }
    .thin-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    .thin-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    /* ─── Declare checkbox ────────────────────────────── */
    .declare-box {
        background: linear-gradient(135deg, #fff8f8, #fff0f0);
        border: 1.5px solid #fecaca;
        border-radius: 14px;
        padding: 16px 18px;
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto space-y-8 pb-12">

    {{-- ─── Page Header ──────────────────────────────────────────── --}}
    <div class="flex items-center justify-between pt-2">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Work Session Control</h1>
            <p class="mt-1 text-sm text-slate-400">Track your session, log per-task status details, and check out.</p>
        </div>
        <span class="pill pill-navy hidden sm:inline-flex">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ auth()->user()->name }}
        </span>
    </div>

    {{-- ─── Error Banner ─────────────────────────────────────────── --}}
    @if($errors->any())
        <div class="wl-card border-red-200 bg-red-50 p-4 flex gap-3">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <p class="text-sm font-bold text-red-800">Please correct the following errors:</p>
                <ul class="mt-1 space-y-0.5 text-sm text-red-700 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- ─── ACTIVE SESSION PANEL ────────────────────────────────── --}}
    @if($activeLog)

        {{-- Hero ─────────────────────────────────────────────────── --}}
        <div class="wl-hero p-7 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="space-y-2 relative z-10">
                <span class="pill pill-red text-[10px]">
                    <span class="pulse-dot"></span>
                    Active Session
                </span>
                <h2 class="text-2xl font-extrabold text-white tracking-tight">Currently Checked In</h2>
                <p class="text-sm text-white/50">Session started {{ $activeLog->start_time->format('M d, Y · H:i') }}</p>
            </div>
            <div class="flex flex-col items-end gap-2 relative z-10">
                <div class="wl-timer text-4xl font-mono font-extrabold text-white tracking-widest" id="timer">00:00:00</div>
                <span class="text-xs text-white/40 font-medium">Time elapsed</span>
            </div>
        </div>

        {{-- Session Focus Summary ────────────────────────────────── --}}
        <div class="wl-card-inner flex flex-col sm:flex-row gap-6">
            <div class="flex-1">
                <span class="section-label">Session Planned Focus</span>
                <div class="text-sm font-semibold text-slate-800 leading-relaxed prose-sm">{!! $activeLog->planned_tasks ?? '—' !!}</div>
            </div>
            @if($activeLog->resources_needed)
            <div class="flex-1 sm:border-l sm:border-slate-200 sm:pl-6">
                <span class="section-label">Resources Requested</span>
                <p class="text-sm text-slate-600 leading-relaxed">{!! $activeLog->resources_needed !!}</p>
            </div>
            @endif
        </div>

        {{-- Check-Out Wizard ──────────────────────────────────────── --}}
        <div class="wl-card">
            <form action="{{ route('team.work-logs.check-out', $activeLog) }}" method="POST" id="checkout-form">
                @csrf
                @method('PUT')

                <input type="hidden" name="actual_tasks_encoded" id="actual_tasks_encoded">
                <input type="hidden" name="challenges_encoded" id="challenges_encoded">

                @php
                    $totalSteps = auth()->user()->isProjectManager() ? 3 : (count($assignedTasks) + 2);
                @endphp

                {{-- ── Wizard Progress ─────────────────────────────── --}}
                <div class="px-8 pt-8 pb-6 border-b border-slate-100">
                    <div class="wl-steps-track overflow-x-auto thin-scroll pb-1">
                        @for($i = 1; $i <= $totalSteps; $i++)
                            <div class="wl-step-wrapper" id="step-wrapper-{{ $i }}">
                                <div class="wl-step-dot @if($i == 1) active @endif" id="step-indicator-{{ $i }}">
                                    @if($i == 1) {{ $i }} @else {{ $i }} @endif
                                </div>
                                <span class="wl-step-label @if($i == 1) active @endif" id="step-text-{{ $i }}">
                                    @if(auth()->user()->isProjectManager())
                                        @if($i == 1) PM Notes @elseif($i == 2) Summary @else Finish @endif
                                    @else
                                        @if($i <= count($assignedTasks)) Task {{ $i }}
                                        @elseif($i == count($assignedTasks) + 1) Summary
                                        @else Finish
                                        @endif
                                    @endif
                                </span>
                            </div>
                        @endfor
                    </div>
                </div>

                {{-- ── Step Panels ──────────────────────────────────── --}}
                <div class="p-8 space-y-0">

                    @if(auth()->user()->isProjectManager())
                        {{-- ══════ PM WIZARD PANELS ══════ --}}

                        {{-- PM Step 1 --}}
                        <div id="step-panel-1" class="wizard-step-panel space-y-6">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Step 1 of 3</p>
                                <h3 class="text-lg font-extrabold text-slate-900">Session Execution & Management Notes</h3>
                            </div>

                            <div class="space-y-5">
                                {{-- PM: Tasks Assigned --}}
                                <div>
                                    <span class="section-label">Tasks Assigned This Session</span>
                                    <p class="text-xs text-slate-400 mb-3">Select the tasks that you successfully assigned or allocated during this work session.</p>
                                    <div class="space-y-2 max-h-52 overflow-y-auto thin-scroll p-3 bg-slate-50 rounded-xl border border-slate-200">
                                        @php
                                            $plannedIds = $activeLog->planned_assignments ?? [];
                                        @endphp
                                        @forelse($pmAvailableTasks as $task)
                                            <label class="task-chip-item cursor-pointer flex items-center justify-between gap-3 p-2 bg-white rounded-lg border border-slate-100 hover:border-slate-300 transition-colors">
                                                <div class="flex items-center gap-2.5 text-xs text-slate-700 cursor-pointer">
                                                    <input type="checkbox" name="actual_assignments[]" value="{{ $task->id }}" class="rounded" style="accent-color:var(--brand)"
                                                           {{ in_array($task->id, $plannedIds) ? 'checked' : '' }}>
                                                    <span class="font-semibold">{{ $task->title }}</span>
                                                </div>
                                                @if($task->assignee)
                                                    <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full font-medium">Assigned to: {{ $task->assignee->name }}</span>
                                                @endif
                                            </label>
                                        @empty
                                            <p class="text-xs text-slate-400 py-2">No open tasks available in the selected projects.</p>
                                        @endforelse
                                    </div>
                                </div>

                                <div>
                                    <span class="section-label">Key Issues / Decisions / Risks <span class="req">*</span></span>
                                    <textarea id="key_issues" name="key_issues" class="wysiwyg-editor" placeholder="Detail any critical risks or updates during this session..."></textarea>
                                </div>

                                <div class="mgmt-card">
                                    <label class="flex items-start gap-3 cursor-pointer select-none">
                                        <input type="hidden" name="requires_mgmt_attention" value="0">
                                        <input id="requires_mgmt_attention" name="requires_mgmt_attention" type="checkbox" value="1"
                                               class="mt-0.5 h-4 w-4 rounded text-amber-500 border-amber-300 focus:ring-amber-400"
                                               onchange="toggleMgmtDetails(this.checked)">
                                        <div>
                                            <p class="text-sm font-bold text-amber-900">Requires Senior Management Attention?</p>
                                            <p class="text-xs text-amber-700 mt-0.5">Check this if blockers require escalation to management.</p>
                                        </div>
                                    </label>
                                    <div id="mgmt_details_container" class="hidden mt-4">
                                        <span class="section-label">Management Attention Details <span class="req">*</span></span>
                                        <textarea id="mgmt_attention_details" name="mgmt_attention_details" class="wysiwyg-editor" placeholder="What needs immediate attention/approval?"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end pt-4 border-t border-slate-100">
                                <button type="button" onclick="nextStep()" class="btn-primary">
                                    Continue to Summary
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- PM Step 2 --}}
                        <div id="step-panel-2" class="wizard-step-panel hidden space-y-6">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Step 2 of 3</p>
                                <h3 class="text-lg font-extrabold text-slate-900">General Accomplishments</h3>
                            </div>

                            <div class="space-y-5">
                                <div>
                                    <span class="section-label">General Work Summary <span class="req">*</span></span>
                                    <textarea id="actual_tasks" name="actual_tasks" class="wysiwyg-editor" placeholder="What did you get done overall?"></textarea>
                                </div>
                                <div>
                                    <span class="section-label">Challenges / Blockers <span class="text-slate-300">(Optional)</span></span>
                                    <textarea id="challenges" name="challenges" class="wysiwyg-editor" placeholder="Any high-level blocker details..."></textarea>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                <button type="button" onclick="prevStep()" class="btn-secondary">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                                    Back
                                </button>
                                <button type="button" onclick="nextStep()" class="btn-primary">
                                    Continue to Review
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- PM Step 3 --}}
                        <div id="step-panel-3" class="wizard-step-panel hidden space-y-6">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Step 3 of 3</p>
                                <h3 class="text-lg font-extrabold text-slate-900">Review & Submit</h3>
                            </div>

                            <div class="wl-card-inner space-y-3">
                                <span class="section-label">Session Summary</span>
                                <div class="space-y-4">
                                    <div class="grid grid-cols-3 gap-3">
                                        <div class="review-item flex-col items-start">
                                            <span class="section-label mb-0.5">Duration</span>
                                            <span class="font-bold text-slate-800" id="review-duration-pm">—</span>
                                        </div>
                                        <div class="review-item flex-col items-start">
                                            <span class="section-label mb-0.5">Tasks Assigned</span>
                                            <span class="font-bold text-slate-800" id="review-assigned-pm">0</span>
                                        </div>
                                        <div class="review-item flex-col items-start">
                                            <span class="section-label mb-0.5">Tasks Accomplished</span>
                                            <span class="font-bold text-slate-800">{{ $tasksClosedCount ?? 0 }}</span>
                                        </div>
                                    </div>
                                    <div class="review-item flex-col items-start w-full">
                                        <span class="section-label mb-0.5">Goal Defined</span>
                                        <div class="font-bold text-slate-800 text-sm prose-sm w-full">{!! $activeLog->session_goal ?? '—' !!}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="declare-box">
                                <label class="flex items-start gap-3 cursor-pointer select-none">
                                    <input id="declare_pm" name="declare_pm" type="checkbox" required
                                           class="mt-0.5 h-4.5 w-4.5 rounded text-brand-primary border-red-200 focus:ring-brand-primary">
                                    <div>
                                        <p class="text-sm font-bold text-red-900">Confirm Declaration Statement</p>
                                        <p class="text-xs text-red-700/80 mt-0.5">I confirm this report reflects the actual work performed, discloses any blocker, and includes available evidence.</p>
                                    </div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                <button type="button" onclick="prevStep()" class="btn-secondary">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                                    Back
                                </button>
                                <button type="submit" class="btn-primary">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    Check Out & Finish Session
                                </button>
                            </div>
                        </div>

                    @else
                        {{-- ══════ DEVELOPER WIZARD PANELS ══════ --}}

                        @foreach($assignedTasks as $index => $task)
                            @php $stepNum = $index + 1; @endphp
                            <div id="step-panel-{{ $stepNum }}" class="wizard-step-panel @if($stepNum > 1) hidden @endif space-y-6">

                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Step {{ $stepNum }} of {{ $totalSteps }}</p>
                                    <h3 class="text-lg font-extrabold text-slate-900">Task Outcome</h3>
                                </div>

                                {{-- Task Header Card --}}
                                <div class="wl-card-inner task-outcomes-card" data-task-index="{{ $index }}" data-task-title="{{ $task->title }}">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5 pb-5 border-b border-slate-200/60">
                                        <div class="space-y-1">
                                            <span class="pill pill-slate">Task #{{ $task->id }}</span>
                                            <h4 class="text-base font-extrabold text-slate-900 leading-tight">{{ $task->title }}</h4>
                                            @if($task->project)
                                                <p class="text-xs text-slate-400 font-medium">{{ $task->project->name }}</p>
                                            @endif
                                        </div>
                                        <span class="pill pill-indigo flex-shrink-0">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Time tracked automatically
                                        </span>
                                    </div>

                                    <input type="hidden" name="tasks_data[{{ $index }}][task_id]" value="{{ $task->id }}">
                                    <input type="hidden" id="evidence_req_indicator_{{ $index }}" value="0">

                                    <div class="space-y-5">
                                        {{-- Row 1: Result & Progress --}}
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <span class="section-label">Result Status <span class="req">*</span></span>
                                                <select name="tasks_data[{{ $index }}][result]"
                                                        id="result_{{ $index }}"
                                                        onchange="toggleTaskResultDetails(this.value, {{ $index }}, {{ $task->id }})"
                                                        class="wl-input" required>
                                                    <option value="">Select outcome...</option>
                                                    <option value="Completed">✓ Completed</option>
                                                    <option value="Partial">⬡ Partial</option>
                                                    <option value="Blocked">✕ Blocked</option>
                                                </select>
                                            </div>
                                            <div>
                                                <span class="section-label">Progress % <span class="req">*</span></span>
                                                <select name="tasks_data[{{ $index }}][progress_percent]"
                                                        id="progress_percent_{{ $index }}"
                                                        class="wl-input"
                                                        required>
                                                    <option value="">Select progress...</option>
                                                    @for($p = 0; $p <= 100; $p += 5)
                                                        <option value="{{ $p }}">{{ $p }}%</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>

                                        {{-- What Changed --}}
                                        <div>
                                            <span class="section-label">What Changed? <span class="req">*</span></span>
                                            <textarea name="tasks_data[{{ $index }}][what_changed]"
                                                      id="what_changed_{{ $index }}"
                                                      class="wysiwyg-editor"
                                                      placeholder="Describe what was developed, tested, or changed in detail..."></textarea>
                                        </div>

                                        {{-- Blocker Panel --}}
                                        <div id="blocker_container_{{ $index }}" class="hidden blocker-panel space-y-4">
                                            <p class="text-xs font-extrabold text-red-700 uppercase tracking-wider">Blocker Details</p>
                                            <div class="space-y-4">
                                                {{-- Row 1: Blocked By --}}
                                                <div>
                                                    <span class="section-label">Blocked By <span class="req">*</span></span>
                                                    <select name="tasks_data[{{ $index }}][blocker_details][blocked_by]"
                                                            id="blocked_by_{{ $index }}"
                                                            style="display:block;width:100%;background:#ffffff;border:1.5px solid #fca5a5;border-radius:12px;padding:10px 14px;font-size:0.875rem;color:#1e293b;outline:none;font-family:inherit;transition:border-color 0.2s,box-shadow 0.2s;">
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

                                                {{-- Row 2: Blocking Item (WYSIWYG) --}}
                                                <div>
                                                    <span class="section-label">Blocking Item <span class="req">*</span></span>
                                                    <textarea name="tasks_data[{{ $index }}][blocker_details][blocking_item]"
                                                              id="blocking_item_{{ $index }}"
                                                              class="wysiwyg-editor"
                                                              placeholder="Describe what is blocking this task..."></textarea>
                                                </div>

                                                {{-- Row 3: Expected Resolution --}}
                                                <div>
                                                    <span class="section-label">Expected Resolution</span>
                                                    <input type="date"
                                                           name="tasks_data[{{ $index }}][blocker_details][expected_resolution]"
                                                           style="display:block;width:100%;background:#ffffff;border:1.5px solid #fca5a5;border-radius:12px;padding:10px 14px;font-size:0.875rem;color:#1e293b;outline:none;font-family:inherit;transition:border-color 0.2s,box-shadow 0.2s;">
                                                </div>

                                                {{-- Row 4: Blocker Owner (WYSIWYG) --}}
                                                <div>
                                                    <span class="section-label">Blocker Owner</span>
                                                    <textarea name="tasks_data[{{ $index }}][blocker_details][owner]"
                                                              class="wysiwyg-editor"
                                                              placeholder="Who owns resolving this blocker?"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Evidence & Output — stacked rows --}}
                                        <div class="space-y-4">

                                            {{-- Row 1: Evidence Type (full width select) --}}
                                            <div>
                                                <span class="section-label">Evidence Type</span>
                                                <select name="tasks_data[{{ $index }}][evidence_type]" class="wl-input">
                                                    <option value="PR">PR</option>
                                                    <option value="Commit">Commit</option>
                                                    <option value="Staging URL">Staging URL</option>
                                                    <option value="Screenshot">Screenshot</option>
                                                    <option value="Video">Video</option>
                                                    <option value="Test result">Test Result</option>
                                                </select>
                                            </div>

                                            {{-- Row 2: Evidence editor (full width) --}}
                                            <div>
                                                <span class="section-label">Evidence / Reference <span id="evidence-req-label-{{ $index }}" class="text-red-400 hidden">(Required)</span></span>
                                                <textarea id="evidence_{{ $index }}"
                                                          name="tasks_data[{{ $index }}][evidence]"
                                                          class="wysiwyg-editor"
                                                          placeholder="URL / proof link / evidence notes"></textarea>
                                            </div>

                                            {{-- Stale partial warning --}}
                                            <div id="evidence_warning_{{ $index }}" class="stale-warn hidden">
                                                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                <span>This task has been partial for 3+ consecutive sessions. Evidence upload is mandatory.</span>
                                            </div>

                                            {{-- Row 3: Output Produced (full width 4-chip grid) --}}
                                            <div>
                                                <span class="section-label">Output Produced</span>
                                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                                    <label class="output-chip">
                                                        <input type="checkbox" name="tasks_data[{{ $index }}][outputs][bug_fix]" value="1" class="rounded text-brand-primary border-slate-300">
                                                        <span>Bug Fix</span>
                                                    </label>
                                                    <label class="output-chip">
                                                        <input type="checkbox" name="tasks_data[{{ $index }}][outputs][feature_code]" value="1" class="rounded text-brand-primary border-slate-300">
                                                        <span>Feature Code</span>
                                                    </label>
                                                    <label class="output-chip">
                                                        <input type="checkbox" name="tasks_data[{{ $index }}][outputs][api_integration]" value="1" class="rounded text-brand-primary border-slate-300">
                                                        <span>API Integration</span>
                                                    </label>
                                                    <label class="output-chip">
                                                        <input type="checkbox" name="tasks_data[{{ $index }}][outputs][documentation]" value="1" class="rounded text-brand-primary border-slate-300">
                                                        <span>Documentation</span>
                                                    </label>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                {{-- Navigation --}}
                                <div class="flex items-center justify-between pt-2">
                                    <button type="button" @if($stepNum == 1) disabled style="opacity:0.4;cursor:not-allowed" @endif onclick="prevStep()" class="btn-secondary">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                                        Back
                                    </button>
                                    <button type="button" onclick="nextStep()" class="btn-primary">
                                        Continue
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach

                        {{-- Dev Summary Step --}}
                        @php $devSummaryStep = count($assignedTasks) + 1; @endphp
                        <div id="step-panel-{{ $devSummaryStep }}" class="wizard-step-panel hidden space-y-6">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Step {{ $devSummaryStep }} of {{ $totalSteps }}</p>
                                <h3 class="text-lg font-extrabold text-slate-900">Session Accomplishments</h3>
                            </div>

                            <div class="space-y-5">
                                <div>
                                    <span class="section-label">General Work Summary <span class="req">*</span></span>
                                    <textarea id="actual_tasks" name="actual_tasks" class="wysiwyg-editor" placeholder="What did you get done overall?"></textarea>
                                </div>
                                <div>
                                    <span class="section-label">Challenges / Blockers <span class="text-slate-300">(Optional)</span></span>
                                    <textarea id="challenges" name="challenges" class="wysiwyg-editor" placeholder="Any high-level blocker details..."></textarea>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                                <button type="button" onclick="prevStep()" class="btn-secondary">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                                    Back
                                </button>
                                <button type="button" onclick="nextStep()" class="btn-primary">
                                    Continue
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Dev Finish Step --}}
                        @php $devFinishStep = count($assignedTasks) + 2; @endphp
                        <div id="step-panel-{{ $devFinishStep }}" class="wizard-step-panel hidden space-y-6">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Step {{ $devFinishStep }} of {{ $totalSteps }}</p>
                                <h3 class="text-lg font-extrabold text-slate-900">Review & Submit</h3>
                            </div>

                            <div class="wl-card-inner space-y-3">
                                <span class="section-label">Session Review</span>
                                <div class="grid grid-cols-2 gap-3 mb-2">
                                    <div class="review-item flex-col items-start">
                                        <span class="section-label mb-0.5">Total Duration</span>
                                        <span class="font-bold text-slate-800" id="review-duration">—</span>
                                    </div>
                                    <div class="review-item flex-col items-start">
                                        <span class="section-label mb-0.5">Tasks Logged</span>
                                        <span class="font-bold text-slate-800">{{ count($assignedTasks) }} task(s)</span>
                                    </div>
                                </div>
                                <div id="review-status-summary" class="space-y-2"></div>
                            </div>

                            <div class="declare-box">
                                <label class="flex items-start gap-3 cursor-pointer select-none">
                                    <input id="declare" name="declare" type="checkbox" required
                                           class="mt-0.5 h-4 w-4 rounded border-red-200 focus:ring-brand-primary" style="accent-color:var(--brand)">
                                    <div>
                                        <p class="text-sm font-bold text-red-900">Confirm Declaration Statement</p>
                                        <p class="text-xs text-red-700/80 mt-0.5">I confirm this report reflects the actual work performed, discloses any blocker, and includes available evidence.</p>
                                    </div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                                <button type="button" onclick="prevStep()" class="btn-secondary">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                                    Back
                                </button>
                                <button type="submit" class="btn-primary">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    Check Out & Finish Session
                                </button>
                            </div>
                        </div>
                    @endif

                </div>{{-- end p-8 --}}
            </form>
        </div>{{-- end wl-card --}}

        {{-- Scripts (active session only) --}}
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
        <script>
            $(document).ready(function () {
                $('.wysiwyg-editor').each(function () {
                    const ph = $(this).attr('placeholder') || 'Type here...';
                    $(this).summernote({
                        placeholder: ph,
                        tabsize: 2,
                        height: 110,
                        toolbar: [
                            ['font', ['bold', 'underline', 'clear']],
                            ['para', ['ul', 'ol']],
                            ['insert', ['link']],
                            ['view', ['fullscreen', 'codeview']]
                        ]
                    });
                });
            });

            function isSummernoteEmpty(id) {
                const el = $('#' + id);
                if (!el.length) return true;
                const code = el.summernote('code').replace(/<[^>]*>/g, '').replace(/&nbsp;/g, ' ').trim();
                return code === '';
            }

            // ── Wizard ─────────────────────────────────────
            let currentStep = 1;
            const totalSteps = {{ $totalSteps }};
            const isPM = {{ auth()->user()->isProjectManager() ? 'true' : 'false' }};
            const tasksCount = {{ count($assignedTasks) }};

            function showStep(step) {
                document.querySelectorAll('.wizard-step-panel').forEach(p => p.classList.add('hidden'));
                const panel = document.getElementById('step-panel-' + step);
                if (panel) panel.classList.remove('hidden');

                for (let i = 1; i <= totalSteps; i++) {
                    const dot = document.getElementById('step-indicator-' + i);
                    const lbl = document.getElementById('step-text-' + i);
                    const wrapper = document.getElementById('step-wrapper-' + i);
                    if (!dot) continue;
                    dot.classList.remove('active', 'done');
                    lbl.classList.remove('active', 'done');
                    if (wrapper) wrapper.classList.remove('done');
                    if (i < step) {
                        dot.classList.add('done');
                        dot.innerHTML = '✓';
                        lbl.classList.add('done');
                        if (wrapper) wrapper.classList.add('done');
                    } else if (i === step) {
                        dot.classList.add('active');
                        dot.innerHTML = i;
                        lbl.classList.add('active');
                    } else {
                        dot.innerHTML = i;
                    }
                }

                document.getElementById('checkout-form')?.scrollIntoView({ behavior: 'smooth', block: 'start' });

                // Populate review step
                if (step === totalSteps && !isPM) {
                    const dur = document.getElementById('timer')?.textContent;
                    const durEl = document.getElementById('review-duration');
                    if (durEl) durEl.textContent = dur + ' (session duration)';

                    const summaryDiv = document.getElementById('review-status-summary');
                    if (summaryDiv) {
                        summaryDiv.innerHTML = '';
                        for (let idx = 0; idx < tasksCount; idx++) {
                            const card = document.querySelector('.task-outcomes-card[data-task-index="' + idx + '"]');
                            if (!card) continue;
                            const title = card.getAttribute('data-task-title');
                            const result = document.getElementById('result_' + idx)?.value || 'Not Selected';
                            const progress = document.getElementById('progress_percent_' + idx)?.value || '0';
                            let badge = 'background:#f1f5f9;color:#475569';
                            if (result === 'Completed') badge = 'background:#dcfce7;color:#166534';
                            else if (result === 'Partial') badge = 'background:#dbeafe;color:#1e40af';
                            else if (result === 'Blocked') badge = 'background:#fee2e2;color:#991b1b';
                            summaryDiv.innerHTML += `
                                <div class="review-item">
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">${title}</p>
                                        <p class="text-xs text-slate-400">Progress: ${progress}%</p>
                                    </div>
                                    <span class="pill" style="${badge}">${result}</span>
                                </div>`;
                        }
                    }
                }
                if (step === totalSteps && isPM) {
                    const dur = document.getElementById('timer')?.textContent;
                    const durEl = document.getElementById('review-duration-pm');
                    if (durEl) durEl.textContent = dur + ' (session duration)';

                    const assignedCount = $('input[name="actual_assignments[]"]:checked').length;
                    const assignedEl = document.getElementById('review-assigned-pm');
                    if (assignedEl) assignedEl.textContent = assignedCount;
                }
            }

            function validateStep(step) {
                if (isPM) {
                    if (step === 1) {
                        if (isSummernoteEmpty('key_issues')) { showAlert('Please detail Key Issues / Decisions / Risks to continue.'); return false; }
                        if (document.getElementById('requires_mgmt_attention').checked && isSummernoteEmpty('mgmt_attention_details')) {
                            showAlert('Please provide Management Attention Details.'); return false;
                        }
                    } else if (step === 2) {
                        if (isSummernoteEmpty('actual_tasks')) { showAlert('Please write a General Work Summary.'); return false; }
                    }
                } else {
                    if (step <= tasksCount) {
                        const idx = step - 1;
                        const resultVal = document.getElementById('result_' + idx)?.value;
                        const progressVal = document.getElementById('progress_percent_' + idx)?.value;
                        if (!resultVal) { showAlert('Please select a Result Status for this task.'); return false; }
                        if (progressVal === '') { showAlert('Please select a Progress % for this task.'); return false; }
                        if (isSummernoteEmpty('what_changed_' + idx)) { showAlert('Please fill in What Changed for this task.'); return false; }
                        if (resultVal === 'Blocked') {
                            const blockedBy = document.getElementById('blocked_by_' + idx)?.value;
                            const blockingItem = document.getElementById('blocking_item_' + idx)?.value.trim();
                            if (!blockedBy || !blockingItem) { showAlert('Please fill in Blocked By and Blocking Item.'); return false; }
                        }
                        const reqInd = document.getElementById('evidence_req_indicator_' + idx);
                        if (reqInd && reqInd.value === '1' && isSummernoteEmpty('evidence_' + idx)) {
                            showAlert('Evidence link or reference is required for this task state.'); return false;
                        }
                    } else if (step === tasksCount + 1) {
                        if (isSummernoteEmpty('actual_tasks')) { showAlert('Please write a General Work Summary.'); return false; }
                    }
                }
                return true;
            }

            function nextStep() {
                if (validateStep(currentStep) && currentStep < totalSteps) {
                    currentStep++; showStep(currentStep);
                }
            }
            function prevStep() {
                if (currentStep > 1) { currentStep--; showStep(currentStep); }
            }

            showStep(1);

            // ── Alert Modal ─────────────────────────────────
            window.showAlert = function (msg) {
                const m = document.getElementById('custom-alert-modal');
                const b = document.getElementById('alert-modal-content');
                document.getElementById('custom-alert-message').textContent = msg;
                m.classList.remove('hidden');
                setTimeout(() => b.classList.add('open'), 10);
            };
            window.closeAlert = function () {
                const m = document.getElementById('custom-alert-modal');
                const b = document.getElementById('alert-modal-content');
                b.classList.remove('open');
                setTimeout(() => m.classList.add('hidden'), 200);
            };

            // ── Timer ───────────────────────────────────────
            const startTime = new Date("{{ $activeLog->start_time }}").getTime();
            function updateTimer() {
                const diff = Date.now() - startTime;
                if (diff < 0) return;
                const h = Math.floor(diff / 3600000);
                const m = Math.floor((diff % 3600000) / 60000);
                const s = Math.floor((diff % 60000) / 1000);
                document.getElementById('timer').textContent =
                    String(h).padStart(2, '0') + ':' + String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
            }
            setInterval(updateTimer, 1000); updateTimer();

            // ── Form Submit ─────────────────────────────────
            document.getElementById('checkout-form')?.addEventListener('submit', function () {
                $('.wysiwyg-editor').each(function () { $(this).val($(this).summernote('code')); });
                const atEl = $('#actual_tasks');
                if (atEl.length) document.getElementById('actual_tasks_encoded').value = btoa(unescape(encodeURIComponent(atEl.summernote('code'))));
                const chEl = $('#challenges');
                if (chEl.length) document.getElementById('challenges_encoded').value = btoa(unescape(encodeURIComponent(chEl.summernote('code'))));
            });

            function toggleMgmtDetails(isChecked) {
                const c = document.getElementById('mgmt_details_container');
                if (isChecked) {
                    c.classList.remove('hidden');
                    // Ensure Summernote initializes properly when unhidden
                    $('#mgmt_attention_details').summernote({
                        placeholder: 'What needs immediate attention/approval?',
                        tabsize: 2,
                        height: 110,
                        toolbar: [
                            ['font', ['bold', 'underline', 'clear']],
                            ['para', ['ul', 'ol']],
                            ['insert', ['link']],
                            ['view', ['fullscreen', 'codeview']]
                        ]
                    });
                } else {
                    c.classList.add('hidden');
                }
            }

            function toggleTaskResultDetails(resultValue, index, taskId) {
                const bc = document.getElementById('blocker_container_' + index);
                const ri = document.getElementById('evidence_req_indicator_' + index);
                const rl = document.getElementById('evidence-req-label-' + index);
                if (resultValue === 'Blocked') {
                    bc.classList.remove('hidden');
                } else {
                    bc.classList.add('hidden');
                }
                if (resultValue === 'Partial') {
                    fetch('/team/work-logs/check-partial-status?task_id=' + taskId)
                        .then(r => r.json())
                        .then(data => {
                            const wd = document.getElementById('evidence_warning_' + index);
                            if (data.is_stale_partial) { wd?.classList.remove('hidden'); if(ri) ri.value = '1'; if(rl) rl.classList.remove('hidden'); }
                            else { wd?.classList.add('hidden'); if(ri) ri.value = '0'; if(rl) rl.classList.add('hidden'); }
                        });
                } else if (resultValue === 'Completed') {
                    if(ri) ri.value = '1'; if(rl) rl.classList.remove('hidden');
                } else {
                    if(ri) ri.value = '0'; if(rl) rl.classList.add('hidden');
                }
            }
        </script>

    @else
        {{-- ─── CHECK-IN PANEL ──────────────────────────────────── --}}

        {{-- Hero --}}
        <div class="checkin-banner p-7 relative z-10">
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <span class="pill pill-red text-[10px]">Ready to Start</span>
                    <h2 class="text-2xl font-extrabold text-white tracking-tight">Start Your Work Session</h2>
                    <p class="text-sm text-white/50">Select your projects and tasks, then check in to begin time tracking.</p>
                </div>
                <div class="flex-shrink-0 text-right hidden sm:block">
                    <p class="text-xs text-white/40 font-medium uppercase tracking-wider">Today</p>
                    <p class="text-lg font-bold text-white">{{ now()->format('D, M d') }}</p>
                </div>
            </div>
        </div>

        <div class="wl-card">
            <form id="checkin-form" action="{{ route('team.work-logs.check-in') }}" method="POST">
                @csrf

                <div class="p-8 space-y-7">

                    {{-- Projects --}}
                    <div>
                        <span class="section-label">Projects Working On <span class="req">*</span></span>
                        <p class="text-xs text-slate-400 mb-3">Select all projects linked to today's session.</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                            @foreach($projects as $project)
                                <label class="project-chip">
                                    <input type="checkbox" name="planned_projects[]" value="{{ $project->id }}" class="rounded" style="accent-color:var(--brand)">
                                    <span class="truncate">{{ $project->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    @if(auth()->user()->isProjectManager())
                        {{-- PM: Session Goal --}}
                        <div>
                            <span class="section-label">PM Session Goal <span class="req">*</span></span>
                            <p class="text-xs text-slate-400 mb-2">What will you close or move forward this session?</p>
                            <textarea id="session_goal" name="session_goal" class="wysiwyg-editor" placeholder="Define your session goal"></textarea>
                        </div>

                        {{-- PM: Task Assignments --}}
                        <div>
                            <span class="section-label">Tasks to Assign This Session</span>
                            <p class="text-xs text-slate-400 mb-3">Select tasks you plan to allocate to developers.</p>
                            <div class="space-y-2 max-h-52 overflow-y-auto thin-scroll p-3 bg-slate-50 rounded-xl border border-slate-200">
                                @forelse($assignedTasks as $task)
                                    <label class="task-chip-item cursor-pointer">
                                        <label class="flex items-center gap-2.5 text-xs text-slate-700 cursor-pointer w-full">
                                            <input type="checkbox" name="planned_assignments[]" value="{{ $task->id }}" class="rounded" style="accent-color:var(--brand)">
                                            <span class="font-semibold">{{ $task->title }}</span>
                                        </label>
                                    </label>
                                @empty
                                    <p class="text-xs text-slate-400 py-2">No open tasks available.</p>
                                @endforelse
                            </div>
                            <label class="flex items-center gap-2 mt-3 cursor-pointer select-none">
                                <input id="no_assignments_planned" name="no_assignments_planned" type="checkbox" value="1" class="h-4 w-4 rounded" style="accent-color:var(--brand)">
                                <span class="text-xs font-semibold text-slate-500">No task assignments planned this session.</span>
                            </label>
                        </div>

                    @else
                        {{-- Dev: Focus Tasks --}}
                        <div>
                            <span class="section-label">Focus Tasks for This Session <span class="req">*</span></span>
                            <p class="text-xs text-slate-400 mb-3">Select a project above to filter your tasks.</p>
                            <div class="space-y-2 max-h-60 overflow-y-auto thin-scroll p-3 bg-slate-50 rounded-xl border border-slate-200" id="checkin-tasks-container">
                                @forelse($assignedTasks as $task)
                                    @php
                                        $isMine = ($task->assigned_to == auth()->id());
                                        $assigneeText = $isMine ? 'Assigned to You' : ($task->assignee ? $task->assignee->name : 'Unassigned');
                                        $badgeStyle = $isMine ? 'background:#fff0f0;color:var(--brand);border:1px solid #ffd5d5' : 'background:#f1f5f9;color:#64748b;border:1px solid #e2e8f0';
                                    @endphp
                                    <div class="task-checkbox-item task-chip-item hidden" data-project-id="{{ $task->project_id }}">
                                        <label class="flex items-center gap-2.5 text-xs text-slate-700 cursor-pointer w-full select-none">
                                            <input type="checkbox" name="planned_tasks[]" value="{{ $task->title }}" class="rounded flex-shrink-0" style="accent-color:var(--brand)">
                                            <div>
                                                <p class="font-bold text-slate-800">{{ $task->title }}</p>
                                                <p class="text-[10px] text-slate-400 mt-0.5">{{ $task->project->name ?? 'No Project' }}</p>
                                            </div>
                                        </label>
                                        <span class="pill flex-shrink-0 text-[10px]" style="{{ $badgeStyle }}">{{ $assigneeText }}</span>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-400 py-2" id="no-tasks-placeholder">No open tasks available.</p>
                                @endforelse
                                <p class="text-xs text-slate-400 py-2" id="select-project-placeholder">Please select a project above to view tasks.</p>
                                <p class="text-xs text-slate-400 py-2 hidden" id="no-filtered-tasks-placeholder">No open tasks found for selected projects.</p>
                            </div>
                        </div>

                        {{-- Dev: Resources --}}
                        <div>
                            <span class="section-label">Resources Needed <span class="text-slate-300">(Optional)</span></span>
                            <textarea id="resources_needed" name="resources_needed"
                                      class="wysiwyg-editor"
                                      placeholder="Any documents, accesses, or permissions required..."></textarea>
                        </div>
                    @endif

                    <div class="pt-2 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="btn-primary text-base px-8 py-3.5">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Check In & Start Timer
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
        <script>
            $(function () {
                // Initialize Summernote for checkin editors
                $('.wysiwyg-editor').each(function () {
                    const ph = $(this).attr('placeholder') || 'Type here...';
                    $(this).summernote({
                        placeholder: ph,
                        tabsize: 2,
                        height: 110,
                        toolbar: [
                            ['font', ['bold', 'underline', 'clear']],
                            ['para', ['ul', 'ol']],
                            ['insert', ['link']],
                            ['view', ['fullscreen', 'codeview']]
                        ]
                    });
                });

                // Sync Summernote code back to textarea on submit
                document.getElementById('checkin-form')?.addEventListener('submit', function () {
                    $('.wysiwyg-editor').each(function () {
                        $(this).val($(this).summernote('code'));
                    });
                });

                function filterCheckinTasks() {
                    const checked = [];
                    $('input[name="planned_projects[]"]:checked').each(function () { checked.push($(this).val().toString()); });
                    const container = $('#checkin-tasks-container');
                    const items = container.find('.task-checkbox-item');
                    if (checked.length === 0) {
                        items.addClass('hidden');
                        $('#select-project-placeholder').removeClass('hidden');
                        $('#no-filtered-tasks-placeholder').addClass('hidden');
                    } else {
                        $('#select-project-placeholder').addClass('hidden');
                        let visible = 0;
                        items.each(function () {
                            if (checked.includes($(this).attr('data-project-id'))) {
                                $(this).removeClass('hidden'); visible++;
                            } else {
                                $(this).addClass('hidden');
                                $(this).find('input[type="checkbox"]').prop('checked', false);
                            }
                        });
                        if (visible === 0) $('#no-filtered-tasks-placeholder').removeClass('hidden');
                        else $('#no-filtered-tasks-placeholder').addClass('hidden');
                    }
                }
                $('input[name="planned_projects[]"]').on('change', filterCheckinTasks);
                filterCheckinTasks();
            });
        </script>
    @endif

    {{-- ─── Completed Sessions History ─────────────────────────── --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-extrabold text-slate-900">Completed Sessions</h2>
            <span class="pill pill-slate">{{ $history->total() }} sessions</span>
        </div>

        <div class="wl-card overflow-hidden">
            <table class="wl-table min-w-full">
                <thead>
                    <tr>
                        <th class="text-left pl-6">Date</th>
                        <th class="text-left">Duration</th>
                        <th class="text-left">Planned Focus</th>
                        <th class="text-left">Summary</th>
                        <th class="text-right pr-6"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $log)
                        <tr onclick="window.location='{{ route('team.work-logs.show', $log) }}'"
                            style="cursor:pointer;">
                            <td class="pl-6">
                                <p class="font-bold text-slate-800">{{ $log->start_time->format('M d, Y') }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">{{ $log->start_time->format('H:i') }} – {{ $log->end_time?->format('H:i') ?? '—' }}</p>
                            </td>
                            <td>
                                <span class="pill pill-slate">{{ $log->duration_minutes }} min</span>
                            </td>
                            <td class="max-w-[180px]">
                                <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">{!! strip_tags($log->planned_tasks ?? '—') !!}</p>
                            </td>
                            <td class="max-w-[220px]">
                                <div class="text-xs text-slate-600 line-clamp-3 leading-relaxed">{!! strip_tags($log->actual_tasks ?? '—') !!}</div>
                            </td>
                            <td class="pr-6 text-right">
                                <span class="pill pill-navy whitespace-nowrap">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    View
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-14">
                                <svg class="w-10 h-10 text-slate-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <p class="text-sm font-semibold text-slate-400">No completed sessions yet</p>
                                <p class="text-xs text-slate-300 mt-1">Your completed sessions will appear here.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($history->hasPages())
            <div class="mt-4">{{ $history->links() }}</div>
        @endif
    </div>

</div>

{{-- ─── Alert Modal ──────────────────────────────────────────── --}}
<div id="custom-alert-modal" class="alert-overlay hidden">
    <div class="alert-box" id="alert-modal-content">
        <div class="flex flex-col items-center text-center gap-4">
            <div class="w-12 h-12 rounded-full bg-red-50 border border-red-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-brand-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-base font-extrabold text-slate-800">Attention Required</h3>
            <p id="custom-alert-message" class="text-sm text-slate-500 leading-relaxed"></p>
            <button type="button" onclick="closeAlert()" class="btn-primary w-full justify-center py-3">Got it</button>
        </div>
    </div>
</div>

@endsection
