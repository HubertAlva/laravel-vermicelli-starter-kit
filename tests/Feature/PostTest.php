<?php

namespace Tests\Feature;

use App\Actions\Post\Create;
use App\Actions\Post\Update;
use App\Data\PostFormData;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create()->assignRole('admin');

        $this->actingAs($this->admin);
    }

    public function test_posts_index_can_be_rendered()
    {
        $response = $this->get(route('admin.posts.index'));

        $response->assertStatus(200);
    }

    public function test_posts_can_be_created()
    {
        $action = $this->app->make(Create::class);

        Storage::fake('posts');

        $data = PostFormData::from([
            'name' => 'Test Post',
            'excerpt' => 'Short description',
            'content' => 'Lorem',
            'published_at' => true,
            'is_new_thumbnail' => false,
            'tags' => ['tag1', 'tag2'],
        ]);

        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg');

        $post = $action->execute($data, $thumbnail);

        $this->assertNotNull($post->id);

        $this->assertInstanceOf(Post::class, $post);

        $this->assertDatabaseHas('posts', [
            'name' => 'Test Post',
        ]);

        $this->assertCount(1, $post->getMedia('posts_thumbnails'));

        $media = $post->getFirstMedia('posts_thumbnails');

        Storage::disk('posts')->assertExists(
            $media->getPathRelativeToRoot()
        );
    }

    public function test_posts_can_be_updated()
    {
        $action = $this->app->make(Update::class);

        Storage::fake('posts');

        $post = Post::factory()->create();

        $data = PostFormData::from([
            'name' => 'Updated Post',
            'excerpt' => 'Short description',
            'content' => 'Lorem',
            'published_at' => true,
            'is_new_thumbnail' => true,
            'tags' => null,
        ]);

        $thumbnail = UploadedFile::fake()->image('new-thumbnail.jpg');

        $updatedPost = $action->execute($post, $data, $thumbnail)->fresh();

        $this->assertNotNull($updatedPost->id);

        $this->assertInstanceOf(Post::class, $updatedPost);

        $this->assertDatabaseHas('posts', [
            'name' => 'Updated Post',
        ]);

        $this->assertCount(1, $updatedPost->getMedia('posts_thumbnails'));

        $media = $updatedPost->getFirstMedia('posts_thumbnails');

        Storage::disk('posts')->assertExists(
            $media->getPathRelativeToRoot()
        );
    }

    public function test_posts_can_be_created_through_the_store_endpoint()
    {
        Storage::fake('posts');

        $response = $this->post(route('admin.posts.store'), [
            'name' => 'HTTP Created Post',
            'excerpt' => 'Short description',
            'content' => 'Lorem ipsum',
            'published_at' => true,
            'is_new_thumbnail' => false,
            'tags' => ['tag1'],
        ]);

        $response->assertRedirect(route('admin.posts.index'));

        $this->assertDatabaseHas('posts', [
            'name' => 'HTTP Created Post',
        ]);
    }

    public function test_store_post_fails_validation_when_required_fields_are_missing()
    {
        $response = $this->post(route('admin.posts.store'), []);

        $response->assertSessionHasErrors(['name', 'excerpt', 'content']);

        $this->assertDatabaseCount('posts', 0);
    }

    public function test_posts_can_be_updated_through_the_update_endpoint()
    {
        $post = Post::factory()->create();

        $response = $this->post(route('admin.posts.update', $post), [
            'name' => 'HTTP Updated Post',
            'excerpt' => 'Short description',
            'content' => 'Lorem ipsum',
            'published_at' => true,
            'is_new_thumbnail' => false,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'name' => 'HTTP Updated Post',
        ]);
    }

    public function test_users_without_the_posts_create_permission_are_forbidden_from_creating_posts()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('admin.view');

        $response = $this->actingAs($user)->post(route('admin.posts.store'), [
            'name' => 'Should Not Be Created',
            'excerpt' => 'Short description',
            'content' => 'Lorem ipsum',
            'published_at' => true,
            'is_new_thumbnail' => false,
        ]);

        $response->assertForbidden();

        $this->assertDatabaseMissing('posts', [
            'name' => 'Should Not Be Created',
        ]);
    }

    public function test_posts_can_be_deleted()
    {
        $post = Post::factory()->create();

        $response = $this->delete(route('admin.posts.destroy', $post));

        $response->assertRedirect(route('admin.posts.index'));

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }

    public function test_posts_can_be_soft_deleted()
    {
        $post = Post::factory()->create();

        $response = $this->delete(route('admin.posts.soft-delete', $post));

        $response->assertRedirect(route('admin.posts.index'));

        $this->assertSoftDeleted('posts', [
            'id' => $post->id,
        ]);
    }

    public function test_posts_can_be_restored()
    {
        $post = Post::factory()->create();

        $post->delete();

        $response = $this->post(route('admin.posts.restore', $post));

        $response->assertRedirect();

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'deleted_at' => null,
        ]);
    }
}
