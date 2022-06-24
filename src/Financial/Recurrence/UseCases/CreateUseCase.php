<?php

namespace Core\Financial\Recurrence\UseCases;

use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\Recurrence\Repository\RecurrenceRepositoryInterface;
use Core\Financial\Recurrence\Domain\RecurrenceEntity as Entity;
use Core\Shared\Interfaces\TransactionInterface;

class CreateUseCase
{
    public function __construct(
        private RecurrenceRepositoryInterface $repo,
    ) {
        //
    }

    public function handle(DTO\Create\CreateInput $input): DTO\Create\CreateOutput
    {
        $obj = Entity::create(
            name: $input->name,
            days: $input->days,
        );

        $this->repo->insert($obj);

        return new DTO\Create\CreateOutput(
            id: $obj->id(),
            name: $obj->name->value,
            days: $obj->days
        );
    }
}
