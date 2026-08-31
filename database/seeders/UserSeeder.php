<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        if (app()->isLocal()) {
            // Admin user
            $user = User::create([
                'name' => 'Administrador',
                'email' => 'admin@hubertalva.com',
                'password' => Hash::make('1234'),
                'email_verified_at' => now(),
            ])->assignRole('admin');

            $user->lockModel();
        }
    }
}
