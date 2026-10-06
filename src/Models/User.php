<?php

namespace ME\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use ME\Traits\HasMedia;

class User extends Authenticatable
{
    use HasFactory, HasMedia, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'phone',
        'role_id',
        'role_title',
        'approved_by',
        'approved_at',
        'is_active',
        'status',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Profile photo in me_media (collection "avatar").
     */
    protected function mediaCollections(): array
    {
        return [
            'avatar' => ['single' => true, 'mimes' => 'jpg,jpeg,png,gif,webp', 'max_kb' => 2048, 'conversions' => ['thumb' => 200]],
        ];
    }

    /**
     * Profile photo URL (small version), or null. Photos saved before me_media still show until imported.
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if ($url = $this->mediaUrl('avatar', 'thumb')) {
            return $url;
        }

        $legacy = $this->attributes['profile_image'] ?? null;

        return $legacy ? route('profile_img.show', $legacy) : null;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Filters for the user list: ?name=, ?email= (partial match), ?role= (role id).
     */
    public function scopeFilter($query, array $filters)
    {
        return $query
            ->when($filters['name'] ?? null, fn ($q, $name) => $q->where('name', 'like', "%{$name}%"))
            ->when($filters['email'] ?? null, fn ($q, $email) => $q->where('email', 'like', "%{$email}%"))
            ->when($filters['role'] ?? null, fn ($q, $role) => $q->whereHas('roles', fn ($r) => $r->where('roles.id', $role)));
    }

    public function activities()
    {
        return $this->hasMany(UserActivity::class);
    }

    public function hasRole($roleSlug)
    {
        return $this->roles()->where('slug', $roleSlug)->exists();
    }

    public function hasPermission($permission)
    {
        if (me_is_developer_only($permission)) {
            return me_is_developer($this);
        }
        if (me_is_developer($this) || $this->isSuperAdmin()) {
            return true;
        }
        foreach ($this->roles as $role) {
            $rolePermission = RolePermission::where('role_id', $role->id)->first();
            if ($rolePermission && is_array($rolePermission->permissions)) {
                if (in_array($permission, $rolePermission->permissions)) {
                    return true;
                }
            }
        }

        return false;
    }

    // public function hasPermission($action)
    // {
    //     return Permission::query()
    //                 ->join('roles', 'roles.id', 'role_permission.role_id')
    //                 ->whereIn('roles.id', Auth::user()->userRoles->pluck('id')->toArray())
    //                 ->where('role_permission.action', $action)
    //                 ->exists();
    // }

    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->firstOrFail();
        }
        $this->roles()->syncWithoutDetaching([$role->id]);
    }

    public function removeRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->firstOrFail();
        }
        $this->roles()->detach($role);
    }

    public function getAllPermissions()
    {
        $permissions = [];
        foreach ($this->roles as $role) {
            $rolePermission = RolePermission::where('role_id', $role->id)->first();
            if ($rolePermission && is_array($rolePermission->permissions)) {
                $permissions = array_merge($permissions, $rolePermission->permissions);
            }
        }

        return array_unique($permissions);
    }

    public function isActive()
    {
        return $this->is_active == 1;
    }

    /** Super admin role (Roles::SUPER_ADMIN_SLUGS) gets every permission. */
    public function isSuperAdmin(): bool
    {
        return $this->roles()->whereIn('slug', Roles::SUPER_ADMIN_SLUGS)->exists();
    }

    public function is_super_admin()
    {
        return me_is_developer($this) || $this->isSuperAdmin();
    }
}
