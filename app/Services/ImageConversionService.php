<?php

namespace App\Services;

use RuntimeException;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Image;

class ImageConversionService
{
    public function convertPathToWebp(
        string $absolutePath,
        ?int   $width = null,
        ?int   $height = null
    ): string
    {
        if (!file_exists($absolutePath)) {
            throw new RuntimeException("File not found: {$absolutePath}");
        }

        if (pathinfo($absolutePath, PATHINFO_EXTENSION) === 'webp') {
            return $absolutePath;
        }

        $webpPath = preg_replace('/\.\w+$/', '.webp', $absolutePath);

        Image::load($absolutePath)
            ->fit(
                Fit::Max,
                $width ?? 1024,
                $height ?? 1024
            )
            ->save($webpPath);

        unlink($absolutePath);

        return $webpPath;
    }
}
