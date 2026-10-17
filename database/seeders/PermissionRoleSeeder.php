<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Contracts\Permission as PermissionContract;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Permissions follow the `resource.verb.attribute` format, where `resource` is
     * the target entity (e.g. `admin`, `users`), `verb` the allowed action (e.g.
     * `viewAny`, `create`) and `attribute` an optional qualifier.
     *
     * @var array<string, list<string>>
     */
    public const array PERMISSIONS = [
        'admin' => [
            'admin.view',
        ],
        'posts' => [
            'posts.viewAny',
            'posts.view.unpublished',
            'posts.create',
            'posts.update',
            'posts.delete',
            'posts.restore',
            'posts.forceDelete',
        ],
        'users' => [
            'users.viewAny',
            'users.create',
            'users.update',
            'users.delete',
        ],
    ];

    /**
     * Seed the application's roles and permissions.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $adminRole = Role::findOrCreate('admin');

        // Pass the models (not names) so the stale permission cache isn't consulted...
        $adminRole->givePermissionTo(
            collect(self::PERMISSIONS)
                ->flatten()
                ->map(fn (string $permission): PermissionContract => Permission::findOrCreate($permission))
                ->all(),
        );
    }
}
