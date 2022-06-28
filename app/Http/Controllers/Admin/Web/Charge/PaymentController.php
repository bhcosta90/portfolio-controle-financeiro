<?php

namespace App\Http\Controllers\Admin\Web\Charge;

use App\Forms\Charge\PayForm;
use App\Http\Controllers\Controller;
use App\Support\FormSupport;
use App\Forms\Charge\PaymentForm as Form;
use App\Http\Controllers\Admin\Web\Presenters\PaginationPresenter;
use Core\Financial\Charge\Modules\Payment\UseCases\{CreateUseCase, ListUseCase, FindUseCase, UpdateUseCase, DeleteUseCase};
use Core\Financial\Charge\Modules\Payment\UseCases\DTO\{Create\CreateInput, Update\UpdateInput};
use Core\Shared\UseCases\Delete\DeleteInput;
use Core\Shared\UseCases\Find\FindInput;
use Core\Shared\UseCases\List\ListInput;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(ListUseCase $listUseCase, Request $request)
    {
        $ret = $listUseCase->handle(new ListInput(filter: $request->all(), page: $request->page));
        $data = PaginationPresenter::render($ret);
        return view('admin.charge.payment.index', compact('data'));
    }

    public function create(FormSupport $formSupport)
    {
        $form = $formSupport
            ->button(__('Cadastrar'))
            ->run(Form::class, route('admin.charge.payment.store'));

        return view('admin.charge.payment.create', compact('form'));
    }

    public function store(FormSupport $formSupport, CreateUseCase $createUseCase)
    {
        $data = $formSupport->data(Form::class);
        $id = str()->uuid();

        $createUseCase->handle(new CreateInput(
            groupId: $id,
            value: $data['value'],
            companyId: $data['relationship_id'],
            date: $data['date'],
            recurrenceId: $data['recurrence_id'],
            parcels: $data['parcel'] ?: 1,
        ));

        return redirect()->route('admin.charge.payment.index')->with('success', __('Registro cadastrado com sucesso'));
    }

    public function edit(FormSupport $formSupport, FindUseCase $findUseCase, string $id)
    {
        $model = $findUseCase->handle(new FindInput($id));
        $model->relationship_id = $model->companyId;

        $form = $formSupport
            ->button(__('Editar'))
            ->run(Form::class, route('admin.charge.payment.update', $id), $model);

        return view('admin.charge.payment.edit', compact('form'));
    }

    public function update(FormSupport $formSupport, UpdateUseCase $updateUseCase, string $id)
    {
        $data = $formSupport->data(Form::class);

        $updateUseCase->handle(new UpdateInput(
            $id,
            value: $data['value'],
            companyId: $data['relationship_id'],
            date: $data['date'],
            recurrenceId: $data['recurrence_id'],
        ));

        return redirect()->route('admin.charge.payment.index')->with('success', __('Registro alterado com sucesso'));
    }

    public function destroy(DeleteUseCase $deleteUseCase, string $id)
    {
        $deleteUseCase->handle(new DeleteInput($id));
        return redirect()->back()->with('success', __('Registro deletado com sucesso'));
    }

    public function payShow(FormSupport $formSupport, FindUseCase $findUseCase, string $id)
    {
        $form = $formSupport
            ->button(__('Editar'))
            ->run(PayForm::class, route('admin.charge.payment.update', $id));

        $data = $findUseCase->handle(new FindInput($id));

        return view('admin.charge.payment.pay', compact('form', 'data'));
    }
}
