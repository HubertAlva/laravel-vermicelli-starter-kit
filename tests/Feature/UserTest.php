<?php

namespace Tests\Feature;

use App\Actions\User\Create;
use App\Actions\User\Update;
use App\Data\PostFormData;
use App\Data\UserFormData;
use App\Models\Post;
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
