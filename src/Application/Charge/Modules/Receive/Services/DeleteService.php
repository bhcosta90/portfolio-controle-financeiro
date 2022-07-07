<?php

namespace Core\Application\Charge\Modules\Receive\Services;

use Core\Application\Charge\Modules\Receive\Domain\ReceiveEntity as Entity;
use Core\Application\Charge\Modules\Receive\Repository\ChargeReceiveRepository as Repo;
use Core\Application\Charge\Modules\Recurrence\Repository\RecurrenceRepository;
use Core\Shared\UseCases\Delete\{DeleteInput, DeleteOutput};


class DeleteService
{
    public function __construct(
        private Repo                 $repository,
        private RecurrenceRepository $recurrence,
    )
    {
        //
    }

    public function handle(DeleteInput $input): DeleteOutput
    {
        /** @var Entity */
        $entity = $this->repository->find($input->id);

        if (
            $entity->pay->value > 0
            && $entity->recurrence && !$objRecurrence = $this->recurrence->find($entity->recurrence)
        ) {
            $objChargeNew = Entity::create(
                title: $entity->title->value,
                resume: $entity->resume->value,
                customer: $entity->customer->id,
                recurrence: $entity->recurrence,
                value: $entity->value->value,
                pay: null,
                group: $entity->group,
                date: $objRecurrence->calculate($entity->date->format('Y-m-d'))->format('Y-m-d'),
            );
            $this->repository->insert($objChargeNew);
        }

        return new DeleteOutput($this->repository->delete($entity));
    }
}
