<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Leads Report — {{ $siteName }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            line-height: 1.5;
            font-size: 12px;
        }
        .container {
            width: 100%;
            padding: 24px 28px;
        }

        /* ─── Watermark ─────────────────────────────── */
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
            width: 520px;
            transform: translate(-50%, -50%) rotate(-45deg);
        }

        /* ─── Header ────────────────────────────────── */
        .header-table { width: 100%; margin-bottom: 28px; border-bottom: 2px solid #0E5E4A; padding-bottom: 18px; }
        .logo { max-height: 70px; max-width: 180px; }
        .company-address { font-size: 11px; color: #777; margin-top: 6px; line-height: 1.6; }
        .report-title { font-size: 26px; font-weight: bold; text-transform: uppercase; color: #0E5E4A; margin: 0 0 4px 0; }
        .report-subtitle { font-size: 12px; color: #555; }
        .report-meta { font-size: 10px; color: #999; margin-top: 6px; }

        /* ─── Summary strip ─────────────────────────── */
        .summary-table {
            width: 100%;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            background: #f9fafb;
            margin-bottom: 24px;
        }
        .summary-table td {
            padding: 14px 10px;
            text-align: center;
            border-right: 1px solid #e5e7eb;
        }
        .summary-table td:last-child { border-right: none; }
        .summary-value { font-size: 22px; font-weight: bold; color: #0E5E4A; display: block; }
        .summary-label { font-size: 8px; text-transform: uppercase; color: #6b7280; letter-spacing: 0.6px; display: block; margin-top: 2px; }

        /* ─── Table ─────────────────────────────────── */
        .leads-table { width: 100%; border-collapse: collapse; }
        .leads-table thead tr { background-color: #0E5E4A; }
        .leads-table thead th {
            padding: 9px 10px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #ffffff;
            font-weight: bold;
        }
        .leads-table tbody tr:nth-child(even) { background-color: #fff5f5; }
        .leads-table tbody tr:nth-child(odd)  { background-color: #ffffff; }
        .leads-table tbody td {
            padding: 8px 10px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 10px;
            vertical-align: top;
        }
        .lead-name    { font-weight: bold; color: #111; font-size: 10px; }
        .lead-company { font-size: 9px; color: #6b7280; margin-top: 1px; }

        /* Status badges — using simple text in dompdf */
        .badge { padding: 2px 7px; border-radius: 9999px; font-size: 8px; font-weight: bold; display: inline; }
        .badge-new       { background: #dbeafe; color: #1e40af; }
        .badge-contacted { background: #fef9c3; color: #854d0e; }
        .badge-nurturing { background: #ede9fe; color: #5b21b6; }
        .badge-qualified { background: #dcfce7; color: #15803d; }
        .badge-converted { background: #d1fae5; color: #065f46; }
        .badge-lost      { background: #fee2e2; color: #b91c1c; }

        /* ─── Footer ─────────────────────────────────── */
        .footer {
            margin-top: 24px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            font-size: 9px;
            color: #9ca3af;
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- Watermark --}}
    @if(\Modules\Settings\Models\Setting::get('watermark_logo'))
    <div class="watermark-container">
        <img src="{{ storage_path('app/public/' . \Modules\Settings\Models\Setting::get('watermark_logo')) }}" class="watermark-img">
    </div>
    @endif

    <div class="container">

        {{-- ── Header ── --}}
        <table class="header-table">
            <tr>
                <td style="width:55%; vertical-align:top;">
                    @if(\Modules\Settings\Models\Setting::get('company_logo'))
                        <img src="{{ storage_path('app/public/' . \Modules\Settings\Models\Setting::get('company_logo')) }}" class="logo">
                    @else
                        <div style="font-size:20px; font-weight:bold; color:#111;">{{ $siteName }}</div>
                    @endif
                    <div class="company-address">
                        {{ \Modules\Settings\Models\Setting::get('contact_address') }}
                        @if(\Modules\Settings\Models\Setting::get('contact_email'))<br>{{ \Modules\Settings\Models\Setting::get('contact_email') }}@endif
                        @if(\Modules\Settings\Models\Setting::get('contact_phone'))<br>{{ \Modules\Settings\Models\Setting::get('contact_phone') }}@endif
                    </div>
                </td>
                <td style="width:45%; vertical-align:top; text-align:right;">
                    <div class="report-title">Leads Report</div>
                    <div class="report-subtitle" style="font-weight:bold; color:#333;">{{ strtoupper($siteName) }}</div>
                    <div class="report-meta">Generated: {{ now()->format('F j, Y \a\t g:i A') }}</div>
                </td>
            </tr>
        </table>

        {{-- ── Summary Strip ── --}}
        <table class="summary-table">
            <tr>
                <td><span class="summary-value">{{ $leads->count() }}</span><span class="summary-label">Total Leads</span></td>
                <td><span class="summary-value">{{ $leads->where('status','new')->count() }}</span><span class="summary-label">New</span></td>
                <td><span class="summary-value">{{ $leads->where('status','contacted')->count() }}</span><span class="summary-label">Contacted</span></td>
                <td><span class="summary-value">{{ $leads->where('status','nurturing')->count() }}</span><span class="summary-label">Nurturing</span></td>
                <td><span class="summary-value">{{ $leads->where('status','qualified')->count() }}</span><span class="summary-label">Qualified</span></td>
                <td><span class="summary-value">{{ $leads->where('status','converted')->count() }}</span><span class="summary-label">Converted</span></td>
                <td><span class="summary-value">{{ $leads->where('status','lost')->count() }}</span><span class="summary-label">Lost</span></td>
            </tr>
        </table>

        {{-- ── Leads Table ── --}}
        <table class="leads-table">
            <thead>
                <tr>
                    <th style="width:3%;">#</th>
                    <th style="width:15%;">Name / Company</th>
                    <th style="width:22%;">Company Details</th>
                    <th style="width:16%;">Email</th>
                    <th style="width:11%;">Phone</th>
                    <th style="width:8%;">Status</th>
                    <th style="width:5%;">Score</th>
                    <th style="width:8%;">Source</th>
                    <th style="width:6%;">Assigned To</th>
                    <th style="width:6%;">Value</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leads as $i => $lead)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>
                        <div class="lead-name">{{ $lead->name }}</div>
                        @if($lead->company)<div class="lead-company">{{ $lead->company }}</div>@endif
                    </td>
                    <td style="font-size:8px; line-height: 1.3;">{{ $lead->company_details ?? '—' }}</td>
                    <td style="font-size:8px;">{{ $lead->email ?? '—' }}</td>
                    <td style="font-size:8px;">{{ $lead->phone ?? '—' }}</td>
                    <td>
                        <span class="badge badge-{{ $lead->status }}">{{ ucfirst($lead->status) }}</span>
                    </td>
                    <td>{{ $lead->score ?? 0 }}%</td>
                    <td style="font-size:8px;">{{ $lead->source ?? '—' }}</td>
                    <td style="font-size:8px;">{{ $lead->assignedTo->name ?? 'Unassigned' }}</td>
                    <td style="font-size:8px;">{{ $lead->value ? '₦'.number_format($lead->value) : '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- ── Footer ── --}}
        <div class="footer">
            {{ $siteName }} &bull; Leads Export &bull; Confidential &bull; {{ now()->format('F j, Y') }}
        </div>

    </div>
</body>
</html>
