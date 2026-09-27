{{--
    Common e-mail layout (like the admin master layout).

    Use in any e-mail view:

        @extends('me::mail.master')
        @section('title', 'Order shipped')            // optional heading
        @section('content')
            <p>Hello {{ $name }}, your order is on its way.</p>
            @include('me::mail.partials.button', ['url' => $url, 'text' => 'Track order'])
        @endsection

    Optional sections: title, preheader (inbox preview text), footer (replaces the default note).
    Works with or without me_mail(): company name / logo / year fall back to the settings.
    Inline styles and tables only, so it renders the same in Gmail, Outlook and phones.
--}}
@php
    $companyName = $companyName ?? get_setting('app_name', config('app.name'));
    $companyLogo = $companyLogo ?? route('app_logo.show');
    $currentYear = $currentYear ?? date('Y');
    $brandColor  = $brandColor ?? '#0052cc';
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">
    <title>{{ trim($__env->yieldContent('title')) ?: $companyName }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f0f2f5; color: #1e293b;">
    {{-- Inbox preview text (hidden) --}}
    <div style="display: none; max-height: 0; overflow: hidden; opacity: 0;">@yield('preheader')</div>

    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="padding: 40px 16px;">
        <tr>
            <td align="center">
                {{-- Card --}}
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 560px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                    {{-- Brand bar --}}
                    <tr>
                        <td style="height: 6px; background-color: {{ $brandColor }}; line-height: 6px; font-size: 0;">&nbsp;</td>
                    </tr>

                    {{-- Header: logo --}}
                    <tr>
                        <td align="center" style="padding: 32px 40px 8px 40px;">
                            <img src="{{ $companyLogo }}" alt="{{ $companyName }}" style="max-width: 120px; max-height: 80px; height: auto; display: block; border: 0;">
                        </td>
                    </tr>

                    {{-- Title --}}
                    @hasSection('title')
                        <tr>
                            <td align="center" style="padding: 16px 40px 0 40px;">
                                <h1 style="margin: 0; font-size: 22px; line-height: 1.3; font-weight: 700; color: #0f172a;">@yield('title')</h1>
                            </td>
                        </tr>
                    @endif

                    {{-- Content --}}
                    <tr>
                        <td style="padding: 20px 40px 8px 40px; font-size: 15px; line-height: 1.65; color: #334155;">
                            @yield('content')
                        </td>
                    </tr>

                    {{-- Footer note --}}
                    <tr>
                        <td style="padding: 16px 40px 32px 40px;">
                            <div style="color: #94a3b8; font-size: 12px; line-height: 1.6; text-align: center; border-top: 1px solid #f1f5f9; padding-top: 20px;">
                                @hasSection('footer')
                                    @yield('footer')
                                @else
                                    {{ __('me::me.mail_footer_note', ['app' => $companyName]) }}
                                @endif
                            </div>
                        </td>
                    </tr>
                </table>

                {{-- Copyright --}}
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 560px;">
                    <tr>
                        <td style="padding: 20px 0; text-align: center; color: #94a3b8; font-size: 12px;">
                            &copy; {{ $currentYear }} <strong>{{ $companyName }}</strong>. {{ __('me::me.all_rights_reserved') }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
