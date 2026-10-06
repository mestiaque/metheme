{{--
    File / image field backed by me_media. Pair it with $model->syncMediaFromRequest($request, 'collection', 'name') in the controller.

    @include('me::components.media-input', [
        'name' => 'images',          // form field (defaults to the collection)
        'model' => $product,         // existing model (null on create)
        'collection' => 'gallery',
        'multiple' => true,          // false = one file (logo, avatar)
        'label' => 'Images',
        'accept' => 'image/*',
        'help' => 'Max 10 images, 4 MB each.',
    ])

    Posts: {name}[] or {name} (new files), {name}_remove[] (ids), {name}_order[] (ids), {name}_primary (id).
--}}
@php
    $collection = $collection ?? 'default';
    $name = $name ?? $collection;
    $multiple = $multiple ?? false;
    $existing = isset($model) && $model?->exists && method_exists($model, 'getMedia') ? $model->getMedia($collection) : collect();
    $fieldId = 'mi_'.preg_replace('/\W/', '_', $name);
@endphp
<div class="me-media-input mb-3" id="{{ $fieldId }}">
    @if(!empty($label))
        <label class="font-weight-bold text-primary d-block mb-1"><i class="fas fa-{{ $multiple ? 'images' : 'image' }} me-1"></i> {{ $label }} @if(!empty($required))<span class="text-danger">*</span>@endif</label>
    @endif

    @if($existing->isNotEmpty())
        <div class="d-flex flex-wrap gap-2 mb-2 me-mi-existing" data-sortable="{{ $multiple ? 1 : 0 }}">
            @foreach($existing as $media)
                <div class="me-mi-item border rounded p-1 text-center" data-id="{{ $media->id }}">
                    <input type="hidden" name="{{ $name }}_order[]" value="{{ $media->id }}">
                    <a href="{{ $media->url() }}" target="_blank" class="d-block me-mi-thumb">
                        @if($media->isImage())
                            <img src="{{ $media->url('thumb') }}" alt="{{ $media->alt }}" loading="lazy">
                        @else
                            <i class="fas fa-file fa-2x text-secondary"></i>
                            <div class="small text-truncate" style="max-width:80px">{{ $media->original_name }}</div>
                        @endif
                    </a>
                    @if($multiple)
                        <div class="form-check form-check-inline small m-0" title="Show first">
                            <input class="form-check-input" type="radio" name="{{ $name }}_primary" value="{{ $media->id }}" id="{{ $fieldId }}_p{{ $media->id }}" @checked($loop->first)>
                            <label class="form-check-label" for="{{ $fieldId }}_p{{ $media->id }}">Main</label>
                        </div>
                    @endif
                    <div class="form-check form-check-inline small m-0">
                        <input class="form-check-input" type="checkbox" name="{{ $name }}_remove[]" value="{{ $media->id }}" id="{{ $fieldId }}_r{{ $media->id }}" data-remove>
                        <label class="form-check-label text-danger" for="{{ $fieldId }}_r{{ $media->id }}">Remove</label>
                    </div>
                </div>
            @endforeach
        </div>
        @if($multiple && $existing->count() > 1)<small class="text-muted d-block mb-1"><i class="fas fa-arrows-alt"></i> Drag to change the order.</small>@endif
    @endif

    <input type="file" class="form-control form-control-sm @error($name) is-invalid @enderror @error($name.'.*') is-invalid @enderror"
           name="{{ $name }}{{ $multiple ? '[]' : '' }}" @if($multiple) multiple @endif accept="{{ $accept ?? 'image/*' }}" data-preview-input
           @if(!empty($required) && $existing->isEmpty()) required @endif>
    @if(!empty($help))<small class="form-text text-muted">{{ $help }}</small>@endif
    @error($name)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    @error($name.'.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    <div class="d-flex flex-wrap gap-2 mt-2 me-mi-preview"></div>
</div>

@once
    @push('css')
    <style>
        .me-mi-item { width: 96px; background: #fff; cursor: grab; }
        .me-mi-item.is-removed { opacity: .35; }
        .me-mi-thumb img, .me-mi-preview img { width: 84px; height: 84px; object-fit: contain; border-radius: 6px; background: #f8f9fa; }
        .me-mi-thumb { min-height: 84px; display: flex; flex-direction: column; align-items: center; justify-content: center; text-decoration: none; }
    </style>
    @endpush
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.me-media-input').forEach(function (box) {
            const list = box.querySelector('.me-mi-existing');
            if (list && list.dataset.sortable === '1' && window.Sortable) new Sortable(list, { animation: 150 });
            box.querySelectorAll('[data-remove]').forEach(cb => cb.addEventListener('change', () => cb.closest('.me-mi-item').classList.toggle('is-removed', cb.checked)));
            const input = box.querySelector('[data-preview-input]'), preview = box.querySelector('.me-mi-preview');
            input.addEventListener('change', function () {
                preview.innerHTML = '';
                Array.from(input.files).forEach(file => {
                    if (!file.type.startsWith('image/')) { preview.insertAdjacentHTML('beforeend', '<span class="badge bg-light text-dark border">' + file.name.replace(/[<>&]/g, '') + '</span>'); return; }
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    preview.appendChild(img);
                });
            });
        });
    });
    </script>
    @endpush
@endonce
