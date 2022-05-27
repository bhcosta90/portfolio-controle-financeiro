<?php

namespace Costa\Modules\Charge\UseCases\Receive;

use Costa\Modules\Charge\Entities\ChargeReceiveEntity;
use Costa\Modules\Charge\Repository\ChargeReceiveRepositoryInterface;
use Costa\Modules\Charge\Repository\RecurrenceRepositoryInterface;
use Costa\Modules\Charge\UseCases\Charge\ChargeCreatedUseCase;
use Costa\Modules\Relationship\Repository\CustomerRepositoryInterface;
use Costa\Shareds\Contracts\TransactionContract;

class ReceiveCreatedUseCase extends ChargeCreatedUseCase
{
    public function __construct(
        protected ChargeReceiveRepositoryInterface $repo,
        protected CustomerRepositoryInterface $relationship,
        protected RecurrenceRepositoryInterface $recurrence,
        protected TransactionContract $transaction,
        protected string $entity = ChargeReceiveEntity::class,
    ) {
        //
    }
}
