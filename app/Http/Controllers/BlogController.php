<?php

namespace App\Http\Controllers;

use App\Data\PostData;
use App\Models\Post;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\LaravelData\PaginatedDataCollection;

class BlogController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('blog/index/Page', [
            'posts' => Inertia::defer(fn () => PostData::collect(
                Post::query()
                    ->published()
                    ->with(['tags', 'media'])
                    ->latest('published_at')
                    ->paginate(12),
                PaginatedDataCollection::class,
            )),
        ]);
    }

    public function show(Post $post): Response
    {
        Gate::authorize('view', $post);

        $post->load(['tags', 'media']);

        return Inertia::render('blog/show/Page', [
            'post' => PostData::from($post)->include('content'),
        ]);
    }
}
