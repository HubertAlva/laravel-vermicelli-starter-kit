<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class UserFormData extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $role = null,
        public ?string $password = null,
        public ?string $password_confirmation = null,
    ) {}
}
