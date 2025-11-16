<?php

namespace Encodex\Metheme\Models;

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
