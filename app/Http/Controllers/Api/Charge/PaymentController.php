<?php

namespace App\Http\Controllers\Api\Charge;

use App\Http\Controllers\Controller;
use Costa\Modules\Charge\Payment\UseCases\{
    CreateUseCase,
    UpdateUseCase,
    FindUseCase,
    DeleteUseCase,
    ListUseCase
};

use Costa\Modules\Charge\Payment\UseCases\DTO\{
    Create\Input as CreateInput,
    Update\Input as UpdateInput,
    Find\Input as FindInput,
    List\Input as ListInput,
};
use DateTime;
use Illuminate\Http\Request;

class PaymentController extends Controller
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
                supplierId: $charge['supplier'] ?? null,
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
            supplier: $request->supplier,
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
}
