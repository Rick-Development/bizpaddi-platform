<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Weekly Progress Report - {{ $project->name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            background-color: #191C47; /* Secondary Brand Color */
            color: white;
            padding: 20px;
            margin-bottom: 30px;
        }
        .header-content {
            width: 100%;
        }
        .header-left {
            width: 60%;
            float: left;
        }
        .header-right {
            width: 40%;
            float: right;
            text-align: right;
        }
        .logo {
            max-height: 60px;
            margin-bottom: 10px;
            background: white;
            padding: 5px;
            border-radius: 4px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #FB4C4B; /* Primary Brand Color */
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0;
            font-size: 12px;
            opacity: 0.9;
        }
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
            clear: both;
        }
        .section h2 {
            color: #191C47;
            font-size: 16px;
            border-bottom: 2px solid #FB4C4B;
            padding-bottom: 5px;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        .stats-grid {
            width: 100%;
            margin-bottom: 20px;
            table-layout: fixed;
            border-collapse: separate;
            border-spacing: 10px;
        }
        .stat-item {
            background: #f8f9fa;
            text-align: center;
            padding: 15px;
            border: 1px solid #eee;
            border-bottom: 3px solid #FB4C4B;
        }
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #191C47;
        }
        .stat-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            margin-top: 5px;
        }
        .progress-container {
            background: #e9ecef;
            height: 20px;
            width: 100%;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 10px;
        }
        .progress-bar {
            background: #FB4C4B;
            height: 100%;
            text-align: center;
            line-height: 20px;
            color: white;
            font-size: 10px;
            font-weight: bold;
        }
        table.full-width {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th {
            text-align: left;
            padding: 8px;
            background-color: #f1f1f1;
            color: #191C47;
            font-size: 11px;
            text-transform: uppercase;
            border-bottom: 2px solid #ddd;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #eee;
            font-size: 12px;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            color: white;
        }
        .badge-success { background: #28a745; }
        .badge-warning { background: #ffc107; color: #856404; }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        
        /* Watermark Styles */
        .watermark-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1000;
            opacity: 0.08;
        }
        .watermark-img {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 70%;
            transform: translate(-50%, -50%) rotate(-45deg);
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    @if(\Modules\Settings\Models\Setting::get('watermark_logo'))
        <div class="watermark-container">
            <img src="{{ storage_path('app/public/' . \Modules\Settings\Models\Setting::get('watermark_logo')) }}" class="watermark-img">
        </div>
    @endif

    <div class="header">
        <div class="header-content">
            <div class="header-left">
                <img src="{{ public_path('logo.png') }}" class="logo">
                <h1>Weekly Report</h1>
                <p><strong>{{ $project->name }}</strong></p>
            </div>
            <div class="header-right">
                <p><strong>Client:</strong> {{ $client->name }}</p>
                <p><strong>Period:</strong> {{ $reportData['weekRange']['start'] }} - {{ $reportData['weekRange']['end'] }}</p>
                <p><strong>Generated:</strong> {{ now()->format('M d, Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Project Overview -->
    <div class="section">
        <h2>Project Status</h2>
        <table class="stats-grid">
            <tr>
                <td class="stat-item">
                    <div class="stat-value">{{ $reportData['overallProgress'] }}%</div>
                    <div class="stat-label">Completion</div>
                </td>
                <td class="stat-item">
                    <div class="stat-value">{{ $reportData['tasksCompletedLastWeek']->count() }}</div>
                    <div class="stat-label">Tasks Done (This Week)</div>
                </td>
                <td class="stat-item">
                    <div class="stat-value">{{ $reportData['pendingApprovals']->count() }}</div>
                    <div class="stat-label">Pending Approvals</div>
                </td>
            </tr>
        </table>

        <!-- Progress Bar -->
        <div class="progress-container">
            <div class="progress-bar" style="width: {{ $reportData['overallProgress'] }}%">
                {{ $reportData['overallProgress'] }}%
            </div>
        </div>
    </div>

    <!-- Usage of tables for better PDF layout compared to divs -->
    
    <!-- Tasks Completed Last Week -->
    <div class="section">
        <h2>Tasks Completed Last Week</h2>
        @if($reportData['tasksCompletedLastWeek']->count() > 0)
            <table class="full-width">
                <thead>
                    <tr>
                        <th width="70%">Task Name</th>
                        <th width="30%">Completed Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData['tasksCompletedLastWeek'] as $task)
                    <tr>
                        <td>
                            <strong>{{ $task->title }}</strong>
                            @if($task->priority === 'high')
                                <span class="badge badge-warning" style="margin-left:5px; font-size:8px;">HIGH</span>
                            @endif
                        </td>
                        <td>{{ $task->completed_at->format('M d, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="color: #666; font-style: italic; padding: 10px 0;">No tasks were completed during this period.</p>
        @endif
    </div>

    <!-- Upcoming Milestones -->
    @if($reportData['upcomingMilestones']->count() > 0)
    <div class="section">
        <h2>Upcoming Milestones</h2>
        <table class="full-width">
            <thead>
                <tr>
                    <th width="60%">Milestone</th>
                    <th width="20%">Due Date</th>
                    <th width="20%">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportData['upcomingMilestones'] as $milestone)
                <tr>
                    <td>{{ $milestone->title }}</td>
                    <td>{{ $milestone->due_date->format('M d, Y') }}</td>
                    <td>
                        <span class="badge badge-success">{{ $milestone->progress }}% Done</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Pending Approvals -->
    @if($reportData['pendingApprovals']->count() > 0)
    <div class="section">
        <h2 style="color: #d9534f; border-color: #d9534f;">Pending Approvals</h2>
        <table class="full-width">
            <thead>
                <tr>
                    <th width="70%">Task</th>
                    <th width="30%">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportData['pendingApprovals'] as $task)
                <tr>
                    <td>
                        <strong>{{ $task->title }}</strong><br>
                        <span style="font-size: 10px; color: #666;">{{ Str::limit($task->description, 80) }}</span>
                    </td>
                    <td><span class="badge badge-warning">Review Needed</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        Page <span class="page-number"></span>
        <br>
        {{ config('app.name') }} &copy; {{ date('Y') }} - Confidential Weekly Report
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $pdf->text(270, 820, "Page " . $PAGE_NUM . " of " . $PAGE_COUNT, $font, 8);
            ');
        }
    </script>
</body>
</html>
