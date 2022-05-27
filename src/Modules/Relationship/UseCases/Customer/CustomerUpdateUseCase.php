<?php

namespace Costa\Modules\Relationship\UseCases\Customer;

use Costa\Modules\Relationship\Repository\CustomerRepositoryInterface;
use Costa\Shareds\Contracts\TransactionContract;
use Costa\Shareds\ValueObjects\Input\InputNameObject;
use Throwable;

class CustomerUpdateUseCase
{
    public function __construct(
        private CustomerRepositoryInterface $repo,
        private TransactionContract $transaction,
    ) {
        //
    }

    public function exec(DTO\Update\Input $input): DTO\Update\Output
    {
        $obj = $this->repo->find($input->id);
        $obj->update(name: new InputNameObject($input->name));

        try {
            $this->repo->update($obj);
            $this->transaction->commit();
            return new DTO\Update\Output(
                name: $obj->name->value,
                id: $obj->id(),
            );    
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }
}
