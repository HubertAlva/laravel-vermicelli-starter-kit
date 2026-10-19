<?php

use App\Models\Role;
use App\Models\User;
use Database\Seeders\PermissionRoleSeeder;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(PermissionRoleSeeder::class);

    $this->admin = User::factory()->admin()->create();
});

test('the index lists the roles with their permissions and users count', function () {
    Role::findOrCreate('editor')->givePermissionTo('posts.viewAny');

    $this->actingAs($this->admin)
        ->get(route('admin.roles.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/roles/index/Page')
            ->has('roles', 2)
            ->where('roles.0.name', 'admin')
            ->where('roles.0.users_count', 1)
            ->where('roles.0.is_protected', true)
            ->where('roles.0.permissions', fn ($permissions) => collect($permissions)->contains('roles.viewAny'))
            ->where('roles.1.name', 'editor')
            ->where('roles.1.users_count', 0)
            ->where('roles.1.is_protected', false)
            ->where('roles.1.permissions', ['posts.viewAny'])
            ->where('permissionGroups.0.resource', 'admin')
            ->has('permissionGroups.0.permissions', 1)
            ->where('permissionGroups.0.permissions.0.name', 'admin.view'),
        );
});

test('users without permission cannot manage roles', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('admin.view');
    $role = Role::findOrCreate('editor');

    $this->actingAs($user)->get(route('admin.roles.index'))->assertForbidden();
    $this->actingAs($user)->get(route('admin.roles.create'))->assertForbidden();
    $this->actingAs($user)->post(route('admin.roles.store'), ['name' => 'nope'])->assertForbidden();
    $this->actingAs($user)->put(route('admin.roles.update', $role), ['name' => 'renamed'])->assertForbidden();

    $this->assertDatabaseMissing('roles', ['name' => 'nope']);
    $this->assertDatabaseMissing('roles', ['name' => 'renamed']);
});

test('roles can be created with permissions', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.roles.store'), [
            'name' => 'editor',
            'permissions' => ['admin.view', 'posts.viewAny'],
        ])
        ->assertRedirect(route('admin.roles.index'));

    $role = Role::findByName('editor');

    expect($role->permissions->pluck('name')->sort()->values()->all())->toBe(['admin.view', 'posts.viewAny']);
});

test('creating a role validates the name and permissions', function (array $data, array $errors) {
    $this->actingAs($this->admin)
        ->post(route('admin.roles.store'), $data)
        ->assertSessionHasErrors($errors);
})->with([
    'missing name' => [['name' => ''], ['name']],
    'taken name' => [['name' => 'admin'], ['name']],
    'invalid name' => [['name' => 'Content Editor'], ['name']],
    'unknown permission' => [['name' => 'editor', 'permissions' => ['posts.fly']], ['permissions.0']],
]);

test('roles can be renamed and their permissions synced', function () {
    $role = Role::findOrCreate('editor');
    $role->givePermissionTo('posts.viewAny');
    $user = User::factory()->create();
    $user->assignRole($role);

    $this->actingAs($this->admin)
        ->get(route('admin.roles.edit', $role))
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/roles/edit/Page')
            ->where('role.name', 'editor')
            ->where('role.users_count', 1),
        );

    $this->actingAs($this->admin)
        ->put(route('admin.roles.update', $role), [
            'name' => 'writer',
            'permissions' => ['posts.create'],
        ])
        ->assertRedirect();

    $role->refresh();

    expect($role->name)->toBe('writer')
        ->and($role->permissions->pluck('name')->all())->toBe(['posts.create'])
        ->and($user->fresh()->can('posts.create'))->toBeTrue()
        ->and($user->fresh()->can('posts.viewAny'))->toBeFalse();
});

test('protected roles cannot be edited', function () {
    $adminRole = Role::findByName('admin');

    $this->actingAs($this->admin)->get(route('admin.roles.edit', $adminRole))->assertForbidden();
    $this->actingAs($this->admin)
        ->put(route('admin.roles.update', $adminRole), ['name' => 'admin', 'permissions' => []])
        ->assertForbidden();

    expect($adminRole->fresh()->permissions)->not->toBeEmpty();
});
