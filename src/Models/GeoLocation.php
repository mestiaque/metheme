<?php

namespace ME\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GeoLocation extends Model
{
    public const TYPE_COUNTRY = 'country';

    public const TYPE_DIVISION = 'division';

    public const TYPE_DISTRICT = 'district';

    public const TYPE_UPAZILA = 'upazila';

    /** Child type of each level, top to bottom. */
    public const HIERARCHY = [
        self::TYPE_COUNTRY => self::TYPE_DIVISION,
        self::TYPE_DIVISION => self::TYPE_DISTRICT,
        self::TYPE_DISTRICT => self::TYPE_UPAZILA,
        self::TYPE_UPAZILA => null,
    ];

    protected $fillable = [
        'parent_id',
        'type',
        'source_id',
        'name',
        'bn_name',
        'lat',
        'long',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'lat' => 'float',
            'long' => 'float',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function childType(): ?string
    {
        return self::HIERARCHY[$this->type] ?? null;
    }
}
