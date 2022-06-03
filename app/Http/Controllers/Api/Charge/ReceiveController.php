<?php

namespace App\Http\Controllers\Api\Charge;

use App\Http\Controllers\Controller;
use Costa\Modules\Charge\Receive\Entity\ChargeEntity;
use Costa\Modules\Charge\Receive\UseCases\{
    CreateUseCase,
    UpdateUseCase,
    FindUseCase,
    DeleteUseCase,
    ListUseCase,
    PaymentUseCase,
    ResumeUseCase,
};

use Costa\Modules\Charge\Receive\UseCases\DTO\{
    Create\Input as CreateInput,
    Update\Input as UpdateInput,
    Find\Input as FindInput,
    List\Input as ListInput,
    Payment\Input as PaymentInput,
    Resume\Input as ResumeInput,
};
use DateTime;
use Illuminate\Http\Request;

class ReceiveController extends Controller
{
    public function index(ListUseCase $uc, Request $request)
    {
        return response()->json($uc->exec(new ListInput(
            $request->all(), 
            $request->limit,
            $request->page,
        )));
    }

    public function store(Request $request, CreateUseCase $uc)
    {
        $input = [];
        foreach($request->charges as $charge){
            $input[] = new CreateInput(
                title: $charge['title'],
                description: $charge['description'] ?? null,
                value: $charge['value'],
                date: new DateTime($charge['date']),
                parcel: empty($charge['parcel']) || $charge['parcel'] < 1 ? 1 : $charge['parcel'],
                recurrence: $charge['recurrence'] ?? null,
                customer: $charge['customer'] ?? null,
            );
        }
        $resp = $uc->handle($input);
        return response()->json(['data' => $resp]);
    }

    public function show($id, FindUseCase $uc)
    {
        $resp = $uc->handle(new FindInput($id));
        return response()->json(['data' => $resp]);
    }

    public function update(Request $request, UpdateUseCase $uc, $id)
    {
        $resp = $uc->handle(new UpdateInput(
            id: $id,
            title: $request->title,
            description: $request->description ?? null,
            customer: $request->customer,
            value: $request->value,
            date: new DateTime($request->date),
            recurrence: $request->recurrence ?? null,
        ));
        
        return response()->json(['data' => $resp]);
    }

    public function destroy($id, DeleteUseCase $uc)
    {
        $uc->handle(new FindInput($id));
        return response()->noContent();
    }

    public function pay(PaymentUseCase $uc, string $id, Request $request){
        $request->request->add(['id' => $id]);

        $data = $request->validate([
            'value' => 'required|min:0|numeric',
            'charge' => 'nullable|min:0|numeric',
            'id' => "required|exists:charges,id,id,{$id},entity," . ChargeEntity::class,
            'date' => 'required',
            'bank' => 'nullable|exists:banks,id',
        ]);

        $resp = $uc->handle(new PaymentInput(
            id: $data['id'],
            value: $data['value'],
            charge: $data['charge'] ?? $data['value'],
            date: new DateTime($data['date']),
            bank: $data['bank'] ?? null
        ));

        return response()->json(['data' => $resp]);
    }

    public function resume(string $type, ResumeUseCase $uc, Request $request){
        $resp = $uc->handle(new ResumeInput(type: $type, date: new DateTime($request->month)));
        $resp->total_real = str()->numberEnToBr($resp->total);
        return response()->json($resp);
    }
}