<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class RoleFormData extends Data
{
    /**
     * @param  array<int, string>  $permissions
     */
    public function __construct(
        public string $name,
        public array $permissions = [],
    ) {}
}
