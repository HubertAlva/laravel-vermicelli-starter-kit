<?php

namespace App\Actions\Post;

use App\Data\FormPostData;
use App\Models\Post;
use App\Services\EditorContentProcessorService;
use App\Traits\HandleMediaUploads;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class Update
{
    use HandleMediaUploads;

    public function __construct(
        private EditorContentProcessorService $contentProcessor,
    )
    {
    }

    public function execute(Post $post, FormPostData $data, ?UploadedFile $thumbnail): Post
    {
        return DB::transaction(function () use ($post, $data, $thumbnail) {

            $publishedAt = $data->published_at ? Carbon::now() : null;

            $oldContent = $post->content;
            $newContent = $data->content;

            $post->update([
                'name' => $data->name,
                'excerpt' => $data->excerpt,
                'published_at' => $publishedAt,
            ]);

            if ($oldContent !== $newContent) {
                $newContent = $this->contentProcessor->sync(
                    model: $post,
                    oldContent: $oldContent,
                    newContent: $newContent,
                    collection: 'posts_images',
                    disk: 'posts'
                );
            }

            $post->update(['content' => $newContent]);

            $post->syncTags($data->tags);

            $this->handleImageUpdate(
                $post,
                $data->is_new_thumbnail,
                $thumbnail,
                $data->thumbnail,
                'posts_thumbnails',
                'posts'
            );

            return $post;
        });
    }
}
