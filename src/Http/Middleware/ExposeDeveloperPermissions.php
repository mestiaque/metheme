<?php

namespace ME\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * When a developer is logged in (developer mode on + email listed), adds
 * config('me_settings.developer_permissions') to config('permissions') for this request,
 * so every role form (in any package) lists them and the developer can grant them.
 * For everyone else they are not listed.
 */
class ExposeDeveloperPermissions
{
    public function handle(Request $request, Closure $next)
    {
        if (me_is_developer()) {
            $permissions = (array) config('permissions', []);

            foreach ((array) config('me_settings.developer_permissions', []) as $module => $data) {
                if (!isset($permissions[$module])) {
                    $permissions[$module] = $data;
                }
            }

            config(['permissions' => $permissions]);
        }

        return $next($request);
    }
}
