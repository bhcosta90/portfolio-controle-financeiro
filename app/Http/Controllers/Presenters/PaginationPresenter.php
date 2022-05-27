<?php

namespace App\Http\Controllers\Presenters;

use Illuminate\Pagination\LengthAwarePaginator;

class PaginationPresenter
{
    public static function render($items, $total, $perPage, $currentPage)
    {
        return new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            [
                'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()
            ]
        );
    }
}
