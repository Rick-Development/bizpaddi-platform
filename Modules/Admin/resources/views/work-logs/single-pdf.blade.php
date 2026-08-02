<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Work Session Log #{{ $workLog->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #1f2937;
            line-height: 1.6;
            font-size: 12px;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 18px;
        }
        .header-table {
            width: 100%;
            margin-bottom: 25px;
            border-bottom: 2px solid #FB4C4B;
            padding-bottom: 12px;
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
            max-height: 45px;
            margin-bottom: 4px;
        }
        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #191C47;
        }
        .report-title {
            font-size: 18px;
            font-weight: bold;
            color: #FB4C4B;
            text-transform: uppercase;
            margin: 0 0 4px 0;
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

        /* Session Summary Cards */
        .summary-box {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
        }
        .summary-table {
            width: 100%;
        }
        .summary-table td {
            vertical-align: top;
            padding: 4px 6px;
        }
        .label {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            color: #6b7280;
            letter-spacing: 0.05em;
        }
        .val {
            font-size: 13px;
            font-weight: bold;
            color: #191C47;
        }

        .section-card {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .section-header {
            font-size: 13px;
            font-weight: bold;
            color: #191C47;
            border-bottom: 1px solid #f3f4f6;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }

        .content-box {
            background-color: #f9fafb;
            padding: 10px 12px;
            border-radius: 6px;
            font-size: 11px;
            white-space: pre-wrap;
            line-height: 1.5;
            color: #374151;
        }

        .challenge-box {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            border-left: 4px solid #ef4444;
            padding: 10px 12px;
            border-radius: 6px;
            font-size: 11px;
            color: #991b1b;
            margin-top: 8px;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 9px;
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

        .project-tag {
            display: inline-block;
            background-color: #e0e7ff;
            color: #3730a3;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            margin-right: 4px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 9px;
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
                    <div class="meta-info">Work Session & Task Performance Record</div>
                </td>
                <td class="title-cell">
                    <div class="report-title">Session Log #{{ $workLog->id }}</div>
                    <div class="meta-info">
                        <strong>Date:</strong> {{ $workLog->start_time ? $workLog->start_time->format('F d, Y') : 'N/A' }}<br>
                        <strong>Status:</strong> 
                        @if($workLog->status === 'completed')
                            <span class="status-badge status-completed">Completed</span>
                        @else
                            <span class="status-badge status-in_progress">In Progress</span>
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <!-- Session Summary Box -->
        <div class="summary-box">
            <table class="summary-table">
                <tr>
                    <td style="width: 25%;">
                        <div class="label">Team Member</div>
                        <div class="val">{{ $workLog->user->name ?? 'Unknown User' }}</div>
                        <div style="font-size: 10px; color: #6b7280;">{{ $workLog->user->email ?? '' }}</div>
                    </td>
                    <td style="width: 25%;">
                        <div class="label">Check-In Time</div>
                        <div class="val">{{ $workLog->start_time ? $workLog->start_time->format('H:i:s') : 'N/A' }}</div>
                    </td>
                    <td style="width: 25%;">
                        <div class="label">Check-Out Time</div>
                        <div class="val">{{ $workLog->end_time ? $workLog->end_time->format('H:i:s') : 'Working...' }}</div>
                    </td>
                    <td style="width: 25%;">
                        <div class="label">Total Session Duration</div>
                        <div class="val" style="color: #FB4C4B;">
                            @if($workLog->duration_minutes)
                                {{ floor($workLog->duration_minutes / 60) }}h {{ $workLog->duration_minutes % 60 }}m
                            @else
                                Tracking...
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- User Assigned Projects & Task Metrics -->
        @if(isset($taskMetrics))
        <div class="section-card">
            <div class="section-header">Assigned Projects & Task Performance Summary</div>
            <div style="margin-bottom: 8px;">
                <span class="label">Assigned Projects:</span>
                @if(!empty($taskMetrics['projects']))
                    @foreach($taskMetrics['projects'] as $projName)
                        <span class="project-tag">{{ $projName }}</span>
                    @endforeach
                @else
                    <span style="color: #6b7280; font-size: 11px;">No assigned projects</span>
                @endif
            </div>

            <table style="width: 100%; border-top: 1px solid #f3f4f6; pt: 8px;">
                <tr>
                    <td><strong>Assigned Tasks:</strong> {{ $taskMetrics['total'] }}</td>
                    <td><strong>Completed:</strong> <span style="color: #059669;">{{ $taskMetrics['completed'] }}</span></td>
                    <td><strong>Pending:</strong> <span style="color: #d97706;">{{ $taskMetrics['pending'] }}</span></td>
                    <td><strong>Overdue:</strong> <span style="color: #dc2626;">{{ $taskMetrics['overdue'] }}</span></td>
                    <td><strong>Completion Rate:</strong> <span style="color: #2563eb; font-weight: bold;">{{ $taskMetrics['rate'] }}%</span></td>
                </tr>
            </table>
        </div>
        @endif

        <!-- Planned Tasks -->
        <div class="section-card">
            <div class="section-header">1. Planned Focus & Activities (Check-In)</div>
            <div class="content-box">
                {{ $workLog->planned_tasks }}
            </div>

            @if($workLog->resources_needed)
                <div style="margin-top: 10px;">
                    <div class="label">Resources / Access Requested</div>
                    <div style="font-size: 11px; color: #4b5563; margin-top: 2px;">
                        {{ $workLog->resources_needed }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Accomplishments -->
        <div class="section-card">
            <div class="section-header">2. Work Accomplished & Outcomes (Check-Out)</div>
            @if($workLog->actual_tasks)
                <div class="content-box" style="background-color: #f0fdf4; border: 1px solid #dcfce7; color: #166534;">
                    {{ $workLog->actual_tasks }}
                </div>
            @else
                <div style="color: #9ca3af; font-style: italic;">Check-out outcome pending completion.</div>
            @endif

            @if($workLog->challenges)
                <div style="margin-top: 10px;">
                    <div class="label" style="color: #991b1b;">Challenges & Blockers Encountered</div>
                    <div class="challenge-box">
                        {{ $workLog->challenges }}
                    </div>
                </div>
            @endif
        </div>

        <div class="footer">
            Official Work Session Record • Rick Development LTD Internal System • Exported {{ now()->format('M d, Y H:i:s') }}
        </div>
    </div>
</body>
</html>
