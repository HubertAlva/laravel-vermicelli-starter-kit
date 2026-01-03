<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        $role_admin = Role::create(['name' => 'admin']);

        // Permissions for admin
        Permission::create(['name' => 'admin.read'])->assignRole($role_admin);
        Permission::create(['name' => 'admin.create'])->assignRole($role_admin);
        Permission::create(['name' => 'admin.update'])->assignRole($role_admin);
        Permission::create(['name' => 'admin.delete'])->assignRole($role_admin);
        Permission::create(['name' => 'admin.all'])->assignRole($role_admin);

        // Admin user
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@hubertalva.com',
            'password' => Hash::make('1234'),
            'email_verified_at' => now(),
        ])->assignRole('admin');
    }
}
