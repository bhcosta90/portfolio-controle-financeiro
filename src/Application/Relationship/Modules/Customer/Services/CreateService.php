<?php

namespace Core\Application\Relationship\Modules\Customer\Services;

use Core\Application\Relationship\Modules\Customer\Domain\CustomerEntity as Entity;
use Core\Application\Relationship\Modules\Customer\Repository\CustomerRepository as Repo;

class CreateService
{
    public function __construct(
        private Repo $repository
    )
    {
        //
    }

    public function handle(DTO\Create\Input $input): DTO\Create\Output
    {
        $entity = Entity::create(tenant: $input->tenant, name: $input->name);
        $this->repository->insert($entity);
        return new DTO\Create\Output(
            name: $entity->name->value,
            id: $entity->id(),
        );
    }
}
