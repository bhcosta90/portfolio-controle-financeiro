<?php

namespace Core\Financial\BankAccount\UseCases;

use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\BankAccount\Repository\BankAccountRepositoryInterface;
use Core\Financial\BankAccount\Domain\BankAccountEntity as Entity;
use Core\Shared\Interfaces\TransactionInterface;
use Throwable;

class UpdateUseCase
{
    public function __construct(
        private BankAccountRepositoryInterface $repo,
        private AccountRepositoryInterface $account,
        private TransactionInterface $transaction,
    ) {
        //
    }

    public function handle(DTO\Update\UpdateInput $input): DTO\Update\UpdateOutput
    {
        try {
            /** @var Entity */
            $obj = $this->repo->find($input->id);

            $obj->update(
                name: $input->name,
            );

            $this->repo->update($obj);
            $objAccount = $this->account->find($obj->id(), get_class($obj));

            if (($value = ($input->value - $objAccount->value)) < 0) {
                $this->account->sub($objAccount, abs($value));
            } else if (($value = ($input->value - $objAccount->value)) > 0) {
                $this->account->add($objAccount, abs($value));
            }

            $this->transaction->commit();
            return new DTO\Update\UpdateOutput(
                id: $obj->id(),
                name: $obj->name->value,
            );
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }
}
