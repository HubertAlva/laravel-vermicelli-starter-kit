<?php

namespace App\Actions\User;

use App\Data\UserFormData;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Create
{
    /**
     * @throws \Throwable
     */
    public function execute(UserFormData $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data->name,
                'email' => $data->email,
                'password' => Hash::make($data->password),
            ]);

            if ($data->role) {
                $user->assignRole($data->role);
            }

            return $user;
        });
    }
}
