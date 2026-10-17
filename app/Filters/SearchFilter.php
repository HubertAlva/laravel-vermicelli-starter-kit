<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\Filters\Filter;

/**
 * Searches the given columns (or `relation.column`) with a single term.
 *
 * @template TModel of Model
 *
 * @implements Filter<TModel>
 */
class SearchFilter implements Filter
{
    /**
     * @param  list<string>  $columns
     */
    public function __construct(
        protected array $columns,
    ) {}

    /**
     * @param  Builder<TModel>  $query
     */
    public function __invoke(Builder $query, mixed $value, string $property): void
    {
        if (is_array($value)) {
            $value = implode(' ', array_filter(array_map('trim', $value)));
        }

        $value = trim((string) $value);

        if ($value === '') {
            return;
        }

        $term = '%'.addcslashes($value, '%_\\').'%';

        $query->where(function (Builder $query) use ($term) {
            foreach ($this->columns as $column) {
                if (str_contains($column, '.')) {
                    [$relation, $relationColumn] = explode('.', $column, 2);

                    $query->orWhereHas($relation, fn (Builder $query) => $query->whereLike($relationColumn, $term));
                } else {
                    $query->orWhereLike($column, $term);
                }
            }
        });
    }
}
