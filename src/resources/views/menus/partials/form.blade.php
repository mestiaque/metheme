<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">{{ __('Name') }}</label>
        <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name', optional($menu)->name) }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">{{ __('Icon') }}</label>
        <input type="text" name="icon" class="form-control form-control-sm" value="{{ old('icon', optional($menu)->icon) }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">{{ __('URL') }}</label>
        <input type="text" name="url" class="form-control form-control-sm" value="{{ old('url', optional($menu)->url) }}">
    </div>

        <div class="col-md-6">
        <label class="form-label">{{ __('Type') }}</label>
        <input type="text" name="type" class="form-control form-control-sm" value="{{ old('type', optional($menu)->type) }}">
    </div>


    <div class="col-md-6">
        <label class="form-label">{{ __('Order') }}</label>
        <input type="number" name="order" class="form-control form-control-sm" value="{{ old('order', optional($menu)->order) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">{{ __('Status') }}</label>
        <div class="form-check form-switch">
            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', optional($menu)->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label font-weight-bold" for="is_active">
                @lang('Active')
            </label>
            <small class="form-text text-muted">@lang('Inactive Menu cannot be displayed.')</small>
        </div>
    </div>

</div>
