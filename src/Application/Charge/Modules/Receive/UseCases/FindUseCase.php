<?php

namespace Core\Application\Charge\Modules\Receive\UseCases;

use Core\Application\Charge\Modules\Receive\Domain\ReceiveEntity as Entity;
use Core\Application\Charge\Modules\Receive\Repository\ReceiveRepository as Repo;
use Core\Application\Relationship\Modules\Customer\Repository\CustomerRepository;
use Core\Shared\UseCases\Find\FindInput;

class FindUseCase
{
    public function __construct(
        private Repo $repository,
        private CustomerRepository $relationship,
    ) {
        //
    }

    public function handle(FindInput $input): DTO\Find\Output
    {
        /** @var Entity */
        $entity = $this->repository->find($input->id);
        $relationship = $this->relationship->find($entity->customer->id);

        return new DTO\Find\Output(
            title: $entity->title->value,
            resume: $entity->resume?->value,
            customer: (string)$entity->customer->id,
            customerName: (string)$relationship->name->value,
            recurrence: (string)$entity->recurrence,
            value: $entity->value->value,
            pay: $entity->pay->value,
            date: $entity->date->format('Y-m-d'),
            group: (string)$entity->group,
            id: $entity->id(),
        );
    }
}
