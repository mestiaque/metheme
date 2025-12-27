<?php

namespace ME\Helpers;

class PermissionHelper
{
    /**
     * Get all permissions from the config file
     *
     * @return array
     */
    public static function getAllPermissions()
    {
        $permissions = [];
        $config = config('permissions');

        foreach ($config as $module => $data) {
            $actions = explode(',', $data['actions']);
            foreach ($actions as $action) {
                $permissions[] = [
                    'module' => $module,
                    'action' => $action,
                    'name' => "$module.$action",
                    'title' => ucfirst($action) . ' ' . $data['title'],
                ];
            }
        }

        return $permissions;
    }

    /**
     * Get all permissions grouped by module
     *
     * @return array
     */
    public static function getGroupedPermissions()
    {
        $grouped = [];
        $config = config('permissions');

        foreach ($config as $module => $data) {
            $actions = explode(',', $data['actions']);
            $permissions = [];

            foreach ($actions as $action) {
                $permissions[] = [
                    'module' => $module,
                    'action' => $action,
                    'name' => "$module.$action",
                    'title' => ucfirst($action),
                ];
            }

            $grouped[$module] = [
                'title' => $data['title'],
                'permissions' => $permissions
            ];
        }

        return $grouped;
    }

    /**
     * Check if a permission exists in the config
     *
     * @param string $permission
     * @return bool
     */
    public static function permissionExists($permission)
    {
        list($module, $action) = explode('.', $permission);
        
        $config = config('permissions');
        
        if (!isset($config[$module])) {
            return false;
        }
        
        $actions = explode(',', $config[$module]['actions']);
        return in_array($action, $actions);
    }
}
