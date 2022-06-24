<?php

namespace App\Http\Controllers\Admin\Web\Relationship;

use App\Http\Controllers\Admin\Web\Presenters\PaginationPresenter;
use App\Http\Controllers\Controller;
use Core\Financial\Relationship\Modules\Customer\UseCases\ListUseCase;
use Core\Shared\UseCases\List\ListInput;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(ListUseCase $listUseCase, Request $request){
        $ret = $listUseCase->handle(new ListInput(filter: $request->all()));
        $data = PaginationPresenter::render($ret);
        dump($data);
    }
}
