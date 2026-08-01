<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #FB4C4B 0%, #191C47 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header img {
            max-width: 180px;
            height: auto;
            margin-bottom: 15px;
            background: white;
            padding: 10px;
            border-radius: 8px;
        }
        .header h1 {
            margin: 10px 0 5px 0;
            font-size: 24px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0;
            opacity: 0.95;
            font-size: 14px;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #FB4C4B;
        }
        .section h2 {
            color: #191C47;
            font-size: 18px;
            margin-top: 0;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        .section h2::before {
            content: '';
            display: inline-block;
            width: 4px;
            height: 20px;
            background: #FB4C4B;
            margin-right: 10px;
        }
        .progress-bar {
            background: #e9ecef;
            height: 30px;
            border-radius: 15px;
            overflow: hidden;
            margin: 10px 0;
        }
        .progress-fill {
            background: linear-gradient(90deg, #FB4C4B 0%, #191C47 100%);
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }
        .task-item, .milestone-item, .approval-item {
            background: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .task-item {
            border-left: 4px solid #FB4C4B;
        }
        .milestone-item {
            border-left: 4px solid #28a745;
        }
        .approval-item {
            border-left: 4px solid #ffc107;
        }
        .task-item strong, .milestone-item strong, .approval-item strong {
            display: block;
            color: #191C47;
            font-size: 15px;
            margin-bottom: 5px;
        }
        .task-item small, .milestone-item small, .approval-item small {
            color: #6c757d;
            font-size: 13px;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 8px;
        }
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }
        .cta-button {
            display: inline-block;
            background: #FB4C4B;
            color: white;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: bold;
            transition: background 0.3s;
        }
        .cta-button:hover {
            background: #e03d3c;
        }
        .stats-row {
            display: table;
            width: 100%;
            margin: 15px 0;
        }
        .stat-box {
            display: table-cell;
            text-align: center;
            padding: 15px;
            background: white;
            border-radius: 6px;
        }
        .stat-value {
            font-size: 28px;
            font-weight: bold;
            color: #FB4C4B;
        }
        .stat-label {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }
        .footer {
            text-align: center;
            padding: 20px 30px;
            background: #f8f9fa;
            color: #6c757d;
            font-size: 13px;
            border-top: 3px solid #FB4C4B;
        }
        .footer a {
            color: #FB4C4B;
            text-decoration: none;
        }
        .empty-state {
            color: #6c757d;
            font-style: italic;
            text-align: center;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <img src="{{ asset('logo.png') }}" alt="{{ config('app.name') }} Logo">
            <h1>Weekly Project Report</h1>
            <p><strong>{{ $project->name }}</strong></p>
            <p>{{ $reportData['weekRange']['start'] }} - {{ $reportData['weekRange']['end'] }}</p>
        </div>

        <div class="content">
            <div class="greeting">
                <p>Hello <strong>{{ $client->name }}</strong>,</p>
                <p>Here's your weekly progress update for <strong>{{ $project->name }}</strong>.</p>
            </div>

            <!-- Overall Progress -->
            <div class="section">
                <h2>Project Overview</h2>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ $reportData['overallProgress'] }}%">
                        {{ $reportData['overallProgress'] }}% Complete
                    </div>
                </div>
                <p style="text-align: center; margin-top: 15px; color: #191C47;">
                    <strong>Status:</strong> <span style="color: #FB4C4B;">{{ ucfirst($project->status) }}</span>
                </p>
            </div>

            <!-- Tasks Completed Last Week -->
            <div class="section">
                <h2>✅ Tasks Completed Last Week</h2>
                @if($reportData['tasksCompletedLastWeek']->count() > 0)
                    @foreach($reportData['tasksCompletedLastWeek'] as $task)
                        <div class="task-item">
                            <strong>{{ $task->title }}</strong>
                            <small>Completed: {{ $task->completed_at->format('M d, Y') }}</small>
                        </div>
                    @endforeach
                @else
                    <p class="empty-state">No tasks completed last week.</p>
                @endif
            </div>

            <!-- Upcoming Milestones -->
            @if($reportData['upcomingMilestones']->count() > 0)
            <div class="section">
                <h2>🎯 Upcoming Milestones</h2>
                @foreach($reportData['upcomingMilestones'] as $milestone)
                    <div class="milestone-item">
                        <strong>{{ $milestone->title }}</strong>
                        <small>Due: {{ $milestone->due_date->format('M d, Y') }}</small>
                        <span class="badge badge-success">{{ $milestone->progress }}% Complete</span>
                    </div>
                @endforeach
            </div>
            @endif

            <!-- Pending Approvals -->
            @if($reportData['pendingApprovals']->count() > 0)
            <div class="section">
                <h2>⏳ Awaiting Your Approval</h2>
                <p style="margin-bottom: 15px;">The following tasks require your review:</p>
                @foreach($reportData['pendingApprovals'] as $task)
                    <div class="approval-item">
                        <strong>{{ $task->title }}</strong>
                        <small>{{ Str::limit($task->description, 100) }}</small>
                        <span class="badge badge-warning">Action Required</span>
                    </div>
                @endforeach
                <div style="text-align: center;">
                    <a href="{{ route('client.projects.show', $project) }}" class="cta-button">
                        Review & Approve Tasks →
                    </a>
                </div>
            </div>
            @endif
        </div>

        <div class="footer">
            <p><strong>{{ config('app.name') }}</strong></p>
            <p>This is an automated weekly report. For detailed project information, please visit your client portal.</p>
            <p style="margin-top: 10px;">
                <a href="{{ route('client.projects.show', $project) }}">View Full Project Dashboard</a>
            </p>
            <p style="margin-top: 15px; font-size: 12px;">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
