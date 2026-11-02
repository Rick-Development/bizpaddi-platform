<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Team Performance Report - {{ $periodLabel }}</title>
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
            border-bottom: 2px solid #0E5E4A;
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
            color: #10231C;
        }
        .report-title {
            font-size: 18px;
            font-weight: bold;
            color: #0E5E4A;
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
            color: #10231C;
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
            color: #10231C;
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
            background-color: #10231C;
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

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 4px;
            color: #10231C;
        }

        .user-header {
            background-color: #10231C;
            color: #ffffff;
            padding: 6px 10px;
            font-size: 13px;
            font-weight: bold;
            border-radius: 4px;
            margin-top: 20px;
            margin-bottom: 6px;
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
            <div class="watermark-text">BIZPADDI</div>
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
                        <div class="company-name">{{ config('app.name', 'Bizpaddi') }}</div>
                    @endif
                </td>
                <td class="title-cell">
                    <h1 class="report-title">Team Performance Report</h1>
                    <div class="meta-info">
                        Period: {{ $periodLabel }}<br>
                        Generated: {{ now()->format('F d, Y h:i A') }}
                    </div>
                </td>
            </tr>
        </table>

        <!-- KPI Cards Summary -->
        <table class="kpi-table">
            <tr>
                <td>
                    <div class="kpi-card">
                        <div class="kpi-val">{{ $totalMembers }}</div>
                        <div class="kpi-lbl">Team Members</div>
                    </div>
                </td>
                <td>
                    <div class="kpi-card">
                        <div class="kpi-val">{{ number_format($averageScore, 1) }}%</div>
                        <div class="kpi-lbl">Avg Performance Score</div>
                    </div>
                </td>
                <td>
                    <div class="kpi-card">
                        <div class="kpi-val">{{ $totalTasksCompleted }}</div>
                        <div class="kpi-lbl">Total Tasks Completed</div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Summary Table -->
        <div class="section-title">Overall Team Performance Summary</div>
        <table class="perf-table">
            <thead>
                <tr>
                    <th>Member</th>
                    <th>Score</th>
                    <th>Tasks (C/L/E)</th>
                    <th>Days Present</th>
                    <th>Total Work Time</th>
                    <th>Penalties</th>
                </tr>
            </thead>
            <tbody>
                @foreach($performances as $perf)
                <tr>
                    <td><strong>{{ $perf->user->name }}</strong></td>
                    <td style="color: {{ $perf->score >= 80 ? '#10b981' : ($perf->score < 50 ? '#ef4444' : '#10231C') }}; font-weight: bold;">
                        {{ number_format($perf->score, 1) }}%
                    </td>
                    <td>{{ $perf->tasks_completed }} / {{ $perf->tasks_late }} / {{ $perf->tasks_early }}</td>
                    <td>{{ $perf->metrics['attendance']['days_present'] }} / {{ $perf->metrics['attendance']['expected_days'] }}</td>
                    <td>{{ floor($perf->metrics['attendance']['total_minutes'] / 60) }}h {{ $perf->metrics['attendance']['total_minutes'] % 60 }}m</td>
                    <td style="color: #ef4444">{{ $perf->penalty_points > 0 ? "-{$perf->penalty_points} pts" : "0" }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Detailed Breakdown -->
        <div style="page-break-before: always;"></div>
        <div class="section-title">Individual Detailed Breakdown</div>
        
        @foreach($performances as $perf)
            <div class="user-header">
                {{ $perf->user->name }} &nbsp;|&nbsp; Score: {{ number_format($perf->score, 1) }}%
            </div>
            
            <table class="perf-table" style="margin-bottom: 8px;">
                <tr>
                    <td width="33%"><strong>Tasks Completed:</strong> {{ $perf->tasks_completed }}</td>
                    <td width="33%"><strong>Tasks Late:</strong> {{ $perf->tasks_late }}</td>
                    <td width="33%"><strong>Tasks Early:</strong> {{ $perf->tasks_early }}</td>
                </tr>
                <tr>
                    <td><strong>Expected Working Days:</strong> {{ $perf->metrics['attendance']['expected_days'] }}</td>
                    <td><strong>Days Present:</strong> {{ $perf->metrics['attendance']['days_present'] }}</td>
                    <td><strong>Days Absent:</strong> <span style="{{ $perf->metrics['attendance']['days_absent'] > 0 ? 'color:#ef4444; font-weight:bold;' : '' }}">{{ $perf->metrics['attendance']['days_absent'] }}</span></td>
                </tr>
                <tr>
                    <td><strong>Total Time Logged:</strong> {{ floor($perf->metrics['attendance']['total_minutes'] / 60) }}h {{ $perf->metrics['attendance']['total_minutes'] % 60 }}m</td>
                    <td><strong>Avg Daily Hours:</strong> {{ number_format($perf->metrics['attendance']['avg_daily_hours'], 1) }}h</td>
                    <td><strong>Bonus Points:</strong> <span style="color:#10b981; font-weight:bold;">+{{ $perf->bonus_points }}</span></td>
                </tr>
            </table>

            @if($perf->penalty_points > 0)
                <div style="background: #fef2f2; border-left: 3px solid #ef4444; padding: 10px; margin-bottom: 15px; font-size: 11px;">
                    <strong style="color: #991b1b;">Penalties Incurred (-{{ $perf->penalty_points }} pts):</strong><br>
                    @if($perf->metrics['violations']['missed_checkin_penalty'] > 0)
                        • Missed Check-ins (Absent Days): {{ $perf->metrics['attendance']['missed_checkins'] }} (-{{ $perf->metrics['violations']['missed_checkin_penalty'] }} pts)<br>
                    @endif
                    @if($perf->metrics['violations']['failed_checkout_penalty'] > 0)
                        • Failed Check-outs (>14h sessions): {{ $perf->metrics['violations']['failed_checkout_count'] }} (-{{ $perf->metrics['violations']['failed_checkout_penalty'] }} pts)<br>
                    @endif
                    @if($perf->metrics['violations']['unrealistic_penalty'] > 0)
                        • Unrealistic Logs (>14h but checked out): {{ $perf->metrics['violations']['unrealistic_count'] }} (-{{ $perf->metrics['violations']['unrealistic_penalty'] }} pts)<br>
                    @endif
                </div>
            @endif
        @endforeach

        <div class="footer">
            <p>Generated by Bizpaddi System &copy; {{ date('Y') }}</p>
        </div>
    </div>
</body>
</html>
