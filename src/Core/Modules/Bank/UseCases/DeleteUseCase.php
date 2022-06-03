<?php

namespace Costa\Modules\Bank\UseCases;

use Costa\Modules\Bank\Entity\BankEntity;
use Costa\Modules\Bank\Repository\BankRepositoryInterface;
use Costa\Shared\ValueObject\DeleteObject;

class DeleteUseCase
{
    public function __construct(
        protected BankRepositoryInterface $repo
    ) {
        //
    }

    public function handle(DTO\Find\Input $input): DeleteObject
    {
        /** @var BankEntity */
        $objEntity = $this->repo->find($input->id);
        return new DeleteObject($this->repo->delete($objEntity));
    }
}
