<?php

namespace App\Http\Controllers\Web\Charge;

use App\Forms\Charge\ChargePayForm;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Presenters\PaginationPresenter;
use App\Support\FormSupport;
use App\Forms\Charge\ChargeReceiveForm as Form;
use Costa\Modules\Charge\Receive\UseCases\CreateUseCase;
use Costa\Modules\Charge\Receive\UseCases\DeleteUseCase;
use Costa\Modules\Charge\Receive\UseCases\ListUseCase;
use Costa\Modules\Charge\Receive\UseCases\PaymentUseCase;
use Costa\Modules\Charge\Receive\UseCases\DTO\List\Input as ListInput;
use Costa\Modules\Charge\Receive\UseCases\DTO\Find\Input as FindInput;
use Costa\Modules\Charge\Receive\UseCases\DTO\Update\Input as UpdateInput;
use Costa\Modules\Charge\Receive\UseCases\DTO\Create\Input as CreateInput;
use Costa\Modules\Charge\Receive\UseCases\DTO\Payment\Input as PaymentInput;
use Costa\Modules\Charge\Receive\UseCases\FindUseCase;
use Costa\Modules\Charge\Receive\UseCases\UpdateUseCase;
use DateTime;
use Illuminate\Http\Request;

class ReceiveController extends Controller
{
    private $routeRedirect = 'admin.charge.receive.index';
    
    public function index(ListUseCase $uc, Request $request)
    {
        $ret = $uc->exec(new ListInput(
            $request->all(), 
            $request->limit,
            $request->page,
        ));

        $data = PaginationPresenter::render($ret);
        $total = $ret->value;

        return view('admin.charge.receive.index', compact('data', 'total'));
    }

    public function create(FormSupport $formSupport)
    {
        return view('admin.charge.receive.create', [
            'form' => $formSupport->button('Cadastrar conta a receber')->run(
                Form::class,
                route('admin.charge.receive.store')
            )
        ]);
    }

    public function store(CreateUseCase $useCase, FormSupport $formSupport)
    {
        $data = $formSupport->data(Form::class);

        $model = $useCase->handle([
            new CreateInput(
                title: $data['title'],
                description: $data['description'] ?? null,
                customer: $data['relationship_id'],
                value: $data['value'],
                date: new DateTime($data['date']),
                parcel: $data['parcel'],
                recurrence: $data['recurrence_id'] ?? null,
                customerName: null,
            )
        ]);

        return redirect()->route($this->routeRedirect)
            ->with('success', 'Conta a receber cadastrada com sucesso')
            ->with('model', $model);
    }

    public function edit(
        FormSupport $formSupport,
        FindUseCase $useCase,
        string $id
    ) {
        $model = $useCase->handle(new FindInput($id));
        $model->relationship_id = $model->customerId;
        $model->recurrence_id = $model->recurrenceId;

        return view('admin.charge.receive.edit', [
            'form' => $formSupport->button('Editar conta a receber')->run(
                Form::class,
                route('admin.charge.receive.update', $model->id),
                $model,
            )
        ]);
    }

    public function update(
        FormSupport $formSupport,
        UpdateUseCase $useCase,
        string $id
    ) {
        $data = $formSupport->data(Form::class);

        $model = $useCase->handle(new UpdateInput(
            id: $id,
            title: $data['title'],
            description: $data['description'] ?? null,
            customer: $data['relationship_id'],
            value: $data['value'],
            date: new DateTime($data['date']),
            recurrence: $data['recurrence_id'],
        ));
        
        return redirect()->route($this->routeRedirect)
            ->with('success', 'Conta a receber editada com sucesso')
            ->with('model', $model);
    }

    public function destroy(
        DeleteUseCase $useCase,
        string $id
    ) {
        $useCase->handle(new FindInput($id));

        return redirect()->route($this->routeRedirect)
            ->with('success', 'Conta a receber deletado com sucesso');
    }

    public function payShow(FindUseCase $useCase, string $uuid, FormSupport $formSupport){
        $model = $useCase->handle(new FindInput($uuid));
        $form = $formSupport->run(ChargePayForm::class, route('admin.charge.receive.pay.store', $model->id), null);

        return view('admin.charge.receive.pay', [
            'data' => $model,
            'form' => $form,
        ]);
    }

    public function payStore(string $uuid, FormSupport $formSupport, PaymentUseCase $uc){
        $data = $formSupport->data(ChargePayForm::class);

        $model = $uc->handle(new PaymentInput(
            id: $uuid,
            bank: $data['bank_id'] ?? null,
            value: $data['value_pay'],
            date: new DateTime($data['date_scheduled']),
            charge: $data['value_charge'],
        ));

        return redirect()->route($this->routeRedirect)
            ->with('success', 'Recebimento feito com sucesso')
            ->with('model', $model);
    }
}
