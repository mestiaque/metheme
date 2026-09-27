{{-- Parent role select. Needs: $parents (allowed parent roles), $canCreateTop (bool), $selectedParent --}}
<div class="form-group mt-3">
    <label for="parent_id" class="font-weight-bold text-primary">
        <i class="fas fa-sitemap me-1"></i> @lang('me::me.Parent Role')
        @unless($canCreateTop) <span class="text-danger">*</span> @endunless
    </label>
    <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id" {{ $canCreateTop ? '' : 'required' }}>
        @if($canCreateTop)
            <option value="" {{ (string) $selectedParent === '' ? 'selected' : '' }}>— @lang('me::me.No parent (top role)') —</option>
        @else
            <option value="" disabled {{ (string) $selectedParent === '' ? 'selected' : '' }}>@lang('me::me.Select parent role')</option>
        @endif
        @foreach($parents as $parent)
            <option value="{{ $parent->id }}" {{ (string) $selectedParent === (string) $parent->id ? 'selected' : '' }}>
                {{ $parent->hierarchyPath() }}
            </option>
        @endforeach
    </select>
    <small class="form-text text-muted">@lang('me::me.parent_role_help')</small>
    @error('parent_id')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
