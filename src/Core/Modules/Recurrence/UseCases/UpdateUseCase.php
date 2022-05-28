<?php

namespace Costa\Modules\Recurrence\UseCases;

use Costa\Modules\Recurrence\Entity\RecurrenceEntity;
use Costa\Modules\Recurrence\Repository\RecurrenceRepositoryInterface;
use Costa\Shared\ValueObject\Input\InputIntObject;
use Costa\Shared\ValueObject\Input\InputNameObject;

class UpdateUseCase
{
    public function __construct(
        protected RecurrenceRepositoryInterface $relationship
    ) {
        //
    }

    public function handle(DTO\Update\Input $input): DTO\Update\Output
    {
        /** @var RecurrenceEntity */
        $objEntity = $this->relationship->find($input->id);
        $objEntity->update(
            name: new InputNameObject($input->name),
            days: new InputIntObject($input->days)
        );

        $this->relationship->update($objEntity);

        return new DTO\Update\Output(
            id: $objEntity->id,
            name: $objEntity->name->value,
            days: $objEntity->days->value,
        );
    }
}
