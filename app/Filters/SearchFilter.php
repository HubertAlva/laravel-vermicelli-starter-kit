<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class SearchFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        $query->where(function (Builder $query) use ($value) {
            $query
                ->where('name', 'LIKE', "%{$value}%")
                ->orWhere('slug', 'LIKE', "%{$value}%");
        });
    }
}
