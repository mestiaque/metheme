@extends('me::master')
@section('title', __('me::me.Media Library'))

@php
    $iconFor = function ($media) {
        return match (true) {
            str_contains((string) $media->mime_type, 'pdf') => 'fa-file-pdf text-danger',
            str_contains((string) $media->mime_type, 'sheet') || in_array($media->extension, ['xls', 'xlsx', 'csv']) => 'fa-file-excel text-success',
            str_contains((string) $media->mime_type, 'word') || in_array($media->extension, ['doc', 'docx']) => 'fa-file-word text-primary',
            str_starts_with((string) $media->mime_type, 'video/') => 'fa-file-video text-info',
            in_array($media->extension, ['zip', 'rar']) => 'fa-file-archive text-warning',
            default => 'fa-file text-secondary',
        };
    };
    $status = request('status');
@endphp

@push('css')
<style>
    .me-media-card { border-radius: 14px; overflow: hidden; transition: transform .15s, box-shadow .15s; }
    .me-media-card:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(15, 45, 74, .12); }
    .me-media-thumb { aspect-ratio: 1; display: flex; align-items: center; justify-content: center; background: repeating-conic-gradient(#f1f3f5 0% 25%, #fff 0% 50%) 50% / 16px 16px; }
    .me-media-thumb img { width: 100%; height: 100%; object-fit: contain; }
    .me-media-thumb .fa { font-size: 3rem; }
    .me-media-name { font-size: .78rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .me-media-meta { font-size: .7rem; color: #6c757d; }
    .me-media-actions .btn { padding: .1rem .4rem; font-size: .75rem; }
    .me-stat-mini { border-radius: 12px; padding: 10px 14px; }
</style>
@endpush

@section('content')
{{-- Stats --}}
<div class="row g-2 mb-3">
    @foreach([['Files', $stats['files'], 'fa-folder-open', 'primary'], ['Storage used', \ME\Models\Media::formatBytes((int) $stats['size']), 'fa-hdd', 'info'], ['Images', $stats['images'], 'fa-image', 'success'], ['Unused', $stats['unused'], 'fa-unlink', 'warning'], ['In trash', $stats['trash'], 'fa-trash', 'danger']] as [$label, $value, $icon, $tone])
        <div class="col-6 col-md">
            <div class="card glass-card me-stat-mini h-100 d-flex flex-row align-items-center gap-2">
                <i class="fas {{ $icon }} text-{{ $tone }} fa-lg"></i>
                <div><div class="small text-muted">{{ $label }}</div><div class="fw-bold">{{ $value }}</div></div>
            </div>
        </div>
    @endforeach
</div>

<div class="card glass-card w-100">
    <form method="GET" action="{{ url()->current() }}" class="mb-3">
        <div class="row g-2">
            <div class="col-md-3"><input type="text" name="search" class="form-control form-control-sm" placeholder="File name, alt or title" value="{{ request('search') }}"></div>
            <div class="col-md">
                <select name="type" class="form-select form-select-sm">
                    <option value="">All types</option>
                    <option value="image" @selected(request('type') === 'image')>Images</option>
                    <option value="file" @selected(request('type') === 'file')>Other files</option>
                </select>
            </div>
            <div class="col-md">
                <select name="owner" class="form-select form-select-sm">
                    <option value="">Any owner</option>
                    @foreach($owners as $owner)
                        <option value="{{ $owner }}" @selected(request('owner') === $owner)>{{ \ME\Services\MediaRegistry::ownerName($owner) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md">
                <select name="collection" class="form-select form-select-sm">
                    <option value="">Any collection</option>
                    @foreach($collections as $collection)
                        <option value="{{ $collection }}" @selected(request('collection') === $collection)>{{ $collection }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md">
                <select name="status" class="form-select form-select-sm">
                    <option value="">In use + unused</option>
                    <option value="unused" @selected($status === 'unused')>Unused only</option>
                    <option value="trash" @selected($status === 'trash')>Trash</option>
                </select>
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-sm btn-encodex-search rounded"><i class="fas fa-search"></i> Search</button>
                <a href="{{ url()->current() }}" class="btn btn-sm btn-encodex-clear rounded"><i class="fas fa-eraser"></i> Reset</a>
            </div>
        </div>
    </form>

    @if($status === 'trash')
        <div class="alert alert-warning small py-2">Files in the trash are removed from the disk automatically after {{ config('me_settings.media.cleanup.trash_days', 30) }} days (<code>php artisan metheme:media-cleanup</code>), or now with "Delete forever".</div>
    @endif

    <div class="row g-3">
        @forelse($media as $item)
            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                <div class="card me-media-card h-100 border">
                    <a href="{{ $item->url() }}" target="_blank" class="me-media-thumb" title="Open">
                        @if($item->isImage())
                            <img src="{{ $item->url('thumb') }}" alt="{{ $item->alt }}" loading="lazy">
                        @else
                            <i class="fa {{ $iconFor($item) }}"></i>
                        @endif
                    </a>
                    <div class="card-body p-2">
                        <div class="me-media-name" title="{{ $item->original_name }}">{{ $item->original_name }}</div>
                        <div class="me-media-meta">
                            {{ $item->human_size }}@if($item->width) · {{ $item->width }}×{{ $item->height }}@endif
                            @if($item->visibility === 'private') · <i class="fas fa-lock" title="Private"></i>@endif
                        </div>
                        <div class="me-media-meta text-truncate" title="{{ $item->owner_label }}">
                            <i class="fas {{ $item->mediable_type ? 'fa-link' : 'fa-unlink text-warning' }}"></i> {{ $item->owner_label }}
                            @if($item->mediable_type)<span class="badge bg-light text-dark border">{{ $item->collection }}</span>@endif
                        </div>
                        <div class="me-media-meta">{{ $item->created_at->format('d M Y') }}</div>
                    </div>
                    <div class="card-footer p-1 d-flex justify-content-center gap-1 me-media-actions">
                        @if($item->trashed())
                            @if(can('me_media.delete'))
                                <form method="POST" action="{{ route('media.restore', $item->id) }}">@csrf<button class="btn btn-outline-success" title="Restore"><i class="fas fa-undo"></i></button></form>
                                <form method="POST" action="{{ route('media.force-delete', $item->id) }}" onsubmit="return confirm('Delete this file forever? It cannot be undone.')">@csrf @method('DELETE')<button class="btn btn-outline-danger" title="Delete forever"><i class="fas fa-times"></i></button></form>
                            @endif
                        @else
                            <button type="button" class="btn btn-outline-secondary" title="Copy link" onclick="navigator.clipboard.writeText(@js($item->url())).then(() => toastr.success('Link copied'))"><i class="fas fa-link"></i></button>
                            <a download href="{{ $item->url() }}" class="no-loader btn btn-outline-secondary" title="Download"><i class="fas fa-download"></i></a>
                            @if(can('me_media.edit'))
                                <button type="button" class="btn btn-outline-primary" title="Alt text / title" data-bs-toggle="modal" data-bs-target="#mediaEdit"
                                    data-action="{{ route('media.update', $item) }}" data-alt="{{ $item->alt }}" data-title="{{ $item->title }}" data-name="{{ $item->original_name }}"><i class="fas fa-pen"></i></button>
                            @endif
                            @if(can('me_media.delete'))
                                <form method="POST" action="{{ route('media.destroy', $item) }}" onsubmit="return confirm('{{ $item->mediable_type ? 'This file is in use ('.e($item->owner_label).'). Move it to the trash?' : 'Move this file to the trash?' }}')">@csrf @method('DELETE')<button class="btn btn-outline-danger" title="Move to trash"><i class="fas fa-trash"></i></button></form>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-5"><i class="fas fa-photo-video fa-2x mb-2 d-block opacity-50"></i>No files found</div>
        @endforelse
    </div>

    @if($media->hasPages())
        <div class="mt-3">{{ $media->links('pagination::bootstrap-5') }}</div>
    @endif
</div>

<div class="modal fade" id="mediaEdit" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" id="mediaEditForm">
            @csrf @method('PATCH')
            <div class="modal-header"><h5 class="modal-title text-truncate"><i class="fas fa-pen me-1"></i> <span data-name></span></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <label class="form-label small fw-semibold mb-1">Alt text <span class="text-muted fw-normal">(describes the image — SEO & screen readers)</span></label>
                <input type="text" name="alt" maxlength="255" class="form-control form-control-sm mb-3">
                <label class="form-label small fw-semibold mb-1">Title</label>
                <input type="text" name="title" maxlength="255" class="form-control form-control-sm">
            </div>
            <div class="modal-footer"><button class="btn btn-sm btn-encodex-save"><i class="fas fa-save me-1"></i> Save</button></div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('mediaEdit').addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget, form = document.getElementById('mediaEditForm');
    form.action = button.dataset.action;
    form.alt.value = button.dataset.alt || '';
    form.title.value = button.dataset.title || '';
    form.querySelector('[data-name]').textContent = button.dataset.name;
});
</script>
@endpush
