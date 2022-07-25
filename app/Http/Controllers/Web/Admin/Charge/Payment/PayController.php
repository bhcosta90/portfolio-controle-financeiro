<?php

namespace App\Http\Controllers\Web\Admin\Charge\Payment;

use App\Forms\Payment\PartialForm;
use App\Forms\Payment\TotalForm;
use App\Http\Controllers\Controller;
use App\Support\FormSupport;
use Core\Application\Charge\Modules\Payment\UseCases;
use Core\Shared\UseCases\Find\FindInput;
use Illuminate\Http\Request;
use stdClass;

class PayController extends Controller
{
    public function show(UseCases\FindUseCase $findUseCase, string $id, FormSupport $formSupport)
    {
        $model = $findUseCase->handle(new FindInput($id));
        
        $formPartial = $formSupport->run(PartialForm::class, route('admin.charge.payment.pay.update', [
            'pay' => $id,
            'type' => 'partial'
        ]), new stdClass);

        $formTotal = $formSupport->run(TotalForm::class, route('admin.charge.payment.pay.update', [
            'pay' => $id,
            'type' => 'total'
        ]), new stdClass);

        return [
            'model' => $model,
            'formPartial' => $formPartial,
            'formTotal' => $formTotal,
        ];
    }

    public function update(
        string $id,
        UseCases\PaymentUseCase $paymentUseCase,
        FormSupport $formSupport,
        Request $request,
        UseCases\FindUseCase $findUseCase
    ) {

        $data = $formSupport->data(match($request->type) {
            'total' => TotalForm::class,
            'partial' => PartialForm::class,
        });

        $objCharge = $findUseCase->handle(new FindInput($id));

        $model = $paymentUseCase->handle(new UseCases\DTO\Payment\Input(
            $id,
            $data['value_pay'] ?? $objCharge->value,
            $data['date_scheduled'],
            $data['bank_id'] != -1 ? $data['bank_id'] : null,
            $request->type == 'partial',
            $data['date_next'] ?? null
        ));

        return [
            'redirect' => route('admin.charge.payment.charge.index'),
            'message' => 'Transação feita com sucesso',
            'model' => $model,
        ];
    }
}
