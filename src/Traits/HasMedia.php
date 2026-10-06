<?php

namespace ME\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use ME\Models\Media;
use ME\Services\MediaUploader;

/**
 * Gives a model files/images stored in me_media (one table for every package).
 *
 * Declare the model's collections (slots) by overriding mediaCollections():
 *
 *     protected function mediaCollections(): array
 *     {
 *         return [
 *             'avatar'  => ['single' => true, 'mimes' => 'jpg,jpeg,png,webp', 'max_kb' => 2048],
 *             'gallery' => ['mimes' => 'jpg,jpeg,png,webp', 'max_kb' => 4096, 'conversions' => ['thumb' => 400]],
 *         ];
 *     }
 *
 * Eager load with ->with('media') to avoid one query per model in lists.
 */
trait HasMedia
{
    public static function bootHasMedia(): void
    {
        // Deleting the model moves its files to the trash (kept while the model is only soft-deleted)
        static::deleting(function ($model) {
            if (method_exists($model, 'isForceDeleting') && ! $model->isForceDeleting()) {
                return;
            }
            $model->media()->get()->each->delete();
        });
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable')->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Collections of this model. Keys: single (bool), mimes, max_kb, conversions (name => px), visibility, disk.
     *
     * @return array<string, array<string, mixed>>
     */
    protected function mediaCollections(): array
    {
        return [];
    }

    /**
     * Rules of one collection (unknown collections get the config defaults).
     *
     * @return array<string, mixed>
     */
    public function mediaCollection(string $collection): array
    {
        return ($this->mediaCollections()[$collection] ?? []) + ['single' => false];
    }

    /**
     * Files of a collection, in order. Uses the eager-loaded "media" relation when it is loaded.
     *
     * @return Collection<int, Media>
     */
    public function getMedia(string $collection = 'default'): Collection
    {
        $media = $this->relationLoaded('media') ? $this->getRelation('media') : $this->media()->get();

        return $media->where('collection', $collection)->sortBy([['sort_order', 'asc'], ['id', 'asc']])->values();
    }

    public function firstMedia(string $collection = 'default'): ?Media
    {
        return $this->getMedia($collection)->first();
    }

    public function hasMedia(string $collection = 'default'): bool
    {
        return $this->getMedia($collection)->isNotEmpty();
    }

    /**
     * URL of the first file of a collection (optionally a conversion such as "thumb"), or $default.
     */
    public function mediaUrl(string $collection = 'default', ?string $conversion = null, ?string $default = null): ?string
    {
        return $this->firstMedia($collection)?->url($conversion) ?? $default;
    }

    /**
     * Add a file (upload, local path, or an unattached Media) to a collection.
     * In a "single" collection the previous file goes to the trash.
     *
     * @param  array<string, mixed>  $attributes  extra Media columns (alt, title, sort_order, visibility …)
     *
     * @throws ValidationException
     */
    public function addMedia(UploadedFile|string|Media $file, string $collection = 'default', array $attributes = []): Media
    {
        $rules = $this->mediaCollection($collection);
        $owner = [
            'mediable_type' => $this->getMorphClass(),
            'mediable_id' => $this->getKey(),
            'collection' => $collection,
        ];
        $attributes += ['sort_order' => (int) $this->media()->where('collection', $collection)->max('sort_order') + 1];

        return DB::transaction(function () use ($file, $collection, $rules, $owner, $attributes) {
            $media = match (true) {
                $file instanceof Media => tap($file)->update($owner + Arr::only($attributes, ['alt', 'title', 'sort_order'])),
                $file instanceof UploadedFile => app(MediaUploader::class)->upload($file, $owner + $attributes, $rules),
                default => app(MediaUploader::class)->fromPath($file, $owner + $attributes, $rules),
            };

            if ($rules['single']) {
                $this->media()->where('collection', $collection)->where('id', '!=', $media->id)->get()->each->delete();
            }

            $this->unsetRelation('media');

            return $media;
        });
    }

    /**
     * Attach a file that is already on a storage disk (no copy) — seeders and imports.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function addMediaFromDisk(string $path, string $collection = 'default', string $disk = 'public', array $attributes = []): Media
    {
        $rules = $this->mediaCollection($collection);
        $attributes += ['sort_order' => (int) $this->media()->where('collection', $collection)->max('sort_order') + 1];

        $media = app(MediaUploader::class)->register($disk, $path, [
            'mediable_type' => $this->getMorphClass(),
            'mediable_id' => $this->getKey(),
            'collection' => $collection,
        ] + $attributes, $rules);

        if ($rules['single']) {
            $this->media()->where('collection', $collection)->where('id', '!=', $media->id)->get()->each->delete();
        }
        $this->unsetRelation('media');

        return $media;
    }

    /**
     * Put a file in a single-file collection (avatar, logo …), trashing the old one.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function replaceMedia(UploadedFile|string|Media $file, string $collection = 'default', array $attributes = []): Media
    {
        $media = $this->addMedia($file, $collection, $attributes);
        $this->media()->where('collection', $collection)->where('id', '!=', $media->id)->get()->each->delete();
        $this->unsetRelation('media');

        return $media;
    }

    /**
     * Move every file of a collection to the trash.
     */
    public function clearMedia(string $collection = 'default'): void
    {
        $this->media()->where('collection', $collection)->get()->each->delete();
        $this->unsetRelation('media');
    }

    /**
     * Trash some files of this model (ids that belong to another model are ignored).
     *
     * @param  array<int, int|string>  $ids
     */
    public function deleteMedia(array $ids, ?string $collection = null): void
    {
        $this->media()->whereIn('id', array_map('intval', $ids))
            ->when($collection, fn ($q) => $q->where('collection', $collection))
            ->get()->each->delete();
        $this->unsetRelation('media');
    }

    /**
     * Save a new order: the first id becomes the main file.
     *
     * @param  array<int, int|string>  $ids
     */
    public function reorderMedia(string $collection, array $ids): void
    {
        foreach (array_values(array_map('intval', $ids)) as $position => $id) {
            $this->media()->where('collection', $collection)->where('id', $id)->update(['sort_order' => $position]);
        }
        $this->unsetRelation('media');
    }

    /**
     * Apply the me::components.media-input form fields for a collection:
     *   {field}[]          new uploads
     *   {field}_remove[]   media ids to trash
     *   {field}_order[]    media ids in display order
     *   {field}_primary    media id to show first
     *
     * @throws ValidationException
     */
    public function syncMediaFromRequest(Request $request, string $collection, ?string $field = null): void
    {
        $field ??= $collection;

        if ($remove = (array) $request->input("{$field}_remove", [])) {
            $this->deleteMedia($remove, $collection);
        }

        if ($order = (array) $request->input("{$field}_order", [])) {
            $this->reorderMedia($collection, $order);
        }

        if ($primary = (int) $request->input("{$field}_primary")) {
            $ids = $this->media()->where('collection', $collection)->orderBy('sort_order')->orderBy('id')->pluck('id')->all();
            if (in_array($primary, $ids, true)) {
                $this->reorderMedia($collection, array_merge([$primary], array_diff($ids, [$primary])));
            }
        }

        $files = $request->file($field);
        foreach (is_array($files) ? $files : array_filter([$files]) as $file) {
            try {
                $this->addMedia($file, $collection);
            } catch (ValidationException $e) {
                // Report the error under the form field, not the uploader's internal "file" key
                throw ValidationException::withMessages([$field => $e->validator->errors()->first()]);
            }
        }
    }
}
