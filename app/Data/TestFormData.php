<?php

namespace App\Data;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

/**
 * Shape of the admin "test form" showcasing every form component.
 */
class TestFormData extends Data
{
    /**
     * @param  array<int, string|int>|null  $taglistbox
     * @param  array<int, string>|null  $tags
     */
    public function __construct(
        public ?string $text,
        public ?string $textarea,
        public ?array $taglistbox,
        public ?array $tags,
        public bool $switch,
        public string|int|null $select,
        public string|int|null $radio,
        public ?string $phone,
        public ?string $password,
        public ?int $number,
        public ?string $markdown,
        public UploadedFile|string|null $image,
        public bool $is_new_image,
        public UploadedFile|string|null $file,
        public string|int|null $combobox,
        public bool $checkbox,
        public ?string $date,
        public ?string $datetime,
    ) {}
}
