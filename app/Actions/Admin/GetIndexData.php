<?php

namespace App\Actions\Admin;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Builds the paginated, filtered and sorted listing used by admin index tables.
 */
class GetIndexData
{
    public const array PER_PAGE_OPTIONS = [10, 30, 50, 100];

    public function __construct(
        protected Request $request,
    ) {}

    /**
     * @template TModel of Model
     *
     * @param  class-string<TModel>  $model
     * @param  array<int, string>  $with
     * @param  array<int, string>  $allowedSorts
     * @param  array<int, AllowedFilter|string>  $allowedFilters
     * @param  (callable(QueryBuilder<TModel>): void)|null  $modifyQuery
     * @return LengthAwarePaginator<int, TModel>
     */
    public function execute(
        string $model,
        array $with = [],
        string $defaultSort = '-id',
        array $allowedSorts = ['id'],
        array $allowedFilters = [],
        ?callable $modifyQuery = null,
    ): LengthAwarePaginator {
        $query = QueryBuilder::for($model::query()->with($with), $this->request)
            ->defaultSort($defaultSort)
            ->allowedSorts(...$allowedSorts)
            ->allowedFilters(...$allowedFilters);

        if ($modifyQuery) {
            $modifyQuery($query);
        }

        return $query->paginate($this->perPage())->withQueryString();
    }

    /**
     * Get the requested page size, limited to the allowed options.
     */
    public function perPage(): int
    {
        $perPage = $this->request->integer('per_page', self::PER_PAGE_OPTIONS[0]);

        return in_array($perPage, self::PER_PAGE_OPTIONS, true) ? $perPage : self::PER_PAGE_OPTIONS[0];
    }
}
