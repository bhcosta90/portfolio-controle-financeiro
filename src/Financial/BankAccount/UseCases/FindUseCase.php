<?php

namespace Core\Financial\BankAccount\UseCases;

use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\BankAccount\Repository\BankAccountRepositoryInterface;
use Core\Financial\BankAccount\Domain\BankAccountEntity as Entity;
use Core\Shared\Interfaces\TransactionInterface;
use Core\Shared\UseCases\Find\FindInput;

class FindUseCase
{
    public function __construct(
        private BankAccountRepositoryInterface $repo,
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
            value: $obj->value,
        );
    }
}
