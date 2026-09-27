<?php

namespace ME\Services;

use Illuminate\Support\Facades\DB;
use ME\Models\Role;

/**
 * Role hierarchy rules (roles.parent_id):
 *
 *  - A user may only manage roles BELOW their own role(s): children, grandchildren, ...
 *    Their own role and anything above or beside it is off-limits.
 *  - A new or edited role's parent must be the user's own role or a role below it.
 *  - A user may only give a role permissions they have themselves.
 *  - A user may only manage users whose roles are all below theirs, and only assign such roles.
 *  - Developers (developer mode) may manage everything. Only they can create/edit top roles.
 *
 * The normal permission checks (me_role.edit, me_user.edit, ...) still apply on top of these rules.
 */
class RoleHierarchy
{
    /** @var array<int|string, array<int>> */
    private array $manageableCache = [];

    public function isDeveloper($actor): bool
    {
        return $actor && me_is_developer($actor);
    }

    /**
     * Ids of the actor's own roles.
     */
    public function ownRoleIds($actor): array
    {
        if (!$actor) {
            return [];
        }

        return DB::table('role_user')->where('user_id', $actor->getAuthIdentifier())
            ->pluck('role_id')->map(fn ($id) => (int) $id)->all();
    }

    /**
     * Ids of the roles the actor may edit, delete and assign (strictly below their own roles).
     */
    public function manageableRoleIds($actor): array
    {
        if (!$actor) {
            return [];
        }

        $key = $actor->getAuthIdentifier();

        if (!isset($this->manageableCache[$key])) {
            if ($this->isDeveloper($actor)) {
                $ids = Role::query()->pluck('id')->map(fn ($id) => (int) $id)->all();
            } else {
                $own = $this->ownRoleIds($actor);
                // A role the actor holds is never manageable by them, even if it is below another of their roles.
                $ids = array_values(array_diff(Role::descendantIdsOf($own), $own));
            }
            $this->manageableCache[$key] = $ids;
        }

        return $this->manageableCache[$key];
    }

    public function canManageRole($actor, Role $role): bool
    {
        return in_array((int) $role->id, $this->manageableRoleIds($actor), true);
    }

    /**
     * Roles that may be chosen as parent when creating/editing $role (null = creating).
     * Excludes $role itself and everything below it, so no cycles can be created.
     */
    public function allowedParentIds($actor, ?Role $role = null): array
    {
        $ids = $this->isDeveloper($actor)
            ? Role::query()->pluck('id')->map(fn ($id) => (int) $id)->all()
            : array_values(array_unique(array_merge($this->ownRoleIds($actor), $this->manageableRoleIds($actor))));

        if ($role) {
            $blocked = array_merge([(int) $role->id], $role->descendantIds());
            $ids = array_values(array_diff($ids, $blocked));
        }

        return $ids;
    }

    /**
     * Whether the actor may create a top role (no parent). Developers only.
     */
    public function canCreateTopRole($actor): bool
    {
        return $this->isDeveloper($actor);
    }

    /**
     * Permission keys the actor may give to a role (null = no limit).
     */
    public function grantablePermissions($actor): ?array
    {
        if ($this->isDeveloper($actor)) {
            return null;
        }

        return method_exists($actor, 'getAllPermissions') ? array_values((array) $actor->getAllPermissions()) : [];
    }

    /**
     * Whether the actor may edit / deactivate / delete / change the role of $target.
     * Allowed when every role of the target is below the actor's roles. Never for oneself
     * (use the profile page), except for developers.
     */
    public function canManageUser($actor, $target): bool
    {
        if (!$actor || !$target) {
            return false;
        }

        if ($this->isDeveloper($actor)) {
            return true;
        }

        if ((string) $actor->getAuthIdentifier() === (string) $target->getKey()) {
            return false;
        }

        $targetRoles = DB::table('role_user')->where('user_id', $target->getKey())
            ->pluck('role_id')->map(fn ($id) => (int) $id)->all();

        return array_diff($targetRoles, $this->manageableRoleIds($actor)) === [];
    }
}
