<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('posts.viewAny');
    }

    public function view(User $user, Post $post): bool
    {
        if ($post->published_at) {
            return true;
        }

        if ($user->can('posts.view.unpublished')) {
            return true;
        }
        
        return false;
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
