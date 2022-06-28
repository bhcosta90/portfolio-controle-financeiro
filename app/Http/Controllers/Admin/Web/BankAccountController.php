<?php

namespace App\Http\Controllers\Admin\Web;

use App\Forms\BankAccountForm as Form;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Web\Presenters\PaginationPresenter;
use App\Support\FormSupport;
use Core\Financial\BankAccount\UseCases\CreateUseCase;
use Core\Financial\BankAccount\UseCases\DeleteUseCase;
use Core\Financial\BankAccount\UseCases\DTO\Create\CreateInput;
use Core\Financial\BankAccount\UseCases\DTO\Update\UpdateInput;
use Core\Financial\BankAccount\UseCases\FindUseCase;
use Core\Financial\BankAccount\UseCases\ListUseCase;
use Core\Financial\BankAccount\UseCases\UpdateUseCase;
use Core\Shared\UseCases\Delete\DeleteInput;
use Core\Shared\UseCases\Find\FindInput;
use Core\Shared\UseCases\List\ListInput;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    public function index(ListUseCase $listUseCase, Request $request)
    {
        $ret = $listUseCase->handle(new ListInput(filter: $request->all(), page: $request->page));
        $data = PaginationPresenter::render($ret);
        return view('admin.bank.index', compact('data'));
    }

    public function create(FormSupport $formSupport)
    {
        $form = $formSupport
            ->button(__('Cadastrar'))
            ->run(Form::class, route('admin.bank.store'));

        return view('admin.bank.create', compact('form'));
    }

    public function store(CreateUseCase $createUseCase, FormSupport $formSupport)
    {
        $data = $formSupport->data(Form::class);

        $createUseCase->handle(new CreateInput(
            name: $data['name'],
            value: $data['value'] ?? 0,
        ));

        return redirect()->route('admin.bank.index')->with('success', __('Registro cadastrado com sucesso'));
    }

    public function edit(FormSupport $formSupport, FindUseCase $findUseCase, string $id)
    {
        $model = $findUseCase->handle(new FindInput($id));

        $form = $formSupport
            ->button(__('Editar'))
            ->run(Form::class, route('admin.bank.update', $id), $model);

        return view('admin.bank.edit', compact('form'));
    }

    public function update(UpdateUseCase $updateUseCase, string $id, FormSupport $formSupport)
    {
        $data = $formSupport->data(Form::class);

        $updateUseCase->handle(new UpdateInput(
            id: $id,
            name: $data['name'],
            value: $data['value'] ?? 0,
        ));

        return redirect()->route('admin.bank.index')->with('success', __('Registro alterado com sucesso'));
    }

    public function destroy(DeleteUseCase $deleteUseCase, string $id)
    {
        $deleteUseCase->handle(new DeleteInput($id));
        return redirect()->back()->with('success', __('Registro deletado com sucesso'));
    }
}
