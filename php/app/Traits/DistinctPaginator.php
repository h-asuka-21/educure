<?php
declare(strict_types=1);

namespace App\Traits;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;


/**
 * Trait PaginatorTrait
 * @package App\Services\Utility\Traits
 */
trait DistinctPaginator
{

    /**
     * @param Builder $builder
     * @param null $perPage
     * @param array $columns
     * @param string $pageName
     * @param null $page
     * @return LengthAwarePaginator
     */
    private function distinctPaginate(Builder $builder, $perPage = null, $columns = ['*'], $pageName = 'page', $page = null): LengthAwarePaginator
    {
        $page = $page ?: Paginator::resolveCurrentPage($pageName);

        $perPage = $perPage ?: $builder->getModel()->getPerPage();

        $results = ($total = $builder->toBase()->getCountForPagination($columns))
            ? $builder->forPage($page, $perPage)->get($columns)
            : $builder->getModel()->newCollection();

        return Container::getInstance()->makeWith(LengthAwarePaginator::class, [
            'items' => $results,
            'total' => $total,
            'perPage' => $perPage,
            'options' => [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ],
        ]);
    }
}
