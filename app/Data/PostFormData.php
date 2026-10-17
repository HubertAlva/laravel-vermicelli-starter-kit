<?php

namespace App\Data;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class PostFormData extends Data
{
    /**
     * @param  array<int, string>|null  $tags
     */
    public function __construct(
        public string $name,
        public string $excerpt,
        public string $content,
        public UploadedFile|string|null $thumbnail = null,
        public bool $is_new_thumbnail = false,
        public bool $published = true,
        public ?array $tags = null,
    ) {}
}
