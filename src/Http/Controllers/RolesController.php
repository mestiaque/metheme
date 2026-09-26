<?php

namespace ME\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use ME\Models\Roles;
use Illuminate\Support\Collection;
use ME\Models\RolePermission;
use ME\Http\Controllers\Controller;
use ME\Http\Middleware\AuthorizationMiddleware;

class RolesController extends Controller
{

    public function __construct()
    {
        $this->middleware('authorization:role.view')->only(['index', 'show']);
        $this->middleware('authorization:role.create')->only(['create', 'store']);
        $this->middleware('authorization:role.edit')->only(['edit', 'update']);
        $this->middleware('authorization:role.delete')->only('destroy');
    }

    public function index()
    {
        $roles = Roles::withCount('users')->latest()->paginate(get_setting('pagination', 10))
;
        return view('me::roles.index', compact('roles'));
    }

    public function create()
    {
        $configPermissions = config('permissions') ?? [];
        $permissions = $this->getPermissionsFromConfig($configPermissions);

        return view('me::roles.create', compact('permissions'));
    }

    private function getPermissionsFromConfig(array $configPermissions): Collection
    {
        $permissions = collect();
        $id = 1;

        foreach ($configPermissions as $module => $config) {
            $actions = explode(',', $config['actions']);

            foreach ($actions as $action) {
                $slug = $module . '.' . trim($action);
                $name = ucfirst(trim($action)) . ' ' . $config['title'];

                $permissions->push((object)[
                    'id' => $id++,
                    'name' => $name,
                    'slug' => $slug,
                ]);
            }
        }

        return $permissions;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);

        $role = Roles::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        if ($request->has('permissions')) {
            $configPermissions = config('permissions') ?? [];
            $allPermissions = $this->getPermissionsFromConfig($configPermissions);

            $selectedPermissions = collect($request->permissions)
                ->map(function ($id) use ($allPermissions) {
                    $permission = $allPermissions->firstWhere('id', $id);
                    return $permission ? $permission->slug : null;
                })
                ->filter()
                ->toArray();

            RolePermission::create([
                'role_id' => $role->id,
                'permissions' => $selectedPermissions,
            ]);
        }

        return redirect()->route('me.roles.index')
            ->with('success', 'Role created successfully');
    }

    public function show(Roles $role)
    {
        $role->load('users', 'rolePermission');
        return view('me::roles.show', compact('role'));
    }

    public function edit(Roles $role)
    {
        $configPermissions = config('permissions') ?? [];
        $permissions = $this->getPermissionsFromConfig($configPermissions);

        $rolePermissions = $role->rolePermission ? $role->rolePermission->permissions : [];

        $selectedPermissionIds = $permissions
            ->filter(function ($permission) use ($rolePermissions) {
                return in_array($permission->slug, $rolePermissions);
            })
            ->pluck('id')
            ->toArray();

        return view('me::roles.edit', compact('role', 'permissions', 'selectedPermissionIds'));
    }

    public function update(Request $request, Roles $role)
    {
        // Super admin: only its permissions can change, name and description stay as they are
        if ($role->isSuperAdmin()) {
            $request->validate(['permissions' => 'nullable|array']);
        } else {
            $request->validate([
                'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
                'description' => 'nullable|string',
                'permissions' => 'nullable|array',
            ]);

            if ($role->name !== $request->name && $role->slug !== 'encodex') {
                $role->slug = Str::slug($request->name);
            }

            $role->name = $request->name;
            $role->description = $request->description;
            $role->save();
        }

        $configPermissions = config('permissions') ?? [];
        $allPermissions = $this->getPermissionsFromConfig($configPermissions);

        $selectedPermissions = collect($request->permissions ?? [])
            ->map(function ($id) use ($allPermissions) {
                $permission = $allPermissions->firstWhere('id', $id);
                return $permission ? $permission->slug : null;
            })
            ->filter()
            ->toArray();

        RolePermission::updateOrCreate(
            ['role_id' => $role->id],
            ['permissions' => $selectedPermissions]
        );

        return redirect()->route('me.roles.index')
            ->with('success', 'Role updated successfully');
    }


    public function destroy(Roles $role)
    {
        if ($role->slug === 'encodex') {
            return redirect()->route('me.roles.index')
                ->with('error', 'Cannot delete the ENCODEX role');
        }

        if ($role->isSuperAdmin()) {
            return redirect()->route('me.roles.index')
                ->with('error', __('me::me.super_admin_role_cannot_be_deleted'));
        }

        if ($role->belongsToUser()) {
            return redirect()->route('me.roles.index')
                ->with('error', __('me::me.you_cannot_delete_your_own_role'));
        }

        $role->delete();

        return redirect()->route('me.roles.index')
            ->with('success', 'Role deleted successfully');
    }
}
