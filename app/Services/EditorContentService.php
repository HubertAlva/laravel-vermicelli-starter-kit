<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Moves images uploaded from the Markdown editor out of temporary storage
 * into the model's media collection and keeps the content's URLs in sync.
 */
class EditorContentService
{
    public const string TEMP_DISK = 'editor_tmp';

    public function __construct(
        protected ImageConversionService $conversionService,
    ) {}

    /**
     * Attach temporary images referenced in the content and rewrite their URLs.
     */
    public function attachImages(HasMedia $model, string $content, string $collection): string
    {
        $disk = Storage::disk(self::TEMP_DISK);

        preg_match_all($this->temporaryUrlPattern(), $content, $matches, PREG_SET_ORDER);

        foreach ($matches as [$url, $fileName]) {
            if (! $disk->exists($fileName)) {
                continue;
            }

            $media = $model
                ->addMedia($this->conversionService->toWebp($disk->path($fileName)))
                ->usingFileName(pathinfo($fileName, PATHINFO_FILENAME).'.webp')
                ->toMediaCollection($collection);

            $disk->delete($fileName);

            $content = str_replace($url, $media->getUrl(), $content);
        }

        return $content;
    }

    /**
     * Delete media no longer referenced in the content, then attach new images.
     */
    public function syncImages(HasMedia $model, string $content, string $collection): string
    {
        $model->getMedia($collection)
            ->reject(fn (Media $media): bool => str_contains($content, (string) parse_url($media->getUrl(), PHP_URL_PATH)))
            ->each(fn (Media $media) => $media->delete());

        return $this->attachImages($model, $content, $collection);
    }

    /**
     * Match absolute, protocol-relative or relative temporary image URLs.
     *
     * Only plain file names are captured, so a crafted URL can never point
     * outside the temporary directory.
     */
    protected function temporaryUrlPattern(): string
    {
        $path = (string) parse_url(Storage::disk(self::TEMP_DISK)->url(''), PHP_URL_PATH);

        return '#(?:https?:)?(?://[^/\s)"\'<>]+)?'.preg_quote(rtrim($path, '/'), '#').'/([A-Za-z0-9_-]+\.[A-Za-z0-9]+)#';
    }
}
