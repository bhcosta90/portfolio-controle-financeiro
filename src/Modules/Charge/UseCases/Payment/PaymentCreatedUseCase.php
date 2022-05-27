<?php

namespace Costa\Modules\Charge\UseCases\Payment;

use Costa\Modules\Charge\Entities\ChargePaymentEntity;
use Costa\Modules\Charge\Repository\ChargePaymentRepositoryInterface;
use Costa\Modules\Charge\Repository\RecurrenceRepositoryInterface;
use Costa\Modules\Charge\UseCases\Charge\ChargeCreatedUseCase;
use Costa\Modules\Relationship\Repository\SupplierRepositoryInterface;
use Costa\Shareds\Contracts\TransactionContract;

class PaymentCreatedUseCase extends ChargeCreatedUseCase
{
    public function __construct(
        protected ChargePaymentRepositoryInterface $repo,
        protected SupplierRepositoryInterface $relationship,
        protected RecurrenceRepositoryInterface $recurrence,
        protected TransactionContract $transaction,
        protected string $entity = ChargePaymentEntity::class,
    ) {
        //
    }
}
