<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Prevents the related model from being deleted (e.g. the main admin user).
 *
 * @property int $id
 * @property string $lockable_type
 * @property int $lockable_id
 * @property string|null $reason
 */
#[Fillable(['reason'])]
class ModelLock extends Model
{
    /**
     * Get the locked model.
     *
     * @return MorphTo<Model, $this>
     */
    public function lockable(): MorphTo
    {
        return $this->morphTo();
    }
}
