@extends('me::master')

@section('title', trans('me::me.app_settings'))
@section('content')
<div class="container-fluids">
    <div class="card shadow mb-4 w-100">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-primary">
                                <i class="fas fa-store me-1"></i> @lang('me::me.app_name') <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="app_name" class="form-control form-control-sm @error('app_name') is-invalid @enderror"
                                   value="{{ old('app_name', $settings['app_name']) }}" required>
                            @error('app_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-primary">
                                <i class="fas fa-map-marker-alt me-1"></i> @lang('me::me.app_address')
                            </label>
                            <textarea name="app_address" class="form-control form-control-sm @error('app_address') is-invalid @enderror"
                                      rows="3">{{ old('app_address', $settings['app_address']) }}</textarea>
                            @error('app_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-primary">
                                <i class="fas fa-envelope me-1"></i> @lang('me::me.app_email')
                            </label>
                            <input type="email" name="app_email" class="form-control form-control-sm @error('app_email') is-invalid @enderror"
                                   value="{{ old('app_email', $settings['app_email']) }}">
                            @error('app_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-primary">
                                <i class="fas fa-phone me-1"></i> @lang('me::me.app_phone')
                            </label>
                            <input type="text" name="app_phone" class="form-control form-control-sm @error('app_phone') is-invalid @enderror"
                                   value="{{ old('app_phone', $settings['app_phone']) }}">
                            @error('app_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-primary">
                                <i class="fas fa-exclamation-triangle me-1"></i> @lang('me::me.low_stock_warning')
                            </label>
                            <div class="input-group">
                                <input type="number" name="low_stock_threshold" min="1"
                                       class="form-control form-control-sm @error('low_stock_threshold') is-invalid @enderror"
                                       value="{{ old('low_stock_threshold', $settings['low_stock_threshold']) }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">@lang('me::me.items')</span>
                                </div>
                            </div>
                            <small class="form-text text-muted">@lang('me::me.products_with_stock_below_this_number_will_be_marked_as_low_stock')</small>
                            @error('low_stock_threshold')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if(get_setting('enable_sms'))
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" name="sms_notifications[create]" id="sms_create" class="form-check-input"
                                        value="1" {{ old('sms_notifications.create', $settings['sms_permit']['create'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sms_create">@lang('me::me.send_sms_for_new_sales')</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="sms_notifications[edit]" id="sms_edit" class="form-check-input"
                                        value="1" {{ old('sms_notifications.edit', $settings['sms_permit']['edit'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sms_edit">@lang('me::me.send_sms_for_edited_sales')</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="sms_notifications[payment]" id="sms_payment" class="form-check-input"
                                        value="1" {{ old('sms_notifications.payment', $settings['sms_permit']['payment'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sms_payment">@lang('me::me.send_sms_for_payment_notifications')</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="sms_notifications[reminder]" id="sms_reminder" class="form-check-input"
                                        value="1" {{ old('sms_notifications.reminder', $settings['sms_permit']['reminder'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sms_reminder">@lang('me::me.send_sms_for_reminder_notifications')</label>
                                </div>
                            </div>
                        @endif

                    </div>

                    <div class="col-md-6">
                        <div class="form-group text-center">
                            <label class="font-weight-bold text-primary d-block">
                                <i class="fas fa-image me-1"></i> @lang('me::me.app_logo')
                            </label>

                            <div class="logo-preview mb-3">
                                @if($settings['app_logo'])
                                    <img src="{{ route('app_logo.show', $settings['app_logo']) }}"
                                         alt="@lang('me::me.app_logo')" class="img-thumbnail" style="max-height: 200px;">
                                @else
                                    <div class="empty-logo p-4 bg-light text-center border rounded">
                                        <i class="fas fa-image fa-3x text-gray-400"></i>
                                        <p class="mt-2 text-gray-500">@lang('me::me.no_logo_uploaded')</p>
                                    </div>
                                @endif
                            </div>

                            <div class="custom-file">
                                <input type="file" class="border border-primary custom-file-input @error('app_logo') is-invalid @enderror"
                                       id="app_logo" name="app_logo" accept="image/*">
                                {{-- <label class="custom-file-label" for="app_logo">@lang('me::me.choose_image')</label> --}}
                                @error('app_logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">@lang('me::me.recommended_logo_size')</small>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-encodex px-4">
                        <i class="fas fa-save me-1"></i> @lang('me::me.save_settings')
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        // Show image name in file input
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);

            // Show image preview
            if (this.files && this.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('.logo-preview').html('<img src="' + e.target.result + '" class="img-thumbnail" style="max-height: 200px;">');
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
@endpush

@push('css')
<style>
    .custom-file-label::after {
        content: "@lang('me::me.browse')";
    }

    .empty-logo {
        border: 2px dashed #ddd;
        border-radius: 5px;
    }
</style>
@endpush
