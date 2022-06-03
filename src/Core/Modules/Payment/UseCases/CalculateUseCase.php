<?php

namespace Costa\Modules\Payment\UseCases;

use App\Repositories\Eloquent\ChargePaymentRepository;
use App\Repositories\Eloquent\ChargeReceiveRepository;
use Costa\Modules\Bank\Repository\BankRepositoryInterface;

class CalculateUseCase
{
    public function __construct(
        private ChargeReceiveRepository $receive,
        private ChargePaymentRepository $payment,
        private BankRepositoryInterface $bank,
    )
    {
        //
    }

    public function handle(DTO\Calculate\Input $input)
    {
        $filter = [
            'type' => 1,
            'date_start' => $input->date->modify('first day of this month')->format('Y-m-d'),
            'date_finish' => $input->date->modify('last day of this month')->format('Y-m-d'),
        ];

        $valueReceive = $this->receive->total($filter);
        $valuePayment = $this->payment->total($filter);

        $valueTotal = $valueReceive - $valuePayment;

        return new DTO\FinancialBalance\Output($valueTotal);
    }
}
