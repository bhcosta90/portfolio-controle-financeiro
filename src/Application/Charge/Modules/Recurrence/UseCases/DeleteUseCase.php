<?php

namespace Core\Application\Charge\Modules\Recurrence\UseCases;

use Core\Application\Charge\Modules\Recurrence\Domain\RecurrenceEntity as Entity;
use Core\Application\Charge\Modules\Recurrence\Repository\RecurrenceRepository as Repo;
use Core\Shared\UseCases\Delete\{DeleteInput, DeleteOutput};


class DeleteUseCase
{
    public function __construct(
        private Repo $repository
    ) {
        //
    }

    public function handle(DeleteInput $input): DeleteOutput
    {
        /** @var Entity */
        $entity = $this->repository->find($input->id);
        return new DeleteOutput($this->repository->delete($entity));
    }
}
