<?php

namespace App\Http\Controllers;

use App\Actions\Admin\GetIndexData;
use App\Actions\Post\Create;
use App\Actions\Post\Update;
use App\Data\FormPostData;
use App\Data\PostData;
use App\Filters\SearchFilter;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Services\ImageConversionService;
use App\Traits\HandleMediaUploads;
use Illuminate\Http\RedirectResponse;
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
        $posts = $action->execute(
            model: Post::class,
            allowedFilters: [
                AllowedFilter::custom('search', new SearchFilter), AllowedFilter::trashed(),
            ],
        );

        return Inertia::render('admin/posts/index/Page', [
            //            'posts' => Inertia::defer(fn () => PostData::collect($posts, PaginatedDataCollection::class)->wrap('data')),
            'posts' => PostData::collect($posts, PaginatedDataCollection::class)->wrap('data'),
            'filters' => [
                'search' => request('filter.search'),
                'trashed' => request('filter.trashed'),
            ],
        ]);
    }

    public function store(StorePostRequest $request, Create $action): RedirectResponse
    {
        $action->execute(
            FormPostData::from($request->validated()),
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
        return Inertia::render('admin/posts/create/Page');
    }

    public function update(UpdatePostRequest $request, Update $action, Post $post): RedirectResponse
    {
        $action->execute(
            $post,
            FormPostData::from($request->validated()),
            $request->file('thumbnail')
        );

        Inertia::flash('toast',
            [
                'type' => 'success',
                'message' => 'Artículo actualizado correctamente.',
                'id' => $post->id,
            ]);

        return Redirect::route('admin.posts.index');
    }

    public function edit(Post $post): Response
    {
        $post->load('tags');

        return Inertia::render('admin/posts/edit/Page', [
            'post' => PostData::from($post),
        ]);
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->forceDelete();

        Inertia::flash('toast',
            [
                'type' => 'success',
                'message' => 'Artículo eliminado correctamente.',
            ]);

        return Redirect::route('admin.posts.index');
    }

    public function soft_delete(Post $post): RedirectResponse
    {
        $post->published_at = null;
        $post->save();

        $post->delete();

        Inertia::flash('toast',
            [
                'type' => 'success',
                'message' => 'Artículo enviado a papelera correctamente.',
            ]);

        return Redirect::route('admin.posts.index');
    }

    public function restore(Post $post): RedirectResponse
    {
        $post->restore();

        Inertia::flash('toast',
            [
                'type' => 'success',
                'message' => 'Artículo restaurado correctamente.',
            ]);

        return Redirect::back();
    }
}
