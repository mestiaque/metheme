<?php

namespace ME\Services;

use Illuminate\Support\Facades\DB;
use ME\Models\GeoLocation;
use RuntimeException;

/**
 * Loads src/public/geo-data.json (divisions > districts > upozila) into geo_locations
 * under a single "Bangladesh" country row. Safe to re-run: rows are matched on
 * (type, source_id), names/coordinates are updated, nothing is duplicated.
 */
class GeoLocationSync
{
    public const COUNTRY_SOURCE_ID = 1;

    /**
     * @return array{country: int, division: int, district: int, upazila: int}
     */
    public function sync(?string $path = null): array
    {
        $path ??= __DIR__.'/../public/geo-data.json';

        if (! is_file($path)) {
            throw new RuntimeException("Geo data file not found: {$path}");
        }

        $divisions = json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR)['divisions'] ?? [];

        return DB::transaction(function () use ($divisions) {
            $country = GeoLocation::updateOrCreate(
                ['type' => GeoLocation::TYPE_COUNTRY, 'source_id' => self::COUNTRY_SOURCE_ID],
                ['name' => 'Bangladesh', 'bn_name' => 'বাংলাদেশ', 'parent_id' => null],
            );

            $divisionRows = [];
            $districtRows = [];
            $upazilaRows = [];

            foreach ($divisions as $division) {
                $divisionRows[] = $this->row($division, $country->id);

                foreach ($division['districts'] ?? [] as $district) {
                    $districtRows[] = $this->row($district, (int) $division['id']);

                    foreach ($district['upozila'] ?? $district['upazilas'] ?? [] as $upazila) {
                        $upazilaRows[] = $this->row($upazila, (int) $district['id']);
                    }
                }
            }

            $this->upsertLevel(GeoLocation::TYPE_DIVISION, $divisionRows);
            $this->upsertLevel(GeoLocation::TYPE_DISTRICT, $districtRows, GeoLocation::TYPE_DIVISION);
            $this->upsertLevel(GeoLocation::TYPE_UPAZILA, $upazilaRows, GeoLocation::TYPE_DISTRICT);

            return [
                'country' => 1,
                'division' => count($divisionRows),
                'district' => count($districtRows),
                'upazila' => count($upazilaRows),
            ];
        });
    }

    /**
     * @param  array<string, mixed>  $item
     * @return array{source_id: int, parent: int, name: string, bn_name: ?string, lat: ?string, long: ?string}
     */
    private function row(array $item, int $parent): array
    {
        return [
            'source_id' => (int) $item['id'],
            'parent' => $parent,
            'name' => trim($item['name']),
            'bn_name' => isset($item['bn_name']) ? trim($item['bn_name']) : null,
            'lat' => filled($item['lat'] ?? null) ? $item['lat'] : null,
            'long' => filled($item['long'] ?? null) ? $item['long'] : null,
        ];
    }

    /**
     * Upsert one level. "parent" is the parent's JSON id, translated to its row id
     * via $parentType (or used as-is for divisions, where it is already the country row id).
     *
     * @param  array<int, array<string, mixed>>  $rows
     */
    private function upsertLevel(string $type, array $rows, ?string $parentType = null): void
    {
        $parentIds = $parentType
            ? GeoLocation::ofType($parentType)->pluck('id', 'source_id')
            : null;

        $now = now();

        $records = array_map(fn (array $row) => [
            'type' => $type,
            'source_id' => $row['source_id'],
            'parent_id' => $parentIds ? $parentIds[$row['parent']] : $row['parent'],
            'name' => $row['name'],
            'bn_name' => $row['bn_name'],
            'lat' => $row['lat'],
            'long' => $row['long'],
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ], $rows);

        foreach (array_chunk($records, 500) as $chunk) {
            GeoLocation::upsert($chunk, ['type', 'source_id'], ['parent_id', 'name', 'bn_name', 'lat', 'long', 'updated_at']);
        }
    }
}
