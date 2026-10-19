<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('roles.viewAny');
    }

    public function create(User $user): bool
    {
        return $user->can('roles.create');
    }

    /**
     * Protected roles (e.g. `admin`) can never be edited, so nobody can lock
     * themselves out of the admin panel.
     */
    public function update(User $user, Role $role): bool
    {
        if ($role->isProtected()) {
            return false;
        }

        return $user->can('roles.update');
    }
}
