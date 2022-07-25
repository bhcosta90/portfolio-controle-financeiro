<?php

namespace Core\Application\BankAccount\Modules\Bank\UseCases;

use Core\Application\BankAccount\Modules\Bank\Repository\BankRepository as Repo;
use Core\Shared\UseCases\Delete\{DeleteInput, DeleteOutput};

class DeleteUseCase
{
    public function __construct(
        private Repo $repository
    ) {
        //
    }

    public function handle(DeleteInput $input): DeleteOutput
    {
        $entity = $this->repository->find($input->id);
        return new DeleteOutput($this->repository->delete($entity));
    }
}
