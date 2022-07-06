<?php

namespace Core\Application\AccountBank\Services;

use Core\Application\AccountBank\Domain\AccountBankEntity as Entity;
use Core\Application\AccountBank\Repository\AccountBankRepository as Repo;
use Core\Shared\UseCases\Delete\{DeleteInput, DeleteOutput};


class DeleteService
{
    public function __construct(
        private Repo $repository
    ) {
        //
    }

    public function handle(DeleteInput $input): DeleteOutput
    {
        /** @var Entity */
        $entity = $this->repository->find($input->id);
        return new DeleteOutput($this->repository->delete($entity));
    }
}
