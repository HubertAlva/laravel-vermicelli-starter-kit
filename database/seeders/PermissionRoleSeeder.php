<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Seeds the database with roles and permissions.
     *
     * - Creates an `admin` role.
     * - Assigns various permissions to the `admin` role.
     *
     * Permissions follow the format `resource.verb.attribute`, where:
     * - `resource` refers to the target entity (e.g., `posts`, `admin`).
     * - `verb` refers to the allowed actions (e.g., `viewAny`, `create`, etc.).
     * - `attribute` is an optional qualifier (e.g., `unpublished`).
     *
     * Defined permissions:
     * - `posts` permissions: Includes basic CRUD operations and advanced actions like restoration or force deletion.
     * - `admin` panel permissions: Grants access and modification capabilities for administrative application areas.
     */
    public function run(): void
    {
        $role_admin = Role::create(['name' => 'admin']);

        // Posts permissions
        $post_permissions = [
            'posts.viewAny',
            'posts.view',
            'posts.view.unpublished',
            'posts.create',
            'posts.update',
            'posts.delete',
            'posts.restore',
            'posts.forceDelete',
        ];

        foreach ($post_permissions as $permission) {
            Permission::create(['name' => $permission])->assignRole($role_admin);
        }

        // Users permissions
        $user_permissions = [
            'users.viewAny',
            'users.create',
            'users.update',
            'users.delete',
        ];

        foreach ($user_permissions as $permission) {
            Permission::create(['name' => $permission])->assignRole($role_admin);
        }

        // Admin panel permissions
        $admin_permissions = [
            'admin.viewAny',
            'admin.view',
            'admin.create',
            'admin.update',
            'admin.delete',
            'admin.restore',
            'admin.forceDelete',
        ];

        foreach ($admin_permissions as $permission) {
            Permission::create(['name' => $permission])->assignRole($role_admin);
        }
    }
}
