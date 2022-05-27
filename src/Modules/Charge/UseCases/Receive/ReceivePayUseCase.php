<?php

namespace Costa\Modules\Charge\UseCases\Receive;

use Costa\Modules\Account\Repository\AccountRepositoryInterface;
use Costa\Modules\Account\Repository\BankRepositoryInterface;
use Costa\Modules\Charge\Repository\ChargeReceiveRepositoryInterface;
use Costa\Modules\Charge\Repository\RecurrenceRepositoryInterface;
use Costa\Modules\Charge\UseCases\Charge\ChargePaymentUseCase;
use Costa\Modules\Payment\Repository\PaymentRepositoryInterface;
use Costa\Shareds\Contracts\TransactionContract;

class ReceivePayUseCase extends ChargePaymentUseCase
{
    public function __construct(
        protected ChargeReceiveRepositoryInterface $repo,
        protected RecurrenceRepositoryInterface $recurrence,
        protected TransactionContract $transaction,
        protected AccountRepositoryInterface $account,
        protected PaymentRepositoryInterface $payment,
        protected BankRepositoryInterface $bank,
    ) {
        //
    }
}
