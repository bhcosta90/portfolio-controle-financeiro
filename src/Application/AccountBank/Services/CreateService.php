<?php

namespace Core\Application\AccountBank\Services;

use Core\Application\AccountBank\Domain\AccountBankEntity as Entity;
use Core\Application\AccountBank\Repository\AccountBankRepository as Repo;

class CreateService
{
    public function __construct(
        private Repo $repository
    ) {
        //
    }

    public function handle(DTO\Create\Input $input): DTO\Create\Output
    {
        $entity = Entity::create(name: $input->name, value: $input->value, tenant: $input->tenant);
        $this->repository->insert($entity);
        return new DTO\Create\Output(
            name: $entity->name->value,
            value: $entity->value,
            id: $entity->id(),
        );
    }
}
