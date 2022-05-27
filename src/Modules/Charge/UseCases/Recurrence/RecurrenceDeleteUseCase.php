<?php

namespace Costa\Modules\Charge\UseCases\Recurrence;

use Costa\Modules\Charge\Repository\RecurrenceRepositoryInterface;
use Costa\Shareds\ValueObjects\DeleteObject;

class RecurrenceDeleteUseCase
{
    public function __construct(private RecurrenceRepositoryInterface $repo)
    {
        //
    }
    
    public function exec(DTO\Find\Input $input): DeleteObject
    {
        return new DeleteObject(success: $this->repo->delete($input->id));
    }
}
