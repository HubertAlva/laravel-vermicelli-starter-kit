<?php

namespace App\Models;

use App\Policies\PermissionPolicy;
use Database\Seeders\PermissionRoleSeeder;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Spatie\Permission\Models\Permission as SpatiePermission;

#[UsePolicy(PermissionPolicy::class)]
class Permission extends SpatiePermission
{
    /**
     * Get the permissions the application's code checks, defined in the seeder.
     *
     * @return list<string>
     */
    public static function systemPermissions(): array
    {
        return array_merge(...array_values(PermissionRoleSeeder::PERMISSIONS));
    }

    /**
     * System permissions are checked by the code, so they can't be renamed
     * or deleted from the admin panel.
     */
    public function isSystem(): bool
    {
        return in_array($this->name, self::systemPermissions(), true);
    }
}
