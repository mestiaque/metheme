@extends('me::blank')
@section('title', '503 Service Unavailable')
@section('meta-title', '503 Service Unavailable')
@section('content')
    <div class="mb-4 animate__animated animate__bounceIn">
        <i class="fas fa-clock encodex-icon"></i>
    </div>
    <div class="error-code animate__animated animate__fadeInDown">503</div>
    <p class="error-message animate__animated animate__fadeInUp mb-4">
        We'll be back soon! The server is under maintenance.
    </p>
    <a href="{{ url('/') }}" class="btn btn-blank mt-4">Try Again</a>
@endsection
