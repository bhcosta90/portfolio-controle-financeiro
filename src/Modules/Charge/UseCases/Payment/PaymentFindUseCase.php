<?php

namespace Costa\Modules\Charge\UseCases\Payment;

use Costa\Modules\Charge\Repository\ChargePaymentRepositoryInterface;
use Costa\Modules\Charge\UseCases\Charge\ChargeFindUseCase;

class PaymentFindUseCase extends ChargeFindUseCase
{
    public function __construct(protected ChargePaymentRepositoryInterface $repo)
    {
        //
    }
}
