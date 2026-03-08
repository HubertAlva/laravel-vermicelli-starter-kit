<?php

namespace App\Data;

use DateTimeInterface;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class TestFormData extends Data
{
    public function __construct(
        public string|Optional          $text,
        public string|Optional          $textarea,
        /** @var array<string|int> */
        public ?array                   $taglistbox,
        /** @var array<string> */
        public ?array                   $tags,
        public bool                     $switch,
        public string|int|null          $select,
        public string|int|null          $radio,
        public string|Optional          $phone,
        public string|Optional          $password,
        public int|Optional             $number,
        public string|Optional          $markdown,
        public UploadedFile|string|null $image,
        public bool                     $is_new_image,
        public UploadedFile|string|null $file,
        public string|int|null          $combobox,
        public bool                     $checkbox,
        #[WithCast(DateTimeInterfaceCast::class)]
        public DateTimeInterface        $date,
        #[WithCast(DateTimeInterfaceCast::class)]
        public DateTimeInterface        $datetime,
    )
    {
    }
}
