@extends('me::blank')
@section('title', '403 Forbidden')
@section('meta-title', '403 Forbidden')
@section('content')
    <div class="mb-4 animate__animated animate__bounceIn">
        <i class="fas fa-user-shield encodex-icon" style="color: #e74c3c;"></i>
    </div>
    <div class="error-code animate__animated animate__fadeInDown">403</div>
    <p class="error-message animate__animated animate__fadeInUp mb-4">
        Sorry, you don't have permission to access this page.
    </p>
    <a href="{{ url('/') }}" class="btn btn-blank mt-4">Go to Homepage</a>
@endsection
