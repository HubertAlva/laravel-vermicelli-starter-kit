<?php

namespace App\Data;

use App\Models\Post;
use Carbon\CarbonInterface;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;

class PostData extends Data
{
    /**
     * @param  Lazy|array<int, TagData>  $tags
     */
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public ?string $excerpt,
        public Lazy|string $content,
        public ?string $thumbnail,
        public ?CarbonInterface $published_at,
        public ?CarbonInterface $created_at,
        public ?CarbonInterface $updated_at,
        public ?CarbonInterface $deleted_at,
        public Lazy|array $tags,
    ) {}

    /**
     * Content and tags are only included when explicitly requested or loaded,
     * keeping listing payloads small.
     */
    public static function fromModel(Post $post): self
    {
        return new self(
            id: $post->id,
            name: $post->name,
            slug: $post->slug,
            excerpt: $post->excerpt,
            content: Lazy::create(fn (): string => $post->content),
            thumbnail: $post->thumbnail,
            published_at: $post->published_at,
            created_at: $post->created_at,
            updated_at: $post->updated_at,
            deleted_at: $post->deleted_at,
            tags: Lazy::whenLoaded('tags', $post, fn () => TagData::collect($post->tags)->all()),
        );
    }
}
