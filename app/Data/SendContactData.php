<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class SendContactData extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public Optional|string $subject,
        public string $message,
    ) {}
}
