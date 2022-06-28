<?php

namespace Core\Financial\Charge\Modules\Receive\UseCases;

use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\BankAccount\Repository\BankAccountRepositoryInterface;
use Core\Financial\BankAccount\Domain\BankAccountEntity as Entity;
use Core\Financial\Charge\Modules\Receive\Repository\ReceiveRepositoryInterface;
use Core\Shared\Interfaces\TransactionInterface;
use Core\Shared\UseCases\Find\FindInput;

class FindUseCase
{
    public function __construct(
        private ReceiveRepositoryInterface $repo,
    ) {
        //
    }

    public function handle(FindInput $input): DTO\Find\FindOutput
    {
        /** @var Entity */
        $obj = $this->repo->find($input->id);

        return new DTO\Find\FindOutput(
            id: $obj->id(),
            value: $obj->value,
            customerId: $obj->customer->id(),
            date: $obj->date->format('Y-m-d'),
            recurrenceId: $obj->recurrence?->id(),
        );
    }
}
