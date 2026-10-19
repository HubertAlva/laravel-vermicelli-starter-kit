<?php

namespace App\Models;

use App\Policies\RolePolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Spatie\Permission\Models\Role as SpatieRole;

/**
 * @property int $id
 */
#[UsePolicy(RolePolicy::class)]
class Role extends SpatieRole
{
    /**
     * Roles the application relies on, which can't be edited from the admin panel.
     *
     * @var list<string>
     */
    public const array PROTECTED_ROLES = ['admin'];

    /**
     * Determine if the role is protected from changes.
     */
    public function isProtected(): bool
    {
        return in_array($this->name, self::PROTECTED_ROLES, true);
    }
}
