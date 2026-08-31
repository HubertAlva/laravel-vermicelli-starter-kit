<?php

namespace Tests\Feature\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class PostPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_view_any_is_denied_without_the_posts_view_any_permission()
    {
        $user = User::factory()->create();

        $this->assertFalse($user->can('viewAny', Post::class));
    }

    public function test_view_any_is_allowed_with_the_posts_view_any_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('posts.viewAny');

        $this->assertTrue($user->can('viewAny', Post::class));
    }

    public function test_guests_can_view_published_posts()
    {
        $post = Post::factory()->create(['published_at' => now()]);

        $this->assertTrue(Gate::allows('view', $post));
    }

    public function test_guests_cannot_view_unpublished_posts()
    {
        $post = Post::factory()->create(['published_at' => null]);

        $this->assertFalse(Gate::allows('view', $post));
    }

    public function test_users_without_permission_cannot_view_unpublished_posts()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['published_at' => null]);

        $this->assertFalse(Gate::forUser($user)->allows('view', $post));
    }

    public function test_users_with_the_posts_view_unpublished_permission_can_view_unpublished_posts()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('posts.view.unpublished');
        $post = Post::factory()->create(['published_at' => null]);

        $this->assertTrue(Gate::forUser($user)->allows('view', $post));
    }

    public function test_create_is_denied_without_the_posts_create_permission()
    {
        $user = User::factory()->create();

        $this->assertFalse($user->can('create', Post::class));
    }

    public function test_create_is_allowed_with_the_posts_create_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('posts.create');

        $this->assertTrue($user->can('create', Post::class));
    }

    public function test_update_is_denied_without_the_posts_update_permission()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->assertFalse($user->can('update', $post));
    }

    public function test_update_is_allowed_with_the_posts_update_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('posts.update');
        $post = Post::factory()->create();

        $this->assertTrue($user->can('update', $post));
    }

    public function test_delete_is_denied_without_the_posts_delete_permission()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->assertFalse($user->can('delete', $post));
    }

    public function test_delete_is_allowed_with_the_posts_delete_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('posts.delete');
        $post = Post::factory()->create();

        $this->assertTrue($user->can('delete', $post));
    }

    public function test_restore_is_denied_without_the_posts_restore_permission()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->assertFalse($user->can('restore', $post));
    }

    public function test_restore_is_allowed_with_the_posts_restore_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('posts.restore');
        $post = Post::factory()->create();

        $this->assertTrue($user->can('restore', $post));
    }

    public function test_force_delete_is_denied_without_the_posts_force_delete_permission()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->assertFalse($user->can('forceDelete', $post));
    }

    public function test_force_delete_is_allowed_with_the_posts_force_delete_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('posts.forceDelete');
        $post = Post::factory()->create();

        $this->assertTrue($user->can('forceDelete', $post));
    }
}
