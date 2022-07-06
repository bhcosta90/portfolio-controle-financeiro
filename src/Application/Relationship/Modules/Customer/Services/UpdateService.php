<?php

namespace Core\Application\Relationship\Modules\Customer\Services;

use Core\Application\Relationship\Modules\Customer\Domain\CustomerEntity as Entity;
use Core\Application\Relationship\Modules\Customer\Repository\CustomerRepository as Repo;

class UpdateService
{
    public function __construct(
        private Repo $repository
    ) {
        //
    }

    public function handle(DTO\Update\Input $input): DTO\Update\Output
    {
        /** @var Entity */
        $entity = $this->repository->find($input->id);
        $entity->update(name: $input->name);
        $this->repository->update($entity);
        return new DTO\Update\Output(
            name: $entity->name->value,
            id: $entity->id(),
        );
    }
}
