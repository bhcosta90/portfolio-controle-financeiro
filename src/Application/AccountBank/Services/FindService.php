<?php

namespace Core\Application\AccountBank\Services;

use Core\Application\AccountBank\Domain\AccountBankEntity as Entity;
use Core\Application\AccountBank\Repository\AccountBankRepository as Repo;
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
            value: $entity->value,
            id: $entity->id(),
        );
    }
}
