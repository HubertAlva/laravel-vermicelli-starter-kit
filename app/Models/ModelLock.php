<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ModelLock extends Model
{
    protected $fillable = [
        'lockable_id',
        'lockable_type',
        'reason',
    ];

    public function lockable(): MorphTo
    {
        return $this->morphTo();
    }
}
