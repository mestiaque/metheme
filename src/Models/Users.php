<?php

namespace ME\Models;

use ME\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use ME\Models\RolePermission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Users extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'profile_image',
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
     * Users who have the encodex role are never visible through this model (lists, find, counts, relations).
     * Use User (singular) when the full set is needed, e.g. for login.
     */
    protected static function booted(): void
    {
        // Checked on the pivot directly: whereHas('roles') would go through Roles, which already hides encodex
        static::addGlobalScope('hide_encodex', function (Builder $query) {
            $query->whereNotExists(function ($sub) use ($query) {
                $sub->selectRaw('1')
                    ->from('role_user')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->whereColumn('role_user.user_id', $query->qualifyColumn('id'))
                    ->where('roles.slug', 'encodex');
            });
        });
    }

    public function roles()
    {
        // Plural model names would make Laravel guess "roles_users" / "users_id", so name the real pivot
        return $this->belongsToMany(Roles::class, 'role_user', 'user_id', 'role_id');
    }

    public function activities()
    {
        return $this->hasMany(UserActivity::class, 'user_id');
    }

    public function hasRole($roleSlug)
    {
        return $this->roles()->where('slug', $roleSlug)->exists();
    }

    public function hasPermission($permission)
    {
        if ($this->hasRole('encodex')) {
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

    public function is_encodex()
    {
        return $this->hasRole('encodex');
    }
}
