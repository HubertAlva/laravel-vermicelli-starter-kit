<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class PaginatorLinkData extends Data
{
    public function __construct(
        public Optional|string $url,
        public string          $label,
        public bool            $active,
    )
    {
    }
}
