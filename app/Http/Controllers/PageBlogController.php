<?php

namespace App\Http\Controllers;

use App\Data\PostData;
use App\Models\Post;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\LaravelData\PaginatedDataCollection;

class PageBlogController extends Controller
{
    public function index(): Response
    {
        $posts = Post::with('tags')
            ->whereNotNull('published_at')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return Inertia::render('blog/index/Page', [
            'posts' => Inertia::defer(fn () => PostData::collect($posts, PaginatedDataCollection::class)->wrap('data')),
        ]);
    }

    public function show(Post $post): Response
    {
        $post->load('tags');

        return Inertia::render('blog/show/Page', [
            'post' => PostData::from($post),
        ]);
    }
}
