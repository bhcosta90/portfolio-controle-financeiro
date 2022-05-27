<?php

namespace App\Http\Controllers\Admin\Charge;

use App\Forms\Charge\ChargePayForm;
use App\Http\Controllers\Controller;
use App\Support\FormSupport;
use Costa\Modules\Charge\UseCases\Receive\{
    ReceiveListUseCase as ListUseCase,
    ReceiveCreatedUseCase as CreateUseCase,
    ReceiveDeleteUseCase as DeleteUseCase,
    ReceiveFindUseCase as FindUseCase,
    ReceiveUpdateUseCase as UpdateUseCase,
    ReceivePayUseCase as PayUseCase,
    ReceiveResumeUseCase as ResumeUseCase,
};

use Costa\Modules\Charge\UseCases\Charge\DTO\{
    List\Input as ListInput,
    Create\Input as CreateInput,
    Find\Input as FindInput,
    Update\Input as UpdateInput,
    Payment\Input as PaymentInput,
    Resume\Input as ResumeInput,
};
use Illuminate\Http\Request;
use App\Forms\Charge\ChargeReceiveForm as Form;
use App\Http\Controllers\Presenters\PaginationPresenter;
use Costa\Modules\Relationship\Entities\CustomerEntity;
use Costa\Shareds\ValueObjects\ModelObject;
use DateTime;

class ChargeReceiveController extends Controller
{
    protected $routeRedirect = 'charge.receive.index';

    public function index(ListUseCase $useCase, Request $request)
    {
        $result = $useCase->exec(new ListInput(
            filter: $request->all(),
            page: $request->page
        ));

        $data = PaginationPresenter::render($result->items, $result->total, $result->per_page, $result->current_page);
        $total = $result->totalCharges;

        return view('admin.charge.receive.index', compact('data', 'total'));
    }

    public function create(FormSupport $formSupport)
    {
        return view('admin.charge.receive.create', [
            'form' => $formSupport->button('Cadastrar cobrança')->run(
                Form::class,
                route('charge.receive.store')
            )
        ]);
    }

    public function store(CreateUseCase $useCase, FormSupport $formSupport)
    {
        
        $data = $formSupport->data(Form::class);

        $model = $useCase->exec(new CreateInput(
            title: $data['title'],
            description: $data['description'],
            value: $data['value'],
            date: new DateTime($data['date']),
            relationship: new ModelObject(id: $data['relationship_id'], type: CustomerEntity::class),
            parcel: $data['parcel'],
            recurrence: $data['recurrence_id'],
        ));

        return redirect()->route($this->routeRedirect)
            ->with('success', 'Cobrança cadastrada com sucesso')
            ->with('model', $model ?? null);
    }
    
    public function edit(
        FormSupport $formSupport,
        FindUseCase $useCase,
        string $uuid
    ) {
        $model = $useCase->exec(new FindInput($uuid));
        $model->relationship_id = $model->relationship->id;
        $model->recurrence_id = $model->recurrence;

        return view('admin.charge.receive.edit', [
            'form' => $formSupport->button('Editar cobrança')->run(
                Form::class,
                route('charge.receive.update', $model->id),
                $model,
            )
        ]);
    }

    public function update(
        FormSupport $formSupport,
        UpdateUseCase $useCase,
        string $uuid
    ) {
        $data = $formSupport->data(Form::class);
        $model = $useCase->exec(new UpdateInput(
            id: $uuid,
            title: $data['title'],
            description: $data['description'],
            value: $data['value'],
            relationship: new ModelObject(id: $data['relationship_id'], type: CustomerEntity::class),
            date: new DateTime($data['date']),
            recurrence: $data['recurrence_id']
        ));
        
        return redirect()->route($this->routeRedirect)
            ->with('success', 'Cobrança editada com sucesso')
            ->with('model', $model);
    }

    public function destroy(
        DeleteUseCase $useCase,
        string $uuid
    ) {
        $useCase->exec(new FindInput($uuid));

        return redirect()->route($this->routeRedirect)
            ->with('success', 'Cobrança deletada com sucesso');
    }

    public function payShow(FindUseCase $useCase, string $uuid, Request $request, FormSupport $formSupport){
        $model = $useCase->exec(new FindInput($uuid));
        if ($request->model) {
            dd($model);
        }

        $form = $formSupport->run(ChargePayForm::class, route('charge.receive.pay.store', $model->id), null);

        return view('admin.charge.pay.show', [
            'data' => $model,
            'form' => $form,
        ]);
    }

    public function payStore(FormSupport $formSupport, PayUseCase $useCase, string $uuid){
        $data = $formSupport->data(ChargePayForm::class);
        $useCase->exec(new PaymentInput(
            id: $uuid,
            valueCharge: $data['value_charge'],
            valuePay: $data['value_pay'],
            dateSchedule: $data['date_scheduled'] ?? null,
            bank: $data['bank_id'],
            accounts: [new ModelObject(id: ($tenant = auth()->user()->tenant)->uuid, type: $tenant)]
        ));

        return redirect()->route($this->routeRedirect)
            ->with('success', 'Cobrança paga com sucesso');
    }

    public function resume(ResumeUseCase $uc, string $type, Request $request)
    {
        $ret = $uc->exec(new ResumeInput(type: $type, date: new DateTime($request->month)));
        $ret->total_real = str()->numberEnToBr($ret->total);

        return response()->json($ret);
    }
}
