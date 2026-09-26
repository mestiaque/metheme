@extends('me::master')

@section('title', trans('me::me.mail_configuration'))

@section('content')
<div class="row g-4">
    {{-- Mail server settings --}}
    <div class="col-lg-8">
        <div class="card glass-card h-100">
            <form method="POST" action="{{ route('me.mail-config.update') }}">
                @csrf
                @method('PUT')

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-primary fw-semibold">
                            <i class="fas fa-envelope me-1"></i> @lang('me::me.mail_server')
                        </h6>
                        <div class="form-check form-switch mb-0">
                            <input type="checkbox" class="form-check-input" id="mail_custom_enabled" name="mail_custom_enabled"
                                   {{ old('mail_custom_enabled', $settings['mail_custom_enabled']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="mail_custom_enabled">@lang('me::me.use_these_mail_settings')</label>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-3">@lang('me::me.mail_settings_hint')</p>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="mail_mailer" class="form-label fw-semibold">@lang('me::me.mail_driver') <span class="text-danger">*</span></label>
                                <select name="mail_mailer" id="mail_mailer" class="form-select form-select-sm @error('mail_mailer') is-invalid @enderror">
                                    @foreach (['smtp' => 'SMTP', 'sendmail' => 'Sendmail', 'log' => __('me::me.log_only_for_testing')] as $value => $label)
                                        <option value="{{ $value }}" {{ old('mail_mailer', $settings['mail_mailer']) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('mail_mailer') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 smtp-only">
                                <label for="mail_encryption" class="form-label fw-semibold">@lang('me::me.encryption') <span class="text-danger">*</span></label>
                                <select name="mail_encryption" id="mail_encryption" class="form-select form-select-sm @error('mail_encryption') is-invalid @enderror">
                                    @foreach (['tls' => 'TLS / STARTTLS (587)', 'ssl' => 'SSL (465)', 'none' => __('me::me.none')] as $value => $label)
                                        <option value="{{ $value }}" {{ old('mail_encryption', $settings['mail_encryption']) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('mail_encryption') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-8 smtp-only">
                                <label for="mail_host" class="form-label fw-semibold">@lang('me::me.smtp_host')</label>
                                <input type="text" name="mail_host" id="mail_host" placeholder="smtp.gmail.com"
                                       class="form-control form-control-sm @error('mail_host') is-invalid @enderror"
                                       value="{{ old('mail_host', $settings['mail_host']) }}">
                                @error('mail_host') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4 smtp-only">
                                <label for="mail_port" class="form-label fw-semibold">@lang('me::me.smtp_port')</label>
                                <input type="number" name="mail_port" id="mail_port" placeholder="587"
                                       class="form-control form-control-sm @error('mail_port') is-invalid @enderror"
                                       value="{{ old('mail_port', $settings['mail_port']) }}">
                                @error('mail_port') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 smtp-only">
                                <label for="mail_username" class="form-label fw-semibold">@lang('me::me.smtp_username')</label>
                                <input type="text" name="mail_username" id="mail_username" autocomplete="off"
                                       class="form-control form-control-sm @error('mail_username') is-invalid @enderror"
                                       value="{{ old('mail_username', $settings['mail_username']) }}">
                                @error('mail_username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 smtp-only">
                                <label for="mail_password" class="form-label fw-semibold">@lang('me::me.smtp_password')</label>
                                <input type="password" name="mail_password" id="mail_password" autocomplete="new-password"
                                       class="form-control form-control-sm @error('mail_password') is-invalid @enderror"
                                       placeholder="{{ $settings['has_password'] ? __('me::me.saved_leave_blank_to_keep') : '' }}">
                                @error('mail_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="mail_from_address" class="form-label fw-semibold">@lang('me::me.from_address') <span class="text-danger">*</span></label>
                                <input type="email" name="mail_from_address" id="mail_from_address"
                                       class="form-control form-control-sm @error('mail_from_address') is-invalid @enderror"
                                       value="{{ old('mail_from_address', $settings['mail_from_address']) }}" required>
                                @error('mail_from_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="mail_from_name" class="form-label fw-semibold">@lang('me::me.from_name') <span class="text-danger">*</span></label>
                                <input type="text" name="mail_from_name" id="mail_from_name"
                                       class="form-control form-control-sm @error('mail_from_name') is-invalid @enderror"
                                       value="{{ old('mail_from_name', $settings['mail_from_name']) }}" required>
                                @error('mail_from_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

    {{-- Test email --}}
    <div class="col-lg-4">
        <div class="card glass-card h-100">
            <form method="POST" action="{{ route('me.mail-config.test') }}">
                @csrf
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light py-2">
                        <h6 class="mb-0 text-primary fw-semibold">
                            <i class="fas fa-paper-plane me-1"></i> @lang('me::me.send_test_email')
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted">@lang('me::me.test_email_hint')</p>
                        <label for="test_email" class="form-label fw-semibold">@lang('me::me.recipient_email')</label>
                        <input type="email" name="test_email" id="test_email" required
                               class="form-control form-control-sm @error('test_email') is-invalid @enderror"
                               value="{{ old('test_email', auth()->user()->email ?? '') }}">
                        @error('test_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

@push('js')
<script>
    // SMTP fields only matter for the SMTP driver
    $(function () {
        const toggleSmtp = () => $('.smtp-only').toggle($('#mail_mailer').val() === 'smtp');
        $('#mail_mailer').on('change', toggleSmtp);
        toggleSmtp();
    });
</script>
@endpush
