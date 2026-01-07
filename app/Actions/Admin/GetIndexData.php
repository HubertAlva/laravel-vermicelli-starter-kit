<?php

namespace App\Actions\Admin;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class GetIndexData
{
    public function __construct(
        protected Request $request
    ) {}

    public function execute(
        string $model,
        array $with = [],
        string $defaultSort = '-id',
        array $allowedSorts = ['id'],
        array $allowedFilters = [],
        ?callable $modifyQuery = null
    ): LengthAwarePaginator {
        $query = QueryBuilder::for($model)
            ->with($with)
            ->defaultSort($defaultSort)
            ->allowedSorts($allowedSorts)
            ->allowedFilters($allowedFilters);

        if ($modifyQuery) {
            $modifyQuery($query);
        }

        return $query->paginate(
            $this->request->integer('per_page', 10),
            ['*'],
            'page', $this->request->integer('page', 1)
        )->withQueryString();
    }
}
