<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Costa\Modules\Account\Repository\BankRepositoryInterface;
use Costa\Modules\Payment\UseCases\PaymentResumeUseCase;
use Costa\Modules\Payment\UseCases\DTO\Resume\Input as ResumeInput;
use DateTime;
use Illuminate\Http\Request;

class FinancialBalanceController extends Controller
{
    public function resume(PaymentResumeUseCase $uc, BankRepositoryInterface $bank, Request $request)
    {
        $ret = $uc->exec(
            new ResumeInput(
                value: $bank->total(),
                date: new DateTime($request->month),
            )
        );
        $ret->total_real = str()->numberEnToBr($ret->value);

        return response()->json($ret);
    }

    public function profit(PaymentResumeUseCase $uc, Request $request)
    {
        $ret = $uc->exec(
            new ResumeInput(
                value: 0,
                date: new DateTime($request->month),
            )
        );
        $ret->total_real = str()->numberEnToBr($ret->value);
        return response()->json($ret);
    }

    public function calcule(PaymentResumeUseCase $uc, BankRepositoryInterface $bank, Request $request)
    {
        $ret = $uc->exec(
            new ResumeInput(
                value: $bank->total(),
                date: new DateTime($request->month),
            )
        );
        return response()->json([
            'total' => $ret->calculate,
            'total_real' => str()->numberEnToBr($ret->calculate)
        ]);
    }
}
