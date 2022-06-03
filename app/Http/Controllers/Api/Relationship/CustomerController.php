<?php

namespace App\Http\Controllers\Api\Relationship;

use App\Http\Controllers\Controller;
use Costa\Modules\Relationship\Customer\UseCases\{CreateUseCase, UpdateUseCase, FindUseCase, DeleteUseCase, ListUseCase};
use Costa\Modules\Relationship\Customer\UseCases\DTO\Create\Input as CreateInput;
use Costa\Modules\Relationship\Customer\UseCases\DTO\Update\Input as UpdateInput;
use Costa\Modules\Relationship\Customer\UseCases\DTO\Find\Input as FindInput;
use Costa\Modules\Relationship\Customer\UseCases\DTO\List\Input as ListInput;
use Illuminate\Http\Request;

class CustomerController extends Controller
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
            documentType: $request->document['type'] ?? null,
            documentValue: $request->document['value'] ?? null,
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
            documentType: $request->document['type'] ?? null,
            documentValue: $request->document['value'] ?? null,
        ));
        
        return response()->json(['data' => $resp]);
    }

    public function destroy($id, DeleteUseCase $uc)
    {
        $uc->handle(new FindInput($id));
        return response()->noContent();
    }
}
