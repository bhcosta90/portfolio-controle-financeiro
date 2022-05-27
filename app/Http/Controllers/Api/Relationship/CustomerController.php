<?php

namespace App\Http\Controllers\Api\Relationship;

use App\Http\Controllers\Controller;
use Costa\Modules\Relationship\Repositories\CustomerRepositoryInterface;
use Costa\Modules\Relationship\UseCases\CustomerCreateUseCase;
use Costa\Modules\Relationship\UseCases\CustomerUpdateUseCase;
use Costa\Modules\Relationship\UseCases\DTO\Create\Input as CreateInput;
use Costa\Modules\Relationship\UseCases\DTO\Update\Input as UpdateInput;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request, CustomerCreateUseCase $uc)
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

    public function show($id, CustomerRepositoryInterface $repository)
    {
        $resp = $repository->find($id);

        return response()->json(['data' => [
            'id' => (string) $resp->id,
            'name' => $resp->name->value,
        ]]);

    }

    public function update(Request $request, CustomerUpdateUseCase $uc, $id)
    {
        $resp = $uc->handle(new UpdateInput(
            id: $id,
            name: $request->name,
            documentType: $request->document['type'] ?? null,
            documentValue: $request->document['value'] ?? null,
        ));
        
        return response()->json(['data' => [
            'id' => (string) $resp->id,
            'name' => $resp->name->value,
        ]]);
    }

    public function destroy($id, CustomerRepositoryInterface $repository)
    {
        $repository->delete($repository->find($id));
        return response()->noContent();
    }
}
