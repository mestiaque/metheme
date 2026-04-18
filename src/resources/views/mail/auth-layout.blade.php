<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $companyName ?? 'Notification' }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f0f2f5;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="padding: 40px 20px;">
        <tr>
            <td align="center">
                <!-- Main Card -->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                    <!-- Header with Logo -->
                    <tr>
                        <td align="center" style="padding: 40px 0 20px 0;">
                            <img src="{{ $companyLogo }}" alt="{{ $companyName }}" style="width: 120px; height: auto; display: block;">
                        </td>
                    </tr>
                    <!-- Content Section -->
                    <tr>
                        <td style="padding: 0 40px;">
                            {!! $content !!}
                        </td>
                    </tr>
                    @if($otp)
                    <!-- OTP Section -->
                    <tr>
                        <td align="center" style="padding: 30px 40px;">
                            <div style="background-color: #f8faff; border: 2px dashed #cbd5e1; border-radius: 12px; padding: 20px;">
                                <span style="font-size: 36px; font-weight: 800; letter-spacing: 10px; color: #0052cc; display: block; font-family: monospace;">
                                    {{ $otp }}
                                </span>
                            </div>
                            <p style="color: #64748b; font-size: 13px; margin-top: 15px;">
                                This code will expire in <span style="color: #ef4444; font-weight: 600;">5 minutes</span>.
                            </p>
                        </td>
                    </tr>
                    @endif
                    <!-- Footer Info -->
                    <tr>
                        <td style="padding: 0 40px 40px 40px;">
                            <p style="color: #94a3b8; font-size: 13px; text-align: center; margin: 0; border-top: 1px solid #f1f5f9; padding-top: 25px;">
                                If you didn't request this, please ignore this email or contact support if you have questions.
                            </p>
                        </td>
                    </tr>
                </table>
                <!-- Copyright Section -->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px;">
                    <tr>
                        <td style="padding: 20px 0; text-align: center;">
                            <p style="color: #94a3b8; font-size: 12px; margin: 0;">
                                &copy; {{ $currentYear }} <strong>{{ $companyName }}</strong>. All rights reserved.
                            </p>
                            <p style="color: #94a3b8; font-size: 12px; margin: 5px 0 0 0;">
                                Dhaka, Bangladesh.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
