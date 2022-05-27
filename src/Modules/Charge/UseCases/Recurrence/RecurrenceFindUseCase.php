<?php

namespace Costa\Modules\Charge\UseCases\Recurrence;

use Costa\Modules\Charge\Repository\RecurrenceRepositoryInterface;

class RecurrenceFindUseCase
{
    public function __construct(private RecurrenceRepositoryInterface $repo)
    {
        //
    }

    public function exec(DTO\Find\Input $input): DTO\Find\Output
    {
        $obj = $this->repo->find($input->id);

        return new DTO\Find\Output(
            name: $obj->name->value,
            days: $obj->days,
            id: $obj->id()
        );
    }
}
