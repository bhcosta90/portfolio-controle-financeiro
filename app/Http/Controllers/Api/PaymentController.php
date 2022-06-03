<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Costa\Modules\Payment\UseCases\FinancialBalanceUseCase;
use Costa\Modules\Payment\UseCases\ProfitUseCase;
use Costa\Modules\Payment\UseCases\CalculateUseCase;
use Costa\Modules\Payment\UseCases\DTO\FinancialBalance\Input as FinancialBalanceInput;
use Costa\Modules\Payment\UseCases\DTO\Profit\Input as ProfitInput;
use Costa\Modules\Payment\UseCases\DTO\Calculate\Input as CalculateInput;
use DateTime;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function financialbalance(FinancialBalanceUseCase $uc, Request $request)
    {
        $resp = $uc->handle(new FinancialBalanceInput(new DateTime($request->month)));
        $resp->total_real = str()->numberEnToBr($resp->total);
        return response()->json($resp);
    }

    public function profitmonth(ProfitUseCase $uc, Request $request)
    {
        $resp = $uc->handle(new ProfitInput(new DateTime($request->month)));
        $resp->total_real = str()->numberEnToBr($resp->total);
        return response()->json($resp);
    }

    public function calcule(CalculateUseCase $uc, Request $request)
    {
        $resp = $uc->handle(new CalculateInput(new DateTime($request->month)));
        $resp->total_real = str()->numberEnToBr($resp->total);
        return response()->json($resp);
    }
}
