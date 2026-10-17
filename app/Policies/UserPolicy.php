<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('users.viewAny');
    }

    public function create(User $user): bool
    {
        return $user->can('users.create');
    }

    public function update(User $user, User $model): bool
    {
        return $user->can('users.update');
    }

    /**
     * Locked users and the current user can never be deleted.
     */
    public function delete(User $user, User $model): bool
    {
        if ($user->is($model) || $model->isLocked()) {
            return false;
        }

        return $user->can('users.delete');
    }
}
