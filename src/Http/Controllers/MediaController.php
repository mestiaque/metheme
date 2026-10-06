<?php

namespace ME\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use ME\Models\Media;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Media Library (all files of every package in me_media) and the file-serving route.
 */
class MediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('authorization:me_media.view')->only('index');
        $this->middleware('authorization:me_media.edit')->only('update');
        $this->middleware('authorization:me_media.delete')->only(['destroy', 'restore', 'forceDelete']);
    }

    public function index(Request $request): View
    {
        $query = Media::query()
            ->with(['mediable', 'uploadedBy'])
            ->when($request->query('status') === 'trash', fn ($q) => $q->onlyTrashed())
            ->when($request->query('status') === 'unused', fn ($q) => $q->unattached())
            ->when($request->query('type') === 'image', fn ($q) => $q->images())
            ->when($request->query('type') === 'file', fn ($q) => $q->where('mime_type', 'not like', 'image/%'))
            ->when($request->query('owner'), fn ($q, $owner) => $q->where('mediable_type', $owner))
            ->when($request->query('collection'), fn ($q, $collection) => $q->where('collection', $collection))
            ->when($request->query('search'), fn ($q, $search) => $q->where(fn ($q) => $q
                ->where('original_name', 'like', "%{$search}%")
                ->orWhere('alt', 'like', "%{$search}%")
                ->orWhere('title', 'like', "%{$search}%")));

        return view('me::media.index', [
            'media' => $query->latest('id')->paginate(48)->withQueryString(),
            'owners' => Media::query()->whereNotNull('mediable_type')->distinct()->orderBy('mediable_type')->pluck('mediable_type'),
            'collections' => Media::query()->distinct()->orderBy('collection')->pluck('collection'),
            'stats' => [
                'files' => Media::count(),
                'size' => Media::sum('size'),
                'images' => Media::images()->count(),
                'unused' => Media::unattached()->count(),
                'trash' => Media::onlyTrashed()->count(),
            ],
        ]);
    }

    public function update(Request $request, Media $media): RedirectResponse
    {
        $data = $request->validate(['alt' => 'nullable|string|max:255', 'title' => 'nullable|string|max:255']);
        me_change_log('Media updated: '.$media->original_name, 'media.update')->watch($media)->run(fn () => $media->update($data));

        return back()->with('success', __('me::me.media_saved'));
    }

    /**
     * Move to the trash (files stay on disk until the trash is emptied).
     */
    public function destroy(Media $media): RedirectResponse
    {
        me_change_log('Media trashed: '.$media->original_name, 'media.delete')->watch($media)->delete(fn () => $media->delete());

        return back()->with('success', __('me::me.media_trashed'));
    }

    public function restore(int $media): RedirectResponse
    {
        $item = Media::onlyTrashed()->findOrFail($media);
        $item->restore();
        me_change_log('Media restored: '.$item->original_name, 'media.restore')->subject($item)->record([], ['restored' => $item->original_name]);

        return back()->with('success', __('me::me.media_restored'));
    }

    /**
     * Delete permanently — the file is removed from the disk.
     */
    public function forceDelete(int $media): RedirectResponse
    {
        $item = Media::onlyTrashed()->findOrFail($media);
        me_change_log('Media deleted permanently: '.$item->original_name, 'media.force_delete')->record($item->only(['original_name', 'path', 'collection']), []);
        $item->forceDelete();

        return back()->with('success', __('me::me.media_deleted'));
    }

    /**
     * Serve a file by uuid. Private files need a valid signed URL (Media::url() makes one).
     */
    public function show(Request $request, string $media, ?string $conversion = null): StreamedResponse
    {
        $item = Media::where('uuid', $media)->firstOrFail();

        abort_if($item->visibility === 'private' && ! $request->hasValidSignature(), 403);

        $path = $item->pathFor($conversion);
        abort_unless(Storage::disk($item->disk)->exists($path), 404);

        return Storage::disk($item->disk)->response($path, $item->original_name, [
            'Cache-Control' => $item->visibility === 'private' ? 'private, max-age=600' : 'public, max-age=31536000',
        ]);
    }
}
