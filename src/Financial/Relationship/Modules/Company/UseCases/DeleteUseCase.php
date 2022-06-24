<?php

namespace Core\Financial\Relationship\Modules\Company\UseCases;

use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\Relationship\Modules\Company\Domain\CompanyEntity as Entity;
use Core\Financial\Relationship\Modules\Company\Repository\CompanyRepositoryInterface;
use Core\Shared\Interfaces\TransactionInterface;
use Core\Shared\UseCases\Delete\DeleteInput;
use Core\Shared\UseCases\Delete\DeleteOutput;
use Throwable;

class DeleteUseCase
{
    public function __construct(
        private CompanyRepositoryInterface $repo,
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
