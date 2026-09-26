@extends('me::master')

@section('title', trans('me::me.sms_configuration'))

@section('content')
<div class="row g-4">
    {{-- SMS gateway settings --}}
    <div class="col-lg-8">
        <div class="card glass-card h-100">
            <form method="POST" action="{{ route('me.sms-config.update') }}">
                @csrf
                @method('PUT')

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-primary fw-semibold">
                            <i class="fas fa-sms me-1"></i> @lang('me::me.sms_gateway')
                        </h6>
                        <div class="form-check form-switch mb-0">
                            <input type="checkbox" class="form-check-input" id="enable_sms" name="enable_sms"
                                   {{ old('enable_sms', $settings['enable_sms']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="enable_sms">@lang('me::me.enable_sms')</label>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-3">@lang('me::me.sms_settings_hint')</p>

                        <div class="row g-3">
                            <div class="col-12">
                                <label for="sms_api_url" class="form-label fw-semibold">@lang('me::me.sms_api_url')</label>
                                <input type="url" name="sms_api_url" id="sms_api_url" placeholder="https://bulksmsbd.net/api/smsapi"
                                       class="form-control form-control-sm @error('sms_api_url') is-invalid @enderror"
                                       value="{{ old('sms_api_url', $settings['sms_api_url']) }}">
                                @error('sms_api_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="sms_api_key" class="form-label fw-semibold">@lang('me::me.sms_api_key')</label>
                                <input type="password" name="sms_api_key" id="sms_api_key" autocomplete="new-password"
                                       class="form-control form-control-sm @error('sms_api_key') is-invalid @enderror"
                                       placeholder="{{ $settings['has_api_key'] ? __('me::me.saved_leave_blank_to_keep') : '' }}">
                                @error('sms_api_key') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="sms_sender_id" class="form-label fw-semibold">@lang('me::me.sms_sender_id')</label>
                                <input type="text" name="sms_sender_id" id="sms_sender_id"
                                       class="form-control form-control-sm @error('sms_sender_id') is-invalid @enderror"
                                       value="{{ old('sms_sender_id', $settings['sms_sender_id']) }}">
                                @error('sms_sender_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label for="sms_balance_url" class="form-label fw-semibold">@lang('me::me.sms_balance_url') <span class="text-muted fw-normal">(@lang('me::me.optional'))</span></label>
                                <input type="url" name="sms_balance_url" id="sms_balance_url" placeholder="https://bulksmsbd.net/api/getBalanceApi"
                                       class="form-control form-control-sm @error('sms_balance_url') is-invalid @enderror"
                                       value="{{ old('sms_balance_url', $settings['sms_balance_url']) }}">
                                <small class="text-muted">@lang('me::me.sms_balance_url_hint')</small>
                                @error('sms_balance_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent text-end">
                        <button type="submit" class="btn btn-sm btn-encodex-save px-4">
                            <i class="fas fa-save me-1"></i> @lang('me::me.save_settings')
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Test SMS --}}
    <div class="col-lg-4">
        <div class="card glass-card h-100">
            <form method="POST" action="{{ route('me.sms-config.test') }}">
                @csrf
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light py-2">
                        <h6 class="mb-0 text-primary fw-semibold">
                            <i class="fas fa-paper-plane me-1"></i> @lang('me::me.send_test_sms')
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted">@lang('me::me.test_sms_hint')</p>

                        <label for="test_phone" class="form-label fw-semibold">@lang('me::me.mobile_number')</label>
                        <input type="text" name="test_phone" id="test_phone" placeholder="01XXXXXXXXX" required
                               class="form-control form-control-sm mb-2 @error('test_phone') is-invalid @enderror"
                               value="{{ old('test_phone') }}">
                        @error('test_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror

                        <label for="test_message" class="form-label fw-semibold">@lang('me::me.message')</label>
                        <textarea name="test_message" id="test_message" rows="3" required maxlength="500"
                                  class="form-control form-control-sm @error('test_message') is-invalid @enderror">{{ old('test_message', __('me::me.test_sms_default_message', ['app' => get_setting('shop_name', config('app.name'))])) }}</textarea>
                        @error('test_message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="card-footer bg-transparent text-end">
                        <button type="submit" class="btn btn-sm btn-encodex px-4">
                            <i class="fas fa-paper-plane me-1"></i> @lang('me::me.send_test')
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
