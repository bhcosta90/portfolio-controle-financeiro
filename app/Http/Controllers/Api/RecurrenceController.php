<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Costa\Modules\Recurrence\UseCases\{
    CreateUseCase,
    UpdateUseCase,
    FindUseCase,
    DeleteUseCase,
    ListUseCase
};

use Costa\Modules\Recurrence\UseCases\DTO\{
    Create\Input as CreateInput,
    Update\Input as UpdateInput,
    Find\Input as FindInput,
    List\Input as ListInput,
};
use Illuminate\Http\Request;

class RecurrenceController extends Controller
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
        $resp = $uc->handle(new CreateInput(
            name: $request->name,
            days: $request->days,
        ));
        
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
