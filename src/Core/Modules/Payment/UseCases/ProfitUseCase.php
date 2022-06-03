<?php

namespace Costa\Modules\Payment\UseCases;

use App\Repositories\Eloquent\ChargePaymentRepository;
use App\Repositories\Eloquent\ChargeReceiveRepository;

class ProfitUseCase
{
    public function __construct(
        private ChargeReceiveRepository $receive,
        private ChargePaymentRepository $payment,
    )
    {
        
    }
    public function handle(DTO\Profit\Input $input): DTO\Profit\Output
    {
        $filter = [
            'type' => 1,
            'date_start' => $input->date->modify('first day of this month')->format('Y-m-d'),
            'date_finish' => $input->date->modify('last day of this month')->format('Y-m-d'),
        ];

        $valueReceive = $this->receive->total($filter);
        $valuePayment = $this->payment->total($filter);

        $valueTotal = $valueReceive - $valuePayment;

        return new DTO\Profit\Output($valueTotal);
    }
}
