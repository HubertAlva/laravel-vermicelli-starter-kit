<?php

namespace App\Traits;

use App\Models\ModelLock;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasLock
{
    public function lock(): MorphOne
    {
        return $this->morphOne(ModelLock::class, 'lockable');
    }

    /**
     * Determines if the current resource is locked.
     *
     * Checks the loaded lock relationship or verifies its existence in the database.
     *
     * @return bool True if the resource is locked, false otherwise.
     */
    public function isLocked(): bool
    {
        if ($this->relationLoaded('lock')) {
            return ! is_null($this->lock);
        }

        return $this->lock()->exists();
    }

    /**
     * Locks the model by creating or updating a lock with the specified reason.
     *
     * @param  string|null  $reason  The reason for locking the model.
     */
    public function lockModel(?string $reason = null): void
    {
        $this->lock()->updateOrCreate([], [
            'reason' => $reason,
        ]);
    }

    /**
     * Unlocks the current resource by deleting the associated lock record.
     */
    public function unlock(): void
    {
        $this->lock()->delete();
    }
}
