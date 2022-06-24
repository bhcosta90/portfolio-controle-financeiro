<?php

namespace App\Http\Controllers\Admin\Web\Relationship;

use App\Forms\Relationship\CustomerForm as Form;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Web\Presenters\PaginationPresenter;
use App\Support\FormSupport;
use Core\Financial\Relationship\Modules\Customer\UseCases\CreateUseCase;
use Core\Financial\Relationship\Modules\Customer\UseCases\DeleteUseCase;
use Core\Financial\Relationship\Modules\Customer\UseCases\DTO\Create\CreateInput;
use Core\Financial\Relationship\Modules\Customer\UseCases\DTO\Update\UpdateInput;
use Core\Financial\Relationship\Modules\Customer\UseCases\FindUseCase;
use Core\Financial\Relationship\Modules\Customer\UseCases\ListUseCase;
use Core\Financial\Relationship\Modules\Customer\UseCases\UpdateUseCase;
use Core\Shared\UseCases\Delete\DeleteInput;
use Core\Shared\UseCases\Find\FindInput;
use Core\Shared\UseCases\List\ListInput;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(ListUseCase $listUseCase, Request $request)
    {
        $ret = $listUseCase->handle(new ListInput(filter: $request->all()));
        $data = PaginationPresenter::render($ret);
        return view('admin.relationship.customer.index', compact('data'));
    }

    public function create(FormSupport $formSupport)
    {
        $form = $formSupport
            ->button(__('Cadastrar'))
            ->run(Form::class, route('admin.relationship.customer.store'));

        return view('admin.relationship.customer.create', compact('form'));
    }

    public function store(CreateUseCase $createUseCase, FormSupport $formSupport)
    {
        $data = $formSupport->data(Form::class);

        $createUseCase->handle(new CreateInput(
            name: $data['name'],
            document_type: $data['type'] ?? null,
            document_value: $data['document'] ?? null,
        ));

        return redirect()->route('admin.relationship.customer.index')->with('success', __('Registro cadastrado com sucesso'));
    }

    public function edit(FormSupport $formSupport, FindUseCase $findUseCase, string $id)
    {
        $model = $findUseCase->handle(new FindInput($id));

        $form = $formSupport
            ->button(__('Editar'))
            ->run(Form::class, route('admin.relationship.customer.update', $id), $model);

        return view('admin.relationship.customer.edit', compact('form'));
    }

    public function update(UpdateUseCase $updateUseCase, string $id, FormSupport $formSupport)
    {
        $data = $formSupport->data(Form::class);

        $updateUseCase->handle(new UpdateInput(
            id: $id,
            name: $data['name'],
            document_type: $data['type'] ?? null,
            document_value: $data['document'] ?? null,
        ));

        return redirect()->route('admin.relationship.customer.index')->with('success', __('Registro alterado com sucesso'));
    }

    public function destroy(DeleteUseCase $deleteUseCase, string $id)
    {
        $deleteUseCase->handle(new DeleteInput($id));
        return redirect()->route('admin.relationship.customer.index')->with('success', __('Registro deletado com sucesso'));
    }
}
