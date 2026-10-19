<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\PermissionRoleSeeder;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(PermissionRoleSeeder::class);

    $this->admin = User::factory()->admin()->create();
});

test('the roles index marks custom permissions', function () {
    Permission::findOrCreate('posts.publish');

    $this->actingAs($this->admin)
        ->get(route('admin.roles.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->where('permissionGroups.0.permissions.0.name', 'admin.view')
            ->where('permissionGroups.0.permissions.0.is_system', true)
            ->where('permissionGroups', fn ($groups) => collect(collect($groups)->firstWhere('resource', 'posts')['permissions'])
                ->contains(fn (array $permission) => $permission['name'] === 'posts.publish' && ! $permission['is_system'])),
        );
});

test('users without permission cannot manage permissions', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('admin.view');
    $permission = Permission::findOrCreate('posts.publish');

    $this->actingAs($user)->get(route('admin.permissions.create'))->assertForbidden();
    $this->actingAs($user)->post(route('admin.permissions.store'), ['name' => 'posts.nope'])->assertForbidden();
    $this->actingAs($user)->put(route('admin.permissions.update', $permission), ['name' => 'posts.renamed'])->assertForbidden();
    $this->actingAs($user)->delete(route('admin.permissions.destroy', $permission))->assertRedirect();

    $this->assertDatabaseMissing('permissions', ['name' => 'posts.nope']);
    $this->assertDatabaseMissing('permissions', ['name' => 'posts.renamed']);
    $this->assertModelExists($permission);
});

test('permissions can be created', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.permissions.store'), ['name' => 'posts.publish'])
        ->assertRedirect(route('admin.roles.index'));

    $this->assertDatabaseHas('permissions', ['name' => 'posts.publish', 'guard_name' => 'web']);
});

test('creating a permission validates the name', function (string $name) {
    Permission::findOrCreate('posts.publish');

    $this->actingAs($this->admin)
        ->post(route('admin.permissions.store'), ['name' => $name])
        ->assertSessionHasErrors('name');
})->with([
    'missing' => '',
    'without verb' => 'posts',
    'too many parts' => 'posts.view.draft.extra',
    'spaces' => 'posts.publish now',
    'taken' => 'posts.publish',
    'system name' => 'posts.update',
]);

test('custom permissions can be renamed and keep their roles', function () {
    $permission = Permission::findOrCreate('posts.publish');
    $role = Role::findOrCreate('editor');
    $role->givePermissionTo($permission);

    $this->actingAs($this->admin)
        ->get(route('admin.permissions.edit', $permission))
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/permissions/edit/Page')
            ->where('permission.name', 'posts.publish')
            ->where('roles', ['editor'])
            ->where('canDelete', true),
        );

    $this->actingAs($this->admin)
        ->put(route('admin.permissions.update', $permission), ['name' => 'posts.schedule'])
        ->assertRedirect();

    expect($permission->fresh()->name)->toBe('posts.schedule')
        ->and($role->fresh()->hasPermissionTo('posts.schedule'))->toBeTrue();
});

test('custom permissions can be deleted', function () {
    $permission = Permission::findOrCreate('posts.publish');
    Role::findOrCreate('editor')->givePermissionTo($permission);

    $this->actingAs($this->admin)
        ->delete(route('admin.permissions.destroy', $permission))
        ->assertRedirect(route('admin.roles.index'));

    $this->assertModelMissing($permission);
    expect(Role::findByName('editor')->permissions)->toBeEmpty();
});

test('system permissions cannot be edited or deleted', function () {
    $permission = Permission::findByName('posts.update');

    $this->actingAs($this->admin)->get(route('admin.permissions.edit', $permission))->assertForbidden();
    $this->actingAs($this->admin)
        ->put(route('admin.permissions.update', $permission), ['name' => 'posts.edit'])
        ->assertForbidden();
    $this->actingAs($this->admin)
        ->delete(route('admin.permissions.destroy', $permission))
        ->assertRedirect()
        ->assertInertiaFlash('toast.type', 'error');

    expect($permission->fresh()->name)->toBe('posts.update');
});
