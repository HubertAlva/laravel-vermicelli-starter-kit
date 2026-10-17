<?php

namespace App\Actions\User;

use App\Data\UserFormData;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateUser
{
    public function execute(User $user, UserFormData $data): User
    {
        return DB::transaction(function () use ($user, $data): User {
            $user->fill([
                'name' => $data->name,
                'email' => $data->email,
            ]);

            if ($data->password) {
                $user->password = $data->password;
            }

            $user->save();

            $user->syncRoles(array_filter([$data->role]));

            return $user;
        });
    }
}
