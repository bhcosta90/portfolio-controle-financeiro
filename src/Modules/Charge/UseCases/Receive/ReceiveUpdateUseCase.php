<?php

namespace Costa\Modules\Charge\UseCases\Receive;

use Costa\Modules\Charge\Entities\ChargeReceiveEntity;
use Costa\Modules\Charge\Repository\ChargeReceiveRepositoryInterface;
use Costa\Modules\Charge\Repository\RecurrenceRepositoryInterface;
use Costa\Modules\Charge\UseCases\Charge\ChargeUpdateUseCase;
use Costa\Modules\Relationship\Repository\CustomerRepositoryInterface;

class ReceiveUpdateUseCase extends ChargeUpdateUseCase
{
    public function __construct(
        protected ChargeReceiveRepositoryInterface $repo,
        protected CustomerRepositoryInterface $relationship,
        protected RecurrenceRepositoryInterface $recurrence,
        protected string $entity = ChargeReceiveEntity::class,
    ) {
        //
    }
}
