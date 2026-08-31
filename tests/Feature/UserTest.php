<?php

namespace Tests\Feature;

use App\Actions\User\Create;
use App\Actions\User\Update;
use App\Data\UserFormData;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create()->assignRole('admin');

        $this->actingAs($this->admin);
    }

    public function test_users_index_can_be_rendered()
    {
        $response = $this->get(route('admin.users.index'));

        $response->assertStatus(200);
    }

    public function test_users_can_be_created()
    {
        $action = $this->app->make(Create::class);

        $data = UserFormData::from([
            'name' => 'Test User',
            'email' => 'test@mail.com',
            'password' => 'password123',
        ]);

        $user = $action->execute($data);

        $this->assertNotNull($user->id);

        $this->assertInstanceOf(User::class, $user);

        $this->assertTrue(Hash::check('password123', $user->password));

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
        ]);
    }

    public function test_users_can_be_updated()
    {
        $action = $this->app->make(Update::class);

        $user = User::factory()->create();

        $data = UserFormData::from([
            'name' => 'Updated User',
            'email' => 'newmail@mail.com',
            'password' => 'password456',
        ]);

        $updatedUser = $action->execute($user, $data);

        $this->assertNotNull($updatedUser->id);

        $this->assertInstanceOf(User::class, $updatedUser);

        $this->assertTrue(Hash::check('password456', $updatedUser->password));

        $this->assertDatabaseHas('users', [
            'name' => 'Updated User',
        ]);
    }

    public function test_users_can_be_created_through_the_store_endpoint()
    {
        $response = $this->post(route('admin.users.store'), [
            'name' => 'HTTP User',
            'email' => 'httpuser@mail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'httpuser@mail.com',
        ]);
    }

    public function test_store_user_fails_validation_when_email_is_already_taken()
    {
        User::factory()->create(['email' => 'taken@mail.com']);

        $response = $this->post(route('admin.users.store'), [
            'name' => 'Duplicate Email User',
            'email' => 'taken@mail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');

        $this->assertDatabaseCount('users', 2); // admin + the pre-existing user
    }

    public function test_users_can_be_updated_through_the_update_endpoint()
    {
        $user = User::factory()->create();

        $response = $this->put(route('admin.users.update', $user), [
            'name' => 'HTTP Updated User',
            'email' => $user->email,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'HTTP Updated User',
        ]);
    }

    public function test_users_without_the_users_create_permission_are_forbidden_from_creating_users()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('admin.view');

        $response = $this->actingAs($user)->post(route('admin.users.store'), [
            'name' => 'Should Not Be Created',
            'email' => 'shouldnotexist@mail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertForbidden();

        $this->assertDatabaseMissing('users', [
            'email' => 'shouldnotexist@mail.com',
        ]);
    }

    public function test_users_can_be_deleted()
    {
        $user = User::factory()->create();

        $response = $this->delete(route('admin.users.destroy', $user));

        $response->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
