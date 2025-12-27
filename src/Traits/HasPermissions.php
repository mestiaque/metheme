<?php

namespace ME\Traits;

use ME\Helpers\PermissionHelper;

trait HasPermissions
{
    /**
     * Check if the user has a given permission
     */
    public function hasPermission(string $permission): bool
    {
        // If permission not declared in config, return false
        if (!PermissionHelper::permissionExists($permission)) {
            return false;
        }

        // Collect user permissions (can be array or json column)
        $userPermissions = $this->permissions ?? [];

        if (is_string($userPermissions)) {
            $userPermissions = json_decode($userPermissions, true) ?: [];
        }

        return in_array($permission, $userPermissions);
    }
}
