<?php

namespace App\Http\Controllers\Web\Admin\Charge;

use App\Forms\Charge\RecurrenceForm as Form;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Presenters\PaginationPresenter;
use App\Support\FormSupport;
use Illuminate\Http\Request;
use Core\Application\Charge\Modules\Recurrence\UseCases;
use Core\Shared\UseCases\Delete\DeleteInput;
use Core\Shared\UseCases\Find\FindInput;
use Core\Shared\UseCases\List\ListInput;

class RecurrenceController extends Controller
{
    private $title = [
        'store' => 'Recorrência cadastrada com sucesso',
        'update' => 'Recorrência editada com sucesso',
        'destroy' => 'Recorrência deletada com sucesso'
    ];

    private $route = 'admin.charge.recurrence';

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
            $data['name'],
            $data['days']
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
        return [
            'form' => $formSupport->run(Form::class, route($this->route . '.update', $id), $model),
        ];
    }

    public function update(FormSupport $formSupport, UseCases\UpdateUseCase $updateUseCase, string $id)
    {
        $data = $formSupport->data(Form::class);
        $model = $updateUseCase->handle(new UseCases\DTO\Update\Input(
            $id,
            $data['name'],
            $data['days']
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
}
