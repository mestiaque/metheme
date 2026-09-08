@extends('me::blank')
@section('title', '500 Server Error')
@section('meta-title', '500 Server Error')
@section('content')
    <div class="mb-4 animate__animated animate__bounceIn">
        <i class="fas fa-tools encodex-icon"></i>
    </div>
    <div class="error-code animate__animated animate__fadeInDown">500</div>
    <p class="error-message animate__animated animate__fadeInUp mb-4">
        Something went wrong on our end. We are fixing it!
    </p>
    <a href="{{ url('/') }}" class="btn btn-blank mt-4">Go to Homepage</a>
@endsection
