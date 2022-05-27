<?php

namespace Costa\Modules\Payment\UseCases;

use Costa\Modules\Account\Repository\BankRepositoryInterface;
use Costa\Modules\Charge\Repository\ChargePaymentRepositoryInterface;
use Costa\Modules\Charge\Repository\ChargeReceiveRepositoryInterface;

class PaymentResumeUseCase
{
    public function __construct(
        private ChargeReceiveRepositoryInterface $receive,
        private ChargePaymentRepositoryInterface $payment,
    ) {
        //
    }

    public function exec(DTO\Resume\Input $input): DTO\Resume\Output
    {
        $filter = [
            'type' => 1,
            'date_start' => $input->date->modify('first day of this month')->format('Y-m-d'),
            'date_finish' => $input->date->modify('last day of this month')->format('Y-m-d'),
        ];

        $valueReceive = $this->receive->getValueTotal($filter);
        $valuePayment = $this->payment->getValueTotal($filter);

        $valueTotal = $valueReceive - $valuePayment + $input->value;

        return new DTO\Resume\Output(
            value: $valueTotal,
            calculate: $valueReceive - $valuePayment,
        );
    }
}
