<?php

namespace Costa\Modules\Charge\UseCases\Recurrence;

use Costa\Modules\Charge\Entities\RecurrenceEntity;
use Costa\Modules\Charge\Repository\RecurrenceRepositoryInterface;

class RecurrenceCreateUseCase
{
    public function __construct(
        private RecurrenceRepositoryInterface $repo
    ) {
        //
    }

    public function exec(DTO\Create\Input $input): DTO\Create\Output
    {
        $obj = new RecurrenceEntity(
            name: $input->name,
            days: $input->days,
        );

        $this->repo->insert($obj);

        return new DTO\Create\Output(
            name: $obj->name,
            days: $obj->days,
            id: $obj->id(),
        );
    }
}
