<?php

namespace Core\Application\Charge\Modules\Recurrence\UseCases;

use Core\Application\Charge\Modules\Recurrence\Domain\RecurrenceEntity as Entity;
use Core\Application\Charge\Modules\Recurrence\Repository\RecurrenceRepository as Repo;

class CreateUseCase
{
    public function __construct(
        private Repo $repository
    ) {
        //
    }

    public function handle(DTO\Create\Input $input): DTO\Create\Output
    {
        $entity = Entity::create(tenant: $input->tenant, name: $input->name, days: $input->days);
        $this->repository->insert($entity);
        return new DTO\Create\Output(
            name: $entity->name->value,
            days: $entity->days->value,
            id: $entity->id(),
        );
    }
}
