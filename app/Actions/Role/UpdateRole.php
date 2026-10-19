<?php

namespace App\Actions\Role;

use App\Data\RoleFormData;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class UpdateRole
{
    public function execute(Role $role, RoleFormData $data): Role
    {
        return DB::transaction(function () use ($role, $data): Role {
            $role->update(['name' => $data->name]);

            $role->syncPermissions($data->permissions);

            return $role;
        });
    }
}
