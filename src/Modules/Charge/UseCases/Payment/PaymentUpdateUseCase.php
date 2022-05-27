<?php

namespace Costa\Modules\Charge\UseCases\Payment;

use Costa\Modules\Charge\Entities\ChargePaymentEntity;
use Costa\Modules\Charge\Repository\ChargePaymentRepositoryInterface;
use Costa\Modules\Charge\Repository\RecurrenceRepositoryInterface;
use Costa\Modules\Charge\UseCases\Charge\ChargeUpdateUseCase;
use Costa\Modules\Relationship\Repository\SupplierRepositoryInterface;

class PaymentUpdateUseCase extends ChargeUpdateUseCase
{
    public function __construct(
        protected ChargePaymentRepositoryInterface $repo,
        protected SupplierRepositoryInterface $relationship,
        protected RecurrenceRepositoryInterface $recurrence,
        protected string $entity = ChargePaymentEntity::class,
    ) {
        //
    }
}
