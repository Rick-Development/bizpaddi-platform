<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            line-height: 1.6;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        .header-table {
            width: 100%;
            margin-bottom: 40px;
        }
        .logo-cell {
            width: 60%;
            vertical-align: top;
        }
        .invoice-info-cell {
            width: 40%;
            text-align: right;
            vertical-align: top;
        }
        .logo {
            max-height: 80px;
            margin-bottom: 15px;
        }
        .company-address {
            font-size: 13px;
            color: #777;
            white-space: pre-line;
        }
        h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            text-transform: uppercase;
            color: #333;
        }
        .invoice-details {
            font-size: 14px;
            color: #555;
        }
        .bill-to {
            margin-bottom: 40px;
        }
        .bill-to h3 {
            margin: 0 0 5px 0;
            font-size: 12px;
            text-transform: uppercase;
            color: #999;
            letter-spacing: 1px;
        }
        .client-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .client-details {
            font-size: 14px;
            color: #555;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            text-align: left;
            padding: 10px;
            background-color: #f8f9fa;
            border-bottom: 2px solid #ddd;
            font-size: 12px;
            text-transform: uppercase;
            color: #666;
        }
        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }
        .text-right {
            text-align: right;
        }
        .totals-table {
            width: 40%;
            margin-left: auto;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 8px 10px;
            text-align: right;
            font-size: 14px;
        }
        .total-row td {
            font-size: 16px;
            font-weight: bold;
            border-top: 2px solid #ddd;
            padding-top: 15px;
            color: #0E5E4A;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .footer-table {
            width: 100%;
        }
        .notes-column {
            width: 48%;
            vertical-align: top;
            font-size: 11px;
            color: #666;
        }
        .terms-column {
            width: 48%;
            vertical-align: top;
            font-size: 11px;
            color: #666;
            padding-left: 4%;
        }
        
        /* Watermark Styles */
        .watermark-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1000;
            opacity: 0.1;
            /* Flexbox doesn't work well in dompdf for centering, use transform */
        }
        .watermark-img {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 500px;
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

    <div class="container">
        <!-- Header -->
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    @if(\Modules\Settings\Models\Setting::get('company_logo'))
                        <img src="{{ storage_path('app/public/' . \Modules\Settings\Models\Setting::get('company_logo')) }}" class="logo">
                    @else
                        <h1>{{ \Modules\Settings\Models\Setting::get('site_name', config('app.name')) }}</h1>
                    @endif
                    
                    <div class="company-address">
                        {{ \Modules\Settings\Models\Setting::get('contact_address') }}
                        @if(\Modules\Settings\Models\Setting::get('contact_email'))<br>{{ \Modules\Settings\Models\Setting::get('contact_email') }}@endif
                        @if(\Modules\Settings\Models\Setting::get('contact_phone'))<br>{{ \Modules\Settings\Models\Setting::get('contact_phone') }}@endif
                    </div>
                </td>
                <td class="invoice-info-cell">
                    <h1>INVOICE</h1>
                    <div class="invoice-details">
                        <strong>Invoice No:</strong> {{ $invoice->invoice_number }}<br>
                        <strong>Date:</strong> {{ $invoice->issue_date->format('M d, Y') }}<br>
                        <strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}<br>
                        @if($invoice->status !== 'draft')
                            <strong>Status:</strong> {{ ucfirst($invoice->status) }}
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <!-- Bill To -->
        <table style="width: 100%; margin-bottom: 40px;">
            <tr>
                <td style="width: 40%; vertical-align: top;">
                    <div class="bill-to">
                        <h3>BILL TO</h3>
                        <div class="client-name">{{ $invoice->client_name }}</div>
                        <div class="client-details">
                            {{ $invoice->client_email }}<br>
                            {{ $invoice->client_phone }}<br>
                            {!! nl2br(e($invoice->client_address)) !!}
                        </div>
                    </div>
                </td>
                <td style="width: 20%;"></td>
                <td style="width: 40%; vertical-align: top; text-align: right;">
                    @if($invoice->serviceCategory || $invoice->service)
                    <div class="bill-to" style="text-align: right;">
                        <h3 style="text-align: right;">PROJECT DETAILS</h3>
                        <div class="client-details" style="text-align: right;">
                            @if($invoice->serviceCategory)
                                <strong>Category:</strong> {{ $invoice->serviceCategory->name }}<br>
                            @endif
                            @if($invoice->service)
                                <strong>Service:</strong> {{ $invoice->service->name }}<br>
                            @endif
                        </div>
                    </div>
                    @endif
                </td>
            </tr>
        </table>

        <!-- Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 55%;">Description</th>
                    <th style="width: 10%;" class="text-right">Qty</th>
                    <th style="width: 15%;" class="text-right">Price</th>
                    <th style="width: 20%;" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>
                        <div style="font-weight: bold; margin-bottom: 4px;">{{ $item->title }}</div>
                        <div style="font-size: 11px; color: #666;">{!! $item->description !!}</div>
                    </td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">{{ $invoice->currency->symbol ?? '$' }}{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right" style="font-weight: bold;">{{ $invoice->currency->symbol ?? '$' }}{{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <table class="totals-table">
            <tr>
                <td>Subtotal:</td>
                <td>{{ $invoice->currency->symbol ?? '$' }}{{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            @if($invoice->tax_amount > 0)
            <tr>
                <td>Tax ({{ $invoice->tax_rate }}%):</td>
                <td>{{ $invoice->currency->symbol ?? '$' }}{{ number_format($invoice->tax_amount, 2) }}</td>
            </tr>
            @endif
            @if($invoice->discount_amount > 0)
            <tr>
                <td>Discount:</td>
                <td>-{{ $invoice->currency->symbol ?? '$' }}{{ number_format($invoice->discount_amount, 2) }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td>Total:</td>
                <td>{{ $invoice->currency->symbol ?? '$' }}{{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
        </table>

        <!-- Footer -->
        <div class="footer">
            <table class="footer-table">
                <tr>
                    <td class="notes-column">
                        @if($invoice->notes || \Modules\Settings\Models\Setting::get('default_invoice_notes'))
                            <div style="font-weight: bold; margin-bottom: 5px; color: #333; text-transform: uppercase; font-size: 10px; letter-spacing: 0.05em;">Notes</div>
                            <div style="line-height: 1.4;">{!! $invoice->notes ?? \Modules\Settings\Models\Setting::get('default_invoice_notes', '') !!}</div>
                        @endif
                    </td>
                    <td class="terms-column">
                        @if($invoice->terms_conditions || \Modules\Settings\Models\Setting::get('default_invoice_terms'))
                            <div style="font-weight: bold; margin-bottom: 5px; color: #333; text-transform: uppercase; font-size: 10px; letter-spacing: 0.05em;">Terms & Conditions</div>
                            <div style="line-height: 1.4;">{!! $invoice->terms_conditions ?? \Modules\Settings\Models\Setting::get('default_invoice_terms', '') !!}</div>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
