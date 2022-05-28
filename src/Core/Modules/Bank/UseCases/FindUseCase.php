<?php

namespace Costa\Modules\Bank\UseCases;

use Costa\Modules\Bank\Entity\BankEntity;
use Costa\Modules\Bank\Repository\BankRepositoryInterface;

class FindUseCase
{
    public function __construct(
        protected BankRepositoryInterface $repo
    ) {
        //
    }

    public function handle(DTO\Find\Input $input): DTO\Find\Output
    {
        /** @var BankEntity */
        $objEntity = $this->repo->find($input->id);

        return new DTO\Find\Output(
            id: $objEntity->id,
            name: $objEntity->name->value,
            value: $objEntity->days->value,
        );
    }
}
