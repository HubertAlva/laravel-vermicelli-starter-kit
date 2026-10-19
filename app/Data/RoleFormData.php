<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class RoleFormData extends Data
{
    /**
     * @param  list<string>  $permissions
     */
    public function __construct(
        public string $name,
        public array $permissions = [],
    ) {}
}
