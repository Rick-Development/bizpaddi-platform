<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $contract->contract_number }} - {{ $contract->title }}</title>
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
        .contract-info-cell {
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
        .contract-details {
            font-size: 14px;
            color: #555;
        }
        .parties-table {
            width: 100%;
            margin-bottom: 40px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .party-cell {
            width: 48%;
            vertical-align: top;
        }
        .party-title {
            font-size: 12px;
            text-transform: uppercase;
            color: #999;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        .party-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .party-details {
            font-size: 14px;
            color: #555;
        }
        .content {
            margin-bottom: 30px;
            text-align: justify;
        }
        .terms {
            margin-top: 30px;
            font-size: 12px;
            color: #555;
            border-top: 2px solid #ddd;
            padding-top: 20px;
        }
        .signatures {
            margin-top: 50px;
            page-break-inside: avoid;
            width: 100%;
        }
        .signature-box {
            width: 45%;
            display: inline-block;
            vertical-align: top; 
        }
        .signature-line {
            border-top: 1px solid #333;
            padding-top: 10px;
            margin-top: 50px;
        }
        .signature-img {
            max-height: 60px;
            margin-bottom: -10px; 
            display: block;
        }
        .footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
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
                <td class="contract-info-cell">
                    <h1>CONTRACT</h1>
                    <div class="contract-details">
                        <strong>Contract #:</strong> {{ $contract->contract_number }}<br>
                        <strong>Start Date:</strong> {{ $contract->start_date->format('M d, Y') }}<br>
                        <strong>Duration:</strong> 
                        @php
                            $durationParts = [];
                            if($contract->duration_months) $durationParts[] = $contract->duration_months . ' Month' . ($contract->duration_months > 1 ? 's' : '');
                            if($contract->duration_weeks) $durationParts[] = $contract->duration_weeks . ' Week' . ($contract->duration_weeks > 1 ? 's' : '');
                            echo !empty($durationParts) ? implode(' ', $durationParts) : 'N/A';
                        @endphp
                        <br>
                        @if($contract->value)
                        <strong>Value:</strong> {{ $contract->currency->symbol ?? '$' }}{{ number_format($contract->value, 2) }}
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <!-- Parties -->
        <table class="parties-table">
            <tr>
                <td class="party-cell">
                    <div class="party-title">First Party (Provider)</div>
                    <div class="party-name">{{ \Modules\Settings\Models\Setting::get('site_name', config('app.name')) }}</div>
                </td>
                <td class="party-cell" style="text-align: right;">
                    <div class="party-title">Second Party ({{ ucfirst($contract->party_type) }})</div>
                    <div class="party-name">{{ $contract->party_name }}</div>
                    <div class="party-details">
                        {{ $contract->party_address }}<br>
                        {{ $contract->party_email }}
                    </div>
                </td>
            </tr>
        </table>

        <!-- Title -->
        <div style="text-align: center; margin: 30px 0; border-bottom: 2px solid #ddd; padding-bottom: 10px;">
            <h2 style="margin: 0; font-size: 20px; color: #333;">{{ $contract->title }}</h2>
        </div>

        <!-- Content -->
        <div class="content">
            {!! $contract->content !!}
        </div>

        <!-- Terms -->
        @if($contract->terms)
        <div class="terms">
            <h3>Terms & Conditions</h3>
            {!! $contract->terms !!}
        </div>
        @endif

        <!-- Signatures -->
        <table class="signatures">
            <tr>
                <td class="signature-box w-100">
                    <strong>Signed for {{ \Modules\Settings\Models\Setting::get('site_name', config('app.name')) }}:</strong>
                    
                    <div style="height: 50px; margin-top: 10px;">
                        @if(\Modules\Settings\Models\Setting::get('company_signature'))
                             <img src="{{ storage_path('app/public/' . \Modules\Settings\Models\Setting::get('company_signature')) }}" class="signature-img">
                        @endif
                    </div>

                    <div class="signature-line">
                        Name: {{ \Modules\Settings\Models\Setting::get('company_representative_name', 'Authorized Representative') }}<br>
                        Date: {{ date('M d, Y') }}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="height: 50px;"></td>
            </tr>
            <tr class="mt-4">
                <td class="signature-box">
                    <strong>Signed for {{ $contract->party_name }}:</strong>

                     <div style="height: 50px; margin-top: 10px;">
                        @if($contract->signature_file)
                             <!-- If we had a stored image signature, we could show it here. 
                                  Currently signature_file is likely a PDF or doc path. 
                                  If it were an image path, we'd use storage_path -->
                             <span style="font-style: italic; color: #666; font-size: 10px;">[Digitally Signed]</span>
                        @endif
                    </div>

                    <div class="signature-line">
                        Name: {{ $contract->party_name }}<br>
                        Date: ______________________
                    </div>
                </td>
            </tr>
        </table>

        <div class="footer">
            Page <span class="page-number"></span> | {{ $contract->contract_number }} | Generated on {{ date('Y-m-d') }}
        </div>
    </div>
</body>
</html>
