<?php

namespace App\Http\Controllers\Api\Charge;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Costa\Modules\Charge\Payment\Entity\ChargeEntity;
use Costa\Modules\Charge\Payment\UseCases\{
    CreateUseCase,
    UpdateUseCase,
    FindUseCase,
    DeleteUseCase,
    ListUseCase,
    PaymentUseCase
};

use Costa\Modules\Charge\Payment\UseCases\DTO\{
    Create\Input as CreateInput,
    Update\Input as UpdateInput,
    Find\Input as FindInput,
    List\Input as ListInput,
    Payment\Input as PaymentInput,
};
use Costa\Modules\Charge\Utils\Enums\ChargeStatusEnum;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                supplier: $charge['supplier'] ?? null,
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

    public function resume(string $type, Request $request){
        $action = "getResume".str_replace(' ', '', ucwords(str_replace('-', ' ', $type)));
        $resp = $this->$action();
        return response()->json([
            'quantity' => $resp,
            'total' => str()->numberEnToBr($resp),
            'total_real' => $resp,
        ]);
    }

    protected function getResumeDueDate(){
        return DB::table('charges')
            ->where('entity', ChargeEntity::class)
            ->where('date_due', '<', Carbon::now()->format('Y-m-d'))
            ->where('status', '!=', ChargeStatusEnum::COMPLETED)
            ->whereNull('deleted_at')
            ->count();
    }

    protected function getResumeToday(){
        return DB::table('charges')
            ->where('entity', ChargeEntity::class)
            ->where('date_due', Carbon::now()->format('Y-m-d'))
            ->where('status', '!=', ChargeStatusEnum::COMPLETED)
            ->whereNull('deleted_at')
            ->count();
    }
}
