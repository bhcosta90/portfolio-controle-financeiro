<?php

namespace Costa\Modules\Charge\UseCases\Payment;

use Costa\Modules\Charge\Repository\ChargePaymentRepositoryInterface;
use Costa\Modules\Charge\UseCases\Charge\ChargeDeleteUseCase;
use Costa\Shareds\Contracts\TransactionContract;

class PaymentDeleteUseCase extends ChargeDeleteUseCase
{
    public function __construct(
        protected ChargePaymentRepositoryInterface $repo,
        protected TransactionContract $transaction,
    ) {
        //
    }
}
