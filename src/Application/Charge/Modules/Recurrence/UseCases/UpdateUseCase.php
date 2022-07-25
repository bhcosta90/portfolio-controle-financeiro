<?php

namespace Core\Application\Charge\Modules\Recurrence\UseCases;

use Core\Application\Charge\Modules\Recurrence\Domain\RecurrenceEntity as Entity;
use Core\Application\Charge\Modules\Recurrence\Repository\RecurrenceRepository as Repo;

class UpdateUseCase
{
    public function __construct(
        private Repo $repository
    ) {
        //
    }

    public function handle(DTO\Update\Input $input): DTO\Update\Output
    {
        /** @var Entity */
        $entity = $this->repository->find($input->id);
        $entity->update(name: $input->name, days: $input->days);
        $this->repository->update($entity);
        return new DTO\Update\Output(
            name: $entity->name->value,
            id: $entity->id(),
        );
    }
}
