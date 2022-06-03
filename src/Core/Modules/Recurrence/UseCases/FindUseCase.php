<?php

namespace Costa\Modules\Recurrence\UseCases;

use Costa\Modules\Recurrence\Entity\RecurrenceEntity;
use Costa\Modules\Recurrence\Repository\RecurrenceRepositoryInterface;

class FindUseCase
{
    public function __construct(
        protected RecurrenceRepositoryInterface $relationship
    ) {
        //
    }

    public function handle(DTO\Find\Input $input): DTO\Find\Output
    {
        /** @var RecurrenceEntity */
        $objEntity = $this->relationship->find($input->id);

        return new DTO\Find\Output(
            id: $objEntity->id,
            name: $objEntity->name->value,
            days: $objEntity->days->value,
        );
    }
}
