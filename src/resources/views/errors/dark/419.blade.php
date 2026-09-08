@extends('me::blank')
@section('title', '419 Page Expired')
@section('meta-title', '419 Page Expired')
@section('content')
    <div class="mb-4 animate__animated animate__bounceIn">
        <i class="fas fa-hourglass-end encodex-icon"></i>
    </div>
    <div class="error-code animate__animated animate__fadeInDown">419</div>
    <p class="error-message animate__animated animate__fadeInUp mb-4">
        Page expired due to inactivity. Please refresh and try again.
    </p>
    <a href="{{ url()->previous() }}" class="btn btn-blank mt-4">Go Back</a>
@endsection
