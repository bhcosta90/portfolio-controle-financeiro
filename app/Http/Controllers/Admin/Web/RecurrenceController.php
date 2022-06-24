<?php

namespace App\Http\Controllers\Admin\Web;

use App\Forms\RecurrenceForm as Form;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Web\Presenters\PaginationPresenter;
use App\Support\FormSupport;
use Core\Financial\Recurrence\UseCases\CreateUseCase;
use Core\Financial\Recurrence\UseCases\DeleteUseCase;
use Core\Financial\Recurrence\UseCases\DTO\Create\CreateInput;
use Core\Financial\Recurrence\UseCases\DTO\Update\UpdateInput;
use Core\Financial\Recurrence\UseCases\FindUseCase;
use Core\Financial\Recurrence\UseCases\ListUseCase;
use Core\Financial\Recurrence\UseCases\UpdateUseCase;
use Core\Shared\UseCases\Delete\DeleteInput;
use Core\Shared\UseCases\Find\FindInput;
use Core\Shared\UseCases\List\ListInput;
use Illuminate\Http\Request;

class RecurrenceController extends Controller
{
    public function index(ListUseCase $listUseCase, Request $request)
    {
        $ret = $listUseCase->handle(new ListInput(filter: $request->all(), page: $request->page));
        $data = PaginationPresenter::render($ret);
        return view('admin.recurrence.index', compact('data'));
    }

    public function create(FormSupport $formSupport)
    {
        $form = $formSupport
            ->button(__('Cadastrar'))
            ->run(Form::class, route('admin.recurrence.store'));

        return view('admin.recurrence.create', compact('form'));
    }

    public function store(CreateUseCase $createUseCase, FormSupport $formSupport)
    {
        $data = $formSupport->data(Form::class);

        $createUseCase->handle(new CreateInput(
            name: $data['name'],
            days: $data['days'] ?? null,
        ));

        return redirect()->route('admin.recurrence.index')->with('success', __('Registro cadastrado com sucesso'));
    }

    public function edit(FormSupport $formSupport, FindUseCase $findUseCase, string $id)
    {
        $model = $findUseCase->handle(new FindInput($id));

        $form = $formSupport
            ->button(__('Editar'))
            ->run(Form::class, route('admin.recurrence.update', $id), $model);

        return view('admin.recurrence.edit', compact('form'));
    }

    public function update(UpdateUseCase $updateUseCase, string $id, FormSupport $formSupport)
    {
        $data = $formSupport->data(Form::class);

        $updateUseCase->handle(new UpdateInput(
            id: $id,
            name: $data['name'],
            days: $data['days'] ?? null,
        ));

        return redirect()->route('admin.recurrence.index')->with('success', __('Registro alterado com sucesso'));
    }

    public function destroy(DeleteUseCase $deleteUseCase, string $id)
    {
        $deleteUseCase->handle(new DeleteInput($id));
        return redirect()->route('admin.recurrence.index')->with('success', __('Registro deletado com sucesso'));
    }
}
