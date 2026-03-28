<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class UserFormData extends Data
{
    public function __construct(
        public Optional|string $name,
        public Optional|string $email,
        public ?string         $role,
        public ?string         $password,
        public ?string         $password_confirmation,
    )
    {
    }
}
