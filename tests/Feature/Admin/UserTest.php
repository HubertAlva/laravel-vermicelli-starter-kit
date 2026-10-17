<?php

use App\Models\User;
use Database\Seeders\PermissionRoleSeeder;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(PermissionRoleSeeder::class);

    $this->admin = User::factory()->admin()->create();
});

test('the index lists users and can filter them by role', function () {
    User::factory()->create(['name' => 'Regular User']);

    $this->actingAs($this->admin)
        ->get(route('admin.users.index', ['filter' => ['role' => 'admin']]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/users/index/Page')
            ->where('roles.0.value', 'admin')
            ->loadDeferredProps(fn (Assert $reload) => $reload
                ->has('users.data', 1)
                ->where('users.data.0.id', $this->admin->id)
                ->where('users.data.0.role', 'admin'),
            ),
        );
});

test('users without permission cannot manage users', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('admin.view');

    $this->actingAs($user)->get(route('admin.users.index'))->assertForbidden();
    $this->actingAs($user)
        ->post(route('admin.users.store'), [
            'name' => 'Nope',
            'email' => 'nope@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
        ->assertForbidden();

    $this->assertDatabaseMissing('users', ['email' => 'nope@example.com']);
});

test('users can be created with a role', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.users.store'), [
            'name' => 'New Admin',
            'email' => 'new@example.com',
            'role' => 'admin',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
        ->assertRedirect(route('admin.users.index'));

    $user = User::firstWhere('email', 'new@example.com');

    expect($user->isAdmin())->toBeTrue()
        ->and(Hash::check('password', $user->password))->toBeTrue()
        ->and($user->hasVerifiedEmail())->toBeTrue();
});

test('creating a user validates the email, role and password', function () {
    User::factory()->create(['email' => 'taken@example.com']);

    $this->actingAs($this->admin)
        ->post(route('admin.users.store'), [
            'name' => 'Duplicate',
            'email' => 'taken@example.com',
            'role' => 'superuser',
            'password' => 'password',
            'password_confirmation' => 'different',
        ])
        ->assertSessionHasErrors(['email', 'role', 'password']);
});

test('updating a user without a password keeps the current one', function () {
    $user = User::factory()->admin()->create();
    $password = $user->password;

    $this->actingAs($this->admin)
        ->put(route('admin.users.update', $user), [
            'name' => 'Renamed',
            'email' => $user->email,
            'role' => null,
        ])
        ->assertRedirect();

    $user->refresh();

    expect($user->name)->toBe('Renamed')
        ->and($user->password)->toBe($password)
        ->and($user->isAdmin())->toBeFalse();
});

test('users can be deleted', function () {
    $user = User::factory()->create();

    $this->actingAs($this->admin)
        ->from(route('admin.users.index'))
        ->delete(route('admin.users.destroy', $user))
        ->assertRedirect(route('admin.users.index'));

    $this->assertModelMissing($user);
});

test('locked users and the current user cannot be deleted', function (Closure $target) {
    $user = $target($this->admin);

    $this->actingAs($this->admin)
        ->delete(route('admin.users.destroy', $user))
        ->assertRedirect()
        ->assertInertiaFlash('toast.type', 'error');

    $this->assertModelExists($user);
})->with([
    'locked user' => fn (User $admin) => tap(User::factory()->create())->lockModel(),
    'current user' => fn (User $admin) => $admin,
]);
