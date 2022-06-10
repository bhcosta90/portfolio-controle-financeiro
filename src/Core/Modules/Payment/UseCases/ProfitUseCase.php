<?php

namespace Costa\Modules\Payment\UseCases;

use App\Repositories\Eloquent\ChargePaymentRepository;
use App\Repositories\Eloquent\ChargeReceiveRepository;

class ProfitUseCase
{
    public function __construct(
        private ChargeReceiveRepository $receive,
        private ChargePaymentRepository $payment,
    ) {
        //
    }
    
    public function handle(DTO\Profit\Input $input): DTO\Profit\Output
    {
        $dateStart = $input->date->modify('first day of this month');
        $dateFinish = $input->date->modify('first day of this month');

        $valueReceive = $this->receive->total($dateStart, $dateFinish);
        $valuePayment = $this->payment->total($dateStart, $dateFinish);

        $valueTotal = $valueReceive - $valuePayment;

        return new DTO\Profit\Output($valueTotal);
    }
}
