<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class SearchFilter implements Filter
{
    protected array $columns;

    public function __construct(array $columns)
    {
        $this->columns = $columns;
    }

    public function __invoke(Builder $query, $value, string $property): void
    {
        $query->where(function (Builder $query) use ($value) {
            foreach ($this->columns as $column) {
                if (str_contains($column, '.')) {
                    [$relation, $relColumn] = explode('.', $column);

                    $query->orWhereHas($relation, function ($q) use ($relColumn, $value) {
                        $q->where($relColumn, 'LIKE', "%{$value}%");
                    });
                } else {
                    $query->orWhere($column, 'LIKE', "%{$value}%");
                }
            }
        });
    }
}
