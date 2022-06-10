<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Presenters\PaginationPresenter;
use Costa\Modules\Payment\UseCases\ListUseCase;
use Costa\Modules\Payment\UseCases\DTO\List\Input as ListInput;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request, ListUseCase $uc){
        $ret = $uc->exec(new ListInput($request->all()));
        $data = PaginationPresenter::render($ret);
        
        return view('admin.payment.index', compact('data'));
    }
}
