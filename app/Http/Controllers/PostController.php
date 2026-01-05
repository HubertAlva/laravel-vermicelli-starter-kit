<?php

namespace App\Http\Controllers;

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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\LaravelData\PaginatedDataCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PostController extends Controller
{
    use HandleMediaUploads;

    protected ImageConversionService $conversionService;

    public function __construct(ImageConversionService $conversionService)
    {
        $this->conversionService = $conversionService;
    }

    public function index(Request $request): Response
    {
        $per_page = $request->get('per_page', 10);
        $page = $request->get('page', 1);

        $posts = QueryBuilder::for(Post::class)
            ->defaultSort('id')
            ->allowedFilters([
                AllowedFilter::custom('search', new SearchFilter)
                , AllowedFilter::trashed()])
            ->paginate($per_page, ['*'], 'page', $page)
            ->withQueryString();

        return Inertia::render('admin/posts/index/Page', [
            'posts' => Inertia::defer(fn() => PostData::collect($posts, PaginatedDataCollection::class)->wrap('data')),
        ]);
    }

    public function store(StorePostRequest $request, Create $create): RedirectResponse
    {
        $create->execute(
            FormPostData::from($request->validated()),
            $request->file('thumbnail')
        );

        return Redirect::route('admin.posts.index')->with('success', 'Artículo creado correctamente.');
    }

    public function create(): Response
    {
        return Inertia::render('admin/posts/create/Page');
    }

    public function update(UpdatePostRequest $request, Update $update, Post $post): RedirectResponse
    {
        $update->execute(
            $post,
            FormPostData::from($request->validated()),
            $request->file('thumbnail')
        );

        return Redirect::route('admin.posts.index')->with('success', 'Artículo actualizado correctamente.');
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

        return Redirect::route('admin.posts.index')->with('success', 'Artículo eliminado correctamente.');
    }

    public function soft_delete(Post $post): RedirectResponse
    {
        $post->published_at = null;
        $post->save();

        $post->delete();

        return Redirect::route('admin.posts.index')
            ->with('success', 'Artículo enviado a papelera correctamente.');

    }

    public function restore(Post $post): RedirectResponse
    {
        $post->restore();

        return Redirect::route('admin.posts.index')->with('success', 'Artículo restaurado correctamente.');
    }
}
