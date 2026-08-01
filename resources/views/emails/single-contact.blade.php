<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Notification' }}</title>
    <style>
        /* Your Professional Template Styles - Hardcoded in Shell for Gmail Compatibility */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333333;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #fc4c4b !important;
            color: #ffffff !important;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #ffffff !important;
        }
        .content {
            padding: 30px 20px;
        }
        .content h2 {
            color: #fc4c4b;
            font-size: 20px;
            margin-top: 0;
        }
        .content p {
            line-height: 1.6;
            font-size: 16px;
            color: #333333;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            margin: 20px 0;
            background-color: #fc4c4b !important;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
        .footer {
            background-color: #fc4c4b !important;
            color: #ffffff !important;
            text-align: center;
            padding: 20px;
            font-size: 12px;
            line-height: 1.5;
        }
        .footer a {
            color: #ffffff !important;
            text-decoration: underline;
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f4;">
    <div style="background-color: #f4f4f4; padding: 20px 0;">
        {!! $contentBody !!}
    </div>
</body>
</html>
