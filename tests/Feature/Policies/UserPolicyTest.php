<?php

namespace Tests\Feature\Policies;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_view_any_is_denied_without_the_users_view_any_permission()
    {
        $user = User::factory()->create();

        $this->assertFalse($user->can('viewAny', User::class));
    }

    public function test_view_any_is_allowed_with_the_users_view_any_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('users.viewAny');

        $this->assertTrue($user->can('viewAny', User::class));
    }

    public function test_view_is_always_denied_regardless_of_permissions()
    {
        $user = User::factory()->create()->assignRole('admin');
        $model = User::factory()->create();

        $this->assertFalse($user->can('view', $model));
    }

    public function test_create_is_denied_without_the_users_create_permission()
    {
        $user = User::factory()->create();

        $this->assertFalse($user->can('create', User::class));
    }

    public function test_create_is_allowed_with_the_users_create_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('users.create');

        $this->assertTrue($user->can('create', User::class));
    }

    public function test_update_is_denied_without_the_users_update_permission()
    {
        $user = User::factory()->create();
        $model = User::factory()->create();

        $this->assertFalse($user->can('update', $model));
    }

    public function test_update_is_allowed_with_the_users_update_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('users.update');
        $model = User::factory()->create();

        $this->assertTrue($user->can('update', $model));
    }

    public function test_delete_is_denied_without_the_users_delete_permission()
    {
        $user = User::factory()->create();
        $model = User::factory()->create();

        $this->assertFalse($user->can('delete', $model));
    }

    public function test_delete_is_allowed_with_the_users_delete_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('users.delete');
        $model = User::factory()->create();

        $this->assertTrue($user->can('delete', $model));
    }

    public function test_delete_is_denied_for_the_users_own_account_even_with_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('users.delete');

        $this->assertFalse($user->can('delete', $user));
    }

    public function test_delete_is_denied_for_a_locked_user_even_with_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('users.delete');

        $model = User::factory()->create();
        $model->lockModel();

        $this->assertFalse($user->can('delete', $model));
    }

    public function test_restore_is_always_denied()
    {
        $user = User::factory()->create()->assignRole('admin');
        $model = User::factory()->create();

        $this->assertFalse($user->can('restore', $model));
    }

    public function test_force_delete_is_always_denied()
    {
        $user = User::factory()->create()->assignRole('admin');
        $model = User::factory()->create();

        $this->assertFalse($user->can('forceDelete', $model));
    }
}
