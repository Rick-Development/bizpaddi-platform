<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Official Task Audit & Workload Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #1f2937;
            line-height: 1.5;
            font-size: 10px;
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
            font-size: 16px;
            font-weight: bold;
            color: #191C47;
        }
        .report-title {
            font-size: 15px;
            font-weight: bold;
            color: #FB4C4B;
            text-transform: uppercase;
            margin: 0 0 3px 0;
        }
        .meta-info {
            font-size: 9px;
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
            font-size: 15px;
            font-weight: bold;
            color: #191C47;
        }
        .kpi-lbl {
            font-size: 8px;
            text-transform: uppercase;
            color: #6b7280;
            letter-spacing: 0.05em;
            margin-top: 2px;
        }

        /* Project Group Header */
        .project-header {
            background-color: #191C47;
            color: #ffffff;
            padding: 6px 10px;
            font-size: 11px;
            font-weight: bold;
            border-radius: 4px;
            margin-top: 15px;
            margin-bottom: 6px;
        }

        /* Tasks Table */
        .tasks-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .tasks-table th {
            background-color: #f3f4f6;
            color: #374151;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 5px 7px;
            text-align: left;
            border-bottom: 2px solid #d1d5db;
        }
        .tasks-table td {
            padding: 5px 7px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
            font-size: 9px;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 4px;
            font-size: 8px;
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
        .status-todo {
            background-color: #f3f4f6;
            color: #4b5563;
        }

        .priority-badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .prio-urgent { background-color: #fee2e2; color: #991b1b; }
        .prio-high { background-color: #ffedd5; color: #9a3412; }
        .prio-medium { background-color: #e0e7ff; color: #3730a3; }
        .prio-low { background-color: #f3f4f6; color: #4b5563; }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 8px;
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
                    <div class="meta-info">Official Task Management & Workload Performance Audit</div>
                </td>
                <td class="title-cell">
                    <div class="report-title">Standalone Task Audit Report</div>
                    <div class="meta-info">
                        @if($projectFilter) <strong>Project:</strong> {{ $projectFilter->title }}<br> @endif
                        @if($userFilter) <strong>Assignee:</strong> {{ $userFilter->name }}<br> @endif
                        <strong>Generated:</strong> {{ now()->format('M d, Y H:i:s') }}
                    </div>
                </td>
            </tr>
        </table>

        <!-- Summary KPI Cards -->
        <table class="kpi-table">
            <tr>
                <td class="kpi-card" style="width: 20%;">
                    <div class="kpi-val">{{ $totalTasks }}</div>
                    <div class="kpi-lbl">Total Tasks</div>
                </td>
                <td class="kpi-card" style="width: 20%;">
                    <div class="kpi-val" style="color: #059669;">{{ $completedTasks }}</div>
                    <div class="kpi-lbl">Completed</div>
                </td>
                <td class="kpi-card" style="width: 20%;">
                    <div class="kpi-val" style="color: #d97706;">{{ $inProgressTasks }}</div>
                    <div class="kpi-lbl">In Progress / Review</div>
                </td>
                <td class="kpi-card" style="width: 20%;">
                    <div class="kpi-val" style="color: #dc2626;">{{ $overdueTasks }}</div>
                    <div class="kpi-lbl">Overdue</div>
                </td>
                <td class="kpi-card" style="width: 20%;">
                    <div class="kpi-val" style="color: #2563eb;">{{ $completionRate }}%</div>
                    <div class="kpi-lbl">Completion Rate</div>
                </td>
            </tr>
        </table>

        <!-- Grouped Tasks -->
        @if($tasks->isEmpty())
            <div style="text-align: center; padding: 30px; color: #6b7280; background-color: #f9fafb; border-radius: 4px; font-style: italic;">
                No tasks match the selected export criteria.
            </div>
        @else
            @foreach($groupedTasks as $projectName => $projectTasks)
                <div class="project-header">
                    📁 Project: {{ $projectName }} ({{ $projectTasks->count() }} Tasks)
                </div>

                <table class="tasks-table">
                    <thead>
                        <tr>
                            <th style="width: 35%;">Task Title</th>
                            <th style="width: 25%;">Assigned To</th>
                            <th style="width: 12%;">Priority</th>
                            <th style="width: 13%;">Due Date</th>
                            <th style="width: 15%;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projectTasks as $task)
                            @php
                                $isOverdue = !in_array($task->status, ['completed', 'done']) && $task->due_date && $task->due_date->isPast();
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $task->title }}</strong>
                                    @if($task->description)
                                        <div style="font-size: 8px; color: #6b7280; margin-top: 2px;">{{ Str::limit(strip_tags($task->description), 80) }}</div>
                                    @endif
                                </td>
                                <td>{{ $task->assignee->name ?? 'Unassigned' }}</td>
                                <td>
                                    <span class="priority-badge prio-{{ strtolower($task->priority ?? 'medium') }}">
                                        {{ ucfirst($task->priority ?? 'Medium') }}
                                    </span>
                                </td>
                                <td>
                                    {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No Due Date' }}
                                </td>
                                <td>
                                    @if(in_array($task->status, ['completed', 'done']))
                                        <span class="status-badge status-completed">Completed</span>
                                    @elseif($isOverdue)
                                        <span class="status-badge status-overdue">Overdue</span>
                                    @elseif($task->status === 'in_progress')
                                        <span class="status-badge status-in_progress">In Progress</span>
                                    @else
                                        <span class="status-badge status-todo">{{ ucfirst($task->status ?? 'Todo') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        @endif

        <div class="footer">
            Confidential Internal Task Audit Report • Generated by Rick Development LTD Platform Management System
        </div>
    </div>
</body>
</html>
