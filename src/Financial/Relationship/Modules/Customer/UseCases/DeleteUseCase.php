<?php

namespace Core\Financial\Relationship\Modules\Customer\UseCases;

use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\Relationship\Modules\Customer\Domain\CustomerEntity as Entity;
use Core\Financial\Relationship\Modules\Customer\Repository\CustomerRepositoryInterface;
use Core\Shared\Interfaces\TransactionInterface;
use Core\Shared\UseCases\Delete\DeleteInput;
use Core\Shared\UseCases\Delete\DeleteOutput;
use Throwable;

class DeleteUseCase
{
    public function __construct(
        private CustomerRepositoryInterface $repo,
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
