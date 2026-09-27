<?php

namespace ME\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'color',
    ];

    public const DEFAULT_COLOR = '#0dcaf0';

    /**
     * Badge background color (hex); falls back to the default when empty or invalid.
     */
    public function badgeColor(): string
    {
        $color = (string) ($this->color ?? '');

        return preg_match('/^#[0-9a-fA-F]{6}$/', $color) ? $color : self::DEFAULT_COLOR;
    }

    /**
     * Inline style for a role badge: the role color with black or white text, whichever is readable.
     */
    public function badgeStyle(): string
    {
        $hex = ltrim($this->badgeColor(), '#');
        [$r, $g, $b] = [hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2))];
        $text = (($r * 299 + $g * 587 + $b * 114) / 1000) > 150 ? '#212529' : '#ffffff';

        return "background-color: #{$hex}; color: {$text};";
    }

    /**
     * Parent role (null = top role).
     */
    public function parent()
    {
        return $this->belongsTo(Role::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Role::class, 'parent_id');
    }

    /**
     * Ids of every role below this one (children, grandchildren, ...).
     */
    public function descendantIds(): array
    {
        return static::descendantIdsOf([$this->id]);
    }

    /**
     * Ids of every role below the given roles (the given roles themselves are not included).
     * Cycle-safe.
     */
    public static function descendantIdsOf(array $roleIds): array
    {
        $parents = static::query()->whereNotNull('parent_id')->pluck('parent_id', 'id'); // child id => parent id
        $found = [];
        $queue = array_map('intval', $roleIds);

        while ($queue) {
            $current = array_shift($queue);
            foreach ($parents as $childId => $parentId) {
                if ((int) $parentId === $current && !isset($found[$childId]) && !in_array((int) $childId, $roleIds, true)) {
                    $found[$childId] = true;
                    $queue[] = (int) $childId;
                }
            }
        }

        return array_map('intval', array_keys($found));
    }

    /**
     * Names from the top role down to this role, e.g. "Super Admin › Admin › Staff".
     */
    public function hierarchyPath(): string
    {
        $names = [$this->name];
        $seen = [$this->id => true];
        $role = $this->parent;

        while ($role && !isset($seen[$role->id])) {
            array_unshift($names, $role->name);
            $seen[$role->id] = true;
            $role = $role->parent;
        }

        return implode(' › ', $names);
    }

    /**
     * The encodex role is permanent: it can never be deleted or have its slug changed.
     */
    protected static function booted(): void
    {
        static::deleting(function (Role $role) {
            if ($role->slug === 'encodex') {
                return false;
            }
        });

        static::updating(function (Role $role) {
            if ($role->getOriginal('slug') === 'encodex' && $role->isDirty('slug')) {
                return false;
            }
        });
    }

    /**
     * The users that belong to the role.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function rolePermission()
    {
        return $this->hasOne(RolePermission::class);
    }

    public function hasPermission($permission)
    {
        if ($this->rolePermission) {
            $permissions = $this->rolePermission->permissions ?? [];
            return in_array($permission, $permissions);
        }
        return false;
    }
}
