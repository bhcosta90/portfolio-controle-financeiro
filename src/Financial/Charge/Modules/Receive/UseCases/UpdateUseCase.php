<?php

namespace Core\Financial\Charge\Modules\Receive\UseCases;

use Core\Financial\Charge\Modules\Receive\Domain\ReceiveEntity as Entity;
use Core\Financial\Charge\Modules\Receive\Repository\ReceiveRepositoryInterface;
use Core\Financial\Relationship\Modules\Customer\Repository\CustomerRepositoryInterface;

class UpdateUseCase
{
    public function __construct(
        private ReceiveRepositoryInterface $repo,
        private CustomerRepositoryInterface $customer,
    ) {
        //
    }

    public function handle(DTO\Update\UpdateInput $input): DTO\Update\UpdateOutput
    {
        /** @var Entity */
        $obj = $this->repo->find($input->id);
        $objCustomer = $this->customer->find($input->customerId);

        $obj->update(
            value: $input->value,
            customer: $objCustomer,
            date: $input->date,
            recurrence: $input->recurrenceId,
        );

        $this->repo->update($obj);

        return new DTO\Update\UpdateOutput(
            id: $obj->id(),
            value: $input->value,
            customerId: $input->customerId,
            date: $input->date,
            recurrenceId: $input->recurrenceId,
        );
    }
}
