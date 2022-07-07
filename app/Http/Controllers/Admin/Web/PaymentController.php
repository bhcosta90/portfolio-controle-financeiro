<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Admin\Presenters\PaginationPresenter;
use App\Http\Controllers\Controller;
use Core\Application\Payment\Services;
use Core\Shared\UseCases\Delete\DeleteInput;
use Core\Shared\UseCases\List\ListInput;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Services\ListService $listService, Request $request)
    {
        $result = $listService->handle(new ListInput(filter: $request->all(), page: $request->page));
        $data = PaginationPresenter::render($result);
        return view('admin.payment.index', compact('data'));
    }

    public function destroy(Services\DeleteService $deleteService, string $id)
    {
        $deleteService->handle(new DeleteInput($id));
        return redirect()->back()->with('success', __('Pagamento removido com sucesso'));
    }
}
