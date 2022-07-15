<?php

namespace App\Http\Controllers\Admin\Web\Charge;

use App\Forms\Charge\PaymentForm as Form;
use App\Forms\Payment\{PartialForm, TotalForm};
use App\Http\Controllers\Admin\Presenters\PaginationPresenter;
use App\Http\Controllers\Controller;
use App\Support\FormSupport;
use Carbon\Carbon;
use Core\Application\Charge\Modules\Payment\Domain\PaymentEntity as Entity;
use Core\Application\Charge\Modules\Payment\Services;
use Core\Application\Charge\Shared\Enums\ChargeStatusEnum;
use Core\Shared\UseCases\Delete\DeleteInput;
use Core\Shared\UseCases\Find\FindInput;
use Core\Shared\UseCases\List\ListInput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;;

class ChargePaymentController extends Controller
{
    public function index(Services\ListService $listService, Request $request)
    {
        $result = $listService->handle(new ListInput(filter: $request->all(), page: $request->page));
        $data = PaginationPresenter::render($result);
        return view('admin.charge.payment.index', compact('data'));
    }

    public function create(FormSupport $formSupport)
    {
        $form = $formSupport->button(__('Cadastrar Conta a Receber'))
            ->run(Form::class, route('admin.charge.payment.store'));
        return view('admin.charge.payment.create', compact('form'));
    }

    public function store(FormSupport $formSupport, Services\CreateService $createService, Request $request)
    {
        $data = $formSupport->data(Form::class);
        $ret = $createService->handle(new Services\DTO\Create\Input(
            $request->user()->tenant_id,
            $data['title'],
            $data['resume'] ?? null,
            $data['relationship_id'],
            $data['recurrence_id'],
            $data['value'],
            $data['date'],
            $data['parcel'] ?: 1,
        ));
        return redirect()->route('admin.charge.payment.index')
            ->with('success', __('Conta a Receber cadastrada com sucesso'))
            ->with('service', $ret);
    }

    public function edit(FormSupport $formSupport, Services\FindService $findService, string $id)
    {
        $model = $findService->handle(new FindInput($id));
        $model->relationship_id = $model->company;
        $model->recurrence_id = $model->recurrence;

        $form = $formSupport->button(__('Cadastrar Conta a Receber'))
            ->run(Form::class, route('admin.charge.payment.update', $id), $model);

        return view('admin.charge.payment.edit', compact('form'));
    }

    public function update(FormSupport $formSupport, Services\UpdateService $createService, string $id)
    {
        $data = $formSupport->data(Form::class);
        $ret = $createService->handle(new Services\DTO\Update\Input(
            $id,
            $data['title'],
            $data['resume'] ?? null,
            $data['relationship_id'],
            $data['recurrence_id'],
            $data['value'],
            $data['date'],
        ));
        return redirect()->route('admin.charge.payment.index')
            ->with('success', __('Conta a pagar editada com sucesso'))
            ->with('service', $ret);
    }

    public function destroy(Services\DeleteService $deleteService, string $id)
    {
        $ret = $deleteService->handle(new DeleteInput($id));
        return redirect()->back()
            ->with('success', __('Conta a pagar excluída com sucesso'))
            ->with('service', $ret);
    }

    public function payShow(FormSupport $formSupport, string $id, Services\FindService $find)
    {
        $model = $find->handle(new FindInput($id));
        $formPartial = $formSupport->run(PartialForm::class, route('admin.charge.payment.pay.partial.store', $id));
        $formTotal = $formSupport->run(TotalForm::class, route('admin.charge.payment.pay.total.store', $id));
        return view('admin.charge.payment.pay', compact('formPartial', 'formTotal', 'model'));
    }

    public function payPartialStore(
        FormSupport $formSupport,
        string $id,
        Services\PaymentService $paymentService,
        Request $request
    ) {
        $data = $formSupport->data(PartialForm::class) + $request->all();
        $ret = $paymentService->handle(new Services\DTO\Payment\Input(
            $id,
            $data['value_pay'],
            (bool) $data['new_payment'],
            $data['date_next'] ?? false,
            $data['bank_id'] != '-1' ? $data['bank_id'] : null,
            $data['date_scheduled'] ?? null,
        ));

        return redirect()->route('admin.charge.payment.index')
            ->with('success', __('Pagamento feito com sucesso, aguarde para ser processado'))
            ->with('service', $ret);
    }

    public function payTotalStore(
        FormSupport $formSupport,
        string $id,
        Services\PaymentService $paymentService,
        Request $request
    ) {
        $data = $formSupport->data(TotalForm::class) + $request->all();
        $ret = $paymentService->handle(new Services\DTO\Payment\Input(
            $id,
            null,
            false,
            null,
            $data['bank_id'] != '-1' ? $data['bank_id'] : null,
            $data['date_scheduled'] ?? null,
        ));

        return redirect()->route('admin.charge.payment.index')
            ->with('success', __('Pagamento feito com sucesso, aguarde para ser processado'))
            ->with('service', $ret);
    }

    public function quantityAll(Request $request)
    {
        $ret = DB::table('charges')
            ->where('tenant_id', $request->user()->tenant_id)
            ->where('entity', Entity::class)
            ->whereIn('status', [ChargeStatusEnum::PENDING])
            ->whereBetween('date', $this->getMonth($request->month))
            ->whereNull('deleted_at')
            ->count();

        return response()->json([
            'quantity' => $ret,
        ]);
    }

    private function getMonth(?string $month)
    {
        $date = new Carbon($month);
        return [$date->firstOfMonth()->format('Y-m-d'), $date->lastOfMonth()->format('Y-m-d')];
    }

    public function quantityToday(Request $request)
    {
        $ret = DB::table('charges')
            ->where('tenant_id', $request->user()->tenant_id)
            ->where('entity', Entity::class)
            ->whereIn('status', [ChargeStatusEnum::PENDING])
            ->where('date', (new Carbon())->format('Y-m-d'))
            ->whereNull('deleted_at')
            ->count();

        return response()->json([
            'quantity' => $ret,
        ]);
    }

    public function valueMonth(Request $request)
    {
        $ret = DB::table('charges')
            ->where('tenant_id', $request->user()->tenant_id)
            ->where('entity', Entity::class)
            ->whereIn('status', [ChargeStatusEnum::PENDING])
            ->whereBetween('date', $this->getMonth($request->month))
            ->whereNull('deleted_at')
            ->sum(DB::raw('value_charge', 'value_pay'));

        return response()->json([
            'total' => $ret ?? 0,
            'total_real' => str()->numberBr($ret ?? 0),
        ]);
    }

    public function valueAll(Request $request)
    {
        $ret = DB::table('charges')
            ->where('tenant_id', $request->user()->tenant_id)
            ->where('entity', Entity::class)
            ->whereIn('status', [ChargeStatusEnum::PENDING])
            ->whereNull('deleted_at')
            ->sum(DB::raw('value_charge', 'value_pay'));

        return response()->json([
            'total' => $ret ?? 0,
            'total_real' => str()->numberBr($ret ?? 0),
        ]);
    }

    public function dueDate(Request $request)
    {
        $ret = DB::table('charges')
            ->where('tenant_id', $request->user()->tenant_id)
            ->where('entity', Entity::class)
            ->whereIn('status', [ChargeStatusEnum::PENDING])
            ->where('date', '<', $this->getMonth($request->month)[0])
            ->whereNull('deleted_at')
            ->count();

        return response()->json([
            'quantity' => $ret ?? 0,
        ]);
    }
}
