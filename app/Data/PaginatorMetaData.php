<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class PaginatorMetaData extends Data
{
    public function __construct(
        public int             $current_page,
        public string          $first_page_url,
        public int             $from,
        public int             $last_page,
        public string          $last_page_url,
        public Optional|string $next_page_url,
        public string          $path,
        public int             $per_page,
        public Optional|string $prev_page_url,
        public int             $to,
        public int             $total,
    )
    {
    }
}
