<?php

namespace Costa\Modules\Payment\UseCases;

use App\Repositories\Eloquent\ChargePaymentRepository;
use App\Repositories\Eloquent\ChargeReceiveRepository;
use Costa\Modules\Bank\Repository\BankRepositoryInterface;

class FinancialBalanceUseCase
{
    public function __construct(
        private ChargeReceiveRepository $receive,
        private ChargePaymentRepository $payment,
        private BankRepositoryInterface $bank,
    )
    {
        //
    }
    
    public function handle(DTO\FinancialBalance\Input $input): DTO\FinancialBalance\Output
    {
        $filter = [
            'type' => 1,
            'date_start' => $input->date->modify('first day of this month')->format('Y-m-d'),
            'date_finish' => $input->date->modify('last day of this month')->format('Y-m-d'),
        ];

        $valueReceive = $this->receive->total($filter);
        $valuePayment = $this->payment->total($filter);
        $totalBank = $this->bank->total();

        $valueTotal = $valueReceive - $valuePayment + $totalBank;

        return new DTO\FinancialBalance\Output($valueTotal);
    }
}
