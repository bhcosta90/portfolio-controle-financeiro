<?php

namespace Costa\Modules\Charge\UseCases\Recurrence;

use Costa\Modules\Charge\Repository\RecurrenceRepositoryInterface;
use Costa\Shareds\ValueObjects\Input\InputNameObject;

class RecurrenceUpdateUseCase
{
    public function __construct(private RecurrenceRepositoryInterface $repo)
    {
        //
    }

    public function exec(DTO\Update\Input $input): DTO\Update\Output
    {
        $obj = $this->repo->find(id: $input->id);

        $obj->update(
            name: new InputNameObject($input->name),
            days: $input->days
        );

        $this->repo->update($obj);

        return new DTO\Update\Output(
            name: $obj->name->value,
            days: $obj->days,
            id: $obj->id(),
        );
    }
}
