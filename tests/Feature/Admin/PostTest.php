<?php

use App\Models\Post;
use App\Models\User;
use Database\Seeders\PermissionRoleSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(PermissionRoleSeeder::class);
    Storage::fake('posts');

    $this->admin = User::factory()->admin()->create();
});

function postPayload(array $overrides = []): array
{
    return [
        'name' => 'My first post',
        'excerpt' => 'A short summary',
        'content' => 'Lorem ipsum',
        'published' => true,
        'is_new_thumbnail' => false,
        'tags' => ['laravel', 'vue'],
        ...$overrides,
    ];
}

test('the index lists posts and can search them', function () {
    Post::factory()->create(['name' => 'Laravel tips']);
    Post::factory()->create(['name' => 'Cooking pasta']);

    $this->actingAs($this->admin)
        ->get(route('admin.posts.index', ['filter' => ['search' => 'laravel']]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/posts/index/Page')
            ->where('filters.search', 'laravel')
            ->missing('posts')
            ->loadDeferredProps(fn (Assert $reload) => $reload
                ->has('posts.data', 1)
                ->where('posts.data.0.name', 'Laravel tips')
                ->where('posts.meta.total', 1),
            ),
        );
});

test('the index only lists trashed posts when requested', function () {
    Post::factory()->create();
    Post::factory()->create(['name' => 'Trashed'])->delete();

    $this->actingAs($this->admin)
        ->get(route('admin.posts.index', ['filter' => ['trashed' => 'only']]))
        ->assertInertia(fn (Assert $page) => $page
            ->loadDeferredProps(fn (Assert $reload) => $reload
                ->has('posts.data', 1)
                ->where('posts.data.0.name', 'Trashed'),
            ),
        );
});

test('the page size is limited to the allowed options', function () {
    Post::factory(12)->create();

    $this->actingAs($this->admin)
        ->get(route('admin.posts.index', ['per_page' => 100000]))
        ->assertInertia(fn (Assert $page) => $page
            ->where('filters.per_page', 10)
            ->loadDeferredProps(fn (Assert $reload) => $reload->has('posts.data', 10)),
        );
});

test('users without permission cannot manage posts', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('admin.view');

    $this->actingAs($user)->get(route('admin.posts.index'))->assertForbidden();
    $this->actingAs($user)->post(route('admin.posts.store'), postPayload())->assertForbidden();

    expect(Post::count())->toBe(0);
});

test('precognitive validation requests are authorized too', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('admin.view');

    $this->actingAs($user)
        ->withHeaders(['Precognition' => 'true', 'Precognition-Validate-Only' => 'name'])
        ->post(route('admin.posts.store'), ['name' => 'x'])
        ->assertForbidden();
});

test('posts can be created with tags and a thumbnail', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.posts.store'), postPayload([
            'is_new_thumbnail' => true,
            'thumbnail' => UploadedFile::fake()->image('cover photo.jpg', 2000, 1000),
        ]))
        ->assertRedirect(route('admin.posts.index'));

    $post = Post::sole();

    expect($post->name)->toBe('My first post')
        ->and($post->slug)->toBe('my-first-post')
        ->and($post->published_at)->not->toBeNull()
        ->and($post->tags->pluck('name')->all())->toBe(['laravel', 'vue']);

    $thumbnail = $post->getFirstMedia(Post::THUMBNAIL_COLLECTION);

    expect($thumbnail->file_name)->toBe('cover-photo.webp')
        ->and($thumbnail->mime_type)->toBe('image/webp');
    Storage::disk('posts')->assertExists($thumbnail->getPathRelativeToRoot());
});

test('drafts are created without a publication date', function () {
    $this->actingAs($this->admin)->post(route('admin.posts.store'), postPayload(['published' => false]));

    expect(Post::sole()->published_at)->toBeNull();
});

test('creating a post requires a name, excerpt and content', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.posts.store'), [])
        ->assertSessionHasErrors(['name', 'excerpt', 'content']);
});

test('post names must be unique', function () {
    Post::factory()->create(['name' => 'Taken']);

    $this->actingAs($this->admin)
        ->post(route('admin.posts.store'), postPayload(['name' => 'Taken']))
        ->assertSessionHasErrors('name');
});

test('the thumbnail must be an image when a new one is uploaded', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.posts.store'), postPayload([
            'is_new_thumbnail' => true,
            'thumbnail' => UploadedFile::fake()->create('script.php', 10, 'application/x-php'),
        ]))
        ->assertSessionHasErrors('thumbnail');
});

test('posts can be updated keeping their publication date', function () {
    $post = Post::factory()->create(['published_at' => now()->subWeek()]);
    $publishedAt = $post->published_at;

    $this->actingAs($this->admin)
        ->post(route('admin.posts.update', $post), postPayload(['name' => 'Renamed', 'tags' => ['php']]))
        ->assertRedirect();

    $post->refresh();

    expect($post->name)->toBe('Renamed')
        ->and($post->published_at->equalTo($publishedAt))->toBeTrue()
        ->and($post->tags->pluck('name')->all())->toBe(['php']);
});

test('unpublishing a post clears its publication date', function () {
    $post = Post::factory()->create();

    $this->actingAs($this->admin)->post(route('admin.posts.update', $post), postPayload(['published' => false]));

    expect($post->refresh()->published_at)->toBeNull();
});

test('removing the thumbnail deletes it', function () {
    $post = Post::factory()->create();
    $post->addMedia(UploadedFile::fake()->image('old.jpg'))->toMediaCollection(Post::THUMBNAIL_COLLECTION);

    $this->actingAs($this->admin)
        ->post(route('admin.posts.update', $post), postPayload(['thumbnail' => null, 'is_new_thumbnail' => false]));

    expect($post->refresh()->getMedia(Post::THUMBNAIL_COLLECTION))->toBeEmpty();
});

test('keeping the current thumbnail does not touch it', function () {
    $post = Post::factory()->create();
    $media = $post->addMedia(UploadedFile::fake()->image('old.jpg'))->toMediaCollection(Post::THUMBNAIL_COLLECTION);

    $this->actingAs($this->admin)
        ->post(route('admin.posts.update', $post), postPayload(['thumbnail' => $media->getUrl()]));

    expect($post->refresh()->getFirstMedia(Post::THUMBNAIL_COLLECTION)->is($media))->toBeTrue();
});

test('deleting a post moves it to the trash and unpublishes it', function () {
    $post = Post::factory()->create();

    $this->actingAs($this->admin)
        ->from(route('admin.posts.index', ['page' => 2]))
        ->delete(route('admin.posts.destroy', $post))
        ->assertRedirect(route('admin.posts.index', ['page' => 2]));

    $this->assertSoftDeleted($post);
    expect(Post::withTrashed()->find($post->id)->published_at)->toBeNull();
});

test('deleting from the edit page redirects to the index', function () {
    $post = Post::factory()->create();

    $this->actingAs($this->admin)
        ->from(route('admin.posts.edit', $post))
        ->delete(route('admin.posts.destroy', $post))
        ->assertRedirect(route('admin.posts.index'));
});

test('trashed posts can be restored and permanently deleted', function () {
    $restored = Post::factory()->create();
    $restored->delete();
    $deleted = Post::factory()->create();
    $deleted->delete();

    $this->actingAs($this->admin)->post(route('admin.posts.restore', $restored))->assertRedirect();
    $this->actingAs($this->admin)->delete(route('admin.posts.force-destroy', $deleted))->assertRedirect();

    $this->assertNotSoftDeleted($restored);
    $this->assertModelMissing($deleted);
});
