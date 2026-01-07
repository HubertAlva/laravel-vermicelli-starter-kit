<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;

class Post extends Model implements HasMedia
{
    use HasSlug, HasTags, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'excerpt',
        'content',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected $appends = [
        'thumbnail',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function getThumbnailAttribute(): ?string
    {
        $media = $this->getFirstMedia('posts_thumbnails');

        if (! $media) {
            return null;
        }

        return $media->getUrl();
    }

    public function getImagesAttribute(): ?array
    {
        $media = $this->getMedia('posts_images');

        if ($media->isEmpty()) {
            return null;
        }

        return $media->map(function ($mediaItem) {
            return $mediaItem->getUrl();
        })->toArray();
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('posts_thumbnails')
            ->useDisk('posts')
            ->singleFile();

        $this
            ->addMediaCollection('posts_images')
            ->useDisk('posts');
    }
}
