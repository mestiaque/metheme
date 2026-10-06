<?php

namespace ME\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ME\Jobs\GenerateMediaConversions;
use ME\Models\Media;
use ME\Models\Setting;
use ME\Models\User;
use ME\Services\MediaRegistry;
use ME\Services\MediaUploader;
use Throwable;

/**
 * Moves file references from old columns / tables / settings into me_media. Files are NOT moved:
 * each media row points to the existing file. Safe to run again (already imported rows are skipped).
 */
class MediaImport extends Command
{
    protected $signature = 'metheme:media-import {--dry-run : Only count what would be imported}';

    protected $description = 'Import old image/file columns of every package into me_media (files stay where they are)';

    private int $created = 0;

    private int $skipped = 0;

    private int $missing = 0;

    public function handle(MediaUploader $uploader): int
    {
        $sources = array_merge([
            ['type' => 'setting', 'key' => 'app_logo', 'directory' => 'images/app_logo'],
            ['type' => 'setting', 'key' => 'app_ico', 'directory' => 'images/app_ico'],
            ['type' => 'column', 'model' => User::class, 'column' => 'profile_image', 'collection' => 'avatar', 'directory' => 'images/profile_images'],
        ], MediaRegistry::imports());

        foreach ($sources as $source) {
            $label = $source['type'].': '.($source['key'] ?? ($source['table'] ?? (($source['model'] ?? '').'.'.($source['column'] ?? ''))));
            $before = [$this->created, $this->skipped, $this->missing];

            try {
                match ($source['type']) {
                    'setting' => $this->importSetting($uploader, $source),
                    'column' => $this->importColumn($uploader, $source),
                    'table' => $this->importTable($uploader, $source),
                };
            } catch (Throwable $e) {
                $this->warn("{$label}: skipped — {$e->getMessage()}");

                continue;
            }

            $this->line(sprintf('%-55s imported %d, already done %d, file missing %d', $label,
                $this->created - $before[0], $this->skipped - $before[1], $this->missing - $before[2]));
        }

        if (! $this->option('dry-run')) {
            $mediaIdFor = fn (string $origin) => Media::withTrashed()->where('custom_properties->imported_from', $origin)->value('id');
            foreach (MediaRegistry::afterImportCallbacks() as $callback) {
                $callback($mediaIdFor, $this);
            }
        }

        $this->info(($this->option('dry-run') ? '[dry run] ' : '')."Done: {$this->created} imported, {$this->skipped} already imported, {$this->missing} files missing.");

        return self::SUCCESS;
    }

    /**
     * @param  array<string, mixed>  $source
     */
    private function importSetting(MediaUploader $uploader, array $source): void
    {
        $setting = Setting::where('key', $source['key'])->first();

        if (! $setting || blank($setting->value) || Str::isUuid((string) $setting->value)) {
            $this->skipped += $setting && Str::isUuid((string) $setting->value) ? 1 : 0;

            return;
        }

        $media = $this->register($uploader, $source, $setting->value, [
            'mediable_type' => Setting::class, 'mediable_id' => $setting->id, 'collection' => 'image',
        ], "setting:{$setting->key}", $setting->mediaCollection('image'));

        if ($media && ! $this->option('dry-run')) {
            $setting->forceFill(['value' => $media->uuid])->save();
        }
    }

    /**
     * @param  array<string, mixed>  $source
     */
    private function importColumn(MediaUploader $uploader, array $source): void
    {
        $model = new $source['model'];

        if (! Schema::hasColumn($model->getTable(), $source['column'])) {
            return; // column already removed
        }

        $source['model']::query()->whereNotNull($source['column'])->where($source['column'], '!=', '')
            ->lazyById()->each(function ($row) use ($uploader, $source) {
                $this->register($uploader, $source, $row->{$source['column']}, [
                    'mediable_type' => $row->getMorphClass(), 'mediable_id' => $row->getKey(), 'collection' => $source['collection'],
                ], 'column:'.$row->getTable().'.'.$source['column'].':'.$row->getKey(),
                    method_exists($row, 'mediaCollection') ? $row->mediaCollection($source['collection']) : []);
            });
    }

    /**
     * @param  array<string, mixed>  $source
     */
    private function importTable(MediaUploader $uploader, array $source): void
    {
        if (! Schema::hasTable($source['table'])) {
            return; // table already removed
        }

        $owner = new $source['model'];

        DB::table($source['table'])->orderBy('id')->lazy()->each(function ($row) use ($uploader, $source, $owner) {
            $media = $this->register($uploader, $source, $row->{$source['path_column'] ?? 'path'}, [
                'mediable_type' => $owner->getMorphClass(),
                'mediable_id' => $row->{$source['foreign_key']},
                'collection' => $source['collection'],
                'sort_order' => (int) ($row->{$source['order_column'] ?? 'sort_order'} ?? 0),
            ], "{$source['table']}:{$row->id}", method_exists($owner, 'mediaCollection') ? $owner->mediaCollection($source['collection']) : [], false);

            // Existing conversions (e.g. a thumbnail column) are kept instead of being made again
            if ($media && ! empty($source['conversions'])) {
                $conversions = collect($source['conversions'])->map(fn ($column) => $row->{$column} ?? null)->filter()->all();
                $conversions ? $media->forceFill(['conversions' => $conversions])->saveQuietly() : $this->makeConversions($media, $owner);
            }
        });
    }

    /**
     * Create the media row for one old value (skipped when already imported or the file is missing).
     *
     * @param  array<string, mixed>  $source
     * @param  array<string, mixed>  $attributes
     * @param  array<string, mixed>  $rules
     */
    private function register(MediaUploader $uploader, array $source, string $value, array $attributes, string $origin, array $rules, bool $conversions = true): ?Media
    {
        if (Media::withTrashed()->where('custom_properties->imported_from', $origin)->exists()) {
            $this->skipped++;

            return null;
        }

        $disk = $source['disk'] ?? 'public';
        $path = ltrim(str_contains($value, '/') ? $value : trim($source['directory'] ?? '', '/').'/'.$value, '/');

        if (str_starts_with($value, 'http') || ! Storage::disk($disk)->exists($path)) {
            $this->missing++;

            return null;
        }

        $this->created++;

        if ($this->option('dry-run')) {
            return null;
        }

        $rules = ['sync_conversions' => true] + $rules;
        if (! $conversions) {
            $rules['conversions'] = [];
        }

        return $uploader->register($disk, $path, $attributes + ['custom_properties' => ['imported_from' => $origin]], $rules);
    }

    private function makeConversions(Media $media, object $owner): void
    {
        $rules = method_exists($owner, 'mediaCollection') ? $owner->mediaCollection($media->collection) : [];
        if ($media->isResizable() && ($conversions = MediaUploader::conversionsFor($rules))) {
            GenerateMediaConversions::dispatchFor($media, $conversions, now: true);
        }
    }
}
