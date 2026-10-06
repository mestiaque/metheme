<?php

namespace ME\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Number;
use ME\Services\MediaRegistry;

/**
 * One uploaded file of any package (me_media). Attached to a model through HasMedia.
 *
 * @property array<string, string>|null $conversions
 */
class Media extends Model
{
    use SoftDeletes;

    protected $table = 'me_media';

    protected $fillable = [
        'uuid', 'mediable_type', 'mediable_id', 'collection', 'disk', 'path', 'original_name', 'mime_type',
        'extension', 'size', 'width', 'height', 'conversions', 'visibility', 'alt', 'title', 'sort_order', 'hash',
        'custom_properties', 'uploaded_by_type', 'uploaded_by_id',
    ];

    protected $casts = [
        'conversions' => 'array',
        'custom_properties' => 'array',
        'size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        // Files leave the disk only on a permanent delete (soft delete = trash, can be restored)
        static::forceDeleted(fn (Media $media) => $media->deleteFiles());
    }

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    public function uploadedBy(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Public URL (or a signed URL for private files). A missing conversion falls back to the original.
     */
    public function url(?string $conversion = null, int $privateMinutes = 60): string
    {
        $path = $this->pathFor($conversion);

        if ($this->visibility === 'private') {
            return URL::temporarySignedRoute('me.media.show', now()->addMinutes($privateMinutes), array_filter([
                'media' => $this->uuid,
                'conversion' => $conversion && $path !== $this->path ? $conversion : null,
            ]));
        }

        if ($this->disk === 'public') {
            return asset('storage/'.ltrim($path, '/')); // follows the current host, like asset()
        }

        return Storage::disk($this->disk)->url($path);
    }

    /**
     * $media->url — URL of the original (same as url()).
     */
    public function getUrlAttribute(): string
    {
        return $this->url();
    }

    /**
     * $media->thumb_url — URL of the "thumb" conversion, the original until it exists.
     */
    public function getThumbUrlAttribute(): string
    {
        return $this->url('thumb');
    }

    /**
     * Storage path of the original or of a conversion (original when the conversion does not exist yet).
     */
    public function pathFor(?string $conversion = null): string
    {
        return ($conversion && ! empty($this->conversions[$conversion])) ? $this->conversions[$conversion] : $this->path;
    }

    public function hasConversion(string $conversion): bool
    {
        return ! empty($this->conversions[$conversion]);
    }

    public function absolutePath(?string $conversion = null): string
    {
        return Storage::disk($this->disk)->path($this->pathFor($conversion));
    }

    public function exists(): bool
    {
        return Storage::disk($this->disk)->exists($this->path);
    }

    public function isImage(): bool
    {
        return str_starts_with((string) $this->mime_type, 'image/');
    }

    /**
     * Raster image we can resize with GD (not SVG / ICO).
     */
    public function isResizable(): bool
    {
        return in_array($this->mime_type, ['image/jpeg', 'image/png', 'image/webp', 'image/gif'], true);
    }

    public function getHumanSizeAttribute(): string
    {
        return static::formatBytes((int) $this->size);
    }

    /**
     * "1.4 MB" — without Number::fileSize(), which needs the intl PHP extension.
     */
    public static function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = $bytes > 0 ? min((int) floor(log($bytes, 1024)), count($units) - 1) : 0;

        return round($bytes / (1024 ** $power), 1).' '.$units[$power];
    }

    /**
     * "Product #12 · gallery", "Setting app_logo", "Unattached".
     */
    public function getOwnerLabelAttribute(): string
    {
        if (! $this->mediable_type) {
            return 'Unattached';
        }

        $name = MediaRegistry::ownerName($this->mediable_type);

        if ($this->mediable_type === Setting::class) {
            return 'Setting '.($this->mediable?->key ?? '#'.$this->mediable_id);
        }

        return "{$name} #{$this->mediable_id}";
    }

    public function scopeInCollection(Builder $query, string $collection): Builder
    {
        return $query->where('collection', $collection);
    }

    public function scopeImages(Builder $query): Builder
    {
        return $query->where('mime_type', 'like', 'image/%');
    }

    public function scopeUnattached(Builder $query): Builder
    {
        return $query->whereNull('mediable_id');
    }

    /**
     * Remove the original and every conversion from the disk.
     */
    public function deleteFiles(): void
    {
        $disk = Storage::disk($this->disk);
        $paths = array_filter(array_merge([$this->path], array_values($this->conversions ?? [])));

        // Never delete a file another media row still points to (imported rows can share paths)
        $shared = static::withTrashed()->where('id', '!=', $this->id)->whereIn('path', $paths)->pluck('path')->all();
        $disk->delete(array_values(array_diff($paths, $shared)));
    }
}
