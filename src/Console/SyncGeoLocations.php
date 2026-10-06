<?php

namespace ME\Console;

use Illuminate\Console\Command;
use ME\Services\GeoLocationSync;

class SyncGeoLocations extends Command
{
    protected $signature = 'metheme:sync-geo-locations {--path= : JSON file to read (default: metheme src/public/geo-data.json)}';

    protected $description = 'Sync country, divisions, districts and upazilas from geo-data.json into geo_locations';

    public function handle(GeoLocationSync $sync): int
    {
        $counts = $sync->sync($this->option('path') ?: null);

        $this->table(['Type', 'Rows'], collect($counts)->map(fn ($count, $type) => [$type, $count])->values()->all());
        $this->info('Geo locations synced.');

        return self::SUCCESS;
    }
}
