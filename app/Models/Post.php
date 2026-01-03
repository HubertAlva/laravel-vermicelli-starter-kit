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
    use SoftDeletes, HasSlug, HasTags, InteractsWithMedia;

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

        if (!$media) {
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

//    public function scopeFilters($query, array $filters)
//    {
//        $query->when($filters['search'] ?? null, function ($query, $search) {
//            $query->where(function ($query) use ($search) {
//                $query->where('name', 'like', '%' . $search . '%')
//                    ->orWhere('slug', 'like', '%' . $search . '%')
//                    ->orWhere('excerpt', 'like', '%' . $search . '%');
//            });
//        });
//
//        if (isset($filters['only_trashed']) && filter_var($filters['only_trashed'], FILTER_VALIDATE_BOOLEAN)) {
//            $query->onlyTrashed();
//        }
//    }

//    public function scopeOnlyTrashed($query, $only_trashed = false)
//    {
//        if ($only_trashed) {
//            return $query->onlyTrashed();
//        }
//
//        return $query;
//    }
}
