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
        'description'
    ];

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
