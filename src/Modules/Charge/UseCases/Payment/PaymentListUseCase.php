<?php

namespace Costa\Modules\Charge\UseCases\Payment;

use Costa\Modules\Charge\Repository\ChargePaymentRepositoryInterface;
use Costa\Modules\Charge\UseCases\Charge\ChargeListUseCase;

class PaymentListUseCase extends ChargeListUseCase
{
    public function __construct(
        protected ChargePaymentRepositoryInterface $repo
    ) {
        //
    }
}
