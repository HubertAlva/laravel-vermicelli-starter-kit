<?php

use App\Models\User;
use Database\Seeders\PermissionRoleSeeder;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(PermissionRoleSeeder::class);
});

test('guests are redirected to the admin login page', function () {
    $this->get(route('admin.dashboard'))
        ->assertRedirect('http://admin.localhost/login');
});

test('users without the admin.view permission cannot access the admin panel', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

test('admins can access the admin panel', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/dashboard/Page')
            ->where('auth.isAdmin', true)
            ->where('auth.permissions', fn ($permissions) => collect($permissions)->contains('admin.view')),
        );
});

test('admin panel routes are not served on the main domain', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->get('http://localhost/')
        ->assertInertia(fn (Assert $page) => $page->component('home/index/Page'));
});

test('admins are redirected to the admin panel after login', function () {
    $admin = User::factory()->admin()->create();

    $this->post(route('login.store'), [
        'email' => $admin->email,
        'password' => 'password',
    ])->assertRedirect(route('admin.dashboard'));
});

test('admins logging in through inertia get a full page visit to the admin panel', function () {
    $admin = User::factory()->admin()->create();

    $this->withHeader('X-Inertia', 'true')
        ->post(route('login.store'), [
            'email' => $admin->email,
            'password' => 'password',
        ])
        ->assertConflict()
        ->assertHeader('X-Inertia-Location', route('admin.dashboard'));
});

test('the admin login page does not offer registration', function () {
    $this->get('http://admin.localhost/login')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('auth/Login')
            ->where('canRegister', false),
        );
});

test('the registration page redirects to login on the admin domain', function () {
    $this->get('http://admin.localhost/register')
        ->assertRedirect('http://admin.localhost/login');
});
