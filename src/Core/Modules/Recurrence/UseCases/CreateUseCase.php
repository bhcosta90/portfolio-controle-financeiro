<?php

namespace Costa\Modules\Recurrence\UseCases;

use Costa\Modules\Recurrence\Entity\RecurrenceEntity;
use Costa\Modules\Recurrence\Repository\RecurrenceRepositoryInterface;
use Costa\Shared\ValueObject\DocumentObject;
use Costa\Shared\ValueObject\Enums\DocumentEnum;
use Costa\Shared\ValueObject\Input\InputIntObject;
use Costa\Shared\ValueObject\Input\InputNameObject;
use Throwable;

class CreateUseCase
{
    public function __construct(
        protected RecurrenceRepositoryInterface $relationship,
    ) {
        //
    }

    public function handle(DTO\Create\Input $input): DTO\Create\Output
    {
        $objEntity = new RecurrenceEntity(
            name: new InputNameObject($input->name),
            days: new InputIntObject($input->days)
        );

        $this->relationship->insert($objEntity);

        return new DTO\Create\Output(
            id: $objEntity->id,
            name: $objEntity->name->value,
            days: $objEntity->days->value,
        );
    }
}
