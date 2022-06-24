<?php

namespace Core\Financial\Recurrence\UseCases;

use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\Recurrence\Repository\RecurrenceRepositoryInterface;
use Core\Financial\Recurrence\Domain\RecurrenceEntity as Entity;
use Core\Shared\Interfaces\TransactionInterface;
use Core\Shared\UseCases\Find\FindInput;

class FindUseCase
{
    public function __construct(
        private RecurrenceRepositoryInterface $repo,
        private AccountRepositoryInterface $account,
        private TransactionInterface $transaction,
    ) {
        //
    }

    public function handle(FindInput $input): DTO\Find\FindOutput
    {
        /** @var Entity */
        $obj = $this->repo->find($input->id);

        return new DTO\Find\FindOutput(
            id: $obj->id(),
            name: $obj->name->value,
            days: $obj->days,
        );
    }
}
