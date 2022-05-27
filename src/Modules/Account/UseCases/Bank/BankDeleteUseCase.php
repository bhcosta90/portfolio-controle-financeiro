<?php

namespace Costa\Modules\Account\UseCases\Bank;

use Costa\Modules\Account\Repository\BankRepositoryInterface;
use Costa\Shareds\ValueObjects\DeleteObject;

class BankDeleteUseCase
{
    public function __construct(private BankRepositoryInterface $repo)
    {
        //
    }

    public function exec(DTO\Find\Input $input): DeleteObject
    {
        return new DeleteObject(success: $this->repo->delete($input->id));
    }
}
