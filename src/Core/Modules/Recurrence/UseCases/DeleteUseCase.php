<?php

namespace Costa\Modules\Recurrence\UseCases;

use Costa\Modules\Recurrence\Entity\RecurrenceEntity;
use Costa\Modules\Recurrence\Repository\RecurrenceRepositoryInterface;
use Costa\Shared\ValueObject\DeleteObject;

class DeleteUseCase
{
    public function __construct(
        protected RecurrenceRepositoryInterface $relationship
    ) {
        //
    }

    public function handle(DTO\Find\Input $input): DeleteObject
    {
        /** @var RecurrenceEntity */
        $objEntity = $this->relationship->find($input->id);
        return new DeleteObject($this->relationship->delete($objEntity));
    }
}
