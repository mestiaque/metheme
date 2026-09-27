{{-- Auth / OTP e-mail (me_mail(..., 'auth') and ME\Mail\AuthMailLayout). Uses the common layout. --}}
@extends('me::mail.master')

@section('preheader', strip_tags($title ?? ''))

@section('content')
    {!! $content ?? '' !!}

    @if(!empty($otp))
        @include('me::mail.partials.otp', ['otp' => $otp, 'minutes' => $otpMinutes ?? 5])
    @endif
@endsection

@section('footer')
    {{ __('me::me.mail_auth_footer_note') }}
@endsection
