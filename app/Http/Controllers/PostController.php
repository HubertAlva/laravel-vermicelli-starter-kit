<?php

namespace App\Http\Controllers;

use App\Data\PostData;
use App\Filters\SearchFilter;
use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use App\Services\ImageConversionService;
use App\Traits\HandleMediaUploads;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
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

    public function store(StorePostRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $published_at = $validated['published_at'] ? Carbon::now() : null;

        $post = Post::create([
            'name' => $validated['name'],
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'published_at' => $published_at,
        ]);

        preg_match_all('#' . preg_quote(config('app.url')) . '/storage/editor/tmp/[^\)"\s]+#', $validated['content'], $matches);

        $tmpImages = $matches[0];

        $replacements = $this->attachEditorImages($post, $tmpImages);

        foreach ($replacements as $replace) {
            $validated['content'] = str_replace(
                $replace['old'],
                $replace['new'],
                $validated['content']
            );
        }

        $post->update([
            'content' => $validated['content'],
        ]);

        $post->attachTags($validated['tags']);

        $this->handleImageStore(
            $post,
            $request->file('thumbnail'),
            'posts_thumbnails',
            'posts'
        );

        return Redirect::route('admin.posts.index')->with('success', 'Artículo creado correctamente.');
    }

    public function create(): Response
    {
        return Inertia::render('admin/posts/create/Page');
    }

    protected function attachEditorImages(Post $post, array $tmpUrls): array
    {

        $replacements = [];

        foreach ($tmpUrls as $tmpUrl) {
            $tmpPath = str_replace(
                config('app.url') . '/storage/',
                '',
                $tmpUrl
            );

            $absolutePath = storage_path('app/public/' . $tmpPath);

            if (!file_exists($absolutePath)) {
                continue;
            }

            $webpPath = $this->conversionService->convertPathToWebp($absolutePath, 1024, 512);

            $media = $post
                ->addMedia($webpPath)
                ->toMediaCollection('posts_images', 'posts');

            $replacements[] = [
                'old' => $tmpUrl,
                'new' => $media->getUrl(),
            ];

            Storage::disk('public')->delete($tmpPath);
        }

        return $replacements;
    }

    public function update(Post $post)
    {

    }

    public function edit(Post $post)
    {

    }

    public function destroy(Post $post)
    {

    }

    public function soft_delete(Post $post)
    {

    }

    public function restore(Post $post)
    {

    }
}
