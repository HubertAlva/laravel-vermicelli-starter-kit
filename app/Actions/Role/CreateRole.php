<?php

namespace App\Actions\Role;

use App\Data\RoleFormData;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class CreateRole
{
    public function execute(RoleFormData $data): Role
    {
        return DB::transaction(function () use ($data): Role {
            $role = Role::create(['name' => $data->name]);

            $role->syncPermissions($data->permissions);

            return $role;
        });
    }
}
