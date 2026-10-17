<?php

namespace App\Data;

use App\Models\User;
use Carbon\CarbonInterface;
use Spatie\LaravelData\Data;

class UserData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public ?string $role,
        public bool $is_locked,
        public ?CarbonInterface $email_verified_at,
        public ?CarbonInterface $created_at,
        public ?CarbonInterface $updated_at,
    ) {}

    /**
     * Load `roles` and `lock` beforehand when transforming many users.
     */
    public static function fromModel(User $user): self
    {
        return new self(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            role: $user->getRoleNames()->first(),
            is_locked: $user->isLocked(),
            email_verified_at: $user->email_verified_at,
            created_at: $user->created_at,
            updated_at: $user->updated_at,
        );
    }
}
