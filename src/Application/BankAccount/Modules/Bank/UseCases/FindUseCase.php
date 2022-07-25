<?php

namespace Core\Application\BankAccount\Modules\Bank\UseCases;

use Core\Application\BankAccount\Modules\Bank\Domain\BankEntity as Entity;
use Core\Application\BankAccount\Modules\Bank\Repository\BankRepository as Repo;
use Core\Shared\UseCases\Find\FindInput;

class FindUseCase
{
    public function __construct(
        private Repo $repository,
    ) {
        //
    }

    public function handle(FindInput $input): DTO\Find\Output
    {
        /** @var Entity */
        $entity = $this->repository->find($input->id);

        return new DTO\Find\Output(
            $entity->id(),
            $entity->name->value,
            $entity->accountEntity->value,
            $entity->active,
            $entity->bank?->code,
            $entity->bank?->agency->account,
            $entity->bank?->agency?->digit,
            $entity->bank?->account->account,
            $entity->bank?->account?->digit,
        );
    }
}
