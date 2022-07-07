<?php

namespace Core\Application\Relationship\Modules\Customer\Services;

use Core\Application\Relationship\Modules\Customer\Domain\CustomerEntity as Entity;
use Core\Application\Relationship\Modules\Customer\Repository\CustomerRepository as Repo;
use Core\Shared\UseCases\Find\FindInput;

class FindService
{
    public function __construct(
        private Repo $repository
    )
    {
        //
    }

    public function handle(FindInput $input): DTO\Find\Output
    {
        /** @var Entity */
        $entity = $this->repository->find($input->id);
        return new DTO\Find\Output(
            name: $entity->name->value,
            id: $entity->id(),
        );
    }
}
