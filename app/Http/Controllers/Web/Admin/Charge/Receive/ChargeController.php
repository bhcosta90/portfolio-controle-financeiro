<?php

namespace App\Http\Controllers\Web\Admin\Charge\Receive;

use App\Forms\Charge\ReceiveForm as Form;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Presenters\PaginationPresenter;
use App\Support\FormSupport;
use App\Support\TenantSupport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Core\Application\Charge\Modules\Receive\UseCases;
use Core\Application\Charge\Shared\Enums\ChargeStatusEnum;
use Core\Shared\UseCases\Delete\DeleteInput;
use Core\Shared\UseCases\Find\FindInput;
use Core\Shared\UseCases\List\ListInput;
use Illuminate\Support\Facades\DB;
use Core\Application\Charge\Modules\Receive\Domain\ReceiveEntity as Entity;

class ChargeController extends Controller
{
    private $title = [
        'store' => 'Recebimento cadastrado com sucesso',
        'update' => 'Recebimento editado com sucesso',
        'destroy' => 'Recebimento deletado com sucesso'
    ];

    private $route = 'admin.charge.receive.charge';

    public function index(UseCases\ListUseCase $listUseCase, Request $request)
    {
        $ret = $listUseCase->handle(new ListInput(filter: $request->all(), page: $request->page));
        $data = PaginationPresenter::render($ret);

        return [
            'data' => $data,
            'filter' => $ret->filter ?? null,
        ];
    }

    public function create(FormSupport $formSupport)
    {
        return [
            'form' => $formSupport->run(Form::class, route($this->route . '.store')),
        ];
    }

    public function store(FormSupport $formSupport, UseCases\CreateUseCase $createUseCase){
        $data = $formSupport->data(Form::class);
        $model = $createUseCase->handle(new UseCases\DTO\Create\Input(
            auth()->user()->tenant_id,
            $data['title'],
            $data['resume'],
            $data['relationship_id'],
            $data['recurrence_id'],
            $data['value'],
            $data['date'],
            $data['parcel'],
        ));
        return [
            'redirect' => route($this->route . '.index'),
            'message' => $this->title[__FUNCTION__],
            'model' => $model,
        ];
    }

    public function edit(FormSupport $formSupport, UseCases\FindUseCase $findUseCase, string $id)
    {
        $model = $findUseCase->handle(new FindInput($id));
        $model->relationship_id = $model->customer;
        return [
            'form' => $formSupport->run(Form::class, route($this->route . '.update', $id), $model),
        ];
    }

    public function update(FormSupport $formSupport, UseCases\UpdateUseCase $updateUseCase, string $id)
    {
        $data = $formSupport->data(Form::class);
        $model = $updateUseCase->handle(new UseCases\DTO\Update\Input(
            $id,
            $data['title'],
            $data['resume'],
            $data['relationship_id'],
            $data['recurrence_id'],
            $data['value'],
            $data['date'],
        ));

        return [
            'redirect' => route($this->route . '.index'),
            'message' => $this->title[__FUNCTION__],
            'model' => $model,
        ];
    }

    public function destroy(UseCases\DeleteUseCase $createUseCase, string $id)
    {
        $model = $createUseCase->handle(new DeleteInput($id));
        return [
            'redirect' => route($this->route . '.index'),
            'message' => $this->title[__FUNCTION__],
            'model' => $model,
        ];
    }

    public function quantityToday(Request $request, TenantSupport $tenantSupport)
    {
        $ret = DB::table('charges')
            ->where('tenant_id', $tenantSupport->validate($request))
            ->where('entity', Entity::class)
            ->whereIn('status', [ChargeStatusEnum::PENDING])
            ->where('date', (new Carbon())->format('Y-m-d'))
            ->count();

        return response()->json([
            'quantity' => $ret,
        ]);
    }

    public function dueDate(Request $request, TenantSupport $tenantSupport)
    {
        $ret = DB::table('charges')
            ->where('tenant_id', $tenantSupport->validate($request))
            ->where('entity', Entity::class)
            ->whereIn('status', [ChargeStatusEnum::PENDING])
            ->where('date', '<', $this->getMonth($request->month)[0])
            ->count();

        return response()->json([
            'quantity' => $ret ?? 0,
        ]);
    }

    public function valueMonth(Request $request, TenantSupport $tenantSupport){
        $ret = DB::table('charges')
            ->where('tenant_id', $tenantSupport->validate($request))
            ->where('entity', Entity::class)
            ->whereIn('status', [ChargeStatusEnum::PENDING])
            ->whereBetween('date', $this->getMonth($request->month))
            ->sum(DB::raw('value_charge', 'value_pay'));

        return response()->json([
            'total' => $ret ?? 0,
            'total_real' => str()->numberBr($ret ?? 0),
        ]);
    }

    private function getMonth(?string $month)
    {
        $date = new Carbon($month);
        return [$date->firstOfMonth()->format('Y-m-d'), $date->lastOfMonth()->format('Y-m-d')];
    }
}
