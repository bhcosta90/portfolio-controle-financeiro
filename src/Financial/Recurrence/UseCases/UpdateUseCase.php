<?php

namespace Core\Financial\Recurrence\UseCases;

use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\Recurrence\Repository\RecurrenceRepositoryInterface;
use Core\Financial\Recurrence\Domain\RecurrenceEntity as Entity;
use Core\Shared\Interfaces\TransactionInterface;
use Throwable;

class UpdateUseCase
{
    public function __construct(
        private RecurrenceRepositoryInterface $repo,
    ) {
        //
    }

    public function handle(DTO\Update\UpdateInput $input): DTO\Update\UpdateOutput
    {
        /** @var Entity */
        $obj = $this->repo->find($input->id);

        $obj->update(
            name: $input->name,
            days: $input->days,
        );

        $this->repo->update($obj);

        return new DTO\Update\UpdateOutput(
            id: $obj->id(),
            name: $obj->name->value,
        );
    }
}
