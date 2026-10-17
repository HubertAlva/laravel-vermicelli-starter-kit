<?php

namespace App\Services;

use Illuminate\Support\Str;
use RuntimeException;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Image;

class ImageConversionService
{
    /**
     * Convert an image to WebP, scaling it down to fit the given bounds.
     *
     * The source file is left untouched; the converted image is written to a
     * new temporary file whose path is returned (media library moves it).
     */
    public function toWebp(string $sourcePath, int $maxWidth = 1024, int $maxHeight = 1024): string
    {
        if (! is_file($sourcePath)) {
            throw new RuntimeException("File not found: {$sourcePath}");
        }

        $targetPath = sys_get_temp_dir().DIRECTORY_SEPARATOR.Str::uuid().'.webp';

        Image::load($sourcePath)
            ->fit(Fit::Max, $maxWidth, $maxHeight)
            ->save($targetPath);

        return $targetPath;
    }
}
