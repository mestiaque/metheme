@extends('me::master')

@section('title', trans('Clear Data'))

@section('content')

<div class="card glass-card shadow-lg">
    <div class="card-header glass-header text-white d-flex align-items-center">
        <i class="fas fa-exclamation-triangle mr-3 fa-2x"></i>
        <h5 class="mb-0">@lang('Clear All Data')</h5>
    </div>

    <div class="card-body">
        <div class="alert glass-card alert-danger d-flex align-items-start" role="alert">

            <div>
                <div class="row">
                    <div class="col-md-4 col-sm-12 text-center" style="min-height: 9rem;">
                        <i class="fas fa-skull-crossbones fa-lg mr-3 mt-1" style="font-size: 9rem;     margin-right: 2rem;margin-top: 1rem !important;"></i>
                    </div>
                    <div class="col-md-8 col-sm-12">
                        <h5 class="alert-heading mb-1">@lang('DANGER ZONE')</h5>
                        <p class="mb-1">
                            @lang('This action will permanently delete all system data, including test records and other content.')
                        </p>
                        <p class="mb-1">
                            <strong>@lang('Users and Roles will be preserved.')</strong>
                        </p>
                        <hr class="my-2">
                        <p class="mb-0">
                            <strong class="text-danger">@lang('This action cannot be undone!')</strong>
                        </p>
                    </div>
                </div>

            </div>
        </div>


        @if(session('success'))
            <div class="alert glass-alert alert-success d-flex align-items-center">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert glass-alert alert-danger d-flex align-items-center">
                <i class="fas fa-times-circle mr-2"></i> {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('me.data.clear') }}" onsubmit="return confirmClear()">
            @csrf

            <div class="form-group">
                <label for="confirm_text" class="font-weight-bold">
                    @lang('Type') <code>CLEAR ALL DATA</code> @lang('to confirm'):
                </label>
                <input type="text"
                       name="confirm_text"
                       id="confirm_text"
                       class="form-control glass-input @error('confirm_text') is-invalid @enderror"
                       placeholder="CLEAR ALL DATA"
                       autocomplete="off">
                @error('confirm_text')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group text-center mt-4">
                <button type="submit"
                        class="btn btn-danger btn-lg shadow-lg glass-button"
                        id="clearButton"
                        disabled>
                    <i class="fas fa-trash-alt mr-2"></i> @lang('Clear All Data')
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('css')
<style>
/* Glass/Card Styles */


.glass-header {
    background: rgba(220, 53, 69, 0.6);
    backdrop-filter: blur(8px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.3);
    border-top-left-radius: 16px;
    border-top-right-radius: 16px;
}


.glass-input {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    color: #fff;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.glass-input:focus {
    background: rgba(255, 255, 255, 0.1);
    border-color: #dc3545;
    box-shadow: 0 0 10px rgba(220, 53, 69, 0.5);
    color: #fff;
}



code {
    background: rgba(255, 255, 255, 0.1);
    padding: 2px 6px;
    border-radius: 6px;
    color: #ff6b6b;
    font-weight: bold;
}
</style>
@endpush

@push('js')
<script>
$(document).ready(function() {
    $('#confirm_text').on('input', function() {
        $('#clearButton').prop('disabled', $(this).val() !== 'CLEAR ALL DATA');
    });
});

function confirmClear() {
    return confirm('Are you absolutely sure you want to clear all data? This action cannot be undone!');
}
</script>
@endpush
