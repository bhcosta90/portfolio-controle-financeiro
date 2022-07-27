<?php

namespace Core\Application\Charge\Modules\Receive\UseCases;

use Core\Application\Charge\Modules\Receive\Domain\ReceiveEntity as Entity;
use Core\Application\Charge\Modules\Receive\Repository\ReceiveRepository as Repo;
use Core\Application\Charge\Modules\Recurrence\Exceptions\RecurrenceException;
use Core\Application\Charge\Modules\Recurrence\Repository\RecurrenceRepository;
use Core\Application\Relationship\Modules\Customer\Repository\CustomerRepository as RelationshipRepository;
use Core\Application\Relationship\Shared\Exceptions\RelationshipException;

class UpdateUseCase
{
    public function __construct(
        private Repo $repository,
        private RelationshipRepository $relationship,
        private RecurrenceRepository $recurrence,
    ) {
        //
    }

    public function handle(DTO\Update\Input $input): DTO\Update\Output
    {
        /** @var Entity */
        $entity = $this->repository->find($input->id);

        if ($input->customer != (string)$entity->customer->id && !$this->relationship->exist($input->customer)) {
            throw new RelationshipException('Customer not found');
        }

        if (
            $input->recurrence
            && $input->recurrence != (string)$entity->recurrence && !$this->recurrence->exist($input->recurrence)
        ) {
            throw new RecurrenceException('Recurrence not found');
        }

        $entity->update(
            title: $input->title,
            resume: $input->resume,
            customer: $input->customer,
            recurrence: $input->recurrence,
            value: $input->value,
            date: $input->date
        );

        $this->repository->update($entity);

        return new DTO\Update\Output(
            title: $entity->title->value,
            resume: $entity->resume?->value,
            customer: (string)$entity->customer->id,
            recurrence: (string)$entity->recurrence,
            value: $entity->value->value,
            date: $entity->date->format('Y-m-d'),
            group: (string)$entity->group,
            id: $entity->id(),
        );
    }
}
