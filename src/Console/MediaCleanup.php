<?php

namespace ME\Console;

use Illuminate\Console\Command;
use ME\Models\Media;

class MediaCleanup extends Command
{
    protected $signature = 'metheme:media-cleanup {--dry-run : Only show what would be deleted}';

    protected $description = 'Delete uploads that were never attached and empty the media trash (files are removed from the disk)';

    public function handle(): int
    {
        $hours = (int) config('me_settings.media.cleanup.unattached_hours', 24);
        $days = (int) config('me_settings.media.cleanup.trash_days', 30);

        $unattached = Media::unattached()->where('created_at', '<', now()->subHours($hours))->get();
        $trashed = Media::onlyTrashed()->where('deleted_at', '<', now()->subDays($days))->get();

        $this->info("Unattached uploads older than {$hours}h: {$unattached->count()}");
        $this->info("Trash older than {$days} days: {$trashed->count()}");

        if (! $this->option('dry-run')) {
            $unattached->merge($trashed)->each->forceDelete();
            $this->info('Deleted.');
        }

        return self::SUCCESS;
    }
}
