<?php

namespace App\Data;

use DateTime;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class FormPostData extends Data
{
    public function __construct(
        public Optional|string $name,
        public Optional|string $excerpt,
        public Optional|string $content,
        public UploadedFile|string|null $thumbnail,
        public bool $published_at,
        public bool $is_new_thumbnail,
        public ?DateTime $deleted_at,
        public ?array $tags,
    ) {}
}
