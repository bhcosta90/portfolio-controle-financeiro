<?php

namespace Costa\Modules\Charge\UseCases\Payment;

use Costa\Modules\Charge\Repository\ChargePaymentRepositoryInterface;
use Costa\Modules\Charge\UseCases\Charge\ChargeResumeUseCase;

class PaymentResumeUseCase extends ChargeResumeUseCase
{
    public function __construct(protected ChargePaymentRepositoryInterface $repo)
    {
        //
    }
}
