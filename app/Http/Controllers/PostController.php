<?php

namespace App\Http\Controllers;

use App\Actions\Admin\GetIndexData;
use App\Actions\Post\Create;
use App\Actions\Post\Update;
use App\Data\PostData;
use App\Data\PostFormData;
use App\Filters\SearchFilter;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Services\ImageConversionService;
use App\Traits\HandleMediaUploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\LaravelData\PaginatedDataCollection;
use Spatie\QueryBuilder\AllowedFilter;

class PostController extends Controller
{
    use HandleMediaUploads;

    protected ImageConversionService $conversionService;

    public function __construct(ImageConversionService $conversionService)
    {
        $this->conversionService = $conversionService;
    }

    public function index(GetIndexData $action): Response
    {
        Gate::authorize('viewAny', Post::class);

        $posts = $action->execute(
            model: Post::class,
            with: ['tags', 'media'],
            allowedFilters: [
                AllowedFilter::custom('search', new SearchFilter(['name', 'slug'])), AllowedFilter::trashed(),
            ],
        );

        return Inertia::render('admin/posts/index/Page', [
            'posts' => Inertia::defer(fn () => PostData::collect($posts, PaginatedDataCollection::class)->wrap('data')),
            'filters' => [
                'search' => request('filter.search'),
                'per_page' => request()->integer('per_page', 10),
                'trashed' => request('filter.trashed'),
            ],
        ]);
    }

    public function store(StorePostRequest $request, Create $action): RedirectResponse
    {
        Gate::authorize('create', Post::class);

        $action->execute(
            PostFormData::from($request->validated()),
            $request->file('thumbnail')
        );

        Inertia::flash('toast',
            [
                'type' => 'success',
                'message' => 'Artículo creado correctamente.',
            ]);

        return Redirect::route('admin.posts.index');
    }

    public function create(): Response
    {
        Gate::authorize('create', Post::class);

        return Inertia::render('admin/posts/create/Page');
    }

    public function update(UpdatePostRequest $request, Update $action, Post $post): RedirectResponse
    {
        Gate::authorize('update', $post);

        $action->execute(
            $post,
            PostFormData::from($request->validated()),
            $request->file('thumbnail')
        );

        Inertia::flash('toast',
            [
                'type' => 'success',
                'message' => 'Artículo actualizado correctamente.',
                'id' => $post->id,
            ]);

        $post->refresh();

        return Redirect::back();
    }

    public function edit(Post $post): Response
    {
        Gate::authorize('update', $post);

        $post->load('tags');

        return Inertia::render('admin/posts/edit/Page', [
            'post' => PostData::from($post),
        ]);
    }

    public function destroy(Post $post): RedirectResponse
    {
        Gate::authorize('forceDelete', $post);

        $post->forceDelete();

        $currentPage = request('currentPage');
        $trashed = request('trashed');

        Inertia::flash('toast',
            [
                'type' => 'success',
                'message' => 'Artículo eliminado correctamente.',
                'id' => $post->id,
            ]);

        return Redirect::route('admin.posts.index', [
            'page' => $currentPage,
            'filter' => $trashed ? [
                'trashed' => $trashed,
            ] : null,
        ]);
    }

    public function soft_delete(Post $post): RedirectResponse
    {
        Gate::authorize('delete', $post);

        $currentPage = request('currentPage');
        $post->published_at = null;
        $post->save();

        $post->delete();

        Inertia::flash('toast',
            [
                'type' => 'success',
                'message' => 'Artículo enviado a papelera correctamente.',
                'id' => $post->id,
            ]);

        return Redirect::route('admin.posts.index', [
            'page' => $currentPage,
        ]);
    }

    public function restore(Post $post): RedirectResponse
    {
        Gate::authorize('restore', $post);

        $post->restore();

        Inertia::flash('toast',
            [
                'type' => 'success',
                'message' => 'Artículo restaurado correctamente.',
            ]);

        return Redirect::back();
    }
}
