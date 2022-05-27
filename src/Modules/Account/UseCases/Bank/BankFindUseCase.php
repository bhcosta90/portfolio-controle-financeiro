<?php

namespace Costa\Modules\Account\UseCases\Bank;

use Costa\Modules\Account\Repository\AccountRepositoryInterface;
use Costa\Modules\Account\Repository\BankRepositoryInterface;
use Costa\Shareds\ValueObjects\ModelObject;

class BankFindUseCase
{
    public function __construct(
        private BankRepositoryInterface $repo,
        private AccountRepositoryInterface $account,
    ) {
        //
    }

    public function exec(DTO\Find\Input $input): DTO\Find\Output
    {
        $obj = $this->repo->find($input->id);
        $objAccount = $this->account->find(new ModelObject(id: $obj->id, type: $obj));

        return new DTO\Find\Output(
            name: $obj->name->value,
            id: $obj->id(),
            value: $objAccount->value,
        );
    }
}
