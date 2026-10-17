<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('posts.viewAny');
    }

    /**
     * Published posts are public; drafts and trashed posts need permission.
     */
    public function view(?User $user, Post $post): bool
    {
        if ($post->isPublished() && ! $post->trashed()) {
            return true;
        }

        return $user?->can('posts.view.unpublished') ?? false;
    }

    public function create(User $user): bool
    {
        return $user->can('posts.create');
    }

    public function update(User $user, Post $post): bool
    {
        return $user->can('posts.update');
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->can('posts.delete');
    }

    public function restore(User $user, Post $post): bool
    {
        return $user->can('posts.restore');
    }

    public function forceDelete(User $user, Post $post): bool
    {
        return $user->can('posts.forceDelete');
    }
}
