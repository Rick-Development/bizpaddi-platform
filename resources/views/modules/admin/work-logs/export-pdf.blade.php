<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $periodLabel }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #1f2937;
            line-height: 1.5;
            font-size: 12px;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 12px;
        }
        .header-table {
            width: 100%;
            margin-bottom: 18px;
            border-bottom: 2px solid #FB4C4B;
            padding-bottom: 10px;
        }
        .logo-cell {
            width: 50%;
            vertical-align: top;
        }
        .title-cell {
            width: 50%;
            text-align: right;
            vertical-align: top;
        }
        .logo {
            max-height: 40px;
            margin-bottom: 4px;
        }
        .company-name {
            font-size: 19px;
            font-weight: bold;
            color: #191C47;
        }
        .report-title {
            font-size: 18px;
            font-weight: bold;
            color: #FB4C4B;
            text-transform: uppercase;
            margin: 0 0 3px 0;
        }
        .meta-info {
            font-size: 11px;
            color: #6b7280;
        }
        
        /* Watermark */
        .watermark-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            z-index: -1000;
            opacity: 0.08;
            width: 500px;
            text-align: center;
        }
        .watermark-img {
            width: 450px;
            height: auto;
        }
        .watermark-text {
            font-size: 60px;
            font-weight: bold;
            color: #191C47;
        }

        /* KPI Cards */
        .kpi-table {
            width: 100%;
            margin-bottom: 18px;
            border-collapse: separate;
            border-spacing: 6px 0;
        }
        .kpi-card {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 8px 10px;
            text-align: center;
        }
        .kpi-val {
            font-size: 18px;
            font-weight: bold;
            color: #191C47;
        }
        .kpi-lbl {
            font-size: 10px;
            text-transform: uppercase;
            color: #6b7280;
            letter-spacing: 0.05em;
            margin-top: 2px;
        }

        /* Performance Table */
        .perf-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .perf-table th {
            background-color: #191C47;
            color: #ffffff;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 6px 8px;
            text-align: left;
        }
        .perf-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }

        /* User Group Header */
        .user-header {
            background-color: #191C47;
            color: #ffffff;
            padding: 6px 10px;
            font-size: 13px;
            font-weight: bold;
            border-radius: 4px;
            margin-top: 15px;
            margin-bottom: 6px;
        }
        .user-stats-bar {
            background-color: #f3f4f6;
            border: 1px solid #e5e7eb;
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 11px;
            margin-bottom: 10px;
        }

        /* Logs Table */
        .logs-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .logs-table th {
            background-color: #f3f4f6;
            color: #374151;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 5px 7px;
            text-align: left;
            border-bottom: 2px solid #d1d5db;
        }
        .logs-table td {
            padding: 5px 7px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
            font-size: 11px;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-completed {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-in_progress {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-overdue {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .challenge-box {
            background-color: #fef2f2;
            border-left: 3px solid #ef4444;
            padding: 3px 6px;
            margin-top: 3px;
            font-size: 10px;
            color: #991b1b;
        }

        .project-tag {
            display: inline-block;
            background-color: #e0e7ff;
            color: #3730a3;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            margin-right: 3px;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
        }
    </style>
</head>
<body>

    <!-- Watermark -->
    <div class="watermark-container">
        @php
            $watermarkPath = \Modules\Settings\Models\Setting::get('watermark_logo');
            $fullPath = $watermarkPath ? storage_path('app/public/' . $watermarkPath) : null;
        @endphp
        @if($fullPath && file_exists($fullPath))
            <img src="{{ $fullPath }}" class="watermark-img">
        @else
            <div class="watermark-text">RICK DEVELOPMENT</div>
        @endif
    </div>

    <div class="container">
        <!-- Header -->
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    @php
                        $logoPath = \Modules\Settings\Models\Setting::get('company_logo');
                        $fullLogoPath = $logoPath ? storage_path('app/public/' . $logoPath) : null;
                    @endphp
                    @if($fullLogoPath && file_exists($fullLogoPath))
                        <img src="{{ $fullLogoPath }}" class="logo">
                    @else
                        <div class="company-name">{{ \Modules\Settings\Models\Setting::get('site_name', 'Rick Development LTD') }}</div>
                    @endif
                    <div class="meta-info">Official Work Session Analytics & Project Workload Performance</div>
                </td>
                <td class="title-cell">
                    <div class="report-title">{{ $periodLabel }}</div>
                    <div class="meta-info">
                        <strong>Period:</strong> {{ $dateLabel }}<br>
                        @if($userFilter)
                            <strong>Team Member:</strong> {{ $userFilter->name }}<br>
                        @else
                            <strong>Scope:</strong> All Team Members (General Report)<br>
                        @endif
                        <strong>Generated:</strong> {{ now()->format('M d, Y H:i:s') }}
                    </div>
                </td>
            </tr>
        </table>

        <!-- Executive Summary KPI Cards -->
        @if($includeKpis)
        <table class="kpi-table">
            <tr>
                <td class="kpi-card" style="width: 20%;">
                    <div class="kpi-val">{{ $totalSessions }}</div>
                    <div class="kpi-lbl">Work Sessions</div>
                </td>
                <td class="kpi-card" style="width: 20%;">
                    <div class="kpi-val">{{ $totalHours }}h {{ $remainingMins }}m</div>
                    <div class="kpi-lbl">Time Worked</div>
                </td>
                <td class="kpi-card" style="width: 20%;">
                    <div class="kpi-val" style="color: #2563eb;">{{ $overallTotalTasks }}</div>
                    <div class="kpi-lbl">Assigned Tasks</div>
                </td>
                <td class="kpi-card" style="width: 20%;">
                    <div class="kpi-val" style="color: #059669;">{{ $overallCompletedTasks }}</div>
                    <div class="kpi-lbl">Tasks Completed</div>
                </td>
                <td class="kpi-card" style="width: 20%;">
                    <div class="kpi-val" style="color: #dc2626;">{{ $overallOverdueTasks }}</div>
                    <div class="kpi-lbl">Overdue Tasks</div>
                </td>
            </tr>
        </table>
        @endif

        <!-- Team Member Performance Overview Table -->
        @if($includePerformance && !empty($userTaskMetrics))
        <div style="margin-bottom: 18px;">
            <div style="font-size: 10px; font-weight: bold; color: #191C47; text-transform: uppercase; margin-bottom: 5px; letter-spacing: 0.05em;">
                Team Workload & Assigned Projects Overview
            </div>
            <table class="perf-table">
                <thead>
                    <tr>
                        <th style="width: 24%;">Team Member</th>
                        <th style="width: 32%;">Assigned Projects</th>
                        <th style="width: 11%;" class="text-center">Work Hours</th>
                        <th style="width: 11%;" class="text-center">Tasks Total</th>
                        <th style="width: 11%;" class="text-center">Completed</th>
                        <th style="width: 11%;" class="text-center">Overdue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($userTaskMetrics as $metric)
                        <tr>
                            <td><strong>{{ $metric['user']->name }}</strong></td>
                            <td>
                                @if(!empty($metric['projects']))
                                    @foreach($metric['projects'] as $projName)
                                        <span class="project-tag">{{ $projName }}</span>
                                    @endforeach
                                @else
                                    <span style="color: #9ca3af; italic;">No assigned projects</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $metric['total_hours'] }}h {{ $metric['total_mins'] }}m</td>
                            <td class="text-center"><strong>{{ $metric['total_tasks'] }}</strong></td>
                            <td class="text-center" style="color: #059669; font-weight: bold;">{{ $metric['completed_tasks'] }}</td>
                            <td class="text-center" style="color: {{ $metric['overdue_tasks'] > 0 ? '#dc2626' : '#6b7280' }}; font-weight: bold;">
                                {{ $metric['overdue_tasks'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Detailed Work Session Logs & Task Breakdown Per User -->
        @foreach($targetUsers as $user)
            @php
                $metric = $userTaskMetrics[$user->id] ?? null;
                $userLogs = $metric['logs'] ?? collect();
                $userTasks = $metric['tasks'] ?? collect();
            @endphp

            <div class="user-header">
                {{ $user->name }} — Assigned Projects, Tasks & Work Session Logs
            </div>

            @if($includePerformance && $metric)
            <div class="user-stats-bar">
                <strong>Assigned Projects:</strong> 
                @if(!empty($metric['projects']))
                    @foreach($metric['projects'] as $projName)
                        <span class="project-tag">{{ $projName }}</span>
                    @endforeach
                @else
                    <span style="color: #6b7280;">None</span>
                @endif
                &nbsp;|&nbsp;
                <strong>Tasks Total:</strong> {{ $metric['total_tasks'] }} &nbsp;|&nbsp;
                <strong>Completed:</strong> <span style="color: #059669;">{{ $metric['completed_tasks'] }}</span> &nbsp;|&nbsp;
                <strong>Pending:</strong> <span style="color: #d97706;">{{ $metric['pending_tasks'] }}</span> &nbsp;|&nbsp;
                <strong>Overdue:</strong> <span style="color: #dc2626;">{{ $metric['overdue_tasks'] }}</span> &nbsp;|&nbsp;
                <strong>Rate:</strong> <span style="color: #2563eb; font-weight: bold;">{{ $metric['completion_rate'] }}%</span>
            </div>
            @endif

            <!-- Tasks Breakdown Table for User — shows only Pending & Overdue tasks -->
            @if($includePerformance)
            <div style="margin-bottom: 10px;">
                <div style="font-size: 10px; font-weight: bold; color: #4b5563; text-transform: uppercase; margin-bottom: 3px;">
                    Pending &amp; Overdue Tasks List:
                    <span style="font-weight: normal; color: #9ca3af; text-transform: none;">(Completed tasks are excluded from this list)</span>
                </div>
                @if($userTasks->isNotEmpty())
                <table class="logs-table" style="margin-bottom: 10px;">
                    <thead>
                        <tr>
                            <th style="width: 40%;">Task Title</th>
                            <th style="width: 30%;">Project</th>
                            <th style="width: 15%;">Due Date</th>
                            <th style="width: 15%;">Task Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($userTasks as $task)
                            @php
                                $isOverdue = !in_array($task->status, ['done', 'completed']) && $task->due_date && $task->due_date->isPast();
                            @endphp
                            <tr>
                                <td><strong>{{ $task->title }}</strong></td>
                                <td>
                                    @if($task->project)
                                        <span class="project-tag">{{ $task->project->title }}</span>
                                    @else
                                        <span style="color: #9ca3af;">General</span>
                                    @endif
                                </td>
                                <td>{{ $task->due_date ? $task->due_date->format('M d, Y') : 'No Due Date' }}</td>
                                <td>
                                    @if($isOverdue)
                                        <span class="status-badge status-overdue">Overdue</span>
                                    @else
                                        <span class="status-badge status-in_progress">{{ ucfirst(str_replace('_', ' ', $task->status ?? 'pending')) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div style="padding: 8px 12px; background: #f0fdf4; border-left: 3px solid #22c55e; border-radius: 3px; font-size: 10px; color: #16a34a; margin-bottom: 10px;">
                    ✓ All assigned tasks have been completed. No pending or overdue tasks.
                </div>
                @endif
            </div>
            @endif


            <!-- Work Session Check-in Logs -->
            @if($userLogs->isEmpty())
                <div style="text-align: center; padding: 12px; color: #6b7280; background-color: #f9fafb; border-radius: 4px; font-style: italic; margin-bottom: 12px;">
                    No work session check-ins recorded for {{ $user->name }} during this period.
                </div>
            @else
                <table class="logs-table">
                    <thead>
                        <tr>
                            <th style="width: 18%;">Date & Time</th>
                            <th style="width: 12%;">Duration</th>
                            @if($includePlanned)<th style="width: 32%;">Planned Tasks</th>@endif
                            @if($includeAccomplishments)<th style="width: 28%;">Accomplishments</th>@endif
                            <th style="width: 10%;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($userLogs as $log)
                            <tr>
                                <td>
                                    <strong>{{ $log->start_time ? $log->start_time->format('M d, Y') : 'N/A' }}</strong><br>
                                    <span style="font-size: 10px; color: #6b7280;">
                                        {{ $log->start_time ? $log->start_time->format('H:i') : '' }} - 
                                        {{ $log->end_time ? $log->end_time->format('H:i') : 'In Progress' }}
                                    </span>
                                </td>
                                <td>
                                    @if($log->duration_minutes)
                                        {{ floor($log->duration_minutes / 60) }}h {{ $log->duration_minutes % 60 }}m
                                    @elseif($log->start_time)
                                        @php
                                            $liveMins = $log->start_time->diffInMinutes(now());
                                        @endphp
                                        <span style="color: #d97706; font-weight: bold;">
                                            Active ({{ floor($liveMins / 60) }}h {{ $liveMins % 60 }}m)
                                        </span>
                                    @else
                                        <span style="color: #d97706;">Running</span>
                                    @endif
                                </td>
                                @if($includePlanned)
                                <td>
                                    {!! strip_tags($log->planned_tasks) !!}
                                    @if($log->resources_needed)
                                        <div style="font-size: 10px; color: #4b5563; margin-top: 2px;">
                                            <strong>Resources:</strong> {!! strip_tags($log->resources_needed) !!}
                                        </div>
                                    @endif
                                </td>
                                @endif
                                @if($includeAccomplishments)
                                <td>
                                    @if($log->actual_tasks)
                                        {{ $log->actual_tasks }}
                                    @else
                                        <span style="color: #9ca3af; italic;">Pending completion...</span>
                                    @endif

                                    @if($includeChallenges && $log->challenges)
                                        <div class="challenge-box">
                                            <strong>Challenge:</strong> {{ $log->challenges }}
                                        </div>
                                    @endif
                                </td>
                                @endif
                                <td>
                                    @if($log->status === 'completed')
                                        <span class="status-badge status-completed">Completed</span>
                                    @else
                                        <span class="status-badge status-in_progress">Working</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endforeach

        <div class="footer">
            Confidential Internal Performance & Project Workload Report • Generated by Rick Development LTD Platform Management System
        </div>
    </div>
</body>
</html>
