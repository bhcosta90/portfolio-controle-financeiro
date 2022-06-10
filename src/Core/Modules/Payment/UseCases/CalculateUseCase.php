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
    ) {
        //
    }

    public function handle(DTO\Calculate\Input $input)
    {
        $dateStart = $input->date->modify('first day of this month');
        $dateFinish = $input->date->modify('first day of this month');

        $valueReceive = $this->receive->total($dateStart, $dateFinish);
        $valuePayment = $this->payment->total($dateStart, $dateFinish);

        $valueTotal = $valueReceive - $valuePayment;

        return new DTO\FinancialBalance\Output($valueTotal);
    }
}
