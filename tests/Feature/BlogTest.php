<?php

use App\Models\Post;
use Inertia\Testing\AssertableInertia as Assert;

test('the blog lists only published posts', function () {
    Post::factory()->create(['name' => 'Published']);
    Post::factory()->unpublished()->create();
    Post::factory()->create()->delete();

    $this->get(route('blog.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('blog/index/Page')
            ->loadDeferredProps(fn (Assert $reload) => $reload
                ->has('posts.data', 1)
                ->where('posts.data.0.name', 'Published')
                ->missing('posts.data.0.content'),
            ),
        );
});

test('published posts can be read', function () {
    $post = Post::factory()->create(['content' => '# Hello']);

    $this->get(route('blog.show', $post->slug))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('blog/show/Page')
            ->where('post.content', '# Hello'),
        );
});

test('drafts are hidden from visitors', function () {
    $post = Post::factory()->unpublished()->create();

    $this->get(route('blog.show', $post->slug))->assertForbidden();
});
