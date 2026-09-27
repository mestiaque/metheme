{{--
    Default e-mail template for me_mail(): any message is shown inside the common layout.

    me_mail($to, 'Subject', '<p>Message</p>');
    me_mail($to, 'Subject', '<p>Message</p>', [
        'heading'     => 'Custom heading',        // default: the subject; false = no heading
        'otp'         => 123456,                  // shows the code box
        'buttonUrl'   => url('/orders/42'),       // shows a button
        'buttonText'  => 'View order',
        'showGreeting'=> true,                    // adds a random closing line
    ]);
--}}
@extends('me::mail.master')

@php $heading = $heading ?? ($title ?? null); @endphp

@if($heading)
    @section('title', $heading)
@endif

@section('preheader', strip_tags($preheader ?? ($heading ?? '')))

@section('content')
    {!! $content ?? '' !!}

    @if(!empty($otp))
        @include('me::mail.partials.otp', ['otp' => $otp, 'minutes' => $otpMinutes ?? 5])
    @endif

    @if(!empty($buttonUrl))
        @include('me::mail.partials.button', ['url' => $buttonUrl, 'text' => $buttonText ?? null, 'color' => $buttonColor ?? null])
    @endif

    @if(!empty($showGreeting) && !empty($greetings))
        <p style="margin-top: 24px; color: #475569;">{{ collect($greetings)->random() }}<br><strong>{{ $companyName ?? '' }}</strong></p>
    @endif
@endsection
