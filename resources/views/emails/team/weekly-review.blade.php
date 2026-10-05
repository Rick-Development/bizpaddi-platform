<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Performance Review</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
            color: #1e293b;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            background-color: #f8fafc;
            padding: 30px 15px;
        }
        .container {
            max-width: 620px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #fc4c4b 100%);
            color: #ffffff;
            padding: 36px 28px;
            text-align: center;
        }
        .header-logo {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #ffffff;
            margin-bottom: 6px;
            text-transform: uppercase;
        }
        .header-title {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            color: #ffffff;
        }
        .header-subtitle {
            font-size: 13px;
            color: #e2e8f0;
            margin-top: 8px;
            font-weight: 500;
        }
        .content {
            padding: 32px 28px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }
        .intro-text {
            font-size: 14px;
            line-height: 1.6;
            color: #475569;
            margin-bottom: 24px;
        }
        .score-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 22px;
            text-align: center;
            margin-bottom: 26px;
        }
        .score-badge {
            display: inline-block;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 4px 12px;
            border-radius: 9999px;
            margin-bottom: 10px;
        }
        .badge-emerald { background-color: #d1fae5; color: #065f46; }
        .badge-blue { background-color: #dbeafe; color: #1e40af; }
        .badge-amber { background-color: #fef3c7; color: #92400e; }
        .badge-rose { background-color: #ffe4e6; color: #9f1239; }
        .score-number {
            font-size: 42px;
            font-weight: 900;
            color: #0f172a;
            line-height: 1;
            margin-bottom: 4px;
        }
        .score-caption {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
        }
        .kpi-grid {
            display: table;
            width: 100%;
            margin-top: 18px;
            border-top: 1px solid #e2e8f0;
            padding-top: 16px;
        }
        .kpi-col {
            display: table-cell;
            width: 25%;
            text-align: center;
            vertical-align: top;
        }
        .kpi-val {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
        }
        .kpi-lbl {
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-top: 2px;
        }
        .section-header {
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding-bottom: 8px;
            margin-top: 28px;
            margin-bottom: 12px;
            border-bottom: 2px solid;
        }
        .section-completed { color: #059669; border-color: #10b981; }
        .section-pending { color: #d97706; border-color: #f59e0b; }
        .section-overdue { color: #dc2626; border-color: #ef4444; }
        .section-worklogs { color: #2563eb; border-color: #3b82f6; }
        
        .task-list {
            margin: 0 0 16px 0;
            padding: 0;
            list-style: none;
        }
        .task-item {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 8px;
            font-size: 13px;
        }
        .task-item-title {
            font-weight: 700;
            color: #0f172a;
        }
        .task-item-meta {
            font-size: 11px;
            color: #64748b;
            margin-top: 4px;
        }
        .tag {
            display: inline-block;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
            margin-left: 4px;
        }
        .tag-early { background: #d1fae5; color: #065f46; }
        .tag-late { background: #fee2e2; color: #991b1b; }
        .tag-overdue { background: #fee2e2; color: #991b1b; }
        .tag-status { background: #e0e7ff; color: #3730a3; }

        .empty-state {
            font-size: 12px;
            color: #94a3b8;
            font-style: italic;
            padding: 8px 12px;
            background: #f8fafc;
            border-radius: 6px;
            margin-bottom: 14px;
        }
        .observations-box {
            background-color: #f1f5f9;
            border-left: 4px solid #fc4c4b;
            padding: 14px 16px;
            border-radius: 0 8px 8px 0;
            margin: 24px 0;
        }
        .observations-box h4 {
            margin: 0 0 6px 0;
            font-size: 13px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .observations-box ul {
            margin: 0;
            padding-left: 18px;
            font-size: 13px;
            color: #334155;
            line-height: 1.6;
        }
        .custom-note-box {
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 14px 16px;
            border-radius: 0 8px 8px 0;
            margin-bottom: 24px;
            font-size: 13px;
            color: #92400e;
            line-height: 1.5;
        }
        .button-wrapper {
            text-align: center;
            margin: 32px 0 16px 0;
        }
        .btn-portal {
            display: inline-block;
            background-color: #fc4c4b;
            color: #ffffff !important;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 10px;
            box-shadow: 0 4px 6px -1px rgba(252, 76, 75, 0.25);
        }
        .footer {
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 24px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            line-height: 1.6;
        }
        .footer a {
            color: #fc4c4b;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <div class="header-logo">Rick Development</div>
                <h1 class="header-title">Weekly Performance Review</h1>
                <div class="header-subtitle">
                    Evaluation Period: {{ $assessment['periodLabel'] ?? 'Past Week' }}
                </div>
            </div>

            <!-- Content -->
            <div class="content">
                <div class="greeting">Hello {{ $user->name }},</div>
                <p class="intro-text">
                    Here is your weekly performance assessment summary covering your completed achievements, ongoing assignments, and workload metrics for the week.
                </p>

                <!-- Score Card -->
                <div class="score-card">
                    <div class="score-badge badge-{{ $assessment['grade']['class'] ?? 'blue' }}">
                        {{ $assessment['grade']['badge'] ?? 'Performance Score' }}
                    </div>
                    <div class="score-number">{{ number_format($assessment['score'] ?? 0, 1) }}%</div>
                    <div class="score-caption">Weekly Overall Performance Score</div>

                    <div class="kpi-grid">
                        <div class="kpi-col">
                            <div class="kpi-val" style="color: #059669;">{{ count($assessment['completedTasks'] ?? []) }}</div>
                            <div class="kpi-lbl">Finished</div>
                        </div>
                        <div class="kpi-col">
                            <div class="kpi-val" style="color: #d97706;">{{ count($assessment['pendingTasks'] ?? []) }}</div>
                            <div class="kpi-lbl">Pending</div>
                        </div>
                        <div class="kpi-col">
                            <div class="kpi-val" style="color: {{ count($assessment['overdueTasks'] ?? []) > 0 ? '#dc2626' : '#64748b' }};">
                                {{ count($assessment['overdueTasks'] ?? []) }}
                            </div>
                            <div class="kpi-lbl">Overdue</div>
                        </div>
                        <div class="kpi-col">
                            <div class="kpi-val" style="color: #2563eb;">{{ $assessment['totalHours'] ?? 0 }}h</div>
                            <div class="kpi-lbl">Logged</div>
                        </div>
                    </div>
                </div>

                @if(!empty($customNote))
                <div class="custom-note-box">
                    <strong>Message from Management:</strong><br>
                    {{ $customNote }}
                </div>
                @endif

                <!-- 1. What You Completed This Week -->
                <div class="section-header section-completed">
                    ✓ Completed Tasks This Week ({{ count($assessment['completedTasks'] ?? []) }})
                </div>
                @if(count($assessment['completedTasks'] ?? []) > 0)
                <ul class="task-list">
                    @foreach($assessment['completedTasks'] as $task)
                    <li class="task-item">
                        <div class="task-item-title">
                            {{ $task->title }}
                            @if(($task->timing ?? '') === 'early')
                                <span class="tag tag-early">+Early Delivery</span>
                            @elseif(($task->timing ?? '') === 'late')
                                <span class="tag tag-late">Completed Late</span>
                            @endif
                        </div>
                        <div class="task-item-meta">
                            📁 Project: <strong>{{ $task->project->name ?? 'General Project' }}</strong>
                            @if($task->completed_at)
                                &bull; Completed: {{ $task->completed_at->format('M d, H:i') }}
                            @endif
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="empty-state">
                    No completed tasks were recorded during this week.
                </div>
                @endif

                <!-- 2. Unfinished & Pending Tasks ("The ones you couldn't finish up") -->
                <div class="section-header section-pending">
                    ⏳ Unfinished & Pending Tasks ({{ count($assessment['pendingTasks'] ?? []) }})
                </div>
                @if(count($assessment['pendingTasks'] ?? []) > 0)
                <p style="font-size: 12px; color: #64748b; margin: 0 0 10px 0;">
                    These assignments are currently carrying over into the upcoming week:
                </p>
                <ul class="task-list">
                    @foreach($assessment['pendingTasks'] as $task)
                    <li class="task-item">
                        <div class="task-item-title">
                            {{ $task->title }}
                            <span class="tag tag-status">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                            @if($task->is_overdue)
                                <span class="tag tag-overdue">⚠️ {{ $task->days_overdue }}d Overdue</span>
                            @endif
                        </div>
                        <div class="task-item-meta">
                            📁 Project: <strong>{{ $task->project->name ?? 'General' }}</strong>
                            @if($task->due_date)
                                &bull; Due: <span style="{{ $task->is_overdue ? 'color: #dc2626; font-weight: bold;' : '' }}">{{ $task->due_date->format('M d, Y') }}</span>
                            @endif
                            &bull; Priority: {{ ucfirst($task->priority ?? 'medium') }}
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="empty-state">
                    Great job! You have no unfinished or pending assignments carried over.
                </div>
                @endif

                <!-- 3. Overdue Tasks Alert (if any) -->
                @if(count($assessment['overdueTasks'] ?? []) > 0)
                <div class="section-header section-overdue">
                    🚨 Overdue Assignments Requiring Urgent Action ({{ count($assessment['overdueTasks']) }})
                </div>
                <div style="background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 12px; margin-bottom: 16px;">
                    <p style="font-size: 13px; color: #991b1b; margin: 0 0 8px 0; font-weight: 600;">
                        Please prioritize clearing the following overdue tasks to prevent further performance score penalties:
                    </p>
                    <ul class="task-list" style="margin: 0;">
                        @foreach($assessment['overdueTasks'] as $task)
                        <li style="font-size: 12px; color: #7f1d1d; padding: 4px 0;">
                            &bull; <strong>{{ $task->title }}</strong> ({{ $task->project->name ?? 'Project' }}) &mdash; Due: {{ $task->due_date ? $task->due_date->format('M d, Y') : 'Overdue' }} ({{ $task->days_overdue }} days past due)
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- 4. Work Sessions & Hours -->
                <div class="section-header section-worklogs">
                    ⏱️ Work Sessions & Attendance
                </div>
                <p style="font-size: 13px; color: #475569; margin: 0 0 10px 0;">
                    Total logged time: <strong>{{ $assessment['totalHours'] ?? 0 }} hours</strong> across <strong>{{ $assessment['daysPresent'] ?? 0 }} active days</strong>.
                </p>

                <!-- 5. Manager Assessment Pointers -->
                @if(!empty($assessment['summaryPoints']))
                <div class="observations-box">
                    <h4>Weekly Insights & Highlights</h4>
                    <ul>
                        @foreach($assessment['summaryPoints'] as $point)
                        <li>{{ $point }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Call To Action -->
                <div class="button-wrapper">
                    <a href="{{ $portalUrl }}" class="btn-portal">
                        Open Team Portal Dashboard →
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p style="margin: 0 0 6px 0;">
                    <strong>Rick Development Team Management System</strong>
                </p>
                <p style="margin: 0;">
                    This is an automated weekly performance report generated for your account.<br>
                    Need assistance or have questions regarding your assessment? Contact your Project Manager or Admin.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
