<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EditorContentProcessorService
{
    public function __construct(
        protected ImageConversionService $conversionService,
    )
    {
    }

    public function process(
        Model  $model,
        string $content,
        string $collection,
        string $disk
    ): string
    {
        preg_match_all(
            '#' . preg_quote(config('app.url')) . '/storage/editor/tmp/[^\)"\s]+#',
            $content,
            $matches
        );

        if (empty($matches[0])) {
            return $content;
        }

        $replacements = $this->attachEditorImages(
            $model,
            $matches[0],
            $collection,
            $disk
        );

        foreach ($replacements as $replace) {
            $content = str_replace(
                $replace['old'],
                $replace['new'],
                $content
            );
        }

        return $content;
    }

    protected function attachEditorImages(
        Model  $model,
        array  $tmpUrls,
        string $collection,
        string $disk
    ): array
    {
        $replacements = [];

        foreach ($tmpUrls as $tmpUrl) {
            $tmpPath = str_replace(
                config('app.url') . '/storage/',
                '',
                $tmpUrl
            );

            $absolutePath = storage_path('app/public/' . $tmpPath);

            if (!file_exists($absolutePath)) {
                continue;
            }

            $webpPath = $this->conversionService->convertPathToWebp($absolutePath, 1024, 512);

            $media = $model
                ->addMedia($webpPath)
                ->toMediaCollection($collection, $disk);

            $replacements[] = [
                'old' => $tmpUrl,
                'new' => $media->getUrl(),
            ];

            Storage::disk('public')->delete($tmpPath);
        }

        return $replacements;
    }

    public function sync(
        Model  $model,
        string $oldContent,
        string $newContent,
        string $collection,
        string $disk
    ): string
    {
        preg_match_all('#/storage/posts/\d+/[^\)"\s]+#', $oldContent, $oldMatches);
        preg_match_all('#/storage/posts/\d+/[^\)"\s]+#', $newContent, $newMatches);
        preg_match_all('#' . preg_quote(config('app.url')) . '/storage/editor/tmp/[^\)"\s]+#', $newContent, $tmpMatches);

        $oldImages = collect($oldMatches[0]);
        $newImages = collect($newMatches[0]);
        $tmpImages = $tmpMatches[0];

        $removed = $oldImages->diff($newImages);

        foreach ($removed as $url) {
            $model->media()
                ->where('collection_name', $collection)
                ->where('file_name', basename($url))
                ->get()
                ->each
                ->delete();
        }

        if (!empty($tmpImages)) {
            $replacements = $this->attachEditorImages(
                $model,
                $tmpImages,
                $collection,
                $disk
            );

            foreach ($replacements as $replace) {
                $newContent = str_replace(
                    $replace['old'],
                    $replace['new'],
                    $newContent
                );
            }
        }

        return $newContent;
    }

}
