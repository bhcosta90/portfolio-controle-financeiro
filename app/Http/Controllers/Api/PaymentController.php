<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Costa\Modules\Bank\Entity\BankEntity;
use Costa\Modules\Charge\Utils\Enums\ChargeStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Costa\Modules\Charge\Payment\Entity\ChargeEntity as PaymentEntity;
use Costa\Modules\Charge\Receive\Entity\ChargeEntity as ReceiveEntity;

class PaymentController extends Controller
{
    public function financialbalance(Request $request)
    {
        $resp = $this->getBankValue()
            + $this->getResumeValueReceiveEntity($request->month)
            - $this->getResumeValuePaymentEntity($request->month);

        return response()->json([
            'quantity' => $resp,
            'total_real' => str()->numberEnToBr($resp),
            'total' => $resp,
        ]);
    }

    public function profitmonth(Request $request)
    {
        $resp = $this->getResumeValuePaymentEntity($request->month);

        return response()->json([
            'quantity' => $resp,
            'total_real' => str()->numberEnToBr($resp),
            'total' => $resp,
        ]);
    }

    public function calcule(Request $request)
    {
        $resp = $this->getResumeValueReceiveEntity($request->month)
            - $this->getResumeValuePaymentEntity($request->month);

        return response()->json([
            'quantity' => $resp,
            'total_real' => str()->numberEnToBr($resp),
            'total' => $resp,
        ]);
    }

    protected function getResumeValuePaymentEntity($date)
    {
        $date = $this->getDateCarbon($date);

        return DB::table('charges')
            ->where('entity', PaymentEntity::class)
            ->whereBetween('date_due', [
                $date->firstOfMonth()->format('Y-m-d'),
                $date->lastOfMonth()->format('Y-m-d')
            ])
            ->where('status', '!=', ChargeStatusEnum::COMPLETED)
            ->whereNull('deleted_at')
            ->sum(DB::raw('value_charge - value_pay'));
    }

    protected function getResumeValueReceiveEntity($date)
    {
        $date = $this->getDateCarbon($date);

        return DB::table('charges')
            ->where('entity', ReceiveEntity::class)
            ->whereBetween('date_due', [
                $date->firstOfMonth()->format('Y-m-d'),
                $date->lastOfMonth()->format('Y-m-d')
            ])
            ->where('status', '!=', ChargeStatusEnum::COMPLETED)
            ->whereNull('deleted_at')
            ->sum(DB::raw('value_charge - value_pay'));
    }

    protected function getDateCarbon($date)
    {
        return new Carbon($date);
    }

    protected function getBankValue()
    {
        return DB::table('accounts')
            ->where('accounts.entity_type', BankEntity::class)
            ->join('banks', 'banks.id', '=', 'accounts.entity_id')
            ->whereNull('banks.deleted_at')
            ->sum('accounts.value');
    }
}
