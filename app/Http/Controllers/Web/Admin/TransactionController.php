<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Presenters\PaginationPresenter;
use Illuminate\Http\Request;
use Core\Application\Transaction\UseCases;
use Core\Shared\UseCases\List\ListInput;

class TransactionController extends Controller
{
    public function index(UseCases\ListUseCase $listUseCase, Request $request)
    {
        $ret = $listUseCase->handle(new ListInput(filter: $request->all(), page: $request->page));
        $data = PaginationPresenter::render($ret);

        return [
            'data' => $data,
            'filter' => $ret->filter,
        ];
    }
}
