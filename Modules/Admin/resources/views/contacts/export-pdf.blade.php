<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; padding: 20px; }

        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-35deg);
            opacity: 0.07;
            z-index: -1;
            width: 400px;
        }
        .watermark img { width: 100%; }

        /* Header */
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; border-bottom: 2px solid #FB4C4B; padding-bottom: 16px; }
        .company-logo img { height: 50px; max-width: 160px; }
        .company-info { text-align: right; }
        .company-name { font-size: 15px; font-weight: bold; color: #1a1a1a; }
        .report-title { font-size: 20px; font-weight: bold; color: #FB4C4B; margin-bottom: 4px; }
        .report-meta { font-size: 10px; color: #666; margin-top: 4px; }

        /* Summary */
        .summary { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px 16px; margin-bottom: 20px; display: flex; gap: 40px; }
        .summary-item { text-align: center; }
        .summary-value { font-size: 22px; font-weight: bold; color: #FB4C4B; }
        .summary-label { font-size: 9px; text-transform: uppercase; color: #6b7280; letter-spacing: 0.5px; }

        /* Table */
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #FB4C4B; color: white; }
        thead th { padding: 9px 10px; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: bold; }
        tbody tr:nth-child(even) { background: #fef2f2; }
        tbody tr:nth-child(odd) { background: #fff; }
        tbody td { padding: 8px 10px; border-bottom: 1px solid #f3f4f6; font-size: 10px; }
        .status-active { background: #dcfce7; color: #15803d; padding: 2px 8px; border-radius: 9999px; font-size: 9px; font-weight: bold; }
        .status-unsubscribed { background: #f3f4f6; color: #374151; padding: 2px 8px; border-radius: 9999px; font-size: 9px; font-weight: bold; }
        .status-bounced { background: #fee2e2; color: #b91c1c; padding: 2px 8px; border-radius: 9999px; font-size: 9px; font-weight: bold; }
        .name { font-weight: bold; color: #111; }
        .email { color: #6b7280; font-size: 9px; }

        /* Footer */
        .footer { margin-top: 20px; padding-top: 10px; border-top: 1px solid #e5e7eb; text-align: center; font-size: 9px; color: #9ca3af; }
    </style>
</head>
<body>

    {{-- Watermark --}}
    @if($watermarkLogo)
    <div class="watermark">
        <img src="{{ $watermarkLogo }}">
    </div>
    @endif

    {{-- Header --}}
    <div class="header">
        <div class="company-logo">
            @if($companyLogo)
                <img src="{{ $companyLogo }}">
            @else
                <div class="company-name" style="font-size:18px;">{{ $siteName }}</div>
            @endif
        </div>
        <div class="company-info">
            <div class="report-title">Contacts Report</div>
            <div class="company-name">{{ $siteName }}</div>
            <div class="report-meta">Generated: {{ now()->format('F j, Y \a\t g:i A') }}</div>
        </div>
    </div>

    {{-- Summary --}}
    <div class="summary">
        <div class="summary-item">
            <div class="summary-value">{{ $contacts->count() }}</div>
            <div class="summary-label">Total Contacts</div>
        </div>
        <div class="summary-item">
            <div class="summary-value">{{ $contacts->where('status','active')->count() }}</div>
            <div class="summary-label">Active</div>
        </div>
        <div class="summary-item">
            <div class="summary-value">{{ $contacts->where('status','unsubscribed')->count() }}</div>
            <div class="summary-label">Unsubscribed</div>
        </div>
        <div class="summary-item">
            <div class="summary-value">{{ $contacts->where('status','bounced')->count() }}</div>
            <div class="summary-label">Bounced</div>
        </div>
    </div>

    {{-- Table --}}
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Company</th>
                <th>Status</th>
                <th>Date Added</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contacts as $i => $contact)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                    <div class="name">{{ $contact->full_name ?: 'N/A' }}</div>
                </td>
                <td>{{ $contact->email ?? '—' }}</td>
                <td>{{ $contact->phone ?? '—' }}</td>
                <td>{{ $contact->company ?? '—' }}</td>
                <td>
                    <span class="status-{{ $contact->status }}">{{ ucfirst($contact->status) }}</span>
                </td>
                <td>{{ $contact->created_at->format('M d, Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Footer --}}
    <div class="footer">
        {{ $siteName }} &bull; Contacts Export &bull; {{ now()->format('Y') }}
    </div>

</body>
</html>
