<?php

namespace ME\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use ME\Models\GeoLocation;

/**
 * Public AJAX endpoints for cascading address selects:
 * countries -> children(country) = divisions -> children(division) = districts -> children(district) = upazilas.
 */
class GeoLocationController extends Controller
{
    public function countries(): JsonResponse
    {
        return $this->respond(GeoLocation::query()->ofType(GeoLocation::TYPE_COUNTRY), GeoLocation::TYPE_COUNTRY);
    }

    public function children(GeoLocation $geoLocation): JsonResponse
    {
        return $this->respond($geoLocation->children()->getQuery(), $geoLocation->childType());
    }

    /**
     * @param  Builder<GeoLocation>  $query
     */
    private function respond(Builder $query, ?string $type): JsonResponse
    {
        return response()->json([
            'type'       => $type,
            'child_type' => $type ? GeoLocation::HIERARCHY[$type] : null,
            'data'       => $query->active()->orderBy('name')->get(['id', 'parent_id', 'type', 'name', 'bn_name']),
        ]);
    }
}
