<?php

namespace App\Http\Controllers\Api\Relationship;

use App\Http\Controllers\Controller;
use Costa\Modules\Relationship\Repositories\SupplierRepositoryInterface;
use Costa\Modules\Relationship\UseCases\SupplierCreateUseCase;
use Costa\Modules\Relationship\UseCases\SupplierUpdateUseCase;
use Costa\Modules\Relationship\UseCases\DTO\Create\Input as CreateInput;
use Costa\Modules\Relationship\UseCases\DTO\Update\Input as UpdateInput;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function store(Request $request, SupplierCreateUseCase $uc)
    {
        $resp = $uc->handle(new CreateInput(
            name: $request->name,
            documentType: $request->document['type'] ?? null,
            documentValue: $request->document['value'] ?? null,
        ));
        
        return response()->json(['data' => [
            'id' => (string) $resp->id,
            'name' => $resp->name->value,
        ]]);
    }

    public function show($id, SupplierRepositoryInterface $repository)
    {
        $resp = $repository->find($id);

        return response()->json(['data' => [
            'id' => (string) $resp->id,
            'name' => $resp->name->value,
        ]]);

    }

    // public function update(Request $request, SupplierUpdateUseCase $uc, $id)
    // {
    //     $resp = $uc->handle(new UpdateInput(
    //         id: $id,
    //         name: $request->name,
    //         documentType: $request->document['type'] ?? null,
    //         documentValue: $request->document['value'] ?? null,
    //     ));
        
    //     return response()->json(['data' => [
    //         'id' => (string) $resp->id,
    //         'name' => $resp->name->value,
    //     ]]);
    // }

    public function destroy($id, SupplierRepositoryInterface $repository)
    {
        $repository->delete($repository->find($id));
        return response()->noContent();
    }
}
