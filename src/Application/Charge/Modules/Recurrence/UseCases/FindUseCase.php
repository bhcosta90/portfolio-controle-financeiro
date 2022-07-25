<?php

namespace Core\Application\Charge\Modules\Recurrence\UseCases;

use Core\Application\Charge\Modules\Recurrence\Domain\RecurrenceEntity as Entity;
use Core\Application\Charge\Modules\Recurrence\Repository\RecurrenceRepository as Repo;
use Core\Shared\UseCases\Find\FindInput;

class FindUseCase
{
    public function __construct(
        private Repo $repository
    ) {
        //
    }

    public function handle(FindInput $input): DTO\Find\Output
    {
        /** @var Entity */
        $entity = $this->repository->find($input->id);
        return new DTO\Find\Output(
            name: $entity->name->value,
            days: $entity->days->value,
            id: $entity->id(),
        );
    }
}
