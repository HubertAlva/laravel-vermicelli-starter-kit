<?php

namespace App\Actions\Post;

use App\Concerns\HandlesImageUploads;
use App\Data\PostFormData;
use App\Models\Post;
use App\Services\EditorContentService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class UpdatePost
{
    use HandlesImageUploads;

    public function __construct(
        protected EditorContentService $editorContent,
    ) {}

    public function execute(Post $post, PostFormData $data): Post
    {
        return DB::transaction(function () use ($post, $data): Post {
            $post->update([
                'name' => $data->name,
                'excerpt' => $data->excerpt,
                'content' => $this->editorContent->syncImages($post, $data->content, Post::CONTENT_IMAGES_COLLECTION),
                'published_at' => match (true) {
                    ! $data->published => null,
                    default => $post->published_at ?? now(),
                },
            ]);

            $post->syncTags($data->tags ?? []);

            $this->syncImage(
                $post,
                $data->is_new_thumbnail,
                $data->thumbnail instanceof UploadedFile ? $data->thumbnail : null,
                is_string($data->thumbnail) ? $data->thumbnail : null,
                Post::THUMBNAIL_COLLECTION,
            );

            return $post;
        });
    }
}
