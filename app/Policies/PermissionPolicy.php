<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;

class PermissionPolicy
{
    public function create(User $user): bool
    {
        return $user->can('permissions.create');
    }

    /**
     * System permissions can never be renamed.
     */
    public function update(User $user, Permission $permission): bool
    {
        if ($permission->isSystem()) {
            return false;
        }

        return $user->can('permissions.update');
    }

    /**
     * System permissions can never be deleted.
     */
    public function delete(User $user, Permission $permission): bool
    {
        if ($permission->isSystem()) {
            return false;
        }

        return $user->can('permissions.delete');
    }
}
