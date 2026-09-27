{{-- Badge color input. Needs: $selectedColor (hex or null) --}}
@php $colorValue = preg_match('/^#[0-9a-fA-F]{6}$/', (string) $selectedColor) ? $selectedColor : \ME\Models\Role::DEFAULT_COLOR; @endphp
<div class="form-group mt-3">
    <label for="color" class="font-weight-bold text-primary">
        <i class="fas fa-palette me-1"></i> @lang('me::me.Badge Color')
    </label>
    <div class="d-flex align-items-center gap-2">
        <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror"
               id="color" name="color" value="{{ $colorValue }}" style="width: 60px; height: 38px; padding: 4px;">
        <span class="badge" id="color-preview" style="background-color: {{ $colorValue }}; font-size: .9rem;">@lang('me::me.Preview')</span>
    </div>
    <small class="form-text text-muted">@lang('me::me.badge_color_help')</small>
    @error('color')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('color');
        const preview = document.getElementById('color-preview');
        const nameInput = document.getElementById('name');
        if (!input || !preview) return;

        const refresh = () => {
            const hex = input.value.replace('#', '');
            const r = parseInt(hex.substr(0, 2), 16), g = parseInt(hex.substr(2, 2), 16), b = parseInt(hex.substr(4, 2), 16);
            preview.style.backgroundColor = input.value;
            preview.style.color = ((r * 299 + g * 587 + b * 114) / 1000) > 150 ? '#212529' : '#ffffff';
            if (nameInput && nameInput.value.trim()) preview.textContent = nameInput.value.trim();
        };

        input.addEventListener('input', refresh);
        if (nameInput) nameInput.addEventListener('input', refresh);
        refresh();
    });
</script>
@endpush
