{{-- One-time code box. @include('me::mail.partials.otp', ['otp' => $otp, 'minutes' => 5]) --}}
<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 24px 0 8px 0;">
    <tr>
        <td align="center">
            <div style="background-color: #f8faff; border: 2px dashed #cbd5e1; border-radius: 12px; padding: 18px 24px; display: inline-block;">
                <span style="font-size: 34px; font-weight: 800; letter-spacing: 10px; color: #0052cc; font-family: monospace;">{{ $otp }}</span>
            </div>
            <p style="color: #64748b; font-size: 13px; margin: 14px 0 0 0;">
                {!! __('me::me.mail_otp_expires', ['minutes' => '<span style="color: #ef4444; font-weight: 600;">' . e($minutes ?? 5) . '</span>']) !!}
            </p>
        </td>
    </tr>
</table>
