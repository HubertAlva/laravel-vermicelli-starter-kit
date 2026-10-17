<?php

namespace App\Actions\Post;

use App\Concerns\HandlesImageUploads;
use App\Data\PostFormData;
use App\Models\Post;
use App\Services\EditorContentService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class CreatePost
{
    use HandlesImageUploads;

    public function __construct(
        protected EditorContentService $editorContent,
    ) {}

    public function execute(PostFormData $data): Post
    {
        return DB::transaction(function () use ($data): Post {
            $post = Post::create([
                'name' => $data->name,
                'excerpt' => $data->excerpt,
                'content' => $data->content,
                'published_at' => $data->published ? now() : null,
            ]);

            $post->update([
                'content' => $this->editorContent->attachImages($post, $data->content, Post::CONTENT_IMAGES_COLLECTION),
            ]);

            $post->syncTags($data->tags ?? []);

            if ($data->thumbnail instanceof UploadedFile) {
                $this->storeImage($post, $data->thumbnail, Post::THUMBNAIL_COLLECTION);
            }

            return $post;
        });
    }
}
