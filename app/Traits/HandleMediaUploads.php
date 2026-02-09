<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Image;

trait HandleMediaUploads
{
    /**
     * Handle media upload during model creation
     */
    public function handleMediaStore(
        Model         $model,
        ?UploadedFile $uploadedFile,
        string        $collection,
        string        $disk,
        ?int          $width = null,
        ?int          $height = null
    ): void
    {
        $this->handleMediaOperation($model, $uploadedFile, $collection, $disk, false, false, $width, $height);
    }

    /**
     * Base method for handling media operations
     */
    protected function handleMediaOperation(
        Model         $model,
        ?UploadedFile $uploadedFile,
        string        $collection,
        string        $disk,
        bool          $shouldConvertToWebp = false,
        bool          $shouldClearCollection = false,
        ?int          $width = null,
        ?int          $height = null
    ): void
    {
        if (!$this->isValidUpload($uploadedFile)) {
            return;
        }

        if ($shouldClearCollection) {
            $model->clearMediaCollection($collection);
        }

        $path = $shouldConvertToWebp
            ? $this->convertImage($uploadedFile, $collection, $width, $height)
            : $this->storeMedia($uploadedFile, $collection);

        $model->addMedia($path)->toMediaCollection($collection, $disk);
    }

    protected function isValidUpload(?UploadedFile $file): bool
    {
        return $file && $file->isValid();
    }

    protected function convertImage(
        UploadedFile $uploadedFile,
        string       $collection,
        ?int         $width = null,
        ?int         $height = null
    ): string
    {
        $path = $this->storeMedia($uploadedFile, $collection);

        if (pathinfo($path, PATHINFO_EXTENSION) !== 'webp') {
            $webpPath = str_replace('.' . pathinfo($path, PATHINFO_EXTENSION), '.webp', $path);
        } else {
            $webpPath = $path;
        }

        Image::load($path)
            ->fit(
                Fit::Max,
                $width ?? 1024,
                $height ?? 1024,
            )
            ->save($webpPath);

        if ($webpPath !== $path) {
            unlink($path);
        }

        return $webpPath;
    }

    protected function storeMedia(UploadedFile $uploadedFile, string $collection): string
    {
        $storedFile = $uploadedFile->store($collection, 'public');
        return $this->getStoragePath($storedFile);
    }

    protected function getStoragePath(string $storedFile): string
    {
        return storage_path("app/public/{$storedFile}");
    }

    /**
     * Handle media upload and replacement during model update
     */
    public function handleMediaUpdate(
        Model         $model,
        bool          $isNew,
        ?UploadedFile $uploadedFile,
        ?string       $mediaFieldValue,
        string        $collection,
        string        $disk,
        ?int          $width = null,
        ?int          $height = null
    ): void
    {
        if ($isNew) {
            $this->handleMediaOperation($model, $uploadedFile, $collection, $disk, false, true, $width, $height);
        } elseif (is_null($mediaFieldValue)) {
            $model->clearMediaCollection($collection);
        }
    }

    /**
     * Handle image upload during model creation
     */
    public function handleImageStore(
        Model         $model,
        ?UploadedFile $uploadedFile,
        string        $collection,
        string        $disk,
        ?int          $width = null,
        ?int          $height = null
    ): void
    {
        $this->handleMediaOperation($model, $uploadedFile, $collection, $disk, true, false, $width, $height);
    }

    /**
     * Handle image upload and replacement during model update
     */
    public function handleImageUpdate(
        Model         $model,
        bool          $isNew,
        ?UploadedFile $uploadedFile,
        ?string       $imageFieldValue,
        string        $collection,
        string        $disk,
        ?int          $width = null,
        ?int          $height = null
    ): void
    {
        if ($isNew) {
            $this->handleMediaOperation($model, $uploadedFile, $collection, $disk, true, true, $width, $height);
        } elseif (is_null($imageFieldValue)) {
            $model->clearMediaCollection($collection);
        }
    }
}
