<?php

namespace Core\Financial\BankAccount\UseCases;

use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\BankAccount\Repository\BankAccountRepositoryInterface;
use Core\Financial\BankAccount\Domain\BankAccountEntity as Entity;
use Core\Shared\Interfaces\TransactionInterface;
use Core\Shared\UseCases\Delete\DeleteInput;
use Core\Shared\UseCases\Delete\DeleteOutput;
use Throwable;

class DeleteUseCase
{
    public function __construct(
        private BankAccountRepositoryInterface $repo,
        private AccountRepositoryInterface $account,
        private TransactionInterface $transaction,
    ) {
        //
    }

    public function handle(DeleteInput $input): DeleteOutput
    {
        /** @var Entity */
        $obj = $this->repo->find($input->id);
        $account = $this->account->find($obj->id(), get_class($obj));

        try {
            $deleteRepo = $this->repo->delete($obj);
            $deleteAccount = $this->account->delete($account);
            $this->transaction->commit();
            return new DeleteOutput($deleteRepo && $deleteAccount);
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }
}
