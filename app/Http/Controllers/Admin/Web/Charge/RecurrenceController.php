<?php

namespace App\Http\Controllers\Admin\Web\Charge;

use App\Forms\Charge\RecurrenceForm as Form;
use App\Http\Controllers\Admin\Presenters\PaginationPresenter;
use App\Http\Controllers\Controller;
use App\Support\FormSupport;
use Core\Application\Charge\Modules\Recurrence\Services;
use Core\Shared\UseCases\Delete\DeleteInput;
use Core\Shared\UseCases\Find\FindInput;
use Core\Shared\UseCases\List\ListInput;
use Illuminate\Http\Request;;

class RecurrenceController extends Controller
{
    public function index(Services\ListService $listService, Request $request)
    {
        $result = $listService->handle(new ListInput(filter: $request->all(), page: $request->page));
        $data = PaginationPresenter::render($result);
        return view('admin.charge.recurrence.index', compact('data'));
    }

    public function create(FormSupport $formSupport)
    {
        $form = $formSupport->button(__('Cadastrar Recorrência'))
            ->run(Form::class, route('admin.charge.recurrence.store'));
        return view('admin.charge.recurrence.create', compact('form'));
    }

    public function store(FormSupport $formSupport, Services\CreateService $createService, Request $request)
    {
        $data = $formSupport->data(Form::class);
        $ret = $createService->handle(new Services\DTO\Create\Input(
            $request->user()->tenant_id,
            $data['name'],
            $data['days']
        ));
        return redirect()->route('admin.charge.recurrence.index')
            ->with('success', __('Recorrência cadastrada com sucesso'))
            ->with('service', $ret);
    }

    public function edit(FormSupport $formSupport, Services\FindService $findService, string $id)
    {
        $model = $findService->handle(new FindInput($id));
        $form = $formSupport->button(__('Cadastrar Recorrência'))
            ->run(Form::class, route('admin.charge.recurrence.update', $id), $model);

        return view('admin.charge.recurrence.edit', compact('form'));
    }

    public function update(FormSupport $formSupport, Services\UpdateService $createService, string $id)
    {
        $data = $formSupport->data(Form::class);
        $ret = $createService->handle(new Services\DTO\Update\Input($id, $data['name'], $data['days']));
        return redirect()->route('admin.charge.recurrence.index')
            ->with('success', __('Recorrência editada com sucesso'))
            ->with('service', $ret);
    }

    public function destroy(Services\DeleteService $deleteService, string $id)
    {
        $ret = $deleteService->handle(new DeleteInput($id));
        return redirect()->back()
            ->with('success', __('Recorrência excluída com sucesso'))
            ->with('service', $ret);
    }
}
