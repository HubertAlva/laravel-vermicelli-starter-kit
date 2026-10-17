<?php

namespace App\Concerns;

use App\Models\ModelLock;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Allows a model to be locked against deletion.
 */
trait HasLock
{
    /**
     * Get the model's lock.
     *
     * @return MorphOne<ModelLock, $this>
     */
    public function lock(): MorphOne
    {
        return $this->morphOne(ModelLock::class, 'lockable');
    }

    /**
     * Determine if the model is locked.
     */
    public function isLocked(): bool
    {
        if ($this->relationLoaded('lock')) {
            return $this->getRelation('lock') !== null;
        }

        return $this->lock()->exists();
    }

    /**
     * Lock the model, optionally recording why.
     */
    public function lockModel(?string $reason = null): void
    {
        $this->lock()->updateOrCreate([], ['reason' => $reason]);
    }

    /**
     * Remove the model's lock.
     */
    public function unlock(): void
    {
        $this->lock()->delete();
    }
}
