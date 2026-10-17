<?php

use App\Models\Post;
use App\Models\User;
use App\Services\EditorContentService;
use Database\Seeders\PermissionRoleSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('editor_tmp');
    Storage::fake('posts');
});

test('admins can upload editor images to temporary storage', function () {
    $this->seed(PermissionRoleSeeder::class);

    $response = $this->actingAs(User::factory()->admin()->create())
        ->postJson(route('admin.editor.images.store'), [
            'image' => UploadedFile::fake()->image('photo.png'),
        ])
        ->assertOk();

    $fileName = basename($response->json('url'));

    Storage::disk('editor_tmp')->assertExists($fileName);
});

test('only images can be uploaded from the editor', function () {
    $this->seed(PermissionRoleSeeder::class);

    $this->actingAs(User::factory()->admin()->create())
        ->postJson(route('admin.editor.images.store'), [
            'image' => UploadedFile::fake()->create('notes.pdf', 10, 'application/pdf'),
        ])
        ->assertJsonValidationErrors('image');
});

test('temporary images in the content are attached to the post', function () {
    $disk = Storage::disk('editor_tmp');
    $disk->put('abc123.png', UploadedFile::fake()->image('x.png')->getContent());
    $post = Post::factory()->create();

    $content = app(EditorContentService::class)->attachImages(
        $post,
        'Intro ![image]('.$disk->url('abc123.png').') outro',
        Post::CONTENT_IMAGES_COLLECTION,
    );

    $media = $post->getFirstMedia(Post::CONTENT_IMAGES_COLLECTION);

    expect($content)->toBe('Intro ![image]('.$media->getUrl().') outro')
        ->and($media->file_name)->toBe('abc123.webp');
    $disk->assertMissing('abc123.png');
});

test('editor URLs cannot reference files outside temporary storage', function () {
    Storage::fake('public')->put('secret.txt', 'secret');
    $post = Post::factory()->create();
    $content = 'Look ![x](/storage/editor/tmp/../../secret.txt)';

    $result = app(EditorContentService::class)->attachImages($post, $content, Post::CONTENT_IMAGES_COLLECTION);

    expect($result)->toBe($content)
        ->and($post->getMedia(Post::CONTENT_IMAGES_COLLECTION))->toBeEmpty();
    Storage::disk('public')->assertExists('secret.txt');
});

test('images removed from the content are deleted when syncing', function () {
    $post = Post::factory()->create();
    $kept = $post->addMedia(UploadedFile::fake()->image('kept.jpg'))->toMediaCollection(Post::CONTENT_IMAGES_COLLECTION);
    $removed = $post->addMedia(UploadedFile::fake()->image('removed.jpg'))->toMediaCollection(Post::CONTENT_IMAGES_COLLECTION);

    app(EditorContentService::class)->syncImages(
        $post,
        '![kept]('.$kept->getUrl().')',
        Post::CONTENT_IMAGES_COLLECTION,
    );

    $this->assertModelExists($kept);
    $this->assertModelMissing($removed);
});

test('the cleanup command deletes only old temporary images', function () {
    $disk = Storage::disk('editor_tmp');
    $disk->put('old.png', 'old');
    $disk->put('new.png', 'new');
    touch($disk->path('old.png'), now()->subHours(3)->getTimestamp());

    $this->artisan('editor:cleanup-images')->assertSuccessful();

    $disk->assertMissing('old.png');
    $disk->assertExists('new.png');
});
