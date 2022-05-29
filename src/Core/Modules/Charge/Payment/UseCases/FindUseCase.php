<?php

namespace Costa\Modules\Charge\Payment\UseCases;

use Costa\Modules\Charge\Payment\Entity\ChargeEntity;
use Costa\Modules\Charge\Payment\Repository\ChargeRepositoryInterface;

class FindUseCase
{
    public function __construct(
        protected ChargeRepositoryInterface $repo
    ) {
        //
    }

    public function handle(DTO\Find\Input $input): DTO\Find\Output
    {
        /** @var ChargeEntity */
        $objEntity = $this->repo->find($input->id);

        return new DTO\Find\Output(
            id: $objEntity->id,
            title: $objEntity->title->value,
            description: $objEntity->description->value,
            value: $objEntity->value->value,
            customerId: $objEntity->relationship->id,
            recurrenceId: $objEntity->recurrence,
        );
    }
}
