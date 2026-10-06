<?php

namespace ME\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;

    protected $table = 'role_permissions';

    protected $fillable = ['role_id', 'permissions'];

    protected $casts = [
        'permissions' => 'array',
    ];

    /**
     * Developer-grantable permissions (config me_settings.developer_permissions) are only
     * listed on role forms for developers. When anyone else saves a role over HTTP, keep the
     * role's existing developer permissions exactly as they were: they are neither removed
     * (the form did not show them) nor added.
     */
    protected static function booted()
    {
        static::saving(function (RolePermission $rolePermission) {
            if (app()->runningInConsole() || ! auth()->check() || me_is_developer()) {
                return;
            }

            $developerKeys = me_developer_permission_keys();
            if (! $developerKeys) {
                return;
            }

            $original = json_decode((string) ($rolePermission->getRawOriginal('permissions') ?? '[]'), true) ?: [];
            $submitted = (array) ($rolePermission->permissions ?? []);

            $rolePermission->permissions = array_values(array_unique(array_merge(
                array_diff($submitted, $developerKeys),
                array_intersect($original, $developerKeys)
            )));
        });
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
