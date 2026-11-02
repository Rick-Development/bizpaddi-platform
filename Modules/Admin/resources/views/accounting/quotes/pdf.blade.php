<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Quote {{ $quote->quote_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif;
            color: #1f2937;
            line-height: 1.5;
            font-size: 14px;
        }
        .header {
            margin-bottom: 40px;
            width: 100%;
        }
        .header-table {
            width: 100%;
        }
        .logo {
            max-height: 60px;
            margin-bottom: 10px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .company-details {
            font-size: 12px;
            color: #6b7280;
        }
        .document-title {
            text-align: right;
        }
        .title-text {
            font-size: 32px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 5px;
        }
        .meta-table {
            float: right;
            margin-top: 10px;
        }
        .meta-table td {
            padding: 2px 0 2px 15px;
            text-align: right;
        }
        .bill-to {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 5px;
            letter-spacing: 0.05em;
        }
        .client-name {
            font-size: 16px;
            font-weight: bold;
            color: #111827;
        }
        .table-container {
            margin-bottom: 30px;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
        }
        table.items th {
            text-align: left;
            padding: 10px;
            background-color: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #6b7280;
            letter-spacing: 0.05em;
        }
        table.items td {
            padding: 12px 10px;
            border-bottom: 1px solid #f3f4f6;
        }
        table.items .text-right {
            text-align: right;
        }
        .totals {
            width: 300px;
            float: right;
        }
        .totals-table {
            width: 100%;
        }
        .totals-table td {
            padding: 5px 0;
            text-align: right;
        }
        .total-row td {
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
            margin-top: 10px;
            font-weight: bold;
            font-size: 16px;
            color: #0E5E4A;
        }
        .footer {
            margin-top: 50px;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
        .footer-table {
            width: 100%;
        }
        .notes-column {
            width: 48%;
            vertical-align: top;
            font-size: 11px;
            color: #4b5563;
        }
        .terms-column {
            width: 48%;
            vertical-align: top;
            font-size: 11px;
            color: #4b5563;
            padding-left: 4%;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            opacity: 0.1;
            z-index: -1000;
            width: 80%;
            text-align: center;
        }
        .watermark img {
            width: 500px;
            height: auto;
        }
    </style>
</head>
<body>
    @if(\Modules\Settings\Models\Setting::get('watermark_logo'))
        <div class="watermark">
            <img src="{{ storage_path('app/public/' . \Modules\Settings\Models\Setting::get('watermark_logo')) }}" alt="Watermark">
        </div>
    @endif

    <div class="header">
        <table class="header-table">
            <tr>
                <td width="50%" valign="top">
                    @if(\Modules\Settings\Models\Setting::get('company_logo'))
                        <img src="{{ storage_path('app/public/' . \Modules\Settings\Models\Setting::get('company_logo')) }}" class="logo" alt="Logo">
                    @else
                        <div class="company-name">{{ \Modules\Settings\Models\Setting::get('site_name', config('app.name')) }}</div>
                    @endif
                    
                    <div class="company-details">
                        {!! nl2br(e(\Modules\Settings\Models\Setting::get('contact_address'))) !!}<br>
                        {{ \Modules\Settings\Models\Setting::get('contact_email') }}<br>
                        {{ \Modules\Settings\Models\Setting::get('contact_phone') }}
                    </div>
                </td>
                <td width="50%" valign="top" class="document-title">
                    <div class="title-text">QUOTE</div>
                    <table class="meta-table">
                        <tr>
                            <td style="color: #6b7280;">Quote #:</td>
                            <td style="font-weight: bold;">{{ $quote->quote_number }}</td>
                        </tr>
                        <tr>
                            <td style="color: #6b7280;">Date:</td>
                            <td>{{ $quote->issue_date->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td style="color: #6b7280;">Valid Until:</td>
                            <td>{{ $quote->valid_until->format('M d, Y') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <table style="width: 100%; margin-bottom: 30px; font-size: 12px;">
        <tr>
            <td style="width: 40%; vertical-align: top;">
                <div class="section-title">BILL TO</div>
                <strong>{{ $quote->client_name }}</strong><br>
                {{ $quote->client_email }}<br>
                {{ $quote->client_phone }}<br>
                {!! nl2br(e($quote->client_address)) !!}
            </td>
            <td style="width: 20%;"></td>
            <td style="width: 40%; vertical-align: top; text-align: right;">
                @if($quote->serviceCategory || $quote->service)
                <div class="section-title" style="text-align: right;">PROJECT DETAILS</div>
                <div style="text-align: right;">
                    @if($quote->serviceCategory)
                        <strong>Category:</strong> {{ $quote->serviceCategory->name }}<br>
                    @endif
                    @if($quote->service)
                        <strong>Service:</strong> {{ $quote->service->name }}
                    @endif
                </div>
                @endif
            </td>
        </tr>
    </table>

    <div class="table-container">
        <table class="items">
            <thead>
                <tr>
                    <th width="55%">Description</th>
                    <th width="10%" class="text-right">Qty</th>
                    <th width="15%" class="text-right">Price</th>
                    <th width="20%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quote->items as $item)
                <tr>
                    <td>
                        <div style="font-weight: bold; margin-bottom: 4px;">{{ $item->title }}</div>
                        <div style="font-size: 11px; color: #4b5563;">{!! $item->description !!}</div>
                    </td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right"><span style="font-family: 'DejaVu Sans', sans-serif;">{{ $quote->currency->symbol ?? '$' }}</span>{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right" style="font-weight: bold;"><span style="font-family: 'DejaVu Sans', sans-serif;">{{ $quote->currency->symbol ?? '$' }}</span>{{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="totals">
        <table class="totals-table">
            <tr>
                <td style="color: #6b7280;">Subtotal:</td>
                <td><span style="font-family: 'DejaVu Sans', sans-serif;">{{ $quote->currency->symbol ?? '$' }}</span>{{ number_format($quote->subtotal, 2) }}</td>
            </tr>
            @if($quote->tax_amount > 0)
            <tr>
                <td style="color: #6b7280;">Tax ({{ $quote->tax_rate }}%):</td>
                <td style="font-weight: 500;"><span style="font-family: 'DejaVu Sans', sans-serif;">{{ $quote->currency->symbol ?? '$' }}</span>{{ number_format($quote->tax_amount, 2) }}</td>
            </tr>
            @endif
            @if($quote->discount_amount > 0)
            <tr>
                <td style="color: #6b7280;">Discount:</td>
                <td style="color: #dc2626; font-weight: 500;">-<span style="font-family: 'DejaVu Sans', sans-serif;">{{ $quote->currency->symbol ?? '$' }}</span>{{ number_format($quote->discount_amount, 2) }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td>Total:</td>
                <td><span style="font-family: 'DejaVu Sans', sans-serif;">{{ $quote->currency->symbol ?? '$' }}</span>{{ number_format($quote->total_amount, 2) }}</td>
            </tr>
        </table>
    </div>
    <div style="clear: both;"></div>

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td class="notes-column">
                    <div style="font-weight: bold; margin-bottom: 5px; color: #111827; text-transform: uppercase; font-size: 10px; letter-spacing: 0.05em;">Notes</div>
                    <div style="line-height: 1.4;">{!! $quote->notes ?? \Modules\Settings\Models\Setting::get('default_quote_notes', 'No additional notes') !!}</div>
                </td>
                <td class="terms-column">
                    <div style="font-weight: bold; margin-bottom: 5px; color: #111827; text-transform: uppercase; font-size: 10px; letter-spacing: 0.05em;">Terms & Conditions</div>
                    <div style="line-height: 1.4;">{!! $quote->terms_conditions ?? \Modules\Settings\Models\Setting::get('default_quote_terms', 'Standard terms apply.') !!}</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
