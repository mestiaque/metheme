<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? ($companyName ?? 'Notice') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #2c3e50;
            padding: 20px;
        }
        .wrapper { max-width: 600px; margin: 0 auto; }
        .container {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px 20px;
            text-align: center;
            color: #ffffff;
            position: relative;
        }

        .logo {
            width: 90px;
            height: 90px;
            margin: 0 auto 20px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .title {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }
        .subtitle {
            font-size: 14px;
            opacity: 0.9;
            font-weight: 300;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .content-wrapper {
            padding: 20px;
            position: relative;
            z-index: 1;
        }
        .content {
            font-size: 15px;
            line-height: 1.8;
            color: #2c3e50;
        }
        .content p {
            margin-bottom: 10px;
        }
        .content strong {
            color: #667eea;
            font-weight: 600;
        }
        .content a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            border-bottom: 2px solid rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }
        .content a:hover {
            border-bottom-color: #667eea;
        }
        .highlight-box {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            border-left: 4px solid #667eea;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .highlight-box strong {
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
            color: #2c3e50;
        }
        .highlight-box ul {
            list-style: none;
            padding-left: 0;
        }
        .highlight-box li {
            padding: 8px 0;
            padding-left: 20px;
            position: relative;
            font-size: 14px;
            line-height: 1.6;
        }
        .highlight-box li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #667eea;
            font-weight: bold;
            font-size: 16px;
        }
        .greeting {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ecf0f1;
            font-style: italic;
            color: #764ba2;
            font-weight: 500;
            font-size: 14px;
        }
        .footer {
            background: #f8f9fa;
            padding: 32px;
            text-align: center;
            border-top: 1px solid #ecf0f1;
        }
        .footer-company {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .footer-text {
            color: #7f8c8d;
            font-size: 12px;
            line-height: 1.6;
        }
        .footer-divider {
            display: inline-block;
            margin: 0 8px;
            color: #bdc3c7;
        }
        .social-links {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #ecf0f1;
        }
        .social-link {
            display: inline-block;
            width: 32px;
            height: 32px;
            background: #667eea;
            border-radius: 50%;
            line-height: 32px;
            text-align: center;
            color: white;
            text-decoration: none;
            margin: 0 6px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .social-link:hover {
            background: #764ba2;
            transform: translateY(-2px);
        }
        @media (max-width: 600px) {
            body { padding: 10px; }
            .container { border-radius: 12px; }
            .header { padding: 20px; }
            .content-wrapper { padding: 20px; }
            .title { font-size: 24px; }
            .content { font-size: 14px; }
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <div class="header">
            @if(!empty($companyLogo))
                <div class="logo">
                    <img src="{{ $companyLogo }}" alt="{{ $companyName ?? 'Logo' }}">
                </div>
            @endif
            <div class="title">{{ $title ?? 'Notice' }}</div>
            <div class="subtitle">{{ $companyName ?? '' }}</div>
        </div>
        <div class="content-wrapper">
            <div class="content">
                {!! $content !!}
                @if(!empty($showGreeting))
                    <div class="greeting">✨ {{ $greetings[array_rand($greetings)] }}</div>
                @endif
            </div>
        </div>
        <div class="footer">
            <div class="footer-company">{{ $companyName ?? '' }}</div>
            <div class="footer-text">
                &copy; {{ $currentYear ?? date('Y') }} All rights reserved.
                <span class="footer-divider">•</span>
                <a href="#" style="color: #667eea; text-decoration: none;">Privacy Policy</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
