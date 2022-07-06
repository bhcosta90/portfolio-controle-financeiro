<?php

namespace App\Http\Controllers\Admin\Web\Charge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Forms\Charge\PaymentForm as Form;
use App\Forms\PaymentForm;
use App\Http\Controllers\Admin\Presenters\PaginationPresenter;
use App\Support\FormSupport;
use Carbon\Carbon;
use Core\Application\Charge\Modules\Payment\Domain\PaymentEntity as Entity;
use Core\Application\Charge\Modules\Payment\Services;
use Core\Application\Charge\Shared\Enums\ChargeStatusEnum;;

use Core\Shared\UseCases\Find\FindInput;
use Core\Shared\UseCases\List\ListInput;
use Illuminate\Support\Facades\DB;

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

    public function store(FormSupport $formSupport, Services\CreateService $createService)
    {
        $data = $formSupport->data(Form::class);
        $ret = $createService->handle(new Services\DTO\Create\Input(
            $data['title'],
            $data['resume'] ?? null,
            $data['relationship_id'],
            $data['recurrence_id'],
            $data['value'],
            $data['date'],
            $data['parcel'],
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

    public function payShow(FormSupport $formSupport, string $id, Services\FindService $find)
    {
        $model = $find->handle(new FindInput($id));
        $form = $formSupport->run(PaymentForm::class, route('admin.charge.payment.pay.store', $id));
        return view('admin.charge.payment.pay', compact('form', 'model'));
    }

    public function payStore(FormSupport $formSupport, string $id, Services\PaymentService $paymentService)
    {
        $data = $formSupport->data(PaymentForm::class);

        $ret = $paymentService->handle(new Services\DTO\Payment\Input(
            $id,
            $data['value_pay'],
            $data['bank_id'] != '-1' ? $data['bank_id'] : null,
            $data['value_charge'],
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
            ->whereIn('status', [ChargeStatusEnum::PENDING, ChargeStatusEnum::PARTIAL])
            ->whereBetween('date', $this->getMonth($request->month))
            ->count();

        return response()->json([
            'quantity' => $ret,
        ]);
    }

    public function quantityToday(Request $request)
    {
        $ret = DB::table('charges')
            ->where('tenant_id', $request->user()->tenant_id)
            ->where('entity', Entity::class)
            ->whereIn('status', [ChargeStatusEnum::PENDING, ChargeStatusEnum::PARTIAL])
            ->where('date', (new Carbon())->format('Y-m-d'))
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
            ->whereIn('status', [ChargeStatusEnum::PENDING, ChargeStatusEnum::PARTIAL])
            ->whereBetween('date', $this->getMonth($request->month))
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
            ->whereIn('status', [ChargeStatusEnum::PENDING, ChargeStatusEnum::PARTIAL])
            ->sum(DB::raw('value_charge', 'value_pay'));

        return response()->json([
            'total' => $ret ?? 0,
            'total_real' => str()->numberBr($ret ?? 0),
        ]);
    }

    public function dueDate(Request $request){
        $ret = DB::table('charges')
            ->where('tenant_id', $request->user()->tenant_id)
            ->where('entity', Entity::class)
            ->whereIn('status', [ChargeStatusEnum::PENDING, ChargeStatusEnum::PARTIAL])
            ->where('date', '<', $this->getMonth($request->month)[0])
            ->count();

        return response()->json([
            'quantity' => $ret ?? 0,
        ]);
    }

    private function getMonth(?string $month)
    {
        $date = new Carbon($month);
        return [$date->firstOfMonth()->format('Y-m-d'), $date->lastOfMonth()->format('Y-m-d')];
    }
}
