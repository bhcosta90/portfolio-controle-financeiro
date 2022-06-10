<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Presenters\PaginationPresenter;
use Costa\Modules\Payment\UseCases\DeleteUseCase;
use Costa\Modules\Payment\UseCases\ListUseCase;
use Costa\Modules\Payment\UseCases\DTO\List\Input as ListInput;
use Costa\Modules\Payment\UseCases\DTO\Find\Input as FindInput;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request, ListUseCase $uc){
        $ret = $uc->exec(new ListInput($request->all()));
        $data = PaginationPresenter::render($ret);
        return view('admin.payment.index', compact('data'));
    }

    public function destroy(DeleteUseCase $uc, string $id){
        $uc->handle(new FindInput($id));

        return redirect()->route('admin.payment.index')
            ->with('success', 'Pagamento deletado com sucesso');
    }
}
