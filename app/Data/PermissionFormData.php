<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class PermissionFormData extends Data
{
    public function __construct(
        public string $name,
    ) {}
}
