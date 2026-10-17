<?php

namespace App\Models;

use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $excerpt
 * @property string $content
 * @property Carbon|null $published_at
 * @property-read string|null $thumbnail
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
#[Fillable(['name', 'slug', 'excerpt', 'content', 'published_at'])]
class Post extends Model implements HasMedia
{
    /** @use HasFactory<PostFactory> */
    use HasFactory, HasSlug, HasTags, InteractsWithMedia, SoftDeletes;

    public const string THUMBNAIL_COLLECTION = 'thumbnail';

    public const string CONTENT_IMAGES_COLLECTION = 'content_images';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::THUMBNAIL_COLLECTION)
            ->useDisk('posts')
            ->singleFile();

        $this->addMediaCollection(self::CONTENT_IMAGES_COLLECTION)
            ->useDisk('posts');
    }

    /**
     * Get the URL of the post's thumbnail.
     *
     * @return Attribute<string|null, never>
     */
    protected function thumbnail(): Attribute
    {
        return Attribute::get(
            fn (): ?string => $this->getFirstMedia(self::THUMBNAIL_COLLECTION)?->getUrl(),
        );
    }

    /**
     * Determine if the post is published.
     */
    public function isPublished(): bool
    {
        return $this->published_at !== null;
    }

    /**
     * Scope the query to published posts.
     *
     * @param  Builder<self>  $query
     */
    #[Scope]
    protected function published(Builder $query): void
    {
        $query->whereNotNull('published_at');
    }
}
