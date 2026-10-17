<?php

namespace App\Concerns;

use App\Services\ImageConversionService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;

/**
 * Stores uploaded images in a media collection, converted to WebP.
 */
trait HandlesImageUploads
{
    /**
     * Add an uploaded image to the model's media collection.
     */
    protected function storeImage(
        HasMedia $model,
        ?UploadedFile $image,
        string $collection,
        int $maxWidth = 1024,
        int $maxHeight = 1024,
    ): void {
        if (! $image?->isValid()) {
            return;
        }

        $path = app(ImageConversionService::class)->toWebp($image->getRealPath(), $maxWidth, $maxHeight);

        $model->addMedia($path)
            ->usingFileName(Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) ?: 'image').'.webp')
            ->toMediaCollection($collection);
    }

    /**
     * Replace or remove the model's image depending on what the form sent.
     *
     * A new upload replaces the current image, while an empty value without
     * a new upload means the user removed the image.
     */
    protected function syncImage(
        HasMedia $model,
        bool $isNewImage,
        ?UploadedFile $image,
        ?string $currentValue,
        string $collection,
        int $maxWidth = 1024,
        int $maxHeight = 1024,
    ): void {
        if ($isNewImage && $image) {
            $model->clearMediaCollection($collection);
            $this->storeImage($model, $image, $collection, $maxWidth, $maxHeight);

            return;
        }

        if (! $isNewImage && $currentValue === null) {
            $model->clearMediaCollection($collection);
        }
    }
}
