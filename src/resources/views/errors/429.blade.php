@extends('me::blank')
@section('title', '429 Too Many Requests')
@section('meta-title', '429 Too Many Requests')
@section('content')
    <div class="mb-4 animate__animated animate__bounceIn">
        <i class="fas fa-hand-paper encodex-icon"></i>
    </div>
    <div class="error-code animate__animated animate__fadeInDown">429</div>
    <p class="error-message animate__animated animate__fadeInUp mb-4">
        Too many requests. Please slow down and wait a moment.
    </p>
    <a href="{{ url('/') }}" class="btn btn-blank mt-4">Go to Homepage</a>
@endsection
