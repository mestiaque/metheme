<?php

namespace ME\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use ME\Models\Media;

/**
 * Makes the smaller webp versions of an image (e.g. thumb = longest side 400px) and saves their paths
 * in me_media.conversions. Runs in the background so uploads stay fast.
 */
class GenerateMediaConversions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    /**
     * @param  array<string, int>  $conversions  name => longest side in px
     */
    public function __construct(public int $mediaId, public array $conversions) {}

    /**
     * Queue the job. With the "sync" queue it runs after the response is sent; $now runs it immediately.
     *
     * @param  array<string, int>  $conversions
     */
    public static function dispatchFor(Media $media, array $conversions, bool $now = false): void
    {
        if ($now) {
            static::dispatchSync($media->id, $conversions);

            return;
        }

        $pending = static::dispatch($media->id, $conversions);

        if (config('queue.connections.'.config('queue.default').'.driver') === 'sync') {
            $pending->afterResponse();
        }
    }

    public function handle(): void
    {
        $media = Media::withTrashed()->find($this->mediaId);

        if (! $media || ! $media->isResizable() || ! $media->exists()) {
            return;
        }

        $disk = Storage::disk($media->disk);
        $source = @imagecreatefromstring($disk->get($media->path));

        if (! $source) {
            return;
        }

        $width = imagesx($source);
        $height = imagesy($source);
        $paths = $media->conversions ?? [];
        $base = dirname($media->path).'/conversions/'.pathinfo($media->path, PATHINFO_FILENAME);

        foreach ($this->conversions as $name => $size) {
            $scale = min(1, max(1, (int) $size) / max($width, $height));
            $w = max(1, (int) round($width * $scale));
            $h = max(1, (int) round($height * $scale));

            $image = imagecreatetruecolor($w, $h);
            imagealphablending($image, false);
            imagesavealpha($image, true);
            imagefill($image, 0, 0, imagecolorallocatealpha($image, 0, 0, 0, 127));
            imagecopyresampled($image, $source, 0, 0, 0, 0, $w, $h, $width, $height);

            ob_start();
            imagewebp($image, null, (int) config('me_settings.media.quality', 80));
            $path = "{$base}-{$name}.webp";
            $disk->put($path, ob_get_clean());

            if (! empty($paths[$name]) && $paths[$name] !== $path) {
                $disk->delete($paths[$name]);
            }
            $paths[$name] = $path;
        }

        $media->forceFill(['conversions' => $paths])->saveQuietly();
    }
}
