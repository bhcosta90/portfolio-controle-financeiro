<?php

namespace Core\Application\Relationship\Modules\Company\Services;

use Core\Application\Relationship\Modules\Company\Domain\CompanyEntity as Entity;
use Core\Application\Relationship\Modules\Company\Repository\CompanyRepository as Repo;
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
