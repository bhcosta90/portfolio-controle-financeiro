<?php

namespace Core\Financial\BankAccount\UseCases;

use Core\Financial\Account\Domain\AccountEntity;
use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\BankAccount\Repository\BankAccountRepositoryInterface;
use Core\Financial\BankAccount\Domain\BankAccountEntity as Entity;
use Core\Shared\Interfaces\TransactionInterface;
use Throwable;

class CreateUseCase
{
    public function __construct(
        private BankAccountRepositoryInterface $repo,
        private AccountRepositoryInterface $account,
        private TransactionInterface $transaction,
    ) {
        //
    }

    public function handle(DTO\Create\CreateInput $input): DTO\Create\CreateOutput
    {
        $obj = Entity::create(
            name: $input->name,
            value: $input->value,
        );

        $account = AccountEntity::create(
            entity: $obj,
            value: $input->value,
        );

        try {
            $this->repo->insert($obj);
            $this->account->insert($account);
            $this->transaction->commit();

            return new DTO\Create\CreateOutput(
                id: $obj->id(),
                name: $obj->name->value,
                account: $account->id(),
            );
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }
}
