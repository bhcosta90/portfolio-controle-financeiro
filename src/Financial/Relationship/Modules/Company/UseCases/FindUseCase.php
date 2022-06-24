<?php

namespace Core\Financial\Relationship\Modules\Company\UseCases;

use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\Relationship\Modules\Company\Domain\CompanyEntity as Entity;
use Core\Financial\Relationship\Modules\Company\Repository\CompanyRepositoryInterface;
use Core\Shared\Interfaces\TransactionInterface;
use Core\Shared\UseCases\Find\FindInput;

class FindUseCase
{
    public function __construct(
        private CompanyRepositoryInterface $repo,
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
