<?php

namespace App\Actions\Post;

use App\Data\FormPostData;
use App\Models\Post;
use App\Services\EditorContentProcessorService;
use App\Traits\HandleMediaUploads;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class Create
{
    use HandleMediaUploads;

    public function __construct(
        private EditorContentProcessorService $contentProcessor,
    ) {}

    public function execute(FormPostData $data, ?UploadedFile $thumbnail): Post
    {
        return DB::transaction(function () use ($data, $thumbnail) {

            $publishedAt = $data->published_at ? Carbon::now() : null;

            $post = Post::create([
                'name' => $data->name,
                'excerpt' => $data->excerpt,
                'content' => $data->content,
                'published_at' => $publishedAt,
            ]);

            $content = $this->contentProcessor->process(
                model: $post,
                content: $data->content,
                collection: 'posts_images',
                disk: 'posts'
            );

            $post->update(['content' => $content]);

            if ($data->tags) {
                $post->attachTags($data->tags);
            }

            $this->handleImageStore(
                $post,
                $thumbnail,
                'posts_thumbnails',
                'posts'
            );

            return $post;
        });
    }
}
