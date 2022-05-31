<?php

namespace Costa\Modules\Charge\Payment\UseCases;

use Costa\Modules\Charge\Payment\Entity\ChargeEntity;
use Costa\Modules\Charge\Payment\Repository\ChargeRepositoryInterface;
use Costa\Modules\Payment\Contracts\PaymentEventManagerContract;

class PaymentUseCase
{
    public function __construct(
        protected ChargeRepositoryInterface $repo,
        protected PaymentEventManagerContract $paymentEventManager
    ) {
        //
    }

    public function handle(DTO\Payment\Input $input)
    {
        /** @var ChargeEntity */
        $objCharge = $this->repo->find($input->id);
        
        dd($objCharge);
    }
}
