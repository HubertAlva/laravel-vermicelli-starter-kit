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
