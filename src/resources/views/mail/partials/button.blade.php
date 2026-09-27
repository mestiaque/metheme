{{-- Button for e-mails. @include('me::mail.partials.button', ['url' => $url, 'text' => 'Open', 'color' => '#0052cc']) --}}
<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 24px 0;">
    <tr>
        <td align="center">
            <a href="{{ $url }}" target="_blank"
               style="display: inline-block; background-color: {{ $color ?? '#0052cc' }}; color: #ffffff; text-decoration: none; font-weight: 600; font-size: 14px; padding: 12px 32px; border-radius: 8px;">
                {{ $text ?? __('me::me.View') }}
            </a>
        </td>
    </tr>
</table>
