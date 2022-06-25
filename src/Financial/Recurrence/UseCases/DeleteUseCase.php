<?php

namespace Core\Financial\Recurrence\UseCases;

use Core\Financial\Recurrence\Repository\RecurrenceRepositoryInterface;
use Core\Financial\Recurrence\Domain\RecurrenceEntity as Entity;
use Core\Shared\UseCases\Delete\DeleteInput;
use Core\Shared\UseCases\Delete\DeleteOutput;

class DeleteUseCase
{
    public function __construct(
        private RecurrenceRepositoryInterface $repo,
    ) {
        //
    }

    public function handle(DeleteInput $input): DeleteOutput
    {
        /** @var Entity */
        $obj = $this->repo->find($input->id);
        return new DeleteOutput($this->repo->delete($obj));
    }
}
