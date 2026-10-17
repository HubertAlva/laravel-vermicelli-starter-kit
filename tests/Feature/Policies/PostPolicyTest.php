<?php

use App\Models\Post;
use App\Models\User;
use App\Policies\PostPolicy;
use Database\Seeders\PermissionRoleSeeder;

beforeEach(function () {
    $this->seed(PermissionRoleSeeder::class);
});

test('each ability requires its permission', function (string $ability, string $permission) {
    $policy = new PostPolicy;
    $post = Post::factory()->create();
    $user = User::factory()->create();

    $arguments = $ability === 'viewAny' || $ability === 'create' ? [$user] : [$user, $post];

    expect($policy->{$ability}(...$arguments))->toBeFalse();

    $user->givePermissionTo($permission);

    expect($policy->{$ability}(...$arguments))->toBeTrue();
})->with([
    ['viewAny', 'posts.viewAny'],
    ['create', 'posts.create'],
    ['update', 'posts.update'],
    ['delete', 'posts.delete'],
    ['restore', 'posts.restore'],
    ['forceDelete', 'posts.forceDelete'],
]);

test('anyone can view published posts', function () {
    expect((new PostPolicy)->view(null, Post::factory()->create()))->toBeTrue();
});

test('drafts and trashed posts require the unpublished permission', function (Post $post) {
    $policy = new PostPolicy;
    $user = User::factory()->create();

    expect($policy->view(null, $post))->toBeFalse()
        ->and($policy->view($user, $post))->toBeFalse();

    $user->givePermissionTo('posts.view.unpublished');

    expect($policy->view($user, $post))->toBeTrue();
})->with([
    'draft' => fn () => Post::factory()->unpublished()->create(),
    'trashed' => fn () => tap(Post::factory()->create())->delete(),
]);
