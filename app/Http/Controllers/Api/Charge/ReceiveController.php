<?php

namespace App\Http\Controllers\Api\Charge;

use App\Http\Controllers\Controller;
use Costa\Modules\Charge\Receive\UseCases\{
    CreateUseCase,
    UpdateUseCase,
    FindUseCase,
    DeleteUseCase,
    ListUseCase
};

use Costa\Modules\Charge\Receive\UseCases\DTO\{
    Create\Input as CreateInput,
    Update\Input as UpdateInput,
    Find\Input as FindInput,
    List\Input as ListInput,
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
                description: $rs['description'] ?? null,
                value: $charge['value'],
                date: new DateTime($charge['date']),
                parcel: empty($charge['parcel']) || $charge['parcel'] < 1 ? 1 : $charge['parcel'],
                recurrence: $charge['recurrence'] ?? null,
                customerId: $charge['customer']['id'] ?? null,
                customerName: $charge['customer']['name'] ?? null
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
            name: $request->name,
            days: $request->days,
        ));
        
        return response()->json(['data' => $resp]);
    }

    public function destroy($id, DeleteUseCase $uc)
    {
        $uc->handle(new FindInput($id));
        return response()->noContent();
    }
}
