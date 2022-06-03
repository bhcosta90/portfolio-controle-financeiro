<?php

namespace Costa\Modules\Charge\Receive\UseCases;

use Costa\Modules\Charge\Receive\Entity\ChargeEntity;
use Costa\Modules\Charge\Receive\Repository\ChargeRepositoryInterface;
use Costa\Modules\Relationship\Customer\Repository\CustomerRepositoryInterface;

class FindUseCase
{
    public function __construct(
        protected ChargeRepositoryInterface $repo,
        protected CustomerRepositoryInterface $relationship
    ) {
        //
    }

    public function handle(DTO\Find\Input $input): DTO\Find\Output
    {
        /** @var ChargeEntity */
        $objEntity = $this->repo->find($input->id);

        $objCustomer = $this->relationship->find($objEntity->customer->id);

        return new DTO\Find\Output(
            id: $objEntity->id,
            title: $objEntity->title->value,
            description: $objEntity->description->value,
            value: $objEntity->value->value,
            customerId: $objEntity->customer->id,
            recurrenceId: $objEntity->recurrence,
            date: $objEntity->date->format('Y-m-d'),
            pay: $objEntity->payValue->value,
            customerName: $objCustomer->name->value,
        );
    }
}