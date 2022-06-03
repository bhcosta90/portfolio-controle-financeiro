<?php

namespace Costa\Modules\Bank\UseCases;

use Costa\Modules\Account\Repository\AccountRepositoryInterface;
use Costa\Modules\Bank\Entity\BankEntity;
use Costa\Modules\Bank\Repository\BankRepositoryInterface;
use Costa\Shared\ValueObject\ModelObject;

class FindUseCase
{
    public function __construct(
        protected BankRepositoryInterface $repo,
        protected AccountRepositoryInterface $accountRepository
    ) {
        //
    }

    public function handle(DTO\Find\Input $input): DTO\Find\Output
    {
        /** @var BankEntity */
        $objEntity = $this->repo->find($input->id);

        $objAccount = $this->accountRepository->findByEntity(new ModelObject($objEntity->id, $objEntity));

        return new DTO\Find\Output(
            id: $objEntity->id,
            name: $objEntity->name->value,
            value: $objAccount->value,
        );
    }
}
