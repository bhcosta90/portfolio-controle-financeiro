<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Forms\AccountBankForm as Form;
use App\Http\Controllers\Admin\Presenters\PaginationPresenter;
use App\Support\FormSupport;
use Carbon\Carbon;
use Core\Application\AccountBank\Services;
use Core\Application\Charge\Modules\Payment\Domain\PaymentEntity;
use Core\Application\Charge\Modules\Receive\Domain\ReceiveEntity;
use Core\Application\Charge\Shared\Enums\ChargeStatusEnum;
use Core\Shared\UseCases\Find\FindInput;
use Core\Shared\UseCases\List\ListInput;
use Illuminate\Support\Facades\DB;

class AccountBankController extends Controller
{
    public function index(Services\ListService $listService, Request $request)
    {
        $result = $listService->handle(new ListInput(filter: $request->all(), page: $request->page));
        $data = PaginationPresenter::render($result);
        return view('admin.bank.account.index', compact('data'));
    }

    public function create(FormSupport $formSupport)
    {
        $form = $formSupport->button(__('Cadastrar Conta bancária'))
            ->run(Form::class, route('admin.bank.account.store'));
        return view('admin.bank.account.create', compact('form'));
    }

    public function store(FormSupport $formSupport, Services\CreateService $createService, Request $request)
    {
        $data = $formSupport->data(Form::class);
        $ret = $createService->handle(
            new Services\DTO\Create\Input(
                $request->user()->tenant_id,
                $data['name'],
                $data['value']
            )
        );
        return redirect()->route('admin.bank.account.index')
            ->with('success', __('Conta bancária cadastrada com sucesso'))
            ->with('service', $ret);
    }

    public function edit(FormSupport $formSupport, Services\FindService $findService, string $id)
    {
        $model = $findService->handle(new FindInput($id));
        $form = $formSupport->button(__('Cadastrar Conta bancária'))
            ->run(Form::class, route('admin.bank.account.update', $id), $model);

        return view('admin.bank.account.edit', compact('form'));
    }

    public function update(FormSupport $formSupport, Services\UpdateService $createService, string $id)
    {
        $data = $formSupport->data(Form::class);
        $ret = $createService->handle(new Services\DTO\Update\Input($id, $data['name'], $data['value']));
        return redirect()->route('admin.bank.account.index')
            ->with('success', __('Conta bancária editada com sucesso'))
            ->with('service', $ret);
    }

    public function financial(Request $request)
    {
        $dateStart = (new Carbon($request->month))->firstOfMonth()->format('Y-m-d');
        $dateFinish = (new Carbon($request->month))->lastOfMonth()->format('Y-m-d');

        $value = DB::table('account_banks')
            ->where('tenant_id', $request->user()->tenant_id)
            ->sum('value');

        $value += DB::table('charges')
            ->where('tenant_id', $request->user()->tenant_id)
            ->where('status', [ChargeStatusEnum::PENDING, ChargeStatusEnum::PARTIAL])
            ->where('entity', ReceiveEntity::class)
            ->whereBetween('charges.date', [$dateStart, $dateFinish])
            ->sum(DB::raw('value_charge - value_pay'));

        $value -= DB::table('charges')
            ->where('tenant_id', $request->user()->tenant_id)
            ->where('status', [ChargeStatusEnum::PENDING, ChargeStatusEnum::PARTIAL])
            ->where('entity', PaymentEntity::class)
            ->sum(DB::raw('value_charge - value_pay'));

        return response()->json([
            'total' => $value ?? 0,
            'total_real' => str()->numberBr($value ?? 0),
        ]);
    }
}
