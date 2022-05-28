<?php

namespace App\Http\Controllers\Api\Relationship;

use App\Http\Controllers\Controller;
use Costa\Modules\Relationship\Supplier\UseCases\{CreateUseCase, UpdateUseCase, FindUseCase, DeleteUseCase};
use Costa\Modules\Relationship\Supplier\UseCases\DTO\Create\Input as CreateInput;
use Costa\Modules\Relationship\Supplier\UseCases\DTO\Update\Input as UpdateInput;
use Costa\Modules\Relationship\Supplier\UseCases\DTO\Find\Input as FindInput;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        //
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
