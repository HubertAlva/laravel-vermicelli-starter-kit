<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\GetIndexData;
use App\Actions\Post\CreatePost;
use App\Actions\Post\UpdatePost;
use App\Data\PostData;
use App\Data\PostFormData;
use App\Filters\SearchFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\LaravelData\PaginatedDataCollection;
use Spatie\QueryBuilder\AllowedFilter;

class PostController extends Controller
{
    public function index(Request $request, GetIndexData $indexData): Response
    {
        Gate::authorize('viewAny', Post::class);

        return Inertia::render('admin/posts/index/Page', [
            'posts' => Inertia::defer(fn () => PostData::collect(
                $indexData->execute(
                    model: Post::class,
                    with: ['media'],
                    allowedFilters: [
                        AllowedFilter::custom('search', new SearchFilter(['name', 'slug'])),
                        AllowedFilter::trashed(),
                    ],
                ),
                PaginatedDataCollection::class,
            )),
            'filters' => [
                'search' => $request->input('filter.search'),
                'trashed' => $request->input('filter.trashed'),
                'per_page' => $indexData->perPage(),
            ],
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', Post::class);

        return Inertia::render('admin/posts/create/Page');
    }

    public function store(PostRequest $request, CreatePost $createPost): RedirectResponse
    {
        $post = $createPost->execute(PostFormData::from($request->validated()));

        $this->toast('Artículo creado correctamente.', id: $post->id);

        return to_route('admin.posts.index');
    }

    public function edit(Post $post): Response
    {
        Gate::authorize('update', $post);

        $post->load(['tags', 'media']);

        return Inertia::render('admin/posts/edit/Page', [
            'post' => PostData::from($post)->include('content'),
        ]);
    }

    public function update(PostRequest $request, Post $post, UpdatePost $updatePost): RedirectResponse
    {
        $updatePost->execute($post, PostFormData::from($request->validated()));

        $this->toast('Artículo actualizado correctamente.', id: $post->id);

        return back();
    }

    /**
     * Move the post to the trash.
     */
    public function destroy(Post $post): RedirectResponse
    {
        Gate::authorize('delete', $post);

        $post->update(['published_at' => null]);
        $post->delete();

        $this->toast('Artículo enviado a la papelera.', id: $post->id);

        return $this->backToIndex('admin.posts.index');
    }

    public function restore(Post $post): RedirectResponse
    {
        Gate::authorize('restore', $post);

        $post->restore();

        $this->toast('Artículo restaurado correctamente.', id: $post->id);

        return back();
    }

    /**
     * Permanently delete the post and its media.
     */
    public function forceDestroy(Post $post): RedirectResponse
    {
        Gate::authorize('forceDelete', $post);

        $post->forceDelete();

        $this->toast('Artículo eliminado definitivamente.', id: $post->id);

        return $this->backToIndex('admin.posts.index');
    }
}
