<?php

namespace App\Actions\User;

use App\Data\UserFormData;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Update
{
    /**
     * @throws \Throwable
     */
    public function execute(User $user, UserFormData $data): User
    {
        return DB::transaction(function () use ($user, $data) {

            $attributes = [
                'name' => $data->name,
                'email' => $data->email,
            ];

            if ($data->password) {
                $attributes['password'] = Hash::make($data->password);
            }

            $user->update($attributes);

            $user->syncRoles([$data->role]);

            return $user;
        });
    }
}
