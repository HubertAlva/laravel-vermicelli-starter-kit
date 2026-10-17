<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class ContactData extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $subject,
        public string $message,
    ) {}
}
