<?php

namespace Core\Application\Relationship\Modules\Customer\Services;

use Core\Application\Relationship\Modules\Customer\Domain\CustomerEntity as Entity;
use Core\Application\Relationship\Modules\Customer\Repository\CustomerRepository as Repo;
use Core\Shared\UseCases\Delete\{DeleteInput, DeleteOutput};


class DeleteService
{
    public function __construct(
        private Repo $repository
    )
    {
        //
    }

    public function handle(DeleteInput $input): DeleteOutput
    {
        /** @var Entity */
        $entity = $this->repository->find($input->id);
        return new DeleteOutput($this->repository->delete($entity));
    }
}
