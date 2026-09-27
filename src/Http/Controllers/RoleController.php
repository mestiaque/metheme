<?php

namespace ME\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use ME\Models\Role;
use Illuminate\Support\Collection;
use ME\Models\RolePermission;
use ME\Http\Controllers\Controller;
use ME\Services\RoleHierarchy;

class RoleController extends Controller
{
    public function __construct(private RoleHierarchy $hierarchy)
    {
        $this->middleware('authorization:me_role.view')->only(['index', 'show']);
        $this->middleware('authorization:me_role.create')->only(['create', 'store']);
        $this->middleware('authorization:me_role.edit')->only(['edit', 'update']);
        $this->middleware('authorization:me_role.delete')->only('destroy');
    }

    public function index()
    {
        $roles = Role::with('parent')->withCount(['users', 'children'])->latest()->paginate(get_setting('pagination', 10));
        $manageable = $this->hierarchy->manageableRoleIds(auth()->user());

        return view('me::roles.index', compact('roles', 'manageable'));
    }

    public function create()
    {
        $user = auth()->user();
        $permissions = $this->grantableList($user);
        $parents = Role::whereIn('id', $this->hierarchy->allowedParentIds($user))->orderBy('name')->get();
        $canCreateTop = $this->hierarchy->canCreateTopRole($user);

        return view('me::roles.create', compact('permissions', 'parents', 'canCreateTop'));
    }

    /**
     * All permissions from config with stable ids (position in the full list).
     */
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

    /**
     * The permissions the actor may give to a role (only permissions they have themselves).
     */
    private function grantableList($actor): Collection
    {
        $all = $this->getPermissionsFromConfig(config('permissions') ?? []);
        $grantable = $this->hierarchy->grantablePermissions($actor);

        return $grantable === null ? $all : $all->filter(fn ($p) => in_array($p->slug, $grantable, true))->values();
    }

    /**
     * Submitted permission ids -> slugs, limited to what the actor may grant.
     */
    private function selectedSlugs(Request $request): array
    {
        $grantable = $this->grantableList($request->user());

        return collect($request->permissions ?? [])
            ->map(fn ($id) => $grantable->firstWhere('id', (int) $id)?->slug)
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Validate the chosen parent. A top role (no parent) can only be created by a developer.
     */
    private function validateParent(Request $request, ?Role $role = null): ?int
    {
        $user = $request->user();
        $parentId = $request->filled('parent_id') ? (int) $request->parent_id : null;

        if ($parentId === null) {
            if (!$this->hierarchy->canCreateTopRole($user)) {
                abort(back()->withInput()->withErrors(['parent_id' => __('me::me.role_parent_required')]));
            }

            return null;
        }

        if (!in_array($parentId, $this->hierarchy->allowedParentIds($user, $role), true)) {
            abort(back()->withInput()->withErrors(['parent_id' => __('me::me.role_parent_not_allowed')]));
        }

        return $parentId;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|integer|exists:roles,id',
            'color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'permissions' => 'nullable|array',
        ]);

        $parentId = $this->validateParent($request);

        $role = Role::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'parent_id' => $parentId,
            'color' => $request->color,
        ]);

        RolePermission::create([
            'role_id' => $role->id,
            'permissions' => $this->selectedSlugs($request),
        ]);

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully');
    }

    public function show(Role $role)
    {
        $role->load('users', 'rolePermission', 'parent', 'children');
        return view('me::roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        $user = auth()->user();
        abort_unless($this->hierarchy->canManageRole($user, $role), 403, __('me::me.role_not_manageable'));

        $permissions = $this->grantableList($user);
        $rolePermissions = $role->rolePermission ? (array) $role->rolePermission->permissions : [];

        $selectedPermissionIds = $permissions
            ->filter(fn ($permission) => in_array($permission->slug, $rolePermissions))
            ->pluck('id')
            ->toArray();

        $parents = Role::whereIn('id', $this->hierarchy->allowedParentIds($user, $role))->orderBy('name')->get();
        $canCreateTop = $this->hierarchy->canCreateTopRole($user);

        return view('me::roles.edit', compact('role', 'permissions', 'selectedPermissionIds', 'parents', 'canCreateTop'));
    }

    public function update(Request $request, Role $role)
    {
        abort_unless($this->hierarchy->canManageRole($request->user(), $role), 403, __('me::me.role_not_manageable'));

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|integer|exists:roles,id',
            'color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'permissions' => 'nullable|array',
        ]);

        $parentId = $this->validateParent($request, $role);

        if ($role->name !== $request->name && $role->slug !== 'encodex') {
            $role->slug = Str::slug($request->name);
        }

        $role->name = $request->name;
        $role->description = $request->description;
        $role->parent_id = $parentId;
        $role->color = $request->color;
        $role->save();

        // Permissions the actor cannot grant were not shown in the form: keep them as they are.
        $grantable = $this->hierarchy->grantablePermissions($request->user());
        $existing = $role->rolePermission ? (array) $role->rolePermission->permissions : [];
        $kept = $grantable === null ? [] : array_values(array_diff($existing, $grantable));

        RolePermission::updateOrCreate(
            ['role_id' => $role->id],
            ['permissions' => array_values(array_unique(array_merge($this->selectedSlugs($request), $kept)))]
        );

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully');
    }

    public function destroy(Role $role)
    {
        if (!$this->hierarchy->canManageRole(auth()->user(), $role)) {
            return redirect()->route('roles.index')->with('error', __('me::me.role_not_manageable'));
        }

        if ($role->slug === 'encodex') {
            return redirect()->route('roles.index')
                ->with('error', 'Cannot delete the ENCODEX role');
        }

        if ($role->children()->exists()) {
            return redirect()->route('roles.index')->with('error', __('me::me.role_has_children'));
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully');
    }
}
