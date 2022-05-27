<?php

namespace Costa\Modules\Charge\UseCases\Payment;

use Costa\Modules\Account\Repository\AccountRepositoryInterface;
use Costa\Modules\Account\Repository\BankRepositoryInterface;
use Costa\Modules\Charge\Repository\ChargePaymentRepositoryInterface;
use Costa\Modules\Charge\Repository\RecurrenceRepositoryInterface;
use Costa\Modules\Charge\UseCases\Charge\ChargePaymentUseCase;
use Costa\Modules\Payment\Repository\PaymentRepositoryInterface;
use Costa\Shareds\Contracts\TransactionContract;

class PaymentPayUseCase extends ChargePaymentUseCase
{
    public function __construct(
        protected ChargePaymentRepositoryInterface $repo,
        protected RecurrenceRepositoryInterface $recurrence,
        protected TransactionContract $transaction,
        protected AccountRepositoryInterface $account,
        protected PaymentRepositoryInterface $payment,
        protected BankRepositoryInterface $bank,
    ) {
        //
    }
}
