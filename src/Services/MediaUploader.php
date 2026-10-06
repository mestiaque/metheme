<?php

namespace ME\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use ME\Jobs\GenerateMediaConversions;
use ME\Models\Media;
use RuntimeException;

/**
 * Stores files in me_media: validates, saves to {directory}/YYYY/MM/{uuid}.{ext}, reads size / dimensions / hash,
 * and queues the image conversions (thumbnails). Used by the HasMedia trait, seeders and the import command.
 */
class MediaUploader
{
    /**
     * Store an uploaded file.
     *
     * @param  array<string, mixed>  $attributes  Media columns (mediable_*, collection, visibility, alt …)
     * @param  array<string, mixed>  $rules  collection rules: mimes, max_kb, conversions, disk
     *
     * @throws ValidationException
     */
    public function upload(UploadedFile $file, array $attributes = [], array $rules = []): Media
    {
        $this->validate($file, $rules);

        $disk = $rules['disk'] ?? config('me_settings.media.disk', 'public');
        $extension = strtolower($file->getClientOriginalExtension() ?: ($file->guessExtension() ?: 'bin'));
        $uuid = (string) Str::uuid();
        $path = $file->storeAs($this->directory(), "{$uuid}.{$extension}", $disk);

        if ($path === false) {
            throw new RuntimeException('The file could not be saved.');
        }

        return $this->create($disk, $path, [
            'uuid' => $uuid,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'extension' => $extension,
        ] + $attributes, $rules);
    }

    /**
     * Copy a local file (seeders, tests, imports from outside the disk) into media storage.
     *
     * @param  array<string, mixed>  $attributes
     * @param  array<string, mixed>  $rules
     */
    public function fromPath(string $absolutePath, array $attributes = [], array $rules = []): Media
    {
        if (! is_file($absolutePath)) {
            throw new RuntimeException("File not found: {$absolutePath}");
        }

        $disk = $rules['disk'] ?? config('me_settings.media.disk', 'public');
        $extension = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION) ?: 'bin');
        $uuid = (string) Str::uuid();
        $path = $this->directory()."/{$uuid}.{$extension}";
        Storage::disk($disk)->put($path, file_get_contents($absolutePath));

        return $this->create($disk, $path, [
            'uuid' => $uuid,
            'original_name' => basename($absolutePath),
            'mime_type' => mime_content_type($absolutePath) ?: null,
            'extension' => $extension,
        ] + $attributes, $rules);
    }

    /**
     * Register a file that is already on the disk (no copy, no move) — used by metheme:media-import.
     *
     * @param  array<string, mixed>  $attributes
     * @param  array<string, mixed>  $rules
     */
    public function register(string $disk, string $path, array $attributes = [], array $rules = []): Media
    {
        $storage = Storage::disk($disk);

        if (! $storage->exists($path)) {
            throw new RuntimeException("File not found on disk [{$disk}]: {$path}");
        }

        return $this->create($disk, $path, [
            'uuid' => (string) Str::uuid(),
            'original_name' => basename($path),
            'mime_type' => $storage->mimeType($path) ?: null,
            'extension' => strtolower(pathinfo($path, PATHINFO_EXTENSION)),
        ] + $attributes, $rules);
    }

    /**
     * @param  array<string, mixed>  $rules
     *
     * @throws ValidationException
     */
    public function validate(UploadedFile $file, array $rules = []): void
    {
        $mimes = $rules['mimes'] ?? config('me_settings.media.mimes');
        $maxKb = (int) ($rules['max_kb'] ?? config('me_settings.media.max_kb', 5120));
        $field = $rules['field'] ?? 'file';

        Validator::make([$field => $file], [$field => ['required', 'file', "mimes:{$mimes}", "max:{$maxKb}"]])->validate();
    }

    /**
     * Conversions for a collection: rules['conversions'] (name => longest side px), else the config default.
     *
     * @param  array<string, mixed>  $rules
     * @return array<string, int>
     */
    public static function conversionsFor(array $rules): array
    {
        return array_map('intval', $rules['conversions'] ?? config('me_settings.media.conversions', []));
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, mixed>  $rules
     */
    private function create(string $disk, string $path, array $attributes, array $rules): Media
    {
        $absolute = Storage::disk($disk)->path($path);
        $dimensions = @getimagesize($absolute) ?: [null, null];
        $user = auth()->user();

        $media = Media::create(array_merge([
            'disk' => $disk,
            'path' => $path,
            'size' => (int) @filesize($absolute),
            'width' => $dimensions[0],
            'height' => $dimensions[1],
            'hash' => @sha1_file($absolute) ?: null,
            'collection' => 'default',
            'visibility' => $rules['visibility'] ?? 'public',
            'uploaded_by_type' => $user ? $user::class : null,
            'uploaded_by_id' => $user?->getAuthIdentifier(),
        ], $attributes));

        if ($media->isResizable() && ($conversions = self::conversionsFor($rules))) {
            GenerateMediaConversions::dispatchFor($media, $conversions, $rules['sync_conversions'] ?? false);
        }

        return $media;
    }

    private function directory(): string
    {
        return trim(config('me_settings.media.directory', 'media'), '/').'/'.now()->format('Y/m');
    }
}
