<?php

namespace ME\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Roles extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'slug',
        'description'
    ];

    /** Super admin can't be deleted or renamed; only its permissions can change. */
    public const SUPER_ADMIN_SLUGS = ['super-admin', 'super_admin'];

    public function isSuperAdmin(): bool
    {
        return in_array($this->getOriginal('slug') ?? $this->slug, self::SUPER_ADMIN_SLUGS, true);
    }

    /** Whether the given user (default: the logged-in one) has this role. */
    public function belongsToUser($user = null): bool
    {
        $userId = $user?->id ?? auth()->id();

        return $userId && DB::table('role_user')->where('role_id', $this->id)->where('user_id', $userId)->exists();
    }

    /**
     * The encodex role is never visible through this model (lists, find, counts, relations),
     * and is permanent: it can never be deleted or have its slug changed.
     * Use Role (singular) when the full set is needed, e.g. for permission checks.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('hide_encodex', function (Builder $query) {
            $query->where($query->qualifyColumn('slug'), '!=', 'encodex');
        });

        static::deleting(function (Roles $role) {
            if ($role->slug === 'encodex' || $role->isSuperAdmin()) {
                return false;
            }
        });

        static::updating(function (Roles $role) {
            if ($role->getOriginal('slug') === 'encodex' && $role->isDirty('slug')) {
                return false;
            }
            if ($role->isSuperAdmin() && $role->isDirty(['name', 'slug', 'description'])) {
                return false;
            }
        });
    }

    /**
     * The users that belong to the role.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(Users::class, 'role_user', 'role_id', 'user_id');
    }

    public function rolePermission()
    {
        return $this->hasOne(RolePermission::class, 'role_id');
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
