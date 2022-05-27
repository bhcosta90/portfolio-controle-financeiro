<?php

namespace Costa\Modules\Relationship\UseCases\Supplier;

use Costa\Modules\Account\Entities\AccountEntity;
use Costa\Modules\Account\Repository\AccountRepositoryInterface;
use Costa\Modules\Relationship\Entities\SupplierEntity;
use Costa\Modules\Relationship\Repository\SupplierRepositoryInterface;
use Costa\Shareds\Contracts\TransactionContract;
use Costa\Shareds\ValueObjects\Input\InputNameObject;
use Costa\Shareds\ValueObjects\ModelObject;
use Throwable;

class SupplierCreateUseCase
{
    public function __construct(
        private SupplierRepositoryInterface $repo,
        private TransactionContract $transaction,
        private AccountRepositoryInterface $account,
    ) {
        //
    }

    public function exec(DTO\Create\Input $input): DTO\Create\Output
    {
        $obj = new SupplierEntity(name: new InputNameObject($input->name));

        try {
            $this->repo->insert($obj);
            $this->account->insert(new AccountEntity(new ModelObject(id: $obj->id(), type: $obj), value: 0));
            $this->transaction->commit();

            return new DTO\Create\Output(
                name: $obj->name->value,
                id: $obj->id(),
            );
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }
}
