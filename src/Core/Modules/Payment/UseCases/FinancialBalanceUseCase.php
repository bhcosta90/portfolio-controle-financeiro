<?php

namespace Costa\Modules\Payment\UseCases;

use Costa\Modules\Bank\Repository\BankRepositoryInterface;
use Costa\Modules\Charge\Payment\Repository\ChargeRepositoryInterface as PaymentInterface;
use Costa\Modules\Charge\Receive\Repository\ChargeRepositoryInterface as ReceiveInterface;

class FinancialBalanceUseCase
{
    public function __construct(
        private PaymentInterface $receive,
        private ReceiveInterface $payment,
        private BankRepositoryInterface $bank,
    ) {
        //
    }

    public function handle(DTO\FinancialBalance\Input $input): DTO\FinancialBalance\Output
    {
        $dateStart = $input->date->modify('first day of this month');
        $dateFinish = $input->date->modify('first day of this month');

        $valueReceive = $this->receive->total($dateStart, $dateFinish);
        $valuePayment = $this->payment->total($dateStart, $dateFinish);
        $totalBank = $this->bank->total();

        $valueTotal = $valueReceive - $valuePayment + $totalBank;

        return new DTO\FinancialBalance\Output($valueTotal);
    }
}
