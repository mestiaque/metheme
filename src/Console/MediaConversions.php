<?php

namespace ME\Console;

use Illuminate\Console\Command;
use ME\Jobs\GenerateMediaConversions;
use ME\Models\Media;
use ME\Services\MediaUploader;

class MediaConversions extends Command
{
    protected $signature = 'metheme:media-conversions
                            {--force : Remake conversions that already exist}
                            {--collection= : Only this collection}';

    protected $description = 'Create the image conversions (thumbnails) of media that do not have them yet';

    public function handle(): int
    {
        $query = Media::query()
            ->whereIn('mime_type', ['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
            ->when($this->option('collection'), fn ($q, $c) => $q->where('collection', $c))
            ->when(! $this->option('force'), fn ($q) => $q->whereNull('conversions'));

        $bar = $this->output->createProgressBar($query->count());

        $query->with('mediable')->lazyById()->each(function (Media $media) use ($bar) {
            $owner = $media->mediable;
            $rules = $owner && method_exists($owner, 'mediaCollection') ? $owner->mediaCollection($media->collection) : [];

            if ($conversions = MediaUploader::conversionsFor($rules)) {
                GenerateMediaConversions::dispatchFor($media, $conversions, now: true);
            }
            $bar->advance();
        });

        $bar->finish();
        $this->newLine();

        return self::SUCCESS;
    }
}
