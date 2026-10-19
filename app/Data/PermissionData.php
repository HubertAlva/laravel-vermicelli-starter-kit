<?php

namespace App\Data;

use App\Models\Permission;
use Spatie\LaravelData\Data;

class PermissionData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public bool $is_system,
    ) {}

    public static function fromModel(Permission $permission): self
    {
        return new self(
            id: $permission->id,
            name: $permission->name,
            is_system: $permission->isSystem(),
        );
    }
}
