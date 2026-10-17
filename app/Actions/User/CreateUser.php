<?php

namespace App\Actions\User;

use App\Data\UserFormData;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateUser
{
    public function execute(UserFormData $data): User
    {
        return DB::transaction(function () use ($data): User {
            $user = User::create([
                'name' => $data->name,
                'email' => $data->email,
                'password' => $data->password,
            ]);

            $user->forceFill(['email_verified_at' => now()])->save();

            if ($data->role) {
                $user->assignRole($data->role);
            }

            return $user;
        });
    }
}
