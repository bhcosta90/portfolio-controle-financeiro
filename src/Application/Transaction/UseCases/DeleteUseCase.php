<?php

namespace Core\Application\Transaction\UseCases;

use Core\Application\Transaction\Domain\TransactionEntity as Entity;
use Core\Application\Transaction\Repository\TransactionRepository as Repo;
use Core\Shared\Interfaces\TransactionInterface;
use Core\Shared\UseCases\Delete\{DeleteInput, DeleteOutput};
use Throwable;

class DeleteUseCase
{
    public function __construct(
        private Repo $repository,
        private TransactionInterface $transactionInterface
    ) {
        //
    }

    public function handle(DeleteInput $input): DeleteOutput
    {
        try {
            /** @var Entity */
            $entity = $this->repository->find($input->id);
            $ret = new DeleteOutput($this->repository->delete($entity));
            $this->transactionInterface->commit();
            return $ret;
        } catch (Throwable $e) {
            $this->transactionInterface->rollback();
            throw $e;
        }
    }
}
