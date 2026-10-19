<?php

namespace App\Data;

use App\Models\Role;
use Spatie\LaravelData\Data;
use Spatie\Permission\Models\Permission as SpatiePermission;

class RoleData extends Data
{
    /**
     * @param  array<int, string>  $permissions
     */
    public function __construct(
        public int $id,
        public string $name,
        public string $label,
        public int $users_count,
        public bool $is_protected,
        public array $permissions,
    ) {}

    /**
     * Load `permissions` and the `users` count beforehand when transforming many roles.
     */
    public static function fromModel(Role $role): self
    {
        return new self(
            id: $role->id,
            name: $role->name,
            label: str($role->name)->headline()->toString(),
            users_count: $role->users_count ?? $role->users()->count(),
            is_protected: $role->isProtected(),
            permissions: $role->permissions
                ->map(fn (SpatiePermission $permission): string => $permission->name)
                ->sort()
                ->values()
                ->all(),
        );
    }
}
