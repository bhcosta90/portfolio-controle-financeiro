<?php

namespace Core\Financial\Relationship\Modules\Customer\UseCases;

use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\Relationship\Modules\Customer\Domain\CustomerEntity as Entity;
use Core\Financial\Relationship\Modules\Customer\Repository\CustomerRepositoryInterface;
use Core\Shared\Interfaces\TransactionInterface;
use Core\Shared\UseCases\Find\FindInput;

class FindUseCase
{
    public function __construct(
        private CustomerRepositoryInterface $repo,
        private AccountRepositoryInterface $account,
        private TransactionInterface $transaction,
    ) {
        //
    }

    public function handle(FindInput $input): DTO\Find\FindOutput
    {
        /** @var Entity */
        $obj = $this->repo->find($input->id);

        return new DTO\Find\FindOutput(
            id: $obj->id(),
            name: $obj->name->value,
            document_type: $obj->document?->type->value,
            document_value: $obj->document?->document,
        );
    }
}
